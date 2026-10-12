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
 * VoIP Domain remote file transfer protocol (sender side).
 *
 * Protocol events (Gearman / notify_server):
 *   - file_transfer_begin   Start a transfer session
 *   - file_transfer_chunk   Send one chunk (base64 payload)
 *   - file_transfer_commit  Validate and dispatch TargetEvent
 *   - file_transfer_abort   Cancel a session
 *
 * Files smaller than FILE_TRANSFER_INLINE_LIMIT are sent with a single
 * TargetEvent and inline base64 Data (legacy behaviour).
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Maximum raw bytes per chunk (256 KiB).
 */
define ( "FILE_TRANSFER_CHUNK_SIZE", 262144);

/**
 * Files at or below this size use inline Data on TargetEvent.
 */
define ( "FILE_TRANSFER_INLINE_LIMIT", 32768);

/**
 * Build notify events for a file transfer.
 *
 * @param string $filepath Absolute path to the source file
 * @param array $options Transfer options:
 *                       - TargetEvent (required) Final handler event name
 *                       - Metadata (optional) Parameters for the final handler
 *                       - Context (optional) Transfer context label
 * @return array List of array ( "event" => string, "data" => array )
 */
function file_transfer_build_events ( $filepath, $options)
{
  if ( ! is_readable ( $filepath))
  {
    return array ();
  }
  if ( ! array_key_exists ( "TargetEvent", $options) || $options["TargetEvent"] == "")
  {
    return array ();
  }

  $metadata = array_key_exists ( "Metadata", $options) && is_array ( $options["Metadata"]) ? $options["Metadata"] : array ();
  $size = filesize ( $filepath);

  /**
   * Small files: single legacy event with inline payload
   */
  if ( $size <= FILE_TRANSFER_INLINE_LIMIT)
  {
    return array (
      array (
        "event" => $options["TargetEvent"],
        "data" => array_merge ( $metadata, array ( "Data" => base64_encode ( file_get_contents ( $filepath))))
      )
    );
  }

  $transferid = hash ( "sha256", $options["TargetEvent"] . $filepath . microtime ( true) . mt_rand ());
  $hash = hash_file ( "sha256", $filepath);
  $total = (int) ceil ( $size / FILE_TRANSFER_CHUNK_SIZE);
  $events = array (
    array (
      "event" => "file_transfer_begin",
      "data" => array (
        "TransferID" => $transferid,
        "Context" => array_key_exists ( "Context", $options) ? $options["Context"] : "generic",
        "TargetEvent" => $options["TargetEvent"],
        "Metadata" => $metadata,
        "Size" => $size,
        "Hash" => $hash,
        "Chunks" => $total
      )
    )
  );

  $handle = fopen ( $filepath, "rb");
  if ( ! $handle)
  {
    return array ();
  }
  for ( $chunk = 0; $chunk < $total; $chunk++)
  {
    $content = fread ( $handle, FILE_TRANSFER_CHUNK_SIZE);
    if ( $content === false)
    {
      fclose ( $handle);
      return array ();
    }
    $events[] = array (
      "event" => "file_transfer_chunk",
      "data" => array (
        "TransferID" => $transferid,
        "Chunk" => $chunk,
        "Total" => $total,
        "Data" => base64_encode ( $content),
        "Hash" => hash ( "sha256", $content)
      )
    );
  }
  fclose ( $handle);

  $events[] = array (
    "event" => "file_transfer_commit",
    "data" => array (
      "TransferID" => $transferid
    )
  );

  return $events;
}

/**
 * Send a file to one or more servers using the file transfer protocol.
 *
 * @global array $_in Framework global configuration variable
 * @param int $serverid Server ID (0 = all servers)
 * @param string $filepath Absolute path to the source file
 * @param array $options Transfer options (see file_transfer_build_events)
 * @param int $handler[optional] Grouped command handler ID
 * @return boolean
 */
function file_transfer_send ( $serverid, $filepath, $options = array (), $handler = null)
{
  $events = file_transfer_build_events ( $filepath, $options);
  if ( sizeof ( $events) == 0)
  {
    return false;
  }
  $transferid = null;
  foreach ( $events as $event)
  {
    if ( $event["event"] == "file_transfer_begin" && array_key_exists ( "TransferID", $event["data"]))
    {
      $transferid = $event["data"]["TransferID"];
    }
    if ( ! notify_server ( $serverid, $event["event"], $event["data"], $handler))
    {
      if ( $transferid)
      {
        notify_server ( $serverid, "file_transfer_abort", array ( "TransferID" => $transferid));
      }
      return false;
    }
  }
  return true;
}
?>
