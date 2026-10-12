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
 * VoIP Domain CDR router module.
 *
 * Receives CDR records from monitor daemons via Gearman, resolves the Tenant
 * from the Server ID, and inserts the record into the central MySQL database.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CDR
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Register Gearman event hook
 */
framework_add_hook ( "cdr_new", "cdr_new");

/**
 * Process a new CDR record received from a monitor daemon.
 *
 * Resolves the Tenant from the Server ID and inserts the record into the
 * central cdr table. Duplicate records (same uniqueid + server) are ignored.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system
 * @param array $parameters CDR data from monitor daemon
 * @return mixed Hook buffer
 */
function cdr_new ( $buffer, $parameters)
{
  global $_in;

  $serverID = (int) $parameters["ServerID"];

  $tenant = cdr_resolve_tenant ( $serverID);
  if ( $tenant === false)
  {
    writeLog ( "CDR rejected: cannot resolve Tenant for Server #" . $serverID, VoIP_LOG_WARNING);
    return $buffer;
  }

  $uniqueid = $_in["mysql"]["id"]->real_escape_string ( $parameters["uniqueid"]);

  $existing = @$_in["mysql"]["id"]->query ( "SELECT 1 FROM `cdr` WHERE `uniqueid` = '" . $uniqueid . "' AND `server` = " . $serverID . " LIMIT 1");
  if ( $existing && $existing->num_rows > 0)
  {
    return $buffer;
  }

  $query = "INSERT INTO `cdr` (" .
    "`Tenant`, `calldate`, `clid`, `src`, `dst`, `dcontext`, `channel`, `dstchannel`, " .
    "`lastapp`, `lastdata`, `duration`, `billsec`, `disposition`, `amaflags`, " .
    "`accountcode`, `uniqueid`, `linkedid`, `sequence`, `peeraccount`, `userfield`, " .
    "`server`, `sourcetype`, `calltype`, `gateway`, `value`, `processed`, " .
    "`nativecodec`, `readcodec`, `writecodec`, `QOS`, `WhoHungUp`, `SIPID`, " .
    "`flags`, `monitor`, `userfieldextra`, `hangupcause`, `dialstatus`, `encryption`" .
    ") VALUES (" .
    (int) $tenant . ", " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["calldate"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["clid"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["src"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["dst"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["dcontext"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["channel"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["dstchannel"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["lastapp"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["lastdata"]) . "', " .
    (int) $parameters["duration"] . ", " .
    (int) $parameters["billsec"] . ", " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["disposition"]) . "', " .
    (int) $parameters["amaflags"] . ", " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["accountcode"]) . "', " .
    "'" . $uniqueid . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( ! empty ( $parameters["linkedid"]) ? $parameters["linkedid"] : "") . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( ! empty ( $parameters["sequence"]) ? $parameters["sequence"] : "") . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( ! empty ( $parameters["peeraccount"]) ? $parameters["peeraccount"] : "") . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["userfield"]) . "', " .
    $serverID . ", " .
    (int) $parameters["sourcetype"] . ", " .
    (int) $parameters["calltype"] . ", " .
    (int) $parameters["gateway"] . ", " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["value"]) . "', " .
    (int) $parameters["processed"] . ", " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["nativecodec"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["readcodec"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["writecodec"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["QOS"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["WhoHungUp"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["SIPID"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["flags"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["monitor"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["userfieldextra"]) . "', " .
    (int) $parameters["hangupcause"] . ", " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["dialstatus"]) . "', " .
    "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["encryption"]) . "'" .
    ")";

  if ( ! @$_in["mysql"]["id"]->query ( $query))
  {
    writeLog ( "CDR insert failed for " . $parameters["uniqueid"] . ": " . $_in["mysql"]["id"]->error, VoIP_LOG_WARNING);
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
function cdr_resolve_tenant ( $serverID)
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
