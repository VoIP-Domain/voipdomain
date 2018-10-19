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
 * VoIP Domain notifications api module. This module add the api calls related to notifications.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Notifications
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch notification events
 */
framework_add_hook ( "notifications_events_fetch", "notifications_events_fetch");
framework_add_api_call ( "/notifications/events", "Read", "notifications_events_fetch", array ( "permissions" => array ( "user", "notifications_events_fetch")));

/**
 * Function to generate notification events list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_events_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Create table structure
   */
  $data = array ();
  foreach ( $_in["events"] as $event => $result)
  {
    $data[] = array ( $event, __ ( $result["Event"]) . " (" . __ ( $result["Description"]) . ")");
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch notifications listing
 */
framework_add_hook ( "notifications_fetch", "notifications_fetch");
framework_add_permission ( "notifications_fetch", __ ( "Request notifications list"));
framework_add_api_call ( "/notifications/fetch", "Read", "notifications_fetch", array ( "permissions" => array ( "user", "notifications_fetch")));

/**
 * Function to generate notification list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Notifications");

  /**
   * Search notifications
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications`"))
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
    $data[] = array ( $result["ID"], __ ( $_in["events"][$result["Event"]]["Event"]), $result["Description"], format_db_timestamp ( $result["Expire"]), ( $result["Expire"] == "0000-00-00 00:00:00" ? __ ( "N/A") : format_db_date ( $result["Expire"])));
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get notification information
 */
framework_add_hook ( "notifications_view", "notifications_view");
framework_add_permission ( "notifications_view", __ ( "View notification informations"));
framework_add_api_call ( "/notifications/:id", "Read", "notifications_view", array ( "permissions" => array ( "user", "notifications_view")));

/**
 * Function to generate notification informations.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_view ( $buffer, $parameters)
{
  global $_in, $_api;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Notifications");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search notifications
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $notification = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $notification["Description"];
  $data["event"] = $notification["Event"];
  $data["eventname"] = __ ( $_in["events"][$notification["Event"]]["Event"]) . " (" . __ ( $_in["events"][$notification["Event"]]["Description"]) . ")";
  $data["method"] = $notification["Method"];
  $data["url"] = $notification["URL"];
  $data["type"] = $notification["Type"];
  $data["headers"] = $notification["Headers"];
  $data["ssl"] = $notification["RelaxSSL"] == "Y";
  $data["validity"] = $notification["Expire"] == "0000-00-00 00:00:00" ? "" : format_db_date ( $notification["Expire"]);

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new notification
 */
framework_add_hook ( "notifications_add", "notifications_add");
framework_add_permission ( "notifications_add", __ ( "Add notifications"));
framework_add_api_call ( "/notifications", "Create", "notifications_add", array ( "permissions" => array ( "user", "notifications_add")));

/**
 * Function to add a new notification.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_add ( $buffer, $parameters)
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
    $data["description"] = __ ( "The description is required.");
  }
  if ( empty ( $parameters["event"]))
  {
    $data["result"] = false;
    $data["event"] = __ ( "The event is required.");
  }
  if ( ! array_key_exists ( "event", $data) && ! array_key_exists ( $parameters["event"], $_in["events"]))
  {
    $data["result"] = false;
    $data["event"] = __ ( "The provided event is invalid.");
  }
  if ( empty ( $parameters["method"]))
  {
    $data["result"] = false;
    $data["method"] = __ ( "The method is required.");
  }
  if ( ! array_key_exists ( "method", $data) && ! in_array ( $parameters["method"], $_in["methods"]))
  {
    $data["result"] = false;
    $data["method"] = __ ( "The provided method is invalid.");
  }
  if ( empty ( $parameters["url"]))
  {
    $data["result"] = false;
    $data["url"] = __ ( "The URL is required.");
  }
  if ( empty ( $parameters["type"]))
  {
    $data["result"] = false;
    $data["type"] = __ ( "The data type is required.");
  }
  if ( ! array_key_exists ( "type", $data) && ! in_array ( $parameters["type"], $_in["datatypes"]))
  {
    $data["result"] = false;
    $data["type"] = __ ( "The provided data type is invalid.");
  }
  $parameters["validity"] = ( ! empty ( $parameters["validity"]) ? format_form_datetime ( urldecode ( $parameters["validity"])) : "0000-00-00 00:00:00");

  /**
   * Check if notification was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `Event` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["event"]) . "' AND `URL` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["url"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["result"] = false;
    $data["event"] = __ ( "The provided event and URL already exist.");
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "notifications_add_sanitize"))
  {
    $data = framework_call ( "notifications_add_sanitize", $parameters, false, $data);
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
  if ( framework_has_hook ( "notifications_add_pre"))
  {
    $parameters = framework_call ( "notifications_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new notification record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Notifications` (`Description`, `Event`, `URL`, `Method`, `Type`, `Headers`, `RelaxSSL`, `Expire`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["event"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["url"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["method"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["type"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["headers"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ssl"] == "on" ? "Y" : "N") . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["validity"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "notifications_add_post"))
  {
    framework_call ( "notifications_add_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Event" => $parameters["event"], "URL" => $parameters["url"], "Method" => $parameters["method"], "Type" => $parameters["type"], "Headers" => $parameters["headers"], "RelaxSSL" => $parameters["ssl"] == "on" ? "Y" : "N", "Expire" => $parameters["validity"]);
  if ( framework_has_hook ( "notifications_add_audit"))
  {
    $audit = framework_call ( "notifications_add_audit", $parameters, false, $audit);
  }
  audit ( "notification", "add", $audit);

  /**
   * Return OK to notification
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "notifications/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing notification
 */
framework_add_hook ( "notifications_edit", "notifications_edit");
framework_add_permission ( "notifications_edit", __ ( "Edit notifications"));
framework_add_api_call ( "/notifications/:id", "Modify", "notifications_edit", array ( "permissions" => array ( "user", "notifications_edit")));
framework_add_api_call ( "/notifications/:id", "Edit", "notifications_edit", array ( "permissions" => array ( "user", "notifications_edit")));

/**
 * Function to edit an existing notification.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_edit ( $buffer, $parameters)
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
    $data["description"] = __ ( "The description is required.");
  }
  if ( empty ( $parameters["event"]))
  {
    $data["result"] = false;
    $data["event"] = __ ( "The event is required.");
  }
  if ( ! array_key_exists ( "event", $data) && ! array_key_exists ( $parameters["event"], $_in["events"]))
  {
    $data["result"] = false;
    $data["event"] = __ ( "The provided event is invalid.");
  }
  if ( empty ( $parameters["method"]))
  {
    $data["result"] = false;
    $data["method"] = __ ( "The method is required.");
  }
  if ( ! array_key_exists ( "method", $data) && ! in_array ( $parameters["method"], $_in["methods"]))
  {
    $data["result"] = false;
    $data["method"] = __ ( "The provided method is invalid.");
  }
  if ( empty ( $parameters["url"]))
  {
    $data["result"] = false;
    $data["url"] = __ ( "The URL is required.");
  }
  if ( empty ( $parameters["type"]))
  {
    $data["result"] = false;
    $data["type"] = __ ( "The data type is required.");
  }
  if ( ! array_key_exists ( "type", $data) && ! in_array ( $parameters["type"], $_in["datatypes"]))
  {
    $data["result"] = false;
    $data["type"] = __ ( "The provided data type is invalid.");
  }
  $parameters["validity"] = ( ! empty ( $parameters["validity"]) ? format_form_datetime ( urldecode ( $parameters["validity"])) : "0000-00-00 00:00:00");

  /**
   * Check if notification was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `Event` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["event"]) . "' AND `URL` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["url"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["result"] = false;
    $data["event"] = __ ( "The provided event and URL already exist.");
  }

  /**
   * Check if notification exist (could be removed by other notification meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $notification = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "notifications_edit_sanitize"))
  {
    $data = framework_call ( "notifications_edit_sanitize", $parameters, false, $data);
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
  if ( framework_has_hook ( "notifications_edit_pre"))
  {
    $parameters = framework_call ( "notifications_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change notification record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Notifications` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Event` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["event"]) . "', `URL` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["url"]) . "', `Method` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["method"]) . "', `Type` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["type"]) . "', `Headers` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["headers"]) . "', `RelaxSSL` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ssl"] == "on" ? "Y" : "N") . "', `Expire` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["validity"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "notifications_edit_post"))
  {
    framework_call ( "notifications_edit_post", $parameters);
  }

  /**
   * Create audit record
   */
  $audit = array ();
  $audit["ID"] = $notification["ID"];
  if ( $notification["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $notification["Description"], "New" => $parameters["description"]);
  }
  if ( $notification["Event"] != $parameters["event"])
  {
    $audit["Event"] = array ( "Old" => $notification["Event"], "New" => $parameters["event"]);
  }
  if ( $notification["URL"] != $parameters["url"])
  {
    $audit["URL"] = array ( "Old" => $notification["URL"], "New" => $parameters["url"]);
  }
  if ( $notification["Method"] != $parameters["method"])
  {
    $audit["Method"] = array ( "Old" => $notification["Method"], "New" => $parameters["method"]);
  }
  if ( $notification["Type"] != $parameters["type"])
  {
    $audit["Type"] = array ( "Old" => $notification["Type"], "New" => $parameters["type"]);
  }
  if ( $notification["Headers"] != $parameters["headers"])
  {
    $audit["Headers"] = array ( "Old" => $notification["Headers"], "New" => $parameters["headers"]);
  }
  if ( $notification["RelaxSSL"] != ( $parameters["ssl"] == "on" ? "Y" : "N"))
  {
    $audit["RelaxSSL"] = array ( "Old" => $notification["RelaxSSL"], "New" => $parameters["ssl"] == "on" ? "Y" : "N");
  }
  if ( $notification["Expire"] != $parameters["validity"])
  {
    $audit["Expire"] = array ( "Old" => $notification["Expire"], "New" => $parameters["validity"]);
  }
  if ( framework_has_hook ( "notifications_edit_audit"))
  {
    $audit = framework_call ( "notifications_edit_audit", $parameters, false, $audit);
  }
  audit ( "notification", "edit", $audit);

  /**
   * Return OK to notification
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a notification
 */
framework_add_hook ( "notifications_remove", "notifications_remove");
framework_add_permission ( "notifications_remove", __ ( "Remove notifications"));
framework_add_api_call ( "/notifications/:id", "Delete", "notifications_remove", array ( "permissions" => array ( "user", "notifications_remove")));

/**
 * Function to remove an existing notification.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if notification exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $notification = $result->fetch_assoc ();

  /**
   * Call remove pre hook, if exist
   */
  if ( framework_has_hook ( "notifications_remove_pre"))
  {
    $parameters = framework_call ( "notifications_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove notification database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Notifications` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "notifications_remove_post"))
  {
    framework_call ( "notifications_remove_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = $notification;
  if ( framework_has_hook ( "notifications_remove_audit"))
  {
    $audit = framework_call ( "notifications_remove_audit", $parameters, false, $audit);
  }
  audit ( "notification", "remove", $audit);

  /**
   * Retorn OK to notification
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to intercept notifications changes
 */
framework_add_hook ( "notifications_add_post", "notifications_reload_router");
framework_add_hook ( "notifications_edit_post", "notifications_reload_router");
framework_add_hook ( "notifications_remove_post", "notifications_reload_router");

/**
 * Function to notify message router server to reload watches.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_reload_router ( $buffer, $parameters)
{
  global $_in;

  /**
   * Notify message router daemon to reload watches
   */
  $ch = curl_init ();
  curl_setopt_array ( $ch, array (
                             CURLOPT_URL => $_in["general"]["routerurl"] . ( substr ( $_in["general"]["routerurl"], -1) != "/" ? "/" : "") . "reload",
                             CURLOPT_RETURNTRANSFER => true,
                             CURLOPT_CUSTOMREQUEST => "PUT"
                    ));
  @curl_exec ( $ch);
  curl_close ( $ch);

  return $buffer;
}

/**
 * API call to send new notification
 */
framework_add_hook ( "notifications_event", "notifications_event");
framework_add_permission ( "notifications_event", __ ( "Fire new notification event"));
framework_add_api_call ( "/notifications/:eventname", "Create", "notifications_event", array ( "permissions" => array ( "user", "server", "notifications_event")));

/**
 * Function to notify message router server of new event.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_event ( $buffer, $parameters)
{
  global $_in;

  /**
   * Extract event name from parameters
   */
  $eventname = $parameters["eventname"];
  unset ( $parameters["eventname"]);

  /**
   * Notify message router daemon of new event
   */
  $ch = curl_init ();
  curl_setopt_array ( $ch, array (
                             CURLOPT_URL => $_in["general"]["routerurl"] . ( substr ( $_in["general"]["routerurl"], -1) != "/" ? "/" : "") . "event/" . urlencode ( $eventname),
                             CURLOPT_RETURNTRANSFER => true,
                             CURLOPT_HTTPHEADER => array ( "Accept: application/json", "Content-Type: application/json"),
                             CURLOPT_CUSTOMREQUEST => "POST",
                             CURLOPT_POSTFIELDS => json_encode ( $parameters)
                    ));
  @curl_exec ( $ch);
  curl_close ( $ch);

  /**
   * Retorn OK to notification
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}
?>
