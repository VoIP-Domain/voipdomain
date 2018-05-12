<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2025 Ernani José Camargo Azevedo
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
 * VoIP Domain queues Asterisk AMI events module. This module add the Asterisk
 * monitoring events related to queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "FullyBooted", "queues_bootup");
framework_add_hook ( "Load", "queues_load");
framework_add_hook ( "Reload", "queues_reload");
framework_add_hook ( "Unload", "queues_unload");
framework_add_hook ( "Shutdown", "queues_shutdown");
framework_add_hook ( "QueueParams", "queues_params");
framework_add_hook ( "QueueMember", "queues_member");
framework_add_hook ( "QueueEntry", "queues_entry");
framework_add_hook ( "QueueStatusComplete", "queues_status_complete");
framework_add_hook ( "QueueCallerJoin", "queues_caller_join");
framework_add_hook ( "QueueCallerAbandon", "queues_caller_abandon");
framework_add_hook ( "QueueCallerLeave", "queues_caller_leave");
framework_add_hook ( "QueueMemberAdded", "queues_member_add");
framework_add_hook ( "QueueMemberStatus", "queues_member_status");
framework_add_hook ( "QueueMemberPause", "queues_member_pause");
framework_add_hook ( "QueueMemberPenalty", "queues_member_penalty");
framework_add_hook ( "QueueMemberRinginuse", "queues_member_ringinuse");
framework_add_hook ( "QueueMemberRemoved", "queues_member_remove");

/**
 * Function to boot up the queues.
 *
 * @global array $_in Framework global configuration variable
 * @global object $ami Asterisk AMI connection object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_bootup ( $buffer, $parameters)
{
  global $_in, $ami;

  // Setup queues variables:
  $_in["Queues"] = array ();
  $_in["Status"]["Queues"] = array ( "BootUp" => true, "Reload" => false);

  // Request all queues status:
  $ami->request ( "QueueStatus", array ());

  // End event:
  return $buffer;
}

/**
 * Function to load queues if needed.
 *
 * @global array $_in Framework global configuration variable
 * @global object $ami Asterisk AMI connection object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_load ( $buffer, $parameters)
{
  global $_in, $ami;

  // Execute only if loaded specific queues module:
  if ( $parameters["Module"] == "app_queue.so" || $parameters["Module"] == "app_queue")
  {
    // Setup queues variables:
    $_in["Queues"] = array ();
    $_in["Status"]["Queues"] = array ( "BootUp" => true, "Reload" => false);

    // Request all queues status:
    $ami->request ( "QueueStatus", array ());
  }

  // End event:
  return $buffer;
}

/**
 * Function to reload queues if needed.
 *
 * @global array $_in Framework global configuration variable
 * @global object $ami Asterisk AMI connection object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_reload ( $buffer, $parameters)
{
  global $_in, $ami;

  // Execute only if reloaded all modules or specific queues module:
  if ( $parameters["Module"] == "All" || $parameters["Module"] == "app_queue.so" || $parameters["Module"] == "app_queue")
  {
    // Create backup and set queues variables:
    $_in["Status"]["Queues"]["Reload"] = true;
    $_in["Status"]["Queues"]["Backup"] = $_in["Queues"];
    $_in["Queues"] = array ();

    // Request all queues status:
    $ami->request ( "QueueStatus", array ());
  }

  // End event:
  return $buffer;
}

/**
 * Function to unload queues if needed.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_unload ( $buffer, $parameters)
{
  global $_in;

  // Execute only if unloaded specific queues module:
  if ( $parameters["Module"] == "app_queue.so" || $parameters["Module"] == "app_queue")
  {
    // Remove queues:
    unset ( $_in["Queues"]);
    unset ( $_in["Status"]["Queues"]);

    // Notify router of event:
    $gm->doBackground ( "queues_unload", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"])));
  }

  // End event:
  return $buffer;
}

/**
 * Function to unload queues on shutdown.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_shutdown ( $buffer, $parameters)
{
  global $_in;

  // Remove queues:
  unset ( $_in["Queues"]);
  unset ( $_in["Status"]["Queues"]);

  // End event:
  return $buffer;
}

/**
 * Function to process queue entry response to action "QueueStatus".
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_params ( $buffer, $parameters)
{
  global $_in;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Remove queue from memory if it already exists:
  if ( array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue description received for existing queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    unset ( $_in["Queues"][$parameters["Queue"]]);
  }

  // Add new queue:
  $_in["Queues"][$parameters["Queue"]] = array ();
  $_in["Queues"][$parameters["Queue"]]["Queue"] = $parameters["Queue"];
  $_in["Queues"][$parameters["Queue"]]["Strategy"] = $parameters["Strategy"];
  $_in["Queues"][$parameters["Queue"]]["MaxCalls"] = (int) $parameters["Max"];
  $_in["Queues"][$parameters["Queue"]]["TotalCalls"] = (int) $parameters["Calls"];
  $_in["Queues"][$parameters["Queue"]]["HoldTime"] = (int) $parameters["Holdtime"];
  $_in["Queues"][$parameters["Queue"]]["TalkTime"] = (int) $parameters["TalkTime"];
  $_in["Queues"][$parameters["Queue"]]["Completed"] = (int) $parameters["Completed"];
  $_in["Queues"][$parameters["Queue"]]["Abandoned"] = (int) $parameters["Abandoned"];
  $_in["Queues"][$parameters["Queue"]]["ServiceLevel"] = (int) $parameters["ServiceLevel"];
  $_in["Queues"][$parameters["Queue"]]["ServiceLevelPerf"] = (float) $parameters["ServiceLevelPerf"];
  $_in["Queues"][$parameters["Queue"]]["ServiceLevelPerf2"] = (float) $parameters["ServiceLevelPerf2"];
  $_in["Queues"][$parameters["Queue"]]["Weight"] = (int) $parameters["Weidght"];
  $_in["Queues"][$parameters["Queue"]]["Members"] = array ();
  $_in["Queues"][$parameters["Queue"]]["Entries"] = array ();

  // End event:
  return $buffer;
}

/**
 * Function to process queue member entry response to action "QueueStatus".
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_member ( $buffer, $parameters)
{
  global $_in;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue member received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Add queue members:
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]] = array ();
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["Name"] = $parameters["Name"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["Location"] = $parameters["Location"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["Membership"] = $parameters["Membership"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["Penalty"] = (int) $parameters["Penalty"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["CallsTaken"] = (int) $parameters["CallsTaken"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["LastCall"] = (int) $parameters["LastCall"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["LastPause"] = 0;
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["InCall"] = (boolean) $parameters["InCall"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["Status"] = (int) $parameters["Status"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["Paused"] = (boolean) $parameters["Paused"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["PauseReason"] = ( $parameters["Paused"] ? $parameters["PausedReason"] : "");
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["RingInUse"] = 0;
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["Name"]]["WrapUpTime"] = 0;

  // End event:
  return $buffer;
}

/**
 * Function to process queue caller entry response to action "QueueStatus".
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_entry ( $buffer, $parameters)
{
  global $_in;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue entry received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Add queue channels:
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]] = array ();
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]["Channel"] = $parameters["Channel"];
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]["Position"] = (int) $parameters["Position"];
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]["CallerID"] = array ( "Number" => ( $parameters["CallerID"] == "<unknown>" ? "" : $parameters["CallerID"]), "Name" => ( $parameters["CallerIDName"] == "<unknown>" ? "" : $parameters["CallerIDName"]));
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]["Wait"] = (int) $parameters["Wait"];

  // End event:
  return $buffer;
}

/**
 * Function to process queue status complete response to action "QueueStatus".
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_status_complete ( $buffer, $parameters)
{
  global $_in, $gm;

  if ( $_in["Status"]["Queues"]["BootUp"] == true)
  {
    $_in["Status"]["Queues"]["BootUp"] = false;
    $gm->doBackground ( "queues_bootup", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queues" => $_in["Queues"])));
  }
  if ( $_in["Status"]["Queues"]["Reload"] == true)
  {
    $_in["Status"]["Queues"]["Reload"] = false;
    unset ( $_in["Status"]["Queues"]["Backup"]);
// TODO: Tem que comparar o backup com o resultado atual e enviar "diferenças"...
  }

  // End event:
  return $buffer;
}

/**
 * Function to add a new caller to a queue.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_caller_join ( $buffer, $parameters)
{
  global $_in, $gm;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue channel add received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Add new channel to queue:
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]] = array ();
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]["Channel"] = $parameters["Channel"];
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]["Position"] = (int) $parameters["Position"];
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]["CallerID"] = array ( "Number" => ( $parameters["CallerID"] == "<unknown>" ? "" : $parameters["CallerID"]), "Name" => ( $parameters["CallerIDName"] == "<unknown>" ? "" : $parameters["CallerIDName"]));
  $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]["Wait"] = (int) $parameters["Wait"];

  // Notify router of event:
  $gm->doBackground ( "queues_caller_add", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queue" => $parameters["Queue"], "Caller" => $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]])));

  // End event:
  return $buffer;
}

/**
 * Function to remove a caller from a queue (abandonned).
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_caller_abandon ( $buffer, $parameters)
{
  global $_in, $gm;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue channel abandon received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Abort if queue channel doesn't exists:
  if ( ! array_key_exists ( $parameters["Channel"], $_in["Queues"][$parameters["Queue"]]["Entries"]))
  {
    writeLog ( "Queue caller abandon received for unexisting caller \"" . $parameters["Channel"] . "\" at queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Remove channel from queue:
  unset ( $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]);

  // Notify router of event:
  $gm->doBackground ( "queues_caller_abandon", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queue" => $parameters["Queue"], "Channel" => $parameters["Channel"], "Position" => (int) $parameters["Position"], "OriginalPosition" => (int) $parameters["OriginalPosition"], "HoldTime" => (int) $parameters["HoldTime"])));

  // End event:
  return $buffer;
}

/**
 * Function to remove a caller from a queue.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_caller_leave ( $buffer, $parameters)
{
  global $_in, $gm;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue channel remove received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Abort if queue channel doesn't exists:
  if ( ! array_key_exists ( $parameters["Channel"], $_in["Queues"][$parameters["Queue"]]["Entries"]))
  {
    writeLog ( "Queue caller remove received for unexisting caller \"" . $parameters["Channel"] . "\" at queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Remove caller from queue:
  unset ( $_in["Queues"][$parameters["Queue"]]["Entries"][$parameters["Channel"]]);

  // Notify router of event:
  $gm->doBackground ( "queues_caller_remove", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queue" => $parameters["Queue"], "Channel" => $parameters["Channel"])));

  // End event:
  return $buffer;
}

/**
 * Function to add a member to a queue.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_member_add ( $buffer, $parameters)
{
  global $_in, $gm;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue member add received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Add new member to queue:
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]] = array ();
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Name"] = $parameters["MemberName"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Location"] = $parameters["Interface"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Membership"] = $parameters["Membership"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Penalty"] = (int) $parameters["Penalty"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["CallsTaken"] = (int) $parameters["CallsTaken"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["LastCall"] = (int) $parameters["LastCall"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["LastPause"] = (int) $parameters["LastPause"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["InCall"] = (boolean) $parameters["InCall"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Status"] = (int) $parameters["Status"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Paused"] = (boolean) $parameters["Paused"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["PauseReason"] = ( $parameters["Paused"] ? $parameters["PausedReason"] : "");
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["RingInUse"] = (boolean) $parameters["Ringinuse"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["WrapUpTime"] = (int) $parameters["Wrapuptime"];

  // Notify router of event:
  $gm->doBackground ( "queues_member_add", json_encode ( array_merge_recursive ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queue" => $parameters["Queue"]), $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]])));

  // End event:
  return $buffer;
}

/**
 * Function to change a member status onto a queue.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_member_status ( $buffer, $parameters)
{
  global $_in, $gm;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue member update received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Abort if queue member doesn't exists:
  if ( ! array_key_exists ( $parameters["MemberName"], $_in["Queues"][$parameters["Queue"]]["Members"]))
  {
    writeLog ( "Queue member update received for unexisting member \"" . $parameters["MemberName"] . "\" at queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Update member into queue:
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Penalty"] = (int) $parameters["Penalty"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["CallsTaken"] = (int) $parameters["CallsTaken"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["LastCall"] = (int) $parameters["LastCall"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["InCall"] = (boolean) $parameters["InCall"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Status"] = (int) $parameters["Status"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Paused"] = (boolean) $parameters["Paused"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["PauseReason"] = ( $parameters["Paused"] ? $parameters["PausedReason"] : "");
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["RingInUse"] = (boolean) $parameters["Ringinuse"];

  // Notify router of event:
  $gm->doBackground ( "queues_member_update", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queue" => $parameters["Queue"], "Member" => $parameters["MemberName"], "Penalty" => (int) $parameters["Penalty"], "CallsTaken" => (int) $parameters["CallsTaken"], "LastCall" => (int) $parameters["LastCall"], "InCall" => (boolean) $parameters["InCall"], "Status" => (int) $parameters["Status"], "Paused" => (boolean) $parameters["Paused"], "PauseReason" => ( $parameters["Paused"] ? $parameters["PausedReason"] : ""), "RingInUse" => (boolean) $parameters["Ringinuse"])));

  // End event:
  return $buffer;
}

/**
 * Function to pause/unpause a member status from a queue.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_member_pause ( $buffer, $parameters)
{
  global $_in, $gm;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue member pause received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Abort if queue member doesn't exists:
  if ( ! array_key_exists ( $parameters["MemberName"], $_in["Queues"][$parameters["Queue"]]["Members"]))
  {
    writeLog ( "Queue member pause received for unexisting member \"" . $parameters["MemberName"] . "\" at queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Update member status:
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Paused"] = (boolean) $parameters["Paused"];
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["PauseReason"] = ( $parameters["Paused"] ? $parameters["PausedReason"] : "");

  // Notify router of event:
  $gm->doBackground ( "queues_member_update", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queue" => $parameters["Queue"], "Member" => $parameters["MemberName"], "Paused" => (boolean) $parameters["Paused"], "PauseReason" => ( $parameters["Paused"] ? $parameters["PausedReason"] : ""))));

  // End event:
  return $buffer;
}

/**
 * Function to change a member penalty from a queue.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_member_penalty ( $buffer, $parameters)
{
  global $_in, $gm;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue member penalty change received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Abort if queue member doesn't exists:
  if ( ! array_key_exists ( $parameters["MemberName"], $_in["Queues"][$parameters["Queue"]]["Members"]))
  {
    writeLog ( "Queue member penalty change received for unexisting member \"" . $parameters["MemberName"] . "\" at queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Update member status:
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["Penalty"] = (int) $parameters["Penalty"];

  // Notify router of event:
  $gm->doBackground ( "queues_member_update", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queue" => $parameters["Queue"], "Member" => $parameters["MemberName"], "Penalty" => (int) $parameters["Penalty"])));

  // End event:
  return $buffer;
}

/**
 * Function to change a member ring in use from a queue.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_member_ringinuse ( $buffer, $parameters)
{
  global $_in, $gm;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue member ring in use change received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Abort if queue member doesn't exists:
  if ( ! array_key_exists ( $parameters["MemberName"], $_in["Queues"][$parameters["Queue"]]["Members"]))
  {
    writeLog ( "Queue member ring in use change received for unexisting member \"" . $parameters["MemberName"] . "\" at queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Update member status:
  $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]["RingInUse"] = (boolean) $parameters["Ringinuse"];

  // Notify router of event:
  $gm->doBackground ( "queues_member_update", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queue" => $parameters["Queue"], "Member" => $parameters["MemberName"], "RingInUse" => (boolean) $parameters["Ringinuse"])));

  // End event:
  return $buffer;
}

/**
 * Function to remove a member from a queue.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function queues_member_remove ( $buffer, $parameters)
{
  global $_in, $gm;

  // Only process queues starting with VoIP Domain prefix:
  if ( substr ( $parameters["Queue"], 0, 5) != "vd_q_")
  {
    return $buffer;
  }

  // Abort if queue doesn't exists:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"]))
  {
    writeLog ( "Queue member remove received for unexisting queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Abort if queue member doesn't exists:
  if ( ! array_key_exists ( $parameters["MemberName"], $_in["Queues"][$parameters["Queue"]]["Members"]))
  {
    writeLog ( "Queue member remove received for unexisting member \"" . $parameters["MemberName"] . "\" at queue \"" . $parameters["Queue"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }

  // Remove member from queue:
  unset ( $_in["Queues"][$parameters["Queue"]]["Members"][$parameters["MemberName"]]);

  // Notify router of event:
  $gm->doBackground ( "queues_member_remove", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Queue" => $parameters["Queue"], "Member" => $parameters["MemberName"])));

  // End event:
  return $buffer;
}
?>
