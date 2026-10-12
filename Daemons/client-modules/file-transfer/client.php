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
 * VoIP Domain remote file transfer client handlers.
 *
 * Transfer protocol:
 *   1. file_transfer_begin  — creates UUID-named temp file pre-allocated to
 *      final size, with a .data JSON sidecar for progress tracking
 *   2. file_transfer_chunk  — validates each chunk (SHA1), writes to correct
 *      offset in the temp file, records hash in .data
 *   3. file_transfer_commit — verifies all chunks received, validates full file
 *      hash (SHA256), dispatches TargetEvent
 *   4. file_transfer_abort  — removes session and temp files
 *
 * Multiple files per logical event are handled as separate TransferIDs, each
 * independently managed.
 *
 * On daemon startup, pending .data files are scanned and reloaded so
 * interrupted transfers resume without re-transferring received chunks.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage File Transfer
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

framework_add_hook ( "file_transfer_begin", "file_transfer_begin");
framework_add_hook ( "file_transfer_chunk", "file_transfer_chunk");
framework_add_hook ( "file_transfer_commit", "file_transfer_commit");
framework_add_hook ( "file_transfer_abort", "file_transfer_abort");

/**
 * Start a remote file transfer session.
 *
 * Creates a UUID-named temporary file pre-allocated to the expected final size,
 * and a .data JSON sidecar that tracks per-chunk SHA1 hashes and progress.
 *
 * @param string $buffer Buffer from plugin system if processed by other function before
 * @param array $parameters Optional parameters to the function
 * @return array
 */
function file_transfer_begin ( $buffer, $parameters)
{
  $required = array ( "TransferID", "TargetEvent", "Size", "Hash", "Chunks");
  foreach ( $required as $key)
  {
    if ( ! array_key_exists ( $key, $parameters))
    {
      return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( $key => "Missing parameter"));
    }
  }
  if ( ! file_transfer_valid_id ( $parameters["TransferID"]))
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( "TransferID" => "Invalid content"));
  }
  if ( ! preg_match ( "/^[a-f0-9]{64}$/i", $parameters["Hash"]))
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( "Hash" => "Invalid content"));
  }
  if ( (int) $parameters["Chunks"] < 1 || (int) $parameters["Size"] < 1)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.");
  }
  if ( ! array_key_exists ( "Metadata", $parameters) || ! is_array ( $parameters["Metadata"]))
  {
    $parameters["Metadata"] = array ();
  }

  file_transfer_cleanup_session ( $parameters["TransferID"]);
  if ( ! is_dir ( file_transfer_staging_dir ()))
  {
    mkdir ( file_transfer_staging_dir (), 0755, true);
  }

  $uuid = file_transfer_uuid ();
  if ( ! file_transfer_preallocate ( file_transfer_tmp_path ( $uuid), (int) $parameters["Size"]))
  {
    return array ( "code" => 500, "message" => "Error creating transfer staging file.");
  }

  $session = array (
    "TransferID" => $parameters["TransferID"],
    "UUID" => $uuid,
    "Context" => array_key_exists ( "Context", $parameters) ? $parameters["Context"] : "generic",
    "TargetEvent" => $parameters["TargetEvent"],
    "Metadata" => $parameters["Metadata"],
    "Filename" => array_key_exists ( "Filename", $parameters["Metadata"]) ? $parameters["Metadata"]["Filename"] : null,
    "Size" => (int) $parameters["Size"],
    "Hash" => strtolower ( $parameters["Hash"]),
    "Chunks" => (int) $parameters["Chunks"],
    "ChunkHashes" => array (),
    "Received" => array (),
    "CreatedAt" => date ( "c")
  );
  if ( ! file_transfer_save_session ( $session))
  {
    @unlink ( file_transfer_tmp_path ( $uuid));
    return array ( "code" => 500, "message" => "Error creating transfer session.");
  }

  writeLog ( "VDFT begin: TransferID=" . $parameters["TransferID"] . " UUID=" . $uuid . " Size=" . $parameters["Size"] . " Chunks=" . $parameters["Chunks"], VoIP_LOG_NOTICE);
  return array ( "code" => 200, "message" => "Transfer session started.");
}

/**
 * Receive one chunk of a remote file transfer session.
 *
 * Validates the chunk data against its SHA1 hash, writes it at the correct
 * offset in the pre-allocated temp file, and records the hash in the .data
 * sidecar for resumability.
 *
 * @param string $buffer Buffer from plugin system if processed by other function before
 * @param array $parameters Optional parameters to the function
 * @return array
 */
function file_transfer_chunk ( $buffer, $parameters)
{
  $required = array ( "TransferID", "Chunk", "Total", "Data", "Hash");
  foreach ( $required as $key)
  {
    if ( ! array_key_exists ( $key, $parameters))
    {
      return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( $key => "Missing parameter"));
    }
  }
  if ( ! file_transfer_valid_id ( $parameters["TransferID"]))
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( "TransferID" => "Invalid content"));
  }

  $session = file_transfer_load_session ( $parameters["TransferID"]);
  if ( ! $session)
  {
    return array ( "code" => 404, "message" => "Transfer session not found.");
  }
  if ( (int) $parameters["Total"] != (int) $session["Chunks"])
  {
    return array ( "code" => 409, "message" => "Transfer chunk total mismatch.");
  }
  $chunk = (int) $parameters["Chunk"];
  if ( $chunk < 0 || $chunk >= (int) $session["Chunks"])
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( "Chunk" => "Invalid content"));
  }
  if ( in_array ( $chunk, $session["Received"]))
  {
    return array ( "code" => 200, "message" => "Chunk already received.");
  }
  if ( base64_encode ( base64_decode ( $parameters["Data"], true)) !== $parameters["Data"])
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( "Data" => "Invalid content"));
  }
  $content = base64_decode ( $parameters["Data"], true);

  $chunkSha1 = sha1 ( $content);
  if ( hash ( "sha256", $content) !== strtolower ( $parameters["Hash"]))
  {
    return array ( "code" => 409, "message" => "Transfer chunk hash mismatch.");
  }

  $tmpPath = file_transfer_tmp_path ( $session["UUID"]);
  $handle = fopen ( $tmpPath, "c+b");
  if ( ! $handle)
  {
    return array ( "code" => 500, "message" => "Error writing transfer chunk.");
  }
  if ( fseek ( $handle, $chunk * FILE_TRANSFER_CHUNK_SIZE) != 0)
  {
    fclose ( $handle);
    return array ( "code" => 500, "message" => "Error seeking transfer chunk.");
  }
  if ( fwrite ( $handle, $content) === false)
  {
    fclose ( $handle);
    return array ( "code" => 500, "message" => "Error writing transfer chunk.");
  }
  fclose ( $handle);

  $session["Received"][] = $chunk;
  sort ( $session["Received"]);
  $session["ChunkHashes"][$chunk] = $chunkSha1;
  if ( ! file_transfer_save_session ( $session))
  {
    return array ( "code" => 500, "message" => "Error updating transfer session.");
  }

  return array ( "code" => 200, "message" => "Chunk received.");
}

/**
 * Finalize a remote file transfer session and dispatch the target handler.
 *
 * Validates all chunks were received, checks the complete file hash (SHA256),
 * then passes the temp file path to the TargetEvent handler.
 *
 * @param string $buffer Buffer from plugin system if processed by other function before
 * @param array $parameters Optional parameters to the function
 * @return array
 */
function file_transfer_commit ( $buffer, $parameters)
{
  global $_in;

  if ( ! array_key_exists ( "TransferID", $parameters) || ! file_transfer_valid_id ( $parameters["TransferID"]))
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( "TransferID" => "Missing parameter"));
  }

  $session = file_transfer_load_session ( $parameters["TransferID"]);
  if ( ! $session)
  {
    return array ( "code" => 404, "message" => "Transfer session not found.");
  }
  if ( sizeof ( $session["Received"]) != (int) $session["Chunks"])
  {
    return array ( "code" => 409, "message" => "Transfer incomplete.");
  }

  $tmpPath = file_transfer_tmp_path ( $session["UUID"]);
  if ( ! is_readable ( $tmpPath))
  {
    file_transfer_cleanup_session ( $parameters["TransferID"]);
    return array ( "code" => 500, "message" => "Transfer staging file missing.");
  }
  if ( filesize ( $tmpPath) != (int) $session["Size"])
  {
    file_transfer_cleanup_session ( $parameters["TransferID"]);
    return array ( "code" => 409, "message" => "Transfer size mismatch.");
  }
  if ( hash_file ( "sha256", $tmpPath) !== $session["Hash"])
  {
    file_transfer_cleanup_session ( $parameters["TransferID"]);
    return array ( "code" => 409, "message" => "Transfer hash mismatch.");
  }

  $payload = is_array ( $session["Metadata"]) ? $session["Metadata"] : array ();
  $payload["FilePath"] = $tmpPath;
  $payload["TransferID"] = $parameters["TransferID"];

  if ( ! framework_has_hook ( $session["TargetEvent"]))
  {
    file_transfer_cleanup_session ( $parameters["TransferID"]);
    return array ( "code" => 404, "message" => "Target event not found.");
  }

  if ( $_in["debug"])
  {
    file_put_contents ( $_in["eventsfile"], "File transfer target event: " . $session["TargetEvent"] . " (TransferID: " . $parameters["TransferID"] . ", UUID: " . $session["UUID"] . ")\n" . print_r ( $payload, true) . "\n", FILE_APPEND);
  }

  writeLog ( "VDFT commit: TransferID=" . $parameters["TransferID"] . " UUID=" . $session["UUID"] . " Event=" . $session["TargetEvent"], VoIP_LOG_NOTICE);
  $result = framework_call ( $session["TargetEvent"], $payload);
  file_transfer_cleanup_session ( $parameters["TransferID"]);

  if ( ! is_array ( $result) || ! array_key_exists ( "code", $result) || ! array_key_exists ( "message", $result))
  {
    writeLog ( $session["TargetEvent"] . "(" . $parameters["TransferID"] . ") - Target event returned invalid result.", VoIP_LOG_WARNING);
    return array ( "code" => 500, "message" => "Target event returned invalid result.");
  }
  writeLog ( $session["TargetEvent"] . "(" . $parameters["TransferID"] . ") - Result " . $result["code"] . ": " . $result["message"] . ( array_key_exists ( "content", $result) && $result["content"] ? " (Content: " . json_encode ( $result["content"]) . ")" : ""), ( $result["code"] < 200 || $result["code"] > 299 ? VoIP_LOG_WARNING : VoIP_LOG_NOTICE));
  return $result;
}

/**
 * Abort a remote file transfer session.
 *
 * @param string $buffer Buffer from plugin system if processed by other function before
 * @param array $parameters Optional parameters to the function
 * @return array
 */
function file_transfer_abort ( $buffer, $parameters)
{
  if ( ! array_key_exists ( "TransferID", $parameters) || ! file_transfer_valid_id ( $parameters["TransferID"]))
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( "TransferID" => "Missing parameter"));
  }

  writeLog ( "VDFT abort: TransferID=" . $parameters["TransferID"], VoIP_LOG_NOTICE);
  file_transfer_cleanup_session ( $parameters["TransferID"]);
  return array ( "code" => 200, "message" => "Transfer aborted.");
}
?>
