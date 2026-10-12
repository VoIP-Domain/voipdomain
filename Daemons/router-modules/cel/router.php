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
 * VoIP Domain CEL router module.
 *
 * Receives CEL (Channel Event Logging) records from monitor daemons via
 * Gearman, resolves the Tenant from the Server ID, and inserts into the
 * central cel table.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CEL
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Register Gearman event hook
 */
framework_add_hook ( "cel_new", "cel_new");

/**
 * Process a new CEL record received from a monitor daemon.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system
 * @param array $parameters CEL data from monitor daemon
 * @return mixed Hook buffer
 */
function cel_new ( $buffer, $parameters)
{
  global $_in;

  $serverID = (int) $parameters["ServerID"];

  $tenant = cel_resolve_tenant ( $serverID);
  if ( $tenant === false)
  {
    writeLog ( "CEL rejected: cannot resolve Tenant for Server #" . $serverID, VoIP_LOG_WARNING);
    return $buffer;
  }

  $query = "INSERT INTO `cel` (" .
    "`Tenant`, `Server`, `EventName`, `EventTime`, `CallerIDnum`, `CallerIDname`, " .
    "`CallerIDani`, `CallerIDrdnis`, `CallerIDdnid`, `Exten`, `Context`, `Channel`, " .
    "`Application`, `AppData`, `AccountCode`, `PeerAccount`, `UniqueID`, `LinkedID`, " .
    "`AMAFlags`, `UserField`, `Peer`, `Extra`" .
    ") VALUES (" .
    (int) $tenant . ", " .
    $serverID . ", " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["EventName"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["EventTime"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["CallerIDnum"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["CallerIDname"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["CallerIDani"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["CallerIDrdnis"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["CallerIDdnid"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Exten"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Context"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Channel"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Application"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["AppData"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["AccountCode"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["PeerAccount"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["UniqueID"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["LinkedID"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["AMAFlags"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["UserField"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Peer"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Extra"]) . "'" .
    ")";

  if ( ! @$_in["mysql"]["id"]->query ( $query))
  {
    writeLog ( "CEL insert failed: " . $_in["mysql"]["id"]->error, VoIP_LOG_WARNING);
  }

  return $buffer;
}

/**
 * Resolve Tenant ID from a Server ID.
 *
 * @global array $_in Framework global configuration variable
 * @param int $serverID The server identifier
 * @return int|false Tenant ID or false on failure
 */
function cel_resolve_tenant ( $serverID)
{
  global $_in;

  $result = @$_in["mysql"]["id"]->query ( "SELECT `Tenant` FROM `Servers` WHERE `ID` = " . (int) $serverID);
  if ( ! $result || ! $row = $result->fetch_assoc ())
  {
    return false;
  }
  return (int) $row["Tenant"];
}
?>
