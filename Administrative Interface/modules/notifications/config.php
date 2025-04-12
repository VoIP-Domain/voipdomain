<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 * * Copyright (C) 2016-2025 Ernani José Camargo Azevedo
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
 * VoIP Domain notifications module configuration file.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Notifications
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Expand system main configuration variable
 */
$_in["events"] = array ();
$_in["events"]["NewCall"] = array (
  "Event" => "New call",
  "Group" => "Core",
  "Description" => "Event fired when a new call happen at the system.",
  "Fields" => array (
    array (
      "Variable" => "callednum",
      "Name" => "Called Number",
      "Type" => "integer",
      "Optional" => false
    ),
    array (
      "Variable" => "calledname",
      "Name" => "Called Name",
      "Type" => "string",
      "Optional" => true
    ),
    array (
      "Variable" => "callernum",
      "Name" => "Caller Number",
      "Type" => "string",
      "Optional" => false
    ),
    array (
      "Variable" => "callername",
      "Name" => "Caller Name",
      "Type" => "string",
      "Optional" => true
    ),
    array (
      "Variable" => "type",
      "Name" => "Call Type",
      "Type" => "enum",
      "TypeEnum" => array (
        "incoming" => "Incoming",
        "outgoing" => "Outgoing",
        "internal" => "Internal"
      ),
      "Optional" => true
    ),
    array (
      "Variable" => "datetime",
      "Name" => "Date and Time",
      "Type" => "datetime",
      "Optional" => true
    )
  )
);
$_in["events"]["ExtensionRinging"] = array ( "Event" => "Extension ringing", "Description" => "Event fired when an extensions start ringing.", "Fields" => array ( array ( "Variable" => "extension", "Name" => "Extension", "Type" => "integer", "APIPath" => "/extensions"), array ( "Variable" => "callernum", "Name" => "Caller Number", "Type" => "string")));
$_in["events"]["ExtensionAnswer"] = array ( "Event" => "Extension answer", "Description" => "Event fired when an extension answer a call.", "Fields" => array ( array ( "Variable" => "extension", "Name" => "Extension", "Type" => "integer", "APIPath" => "/extensions"), array ( "Variable" => "callernum", "Name" => "Caller Number", "Type" => "string")));
$_in["events"]["ExtensionBusy"] = array ( "Event" => "Extension busy", "Description" => "Event fired when an extension is busy and receive a new call.", "Fields" => array ( array ( "Variable" => "extension", "Name" => "Extension", "Type" => "integer", "APIPath" => "/extensions"), array ( "Variable" => "callernum", "Name" => "Caller Number", "Type" => "string")));
$_in["events"]["ExtensionNoAnswer"] = array ( "Event" => "Extension no answer", "Description" => "Event fired when an extension has a new unanswered call.", "Fields" => array ( array ( "Variable" => "extension", "Name" => "Extension", "Type" => "integer", "APIPath" => "/extensions"), array ( "Variable" => "callernum", "Name" => "Caller Number", "Type" => "string")));
$_in["events"]["ExtensionDND"] = array ( "Event" => "Extension DND", "Description" => "Event fired when an extension has a new call and have DND enabled.", "Fields" => array ( array ( "Variable" => "extension", "Name" => "Extension", "Type" => "integer", "APIPath" => "/extensions"), array ( "Variable" => "callernum", "Name" => "Caller Number", "Type" => "string")));
$_in["events"]["ExtensionHangup"] = array ( "Event" => "Extension hangup", "Description" => "Event fired when an extension hangup a call.", "Fields" => array ( array ( "Variable" => "extension", "Name" => "Extension", "Type" => "integer", "APIPath" => "/extensions"), array ( "Variable" => "callernum", "Name" => "Caller Number", "Type" => "string")));
$_in["events"]["CallBlocked"] = array ( "Event" => "Call blocked", "Description" => "Event fired when a new call with a blocked number is received.", "Fields" => array ( array ( "Variable" => "extension", "Name" => "Extension", "Type" => "integer", "APIPath" => "/extensions"), array ( "Variable" => "callernum", "Name" => "Caller Number", "Type" => "string")));
$_in["events"]["QueueMemberAdded"] = array ( "Event" => "Queue member added", "Description" => "Event fired when a member log into a call queue.", "Fields" => array ( array ( "Variable" => "queue", "Name" => "Queue", "Type" => "integer", "APIPath" => "/queues"), array ( "Variable" => "member", "Name" => "Member", "Type" => "string")));
$_in["events"]["QueueMemberPaused"] = array ( "Event" => "Queue member paused", "Description" => "Event fired when a member pause on a call queue.", "Fields" => array ( array ( "Variable" => "queue", "Name" => "Queue", "Type" => "integer", "APIPath" => "/queues"), array ( "Variable" => "member", "Name" => "Member", "Type" => "string")));
$_in["events"]["QueueMemberUnPaused"] = array ( "Event" => "Queue member unpaused", "Description" => "Event fired when a member return from a pause on a call queue.", "Fields" => array ( array ( "Variable" => "queue", "Name" => "Queue", "Type" => "integer", "APIPath" => "/queues"), array ( "Variable" => "member", "Name" => "Member", "Type" => "string")));
$_in["events"]["QueueMemberRemoved"] = array ( "Event" => "Queue member removed", "Description" => "Event fired when a member has been removed from a call queue.", "Fields" => array ( array ( "Variable" => "queue", "Name" => "Queue", "Type" => "integer", "APIPath" => "/queues"), array ( "Variable" => "member", "Name" => "Member", "Type" => "string")));
$_in["events"]["PeerRegistered"] = array ( "Event" => "Peer registered", "Description" => "Event fired when a peer is registered.", "Fields" => array ( array ( "Variable" => "peer", "Name" => "Peer", "Type" => "string")));
$_in["events"]["PeerUnregistered"] = array ( "Event" => "Peer unregistered", "Description" => "Event fired when a peer is unregistered.", "Fields" => array ( array ( "Variable" => "peer", "Name" => "Peer", "Type" => "string")));
$_in["events"]["PeerUnreachable"] = array ( "Event" => "Peer unreachable", "Description" => "Event fired when a peer is unreachable.", "Fields" => array ( array ( "Variable" => "peer", "Name" => "Peer", "Type" => "string")));
$_in["events"]["PeerRejected"] = array ( "Event" => "Peer rejected", "Description" => "Event fired when a peer is rejected.", "Fields" => array ( array ( "Variable" => "peer", "Name" => "Peer", "Type" => "string")));
$_in["events"]["PeerLagged"] = array ( "Event" => "Peer lagged", "Description" => "Event fired when a peer is lagged.", "Fields" => array ( array ( "Variable" => "peer", "Name" => "Peer", "Type" => "string")));

$_in["methods"] = array ();
$_in["methods"][] = "GET";
$_in["methods"][] = "POST";
$_in["methods"][] = "PUT";
$_in["methods"][] = "DELETE";
$_in["methods"][] = "HEAD";
$_in["methods"][] = "OPTIONS";
$_in["methods"][] = "TRACE";
$_in["methods"][] = "CONNECT";

$_in["datatypes"] = array ();
$_in["datatypes"][] = "JSON";
$_in["datatypes"][] = "FORM-DATA";
$_in["datatypes"][] = "PHP";
?>
