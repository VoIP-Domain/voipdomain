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
 * VoIP Domain CDR monitor module.
 *
 * Captures CDR records from Asterisk via two mechanisms:
 *   1. Real-time: AMI 'Cdr' event (from cdr_manager) delivers completed CDRs
 *      with all custom fields as they happen.
 *   2. Startup recovery: On FullyBooted, queries local SQLite3 database for
 *      records where processed=0 (not yet forwarded to the central router).
 *
 * Both paths forward CDR data to the router daemon via Gearman for central
 * storage with Tenant resolution.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CDR
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Register AMI event hooks
 */
framework_add_hook ( "Cdr", "cdr_event");
framework_add_hook ( "FullyBooted", "cdr_startup_sync");

/**
 * Default path for the local CDR SQLite3 database (cdr_sqlite3_custom default)
 */
if ( ! defined ( "CDR_SQLITE_PATH"))
{
  define ( "CDR_SQLITE_PATH", "/var/log/asterisk/cdr.db");
}

/**
 * Process a real-time CDR event from AMI (cdr_manager backend).
 *
 * The event contains standard CDR fields plus custom fields defined in the
 * [mappings] section of cdr_manager.conf (Server, CallType, Gateway, etc.).
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system
 * @param array $parameters AMI event parameters
 * @return mixed Hook buffer
 */
function cdr_event ( $buffer, $parameters)
{
  global $_in, $gm;

  $payload = array (
    "ServerID" => $_in["daemon"]["serverid"],
    "calldate" => ! empty ( $parameters["StartTime"]) ? $parameters["StartTime"] : "",
    "clid" => ! empty ( $parameters["CallerID"]) ? $parameters["CallerID"] : "",
    "src" => ! empty ( $parameters["Source"]) ? $parameters["Source"] : "",
    "dst" => ! empty ( $parameters["Destination"]) ? $parameters["Destination"] : "",
    "dcontext" => ! empty ( $parameters["DestinationContext"]) ? $parameters["DestinationContext"] : "",
    "channel" => ! empty ( $parameters["Channel"]) ? $parameters["Channel"] : "",
    "dstchannel" => ! empty ( $parameters["DestinationChannel"]) ? $parameters["DestinationChannel"] : "",
    "lastapp" => ! empty ( $parameters["LastApplication"]) ? $parameters["LastApplication"] : "",
    "lastdata" => ! empty ( $parameters["LastData"]) ? $parameters["LastData"] : "",
    "duration" => ! empty ( $parameters["Duration"]) ? (int) $parameters["Duration"] : 0,
    "billsec" => ! empty ( $parameters["BillableSeconds"]) ? (int) $parameters["BillableSeconds"] : 0,
    "disposition" => ! empty ( $parameters["Disposition"]) ? $parameters["Disposition"] : "",
    "amaflags" => ! empty ( $parameters["AMAFlags"]) ? $parameters["AMAFlags"] : "",
    "accountcode" => ! empty ( $parameters["AccountCode"]) ? $parameters["AccountCode"] : "",
    "uniqueid" => ! empty ( $parameters["UniqueID"]) ? $parameters["UniqueID"] : "",
    "linkedid" => ! empty ( $parameters["LinkedID"]) ? $parameters["LinkedID"] : "",
    "userfield" => ! empty ( $parameters["UserField"]) ? $parameters["UserField"] : "",
    "server" => ! empty ( $parameters["Server"]) ? $parameters["Server"] : $_in["daemon"]["serverid"],
    "sourcetype" => ! empty ( $parameters["SourceType"]) ? $parameters["SourceType"] : "0",
    "calltype" => ! empty ( $parameters["CallType"]) ? $parameters["CallType"] : "0",
    "gateway" => ! empty ( $parameters["Gateway"]) ? $parameters["Gateway"] : "0",
    "value" => ! empty ( $parameters["Value"]) ? $parameters["Value"] : "",
    "processed" => ! empty ( $parameters["BillingProcessed"]) ? $parameters["BillingProcessed"] : "0",
    "nativecodec" => ! empty ( $parameters["NativeCodec"]) ? $parameters["NativeCodec"] : "",
    "readcodec" => ! empty ( $parameters["ReadCodec"]) ? $parameters["ReadCodec"] : "",
    "writecodec" => ! empty ( $parameters["WriteCodec"]) ? $parameters["WriteCodec"] : "",
    "QOS" => ! empty ( $parameters["QOS"]) ? $parameters["QOS"] : "",
    "WhoHungUp" => ! empty ( $parameters["WhoHungUp"]) ? $parameters["WhoHungUp"] : "",
    "SIPID" => ! empty ( $parameters["SIPID"]) ? $parameters["SIPID"] : "",
    "flags" => ! empty ( $parameters["Flags"]) ? $parameters["Flags"] : "",
    "monitor" => ! empty ( $parameters["Monitor"]) ? $parameters["Monitor"] : "",
    "userfieldextra" => ! empty ( $parameters["UserFieldExtra"]) ? $parameters["UserFieldExtra"] : "",
    "hangupcause" => ! empty ( $parameters["HangupCause"]) ? $parameters["HangupCause"] : "0",
    "dialstatus" => ! empty ( $parameters["DialStatus"]) ? $parameters["DialStatus"] : "",
    "encryption" => ! empty ( $parameters["Encryption"]) ? $parameters["Encryption"] : ""
  );

  $result = @$gm->doBackground ( "cdr_new", json_encode ( $payload));

  if ( $gm->returnCode () == GEARMAN_SUCCESS)
  {
    cdr_mark_sqlite_processed ( $payload["uniqueid"]);
    writeLog ( "CDR forwarded to router: " . $payload["src"] . " -> " . $payload["dst"] . " (" . $payload["uniqueid"] . ")");
  } else {
    writeLog ( "CDR forward to router failed for " . $payload["uniqueid"] . ": " . $gm->error (), VoIP_LOG_WARNING);
  }

  return $buffer;
}

/**
 * Startup synchronization — query local SQLite3 for unprocessed CDR records
 * and forward them to the router. Called on FullyBooted after AMI connects.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system
 * @param array $parameters AMI event parameters
 * @return mixed Hook buffer
 */
function cdr_startup_sync ( $buffer, $parameters)
{
  global $_in, $gm;

  $dbpath = CDR_SQLITE_PATH;
  if ( ! file_exists ( $dbpath))
  {
    writeLog ( "CDR startup sync: SQLite database not found at " . $dbpath . ", skipping.");
    return $buffer;
  }

  try
  {
    $db = new SQLite3 ( $dbpath, SQLITE3_OPEN_READWRITE);
  } catch ( Exception $e)
  {
    writeLog ( "CDR startup sync: cannot open SQLite database: " . $e->getMessage (), VoIP_LOG_WARNING);
    return $buffer;
  }

  $result = @$db->query ( "SELECT rowid, * FROM cdr WHERE processed = 0 ORDER BY calldate ASC");
  if ( ! $result)
  {
    writeLog ( "CDR startup sync: query failed: " . $db->lastErrorMsg (), VoIP_LOG_WARNING);
    $db->close ();
    return $buffer;
  }

  $count = 0;
  $errors = 0;
  while ( $row = $result->fetchArray ( SQLITE3_ASSOC))
  {
    $payload = array (
      "ServerID" => $_in["daemon"]["serverid"],
      "calldate" => $row["calldate"],
      "clid" => $row["clid"],
      "src" => $row["src"],
      "dst" => $row["dst"],
      "dcontext" => $row["dcontext"],
      "channel" => $row["channel"],
      "dstchannel" => $row["dstchannel"],
      "lastapp" => $row["lastapp"],
      "lastdata" => $row["lastdata"],
      "duration" => (int) $row["duration"],
      "billsec" => (int) $row["billsec"],
      "disposition" => $row["disposition"],
      "amaflags" => $row["amaflags"],
      "accountcode" => $row["accountcode"],
      "uniqueid" => $row["uniqueid"],
      "linkedid" => $row["linkedid"],
      "userfield" => $row["userfield"],
      "server" => ! empty ( $row["server"]) ? $row["server"] : $_in["daemon"]["serverid"],
      "sourcetype" => ! empty ( $row["sourcetype"]) ? $row["sourcetype"] : "0",
      "calltype" => ! empty ( $row["calltype"]) ? $row["calltype"] : "0",
      "gateway" => ! empty ( $row["gateway"]) ? $row["gateway"] : "0",
      "value" => ! empty ( $row["value"]) ? $row["value"] : "",
      "processed" => ! empty ( $row["billing_processed"]) ? $row["billing_processed"] : "0",
      "nativecodec" => ! empty ( $row["nativecodec"]) ? $row["nativecodec"] : "",
      "readcodec" => ! empty ( $row["readcodec"]) ? $row["readcodec"] : "",
      "writecodec" => ! empty ( $row["writecodec"]) ? $row["writecodec"] : "",
      "QOS" => ! empty ( $row["QOS"]) ? $row["QOS"] : "",
      "WhoHungUp" => ! empty ( $row["WhoHungUp"]) ? $row["WhoHungUp"] : "",
      "SIPID" => ! empty ( $row["SIPID"]) ? $row["SIPID"] : "",
      "flags" => ! empty ( $row["flags"]) ? $row["flags"] : "",
      "monitor" => ! empty ( $row["monitor"]) ? $row["monitor"] : "",
      "userfieldextra" => ! empty ( $row["userfieldextra"]) ? $row["userfieldextra"] : "",
      "hangupcause" => ! empty ( $row["hangupcause"]) ? $row["hangupcause"] : "0",
      "dialstatus" => ! empty ( $row["dialstatus"]) ? $row["dialstatus"] : "",
      "encryption" => ! empty ( $row["encryption"]) ? $row["encryption"] : ""
    );

    $gm->doBackground ( "cdr_new", json_encode ( $payload));
    if ( $gm->returnCode () == GEARMAN_SUCCESS)
    {
      $db->exec ( "UPDATE cdr SET processed = 1 WHERE rowid = " . (int) $row["rowid"]);
      $count++;
    } else {
      $errors++;
    }
  }

  $db->close ();

  if ( $count > 0 || $errors > 0)
  {
    writeLog ( "CDR startup sync: forwarded " . $count . " records" . ( $errors > 0 ? ", " . $errors . " errors" : "") . ".");
  }

  return $buffer;
}

/**
 * Mark a CDR record as processed in the local SQLite3 database.
 *
 * @param string $uniqueid The CDR unique identifier
 * @return boolean True on success
 */
function cdr_mark_sqlite_processed ( $uniqueid)
{
  $dbpath = CDR_SQLITE_PATH;
  if ( ! file_exists ( $dbpath))
  {
    return false;
  }

  try
  {
    $db = new SQLite3 ( $dbpath, SQLITE3_OPEN_READWRITE);
    $stmt = $db->prepare ( "UPDATE cdr SET processed = 1 WHERE uniqueid = :uid AND processed = 0");
    $stmt->bindValue ( ":uid", $uniqueid, SQLITE3_TEXT);
    $stmt->execute ();
    $db->close ();
    return true;
  } catch ( Exception $e)
  {
    writeLog ( "CDR mark processed failed for " . $uniqueid . ": " . $e->getMessage (), VoIP_LOG_WARNING);
    return false;
  }
}
?>
