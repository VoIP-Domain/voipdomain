<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain CEL (Channel Event Logging) monitor module.
 *
 * Captures CEL events from Asterisk AMI (cel_manager backend) and forwards
 * them to the central router daemon for storage. CEL provides granular
 * channel lifecycle events for detailed call flow reconstruction.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CEL
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Register AMI event hook for CEL
 */
framework_add_hook ( "CEL", "cel_event");

/**
 * Process a CEL event from AMI (cel_manager backend).
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system
 * @param array $parameters AMI event parameters
 * @return mixed Hook buffer
 */
function cel_event ( $buffer, $parameters)
{
  global $_in, $gm;

  $payload = array (
    "ServerID" => $_in["daemon"]["serverid"],
    "EventName" => ! empty ( $parameters["EventName"]) ? $parameters["EventName"] : "",
    "EventTime" => ! empty ( $parameters["EventTime"]) ? $parameters["EventTime"] : "",
    "CallerIDnum" => ! empty ( $parameters["CallerIDnum"]) ? $parameters["CallerIDnum"] : "",
    "CallerIDname" => ! empty ( $parameters["CallerIDname"]) ? $parameters["CallerIDname"] : "",
    "CallerIDani" => ! empty ( $parameters["CallerIDani"]) ? $parameters["CallerIDani"] : "",
    "CallerIDrdnis" => ! empty ( $parameters["CallerIDrdnis"]) ? $parameters["CallerIDrdnis"] : "",
    "CallerIDdnid" => ! empty ( $parameters["CallerIDdnid"]) ? $parameters["CallerIDdnid"] : "",
    "Exten" => ! empty ( $parameters["Exten"]) ? $parameters["Exten"] : "",
    "Context" => ! empty ( $parameters["Context"]) ? $parameters["Context"] : "",
    "Channel" => ! empty ( $parameters["Channel"]) ? $parameters["Channel"] : "",
    "Application" => ! empty ( $parameters["Application"]) ? $parameters["Application"] : "",
    "AppData" => ! empty ( $parameters["AppData"]) ? $parameters["AppData"] : "",
    "AccountCode" => ! empty ( $parameters["AccountCode"]) ? $parameters["AccountCode"] : "",
    "PeerAccount" => ! empty ( $parameters["PeerAccount"]) ? $parameters["PeerAccount"] : "",
    "UniqueID" => ! empty ( $parameters["UniqueID"]) ? $parameters["UniqueID"] : "",
    "LinkedID" => ! empty ( $parameters["LinkedID"]) ? $parameters["LinkedID"] : "",
    "AMAFlags" => ! empty ( $parameters["AMAFlags"]) ? $parameters["AMAFlags"] : "",
    "UserField" => ! empty ( $parameters["Userfield"]) ? $parameters["Userfield"] : "",
    "Peer" => ! empty ( $parameters["Peer"]) ? $parameters["Peer"] : "",
    "Extra" => ! empty ( $parameters["Extra"]) ? $parameters["Extra"] : ""
  );

  @$gm->doBackground ( "cel_new", json_encode ( $payload));

  return $buffer;
}
?>
