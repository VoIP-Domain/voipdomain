<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 * * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * VoIP Domain notifications module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Notifications
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Expand system main configuration variable
 */
$_in["events"] = array ();
$_in["events"]["NewCall"] = array ( "Event" => "New call", "Description" => "Event fired when a new call happen at the system.");
$_in["events"]["ExtensionRinging"] = array ( "Event" => "Extension ringing", "Description" => "Event fired when an extensions start ringing.");
$_in["events"]["ExtensionAnswer"] = array ( "Event" => "Extension answer", "Description" => "Event fired when an extension answer a call.");
$_in["events"]["ExtensionBusy"] = array ( "Event" => "Extension busy", "Description" => "Event fired when an extension is busy and receive a new call.");
$_in["events"]["ExtensionNoAnswer"] = array ( "Event" => "Extension no answer", "Description" => "Event fired when an extension has a new unanswered call.");
$_in["events"]["ExtensionDND"] = array ( "Event" => "Extension DND", "Description" => "Event fired when an extension has a new call and have DND enabled.");
$_in["events"]["ExtensionHangup"] = array ( "Event" => "Extension hangup", "Description" => "Event fired when an extension hangup a call.");
$_in["events"]["CallBlocked"] = array ( "Event" => "Call blocked", "Description" => "Event fired when a new call with a blocked number is received.");
$_in["events"]["QueueMemberAdded"] = array ( "Event" => "Queue member added", "Description" => "Event fired when a member log into a call queue.");
$_in["events"]["QueueMemberPaused"] = array ( "Event" => "Queue member paused", "Description" => "Event fired when a member pause on a call queue.");
$_in["events"]["QueueMemberUnPaused"] = array ( "Event" => "Queue member unpaused", "Description" => "Event fired when a member return from a pause on a call queue.");
$_in["events"]["QueueMemberRemoved"] = array ( "Event" => "Queue member removed", "Description" => "Event fired when a member has been removed from a call queue.");
$_in["events"]["PeerRegistered"] = array ( "Event" => "Peer registered", "Description" => "Event fired when a peer is registered.");
$_in["events"]["PeerUnregistered"] = array ( "Event" => "Peer unregistered", "Description" => "Event fired when a peer is unregistered.");
$_in["events"]["PeerUnreachable"] = array ( "Event" => "Peer unreachable", "Description" => "Event fired when a peer is unreachable.");
$_in["events"]["PeerRejected"] = array ( "Event" => "Peer rejected", "Description" => "Event fired when a peer is rejected.");
$_in["events"]["PeerLagged"] = array ( "Event" => "Peer lagged", "Description" => "Event fired when a peer is lagged.");

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
