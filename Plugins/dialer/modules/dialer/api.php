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
 * VoIP Domain dialer api module. This module add the api calls related to
 * dialer.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Dialer
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search campaigns (datatables compatible response)
 */
framework_add_hook ( "campaigns_search", "campaigns_search");
framework_add_permission ( "campaigns_search", __ ( "Search campaigns (select list standard)"));
framework_add_api_call ( "/campaigns/search", "Read", "campaigns_search", array ( "permissions" => array ( "user", "campaigns_search")));

/**
 * Function to generate campaigns list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Campaigns");

  /**
   * Search extensions
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` " . ( ! empty ( $parameters["q"]) ? "WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' " : "") . "ORDER BY `Description`"))
  {
    while ( $campaign = $result->fetch_assoc ())
    {
      $data[] = array ( $campaign["ID"], $campaign["Description"] . " (" . ( $campaign["State"] == "A" ? __ ( "Active") : __ ( "Inactive")) . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch campaign listing
 */
framework_add_hook ( "campaigns_fetch", "campaigns_fetch");
framework_add_permission ( "campaigns_fetch", __ ( "Request campaigns listing"));
framework_add_api_call ( "/campaigns/fetch", "Read", "campaigns_fetch", array ( "permissions" => array ( "user", "campaigns_fetch")));

/**
 * Function to generate campaigns list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Campaigns");

  /**
   * Search campaigns
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Campaigns`.*, (SELECT COUNT(*) FROM `CampaignEntries` WHERE `Campaign` = `Campaigns`.`ID`) AS `Entries` FROM `Campaigns`"))
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
    $data[] = array ( $result["ID"], $result["Description"], $result["State"], $result["Entries"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get campaigns information
 */
framework_add_hook ( "campaigns_view", "campaigns_view");
framework_add_permission ( "campaigns_view", __ ( "View campaign informations"));
framework_add_api_call ( "/campaigns/:id", "Read", "campaigns_view", array ( "permissions" => array ( "user", "campaigns_view")));

/**
 * Function to generate campaign informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Campaigns");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search campaign
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Campaigns`.*, `Queues`.`Description` AS `QueueDescription` FROM `Campaigns` LEFT JOIN `Queues` ON `Campaigns`.`Queue` = `Queues`.`ID` WHERE `Campaigns`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $campaign = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $campaign["Description"];
  $data["state"] = $campaign["State"] == "A";
  $data["queue"] = $campaign["Queue"];
  $data["queuedescription"] = $campaign["QueueDescription"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new campaign
 */
framework_add_hook ( "campaigns_add", "campaigns_add");
framework_add_permission ( "campaigns_add", __ ( "Add campaign"));
framework_add_api_call ( "/campaigns", "Create", "campaigns_add", array ( "permissions" => array ( "user", "campaigns_add")));

/**
 * Function to add a new campaign.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_add ( $buffer, $parameters)
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
    $data["description"] = __ ( "The campaign description is required.");
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "campaigns_add_sanitize"))
  {
    $data = framework_call ( "campaigns_add_sanitize", $parameters, false, $data);
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
  if ( framework_has_hook ( "campaigns_add_pre"))
  {
    $parameters = framework_call ( "campaigns_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new campaign entry record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Campaigns` (`Description`, `State`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["state"] == "on" ? "A" : "I") . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "campaigns_add_post"))
  {
    framework_call ( "campaigns_add_post", $parameters);
  }

  /**
   * Add new campaign at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["id"], "State" => $parameters["state"] == "on");
  if ( framework_has_hook ( "campaigns_add_notify"))
  {
    $notify = framework_call ( "campaigns_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "createcampaign", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "State" => $parameters["state"] == "on");
  if ( framework_has_hook ( "campaigns_add_audit"))
  {
    $audit = framework_call ( "campaigns_add_audit", $parameters, false, $audit);
  }
  audit ( "campaign", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "campaigns/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing campaign
 */
framework_add_hook ( "campaigns_edit", "campaigns_edit");
framework_add_permission ( "campaigns_edit", __ ( "Edit campaign"));
framework_add_api_call ( "/campaign/:id", "Modify", "campaigns_edit", array ( "permissions" => array ( "user", "campaigns_edit")));
framework_add_api_call ( "/campaign/:id", "Edit", "campaigns_edit", array ( "permissions" => array ( "user", "campaigns_edit")));

/**
 * Function to edit an existing campaign.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_edit ( $buffer, $parameters)
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
    $data["description"] = __ ( "The campaign description is required.");
  }

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "campaigns_edit_sanitize"))
  {
    $data = framework_call ( "campaigns_edit_sanitize", $parameters, false, $data);
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
   * Request campaign entry
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $campaign = $result->fetch_assoc ();

  /**
   * Call edit pre hook, if exist
   */
  if ( framework_has_hook ( "campaigns_edit_pre"))
  {
    $parameters = framework_call ( "campaigns_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change campaign record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Campaigns` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `State` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["state"] == "on" ? true : false) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "campaigns_edit_post"))
  {
    framework_call ( "campaigns_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["id"], "State" => $parameters["state"] == "on");
  if ( framework_has_hook ( "campaigns_edit_notify"))
  {
    $notify = framework_call ( "campaigns_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "changecampaign", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $parameters["id"];
  if ( $campaign["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $campaign["Description"], "New" => $parameters["description"]);
  }
  if ( $campaign["State"] != ( $parameters["state"] == "on" ? "A" : "I"))
  {
    $audit["State"] = array ( "Old" => $campaign["State"], "New" => ( $parameters["state"] == "on" ? "A" : "I"));
  }
  if ( framework_has_hook ( "campaigns_edit_audit"))
  {
    $audit = framework_call ( "campaigns_edit_audit", $parameters, false, $audit);
  }
  audit ( "campaign", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a campaign
 */
framework_add_hook ( "campaigns_remove", "campaigns_remove");
framework_add_permission ( "campaigns_remove", __ ( "Remove campaign"));
framework_add_api_call ( "/campaigns/:id", "Delete", "campaigns_remove", array ( "permissions" => array ( "user", "campaigns_remove")));

/**
 * Function to remove an existing campaign.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if campaign exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $campaign = $result->fetch_assoc ();

  /**
   * Call remove pre hook, if exist
   */
  if ( framework_has_hook ( "campaigns_remove_pre"))
  {
    $parameters = framework_call ( "campaigns_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove campaign database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "campaigns_remove_post"))
  {
    framework_call ( "campaigns_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["id"]);
  if ( framework_has_hook ( "campaigns_remove_notify"))
  {
    $notify = framework_call ( "campaigns_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "removecampaign", $notify);

  /**
   * Insert audit registry
   */
  $audit = $campaign;
  if ( framework_has_hook ( "campaigns_remove_audit"))
  {
    $audit = framework_call ( "campaigns_remove_audit", $parameters, false, $audit);
  }
  audit ( "campaign", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to fetch campaign entries listing
 */
framework_add_hook ( "campaigns_entries_fetch", "campaigns_entries_fetch");
framework_add_permission ( "campaigns_entries_fetch", __ ( "Request campaign entries listing"));
framework_add_api_call ( "/campaigns/:id/fetch", "Read", "campaigns_entries_fetch", array ( "permissions" => array ( "user", "campaigns_entries_fetch")));

/**
 * Function to generate campaign entries list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_entries_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "CampaignEntries");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search campaign entries
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntries` WHERE `Campaign` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
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
    $data[] = array ( $result["ID"], $result["Description"], $result["Grouper"], $result["Number"], $result["Tries"], $result["State"], format_db_timestamp ( $result["InsertDate"]), format_db_datetime ( $result["InsertDate"]));
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to list campaigns associated to a queue
 */
framework_add_hook ( "campaigns_associated_fetch", "campaigns_associated_fetch");
framework_add_permission ( "campaigns_associated_fetch", __ ( "Request campaigns associated to a queue"));
framework_add_api_call ( "/campaigns/associated/:id", "Read", "campaigns_associated_fetch", array ( "permissions" => array ( "user", "campaigns_associated_fetch")));

/**
 * Function to generate list of campaigns associated to a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_associated_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Campaigns");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search campaigns entries
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
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
    $data[] = $result["ID"];
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch campaign associated to a queue entries listing
 */
framework_add_hook ( "campaigns_associated_entries_fetch", "campaigns_associated_entries_fetch");
framework_add_permission ( "campaigns_associated_entries_fetch", __ ( "Request campaign entries associated to a queue"));
framework_add_api_call ( "/campaigns/fetch/associated/:id", "Read", "campaigns_associated_entries_fetch", array ( "permissions" => array ( "user", "campaigns_associated_entries_fetch")));

/**
 * Function to generate campaign entries associated to a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_associated_entries_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "CampaignEntries");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search campaign entries
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `CampaignEntries`.*, `Campaigns`.`Description` AS `CampaignDescription` FROM `CampaignEntries` LEFT JOIN `Campaigns` ON `CampaignEntries`.`Campaign` = `Campaigns`.`ID` WHERE `Campaigns`.`Queue` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
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
    $data[] = array ( "id" => $result["ID"], "cid" => $result["Campaign"], "description" => $result["Description"], "grouper" => $result["Grouper"], "number" => $result["Number"], "tries" => $result["Tries"], "state" => $result["State"], "inserted" => array ( "timestamp" => format_db_timestamp ( $result["InsertDate"]), "datetime" => format_db_datetime ( $result["InsertDate"])), "campaign" => $result["CampaignDescription"], "extra" => json_decode ( $result["Extra"], true), "log" => json_decode ( $result["Log"], true), "member" => $result["Member"], "result" => $result["Result"], "detail" => $result["Detail"], "registry" => $result["Registry"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to associate a campaign to a queue
 */
framework_add_hook ( "campaigns_associate", "campaigns_associate");
framework_add_permission ( "campaigns_associate", __ ( "Associate campaign"));
framework_add_api_call ( "/campaigns/:id/associate", "Create", "campaigns_associate", array ( "permissions" => array ( "user", "campaigns_associate")));

/**
 * Function to associate a campaign to a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_associate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["queue"] = (int) $parameters["queue"];
  if ( empty ( $parameters["queue"]))
  {
    $data["result"] = false;
    $data["queue"] = __ ( "The queue to associate is required.");
  }

  /**
   * Request campaign
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $campaign = $result->fetch_assoc ();

  /**
   * Check if queue exist
   */
  $queue = filters_call ( "get_queues", array ( "id" => $parameters["queue"]));
  if ( sizeof ( $queue) == 0)
  {
    $data["result"] = false;
    $data["queue"] = __ ( "The queue to associate is invalid.");
  }

  /**
   * Call associate sanitize hook, if exist
   */
  if ( framework_has_hook ( "campaigns_associate_sanitize"))
  {
    $data = framework_call ( "campaigns_associate_sanitize", $parameters, false, $data);
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
   * Call associate pre hook, if exist
   */
  if ( framework_has_hook ( "campains_associate_pre"))
  {
    $parameters = framework_call ( "campains_associate_pre", $parameters, false, $parameters);
  }

  /**
   * Change campaign record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Campaigns` SET `Queue` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["queue"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call associate post hook, if exist
   */
  if ( framework_has_hook ( "campains_associate_post"))
  {
    framework_call ( "campains_associate_post", $parameters);
  }

  /**
   * Associate campaign to queue at Asterisk servers
   */
  $notify = array ( "Campaign" => $parameters["id"], "Queue" => $queue[0]["Extension"]);
  if ( framework_has_hook ( "campains_associate_notify"))
  {
    $notify = framework_call ( "campains_associate_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "associatecampaign", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Queue" => $parameters["queue"]);
  if ( framework_has_hook ( "campains_associate_audit"))
  {
    $audit = framework_call ( "campains_associate_audit", $parameters, false, $audit);
  }
  audit ( "campaign", "associate", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to unassociate a campaign to a queue
 */
framework_add_hook ( "campaigns_unassociate", "campaigns_unassociate");
framework_add_permission ( "campaigns_unassociate", __ ( "Unassociate campaign"));
framework_add_api_call ( "/campaigns/:id/unassociate", "Create", "campaigns_unassociate", array ( "permissions" => array ( "user", "campaigns_unassociate")));

/**
 * Function to unassociate a campaign to a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_unassociate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Request campaign
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $campaign = $result->fetch_assoc ();

  /**
   * Call unassociate sanitize hook, if exist
   */
  if ( framework_has_hook ( "campaigns_unassociate_sanitize"))
  {
    $data = framework_call ( "campaigns_unassociate_sanitize", $parameters, false, $data);
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
   * Get dialer queue informations
   */
  $queue = filters_call ( "get_queues", array ( "id" => $campaign["Queue"]));
  if ( sizeof ( $queue) == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call unassociate pre hook, if exist
   */
  if ( framework_has_hook ( "campains_unassociate_pre"))
  {
    $parameters = framework_call ( "campains_unassociate_pre", $parameters, false, $parameters);
  }

  /**
   * Change campaign record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Campaigns` SET `Queue` = NULL WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call unassociate post hook, if exist
   */
  if ( framework_has_hook ( "campains_unassociate_post"))
  {
    framework_call ( "campains_unassociate_post", $parameters);
  }

  /**
   * Unassociate campaign from queue at Asterisk servers
   */
  $notify = array ( "Campaign" => $parameters["id"], "Queue" => $queue[0]["Extension"]);
  if ( framework_has_hook ( "campains_unassociate_notify"))
  {
    $notify = framework_call ( "campains_unassociate_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "unassociatecampaign", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"]);
  if ( framework_has_hook ( "campains_unassociate_audit"))
  {
    $audit = framework_call ( "campains_unassociate_audit", $parameters, false, $audit);
  }
  audit ( "campaign", "unassociate", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to import campaign entries
 */
framework_add_hook ( "campaigns_import", "campaigns_import");
framework_add_permission ( "campaigns_import", __ ( "Import campaign entries"));
framework_add_api_call ( "/campaigns/import", "Create", "campaigns_import", array ( "permissions" => array ( "user", "campaigns_import")));

/**
 * Function to import a campaign to a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_import ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["campaign"] = (int) $parameters["campaign"];

  /**
   * Request campaign
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["campaign"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $campaign = $result->fetch_assoc ();

  /**
   * Call import sanitize hook, if exist
   */
  if ( framework_has_hook ( "campaigns_import_sanitize"))
  {
    $data = framework_call ( "campaigns_import_sanitize", $parameters, false, $data);
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
   * Call import pre hook, if exist
   */
  if ( framework_has_hook ( "campains_import_pre"))
  {
    $parameters = framework_call ( "campains_import_pre", $parameters, false, $parameters);
  }

  /**
   * Process all received files
   */
  $numbers = array ();
  $files = array ();
  $totalfiles = 0;
  foreach ( $_FILES["files"]["tmp_name"] as $id => $tmp_name)
  {
    if ( is_uploaded_file ( $tmp_name))
    {
      $totalfiles++;

      /**
       * Check character set
       */
      $convert = @iconv ( "utf-8", "utf-8//IGNORE", file_get_contents ( $tmp_name)) != file_get_contents ( $tmp_name);

      /**
       * Open file and process it
       */
      if ( $fp = fopen ( $tmp_name, "rb"))
      {
        $processed = 0;
        $failed = 0;
        $report = "";
        $linenum = 0;
        while ( ( $line = fgets ( $fp, 4096)) !== false)
        {
          if ( $convert)
          {
            $line = utf8_encode ( $line);
          }
          $linenum++;
          $line = str_replace ( "\r", "", str_replace ( "\n", "", $line));
          // Ignore if line start with # or is empty
          if ( substr ( $line, 0, 1) == "#" || $line == "")
          {
            $report .= sprintf ( __ ( "Line %d"), $linenum) . ": " . __ ( "Ignored line.") . " >>>" . strip_tags ( $line) . "<<<\n";
            $failed++;
            continue;
          }
          if ( $data = str_getcsv ( $line, ";"))
          {
            if ( sizeof ( $data) < 3)
            {
              $report .= sprintf ( __ ( "Line %d"), $linenum) . ": " . __ ( "Invalid number of fields.") . " >>>" . strip_tags ( $line) . "<<<\n";
              $failed++;
              continue;
            }
            $phone = filters_call ( "e164_identify", array ( "number" => $data[2]));
            if ( ! array_key_exists ( "country", $phone))
            {
              $report .= sprintf ( __ ( "Line %d"), $linenum) . ": " . __ ( "Invalid phone number.") . " >>>" . strip_tags ( $line) . "<<<\n";
              $failed++;
              continue;
            }
            if ( ! empty ( $data[3]))
            {
              if ( ! isJSON ( $data[3]))
              {
                $data[3] = json_encode ( $data[3]);
              }
            }
            if ( ! $_in["mysql"]["id"]->query ( "INSERT INTO `CampaignEntries` (`Description`, `Grouper`, `Number`, `Campaign`, `Tries`, `State`, `Log`, `Extra`, `Member`, `Result`, `Detail`, `Registry`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( strip_tags ( $data[0])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( strip_tags ( $data[1])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( strip_tags ( $data[2])) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["campaign"]) . ", 0, 'W', '', '" . $_in["mysql"]["id"]->real_escape_string ( ! empty ( $data[3]) ? $data[3] : "") . "', NULL, NULL, NULL, '')"))
            {
              $report .= sprintf ( __ ( "Line %d"), $linenum) . ": " . __ ( "Error adding entry to database.") . " >>>" . strip_tags ( $line) . "<<<\n";
              $failed++;
              continue;
            } else {
              $numbers[] = array ( "ID" => $_in["mysql"]["id"]->insert_id, "Grouper" => strip_tags ( $data[1]), "Number" => strip_tags ( $data[2]), "Tries" => 0, "State" => 'W');
            }
            $processed++;
          } else {
            $report .= sprintf ( __ ( "Line %d"), $linenum) . ": " . __ ( "Cannot parse CSV line.") . " >>>" . strip_tags ( $line) . "<<<\n";
            continue;
          }
        }
      }
      fclose ( $fp);
      $files[] = array ( "name" => $_FILES["files"]["name"][$id], "size" => $_FILES["files"]["size"][$id], "processed" => $processed, "failed" => $failed, "report" => base64_encode ( ( $convert ? utf8_decode ( $report) : $report)));
    } else {
      $files[] = array ( "name" => $_FILES["files"]["name"][$id], "size" => $_FILES["files"]["size"][$id], "error" => __ ( "Cannot read file."));
    }
  }

  /**
   * Call import post hook, if exist
   */
  if ( framework_has_hook ( "campaings_import_post"))
  {
    framework_call ( "campaings_import_post", $parameters);
  }

  /**
   * Load numbers to campaign at Asterisk servers
   */
  $notify = array ( "Campaign" => $parameters["campaign"], "Numbers" => $numbers);
  if ( framework_has_hook ( "campaigns_import_notify"))
  {
    $notify = framework_call ( "campaigns_import_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "loadcampaignnumbers", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["campaign"], "ProcessedFiles" => $totalfiles);
  if ( framework_has_hook ( "campaigns_import_audit"))
  {
    $audit = framework_call ( "campaigns_import_audit", $parameters, false, $audit);
  }
  audit ( "campagins", "import", $audit);

  /**
   * Return file upload report to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $files);
}

/**
 * API call to update a campaign entry extra fields
 */
framework_add_hook ( "campaign_entry_extra_update", "campaign_entry_extra_update");
framework_add_permission ( "campaign_entry_extra_update", __ ( "Update a campaign extra entry fields"));
framework_add_api_call ( "/campaigns/entry/:id/extra", "Create", "campaign_entry_extra_update", array ( "permissions" => array ( "user", "server", "campaign_entry_extra_update")));

/**
 * Function to update a campaign entry extra fields.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_entry_extra_update ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["detail"] = (int) $parameters["detail"];
  $parameters["result"] = (int) $parameters["result"];
  $parameters["registry"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["registry"])));

  /**
   * Request campaign entry
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntries` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $entry = $result->fetch_assoc ();

  /**
   * Call campaign entry sanitize hook, if exist
   */
  if ( framework_has_hook ( "campaign_entry_extra_update_sanitize"))
  {
    $data = framework_call ( "campaign_entry_extra_update_sanitize", $parameters, false, $data);
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
   * Call campaign entry update pre hook, if exist
   */
  if ( framework_has_hook ( "campaing_entry_extra_update_pre"))
  {
    $parameters = framework_call ( "campaing_entry_extra_update_pre", $parameters, false, $parameters);
  }

  /**
   * Update entry
   */
  if ( ! $_in["mysql"]["id"]->query ( "UPDATE `CampaignEntries` SET `Detail` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["detail"]) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $_in["mysql"]["id"]->query ( "UPDATE `CampaignEntries` SET `Result` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["result"]) . ", `Registry` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["registry"]) . "' WHERE `Campaign` = " . $_in["mysql"]["id"]->real_escape_string ( $entry["Campaign"]) . " AND `Grouper` = '" . $_in["mysql"]["id"]->real_escape_string ( $entry["Grouper"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call campaign entry update post hook, if exist
   */
  if ( framework_has_hook ( "campaing_entry_extra_update_post"))
  {
    framework_call ( "campaing_entry_extra_update_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Result" => $parameters["result"], "Detail" => $parameters["detail"], "Registry" => $parameters["registry"]);
  if ( framework_has_hook ( "campaig_entry_extra_update_audit"))
  {
    $audit = framework_call ( "campaign_entry_extra_update_audit", $parameters, false, $audit);
  }
  audit ( "campaginentryextra", "update", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to update a campaign entry
 */
framework_add_hook ( "campaign_entry_update", "campaign_entry_update");
framework_add_permission ( "campaign_entry_update", __ ( "Update a campaign entry"));
framework_add_api_call ( "/campaigns/entry/:id", "Modify", "campaign_entry_update", array ( "permissions" => array ( "user", "server", "campaign_entry_update")));
framework_add_api_call ( "/campaigns/entry/:id", "Edit", "campaign_entry_update", array ( "permissions" => array ( "user", "server", "campaign_entry_update")));

/**
 * Function to update a campaign entry.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_entry_update ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["tries"] = (int) $parameters["tries"];
  switch ( $parameters["state"])
  {
    case "W": // Waiting
    case "D": // Dialing
    case "A": // Answered
    case "F": // Finished
    case "C": // Canceled
    case "I": // Ignored
    case "R": // Ringing
      break;
    default:
      $data["result"] = false;
      $data["state"] = __ ( "Invalid state.");
      break;
  }
  if ( ! empty ( $parameters["extra"]))
  {
    if ( ! isJSON ( $parameters["extra"]))
    {
      $parameters["extra"] = json_encode ( array ( $parameters["extra"]));
    }
  }

  /**
   * Request campaign entry
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntries` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $entry = $result->fetch_assoc ();

  /**
   * Call campaign entry sanitize hook, if exist
   */
  if ( framework_has_hook ( "campaign_entry_update_sanitize"))
  {
    $data = framework_call ( "campaign_entry_update_sanitize", $parameters, false, $data);
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
   * Call campaign entry update pre hook, if exist
   */
  if ( framework_has_hook ( "campaing_entry_update_pre"))
  {
    $parameters = framework_call ( "campaing_entry_update_pre", $parameters, false, $parameters);
  }

  /**
   * Update entry
   */
  if ( ! $_in["mysql"]["id"]->query ( "UPDATE `CampaignEntries` SET `Tries` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["tries"]) . ", `State` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["state"]) . "'" . ( ! empty ( $parameters["extra"]) ? ", `Extra` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["extra"]) . "'" : "") . ( ! empty ( $parameters["member"]) ? ", `Member` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["member"]) . "'" : "") . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call campaign entry update post hook, if exist
   */
  if ( framework_has_hook ( "campaing_entry_update_post"))
  {
    framework_call ( "campaing_entry_update_post", $parameters);
  }

  /**
   * Update number to campaign at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["id"], "Grouper" => $entry["Grouper"], "Tries" => $parameters["tries"], "State" => $parameters["state"]);
  if ( framework_has_hook ( "campaigns_entry_update_notify"))
  {
    $notify = framework_call ( "campaigns_entry_update_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "changedialernumber", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"]);
  if ( $entry["Tries"] != $parameters["tries"])
  {
    $audit["Tries"] = array ( "Old" => $entry["Tries"], "New" => $parameters["tries"]);
  }
  if ( $entry["State"] != $parameters["state"])
  {
    $audit["State"] = array ( "Old" => $entry["State"], "New" => $parameters["state"]);
  }
  if ( framework_has_hook ( "campaig_entry_update_audit"))
  {
    $audit = framework_call ( "campaign_entry_update_audit", $parameters, false, $audit);
  }
  audit ( "campaginentry", "update", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to update a campaign status
 */
framework_add_hook ( "campaign_status_update", "campaign_status_update");
framework_add_permission ( "campaign_status_update", __ ( "Update a campaign status"));
framework_add_api_call ( "/campaigns/:id/status", "Modify", "campaign_status_update", array ( "permissions" => array ( "user", "server", "campaign_status_update")));
framework_add_api_call ( "/campaigns/:id/status", "Edit", "campaign_status_update", array ( "permissions" => array ( "user", "server", "campaign_status_update")));

/**
 * Function to update a campaign status.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_status_update ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  switch ( $parameters["state"])
  {
    case "A": // Active
    case "I": // Inactive
      break;
    default:
      $data["result"] = false;
      $data["state"] = __ ( "Invalid state.");
      break;
  }

  /**
   * Request campaign entry
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $entry = $result->fetch_assoc ();

  /**
   * Call campaign status sanitize hook, if exist
   */
  if ( framework_has_hook ( "campaign_status_update_sanitize"))
  {
    $data = framework_call ( "campaign_status_update_sanitize", $parameters, false, $data);
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
   * Call campaign status update pre hook, if exist
   */
  if ( framework_has_hook ( "campaing_status_update_pre"))
  {
    $parameters = framework_call ( "campaing_status_update_pre", $parameters, false, $parameters);
  }

  /**
   * Update entry
   */
  if ( ! $_in["mysql"]["id"]->query ( "UPDATE `Campaigns` SET `State` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["state"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call campaign status update post hook, if exist
   */
  if ( framework_has_hook ( "campaing_status_update_post"))
  {
    framework_call ( "campaing_status_update_post", $parameters);
  }

  /**
   * Update status to campaign at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["id"], "State" => $parameters["state"]);
  if ( framework_has_hook ( "campaigns_status_update_notify"))
  {
    $notify = framework_call ( "campaigns_status_update_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "changecampaign", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"]);
  if ( $entry["State"] != $parameters["state"])
  {
    $audit["State"] = array ( "Old" => $entry["State"], "New" => $parameters["state"]);
  }
  if ( framework_has_hook ( "campaig_status_update_audit"))
  {
    $audit = framework_call ( "campaign_status_update_audit", $parameters, false, $audit);
  }
  audit ( "campaginstatus", "update", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a campaign entry
 */
framework_add_hook ( "campaign_entry_add", "campaign_entry_add");
framework_add_permission ( "campaign_entry_add", __ ( "Add a campaign entry"));
framework_add_api_call ( "/campaigns/:cid/entry", "Create", "campaign_entry_add", array ( "permissions" => array ( "user", "server", "campaign_entry_add")));

/**
 * Function to add a campaign entry.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_entry_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["cid"] = (int) $parameters["cid"];
  $parameters["grouper"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["grouper"])));
  if ( empty ( $parameters["grouper"]))
  {
    $data["result"] = false;
    $data["grouper"] = __ ( "The grouper is required.");
  }
  $parameters["description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["description"])));
  if ( empty ( $parameters["description"]))
  {
    $data["result"] = false;
    $data["description"] = __ ( "The description is required.");
  }
  $parameters["number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["number"])));
  $phone = filters_call ( "e164_identify", array ( "number" => $data[2]));
  if ( ! array_key_exists ( "country", $phone))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The number is invalid.");
  }

  /**
   * Check if campaign exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["cid"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["result"] = false;
    $data["cid"] = __ ( "The provided campaign ID was not found.");
  }
  $campaign = $result->fetch_assoc ();

  /**
   * Check if grouper and number already exist to this campaign
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntries` WHERE `Campaign` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["cid"]) . " AND `Grouper` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["grouper"]) . "' AND `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["result"] = false;
    $data["number"] = __ ( "The provided grouper and number already exist to this campaign.");
  }

  /**
   * Call campaign entry sanitize hook, if exist
   */
  if ( framework_has_hook ( "campaign_entry_add_sanitize"))
  {
    $data = framework_call ( "campaign_entry_add_sanitize", $parameters, false, $data);
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
   * Call campaign entry add pre hook, if exist
   */
  if ( framework_has_hook ( "campaing_entry_add_pre"))
  {
    $parameters = framework_call ( "campaing_entry_add_pre", $parameters, false, $parameters);
  }

  /**
   * Insert entry
   */
  if ( ! $_in["mysql"]["id"]->query ( "INSERT INTO `CampaignEntries` (`Description`, `Grouper`, `Number`, `Campaign`, `Tries`, `State`, `Log`, `Extra`, `Member`, `Result`, `Detail`, `Registry`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["grouper"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["cid"]) . ", 0, 'W', '', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["extra"]) . "', NULL, NULL, NULL, '')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call campaign entry add post hook, if exist
   */
  if ( framework_has_hook ( "campaing_entry_add_post"))
  {
    framework_call ( "campaing_entry_add_post", $parameters);
  }

  /**
   * Insert number to campaign at Asterisk servers
   */
  $notify = array ( "Campaign" => $parameters["cid"], "ID" => $parameters["id"], "Grouper" => $parameters["grouper"], "Number" => $parameters["number"], "Tries" => 0, "State" => "W");
  if ( framework_has_hook ( "campaigns_entry_add_notify"))
  {
    $notify = framework_call ( "campaigns_entry_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "createdialernumber", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Grouper" => $parameters["grouper"], "Number" => $parameters["number"]);
  if ( framework_has_hook ( "campaig_entry_add_audit"))
  {
    $audit = framework_call ( "campaign_entry_add_audit", $parameters, false, $audit);
  }
  audit ( "campaginentry", "add", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to intercept new server and server reinstall
 */
framework_add_hook ( "servers_add_post", "campaigns_server_reconfig");
framework_add_hook ( "servers_reinstall_config", "campaigns_server_reconfig");

/**
 * Function to notify server to include all campaigns.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaigns_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all campaigns and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Campaigns`.`ID`, `Campaigns`.`State`, `Queues`.`Extension`, `Ranges`.`Server` FROM `Campaigns` LEFT JOIN `Queues` ON `Campaigns`.`Queue` = `Queues`.`ID` LEFT JOIN `Ranges` ON `Queues`.`Range` = `Ranges`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $campaign = $result->fetch_assoc ())
  {
    $notify = array ( "ID" => $campaign["ID"], "State" => $campaign["State"] == "A");
    if ( framework_has_hook ( "campaigns_add_notify"))
    {
      $notify = framework_call ( "campaigns_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["id"], "createcampaign", $notify);

    /**
     * Associate campaign to queue at Asterisk servers if needed
     */
    if ( $campaign["Server"] == $parameters["id"] && $campaign["Extension"] != "")
    {
      $notify = array ( "Campaign" => $campaign["ID"], "Queue" => $campaign["Extension"]);
      if ( framework_has_hook ( "campains_associate_notify"))
      {
        $notify = framework_call ( "campains_associate_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["id"], "associatecampaign", $notify);
    }

    /**
     * Load all campaign numbers
     */
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Grouper`, `Number`, `Tries`, `State` FROM `CampaignEntries` WHERE `Campaign` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $campaign["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $entry = $result2->fetch_assoc ())
    {
      $notify = array ( "Campaign" => $campaign["ID"], "ID" => $entry["ID"], "Grouper" => $entry["Grouper"], "Number" => $entry["Number"], "Tries" => $entry["Tries"], "State" => $entry["State"]);
      if ( framework_has_hook ( "campaigns_entry_add_notify"))
      {
        $notify = framework_call ( "campaigns_entry_add_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["id"], "createdialernumber", $notify);
    }
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
