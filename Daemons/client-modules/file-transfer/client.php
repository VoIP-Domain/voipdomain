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
  if ( ! is_array ( $parameters["Metadata"]))
  {
    $parameters["Metadata"] = array ();
  }

  file_transfer_cleanup_session ( $parameters["TransferID"]);
  if ( ! is_dir ( file_transfer_staging_dir ()))
  {
    mkdir ( file_transfer_staging_dir (), 0755, true);
  }
  if ( ! touch ( file_transfer_part_path ( $parameters["TransferID"])))
  {
    return array ( "code" => 500, "message" => "Error creating transfer staging file.");
  }

  $session = array (
    "TransferID" => $parameters["TransferID"],
    "Context" => array_key_exists ( "Context", $parameters) ? $parameters["Context"] : "generic",
    "TargetEvent" => $parameters["TargetEvent"],
    "Metadata" => $parameters["Metadata"],
    "Size" => (int) $parameters["Size"],
    "Hash" => strtolower ( $parameters["Hash"]),
    "Chunks" => (int) $parameters["Chunks"],
    "Received" => array ()
  );
  if ( ! file_transfer_save_session ( $session))
  {
    file_transfer_cleanup_session ( $parameters["TransferID"]);
    return array ( "code" => 500, "message" => "Error creating transfer session.");
  }

  return array ( "code" => 200, "message" => "Transfer session started.");
}

/**
 * Receive one chunk of a remote file transfer session.
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
  if ( hash ( "sha256", $content) !== strtolower ( $parameters["Hash"]))
  {
    return array ( "code" => 409, "message" => "Transfer chunk hash mismatch.");
  }

  $handle = fopen ( file_transfer_part_path ( $parameters["TransferID"]), "c+b");
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
  if ( ! file_transfer_save_session ( $session))
  {
    return array ( "code" => 500, "message" => "Error updating transfer session.");
  }

  return array ( "code" => 200, "message" => "Chunk received.");
}

/**
 * Finalize a remote file transfer session and dispatch the target handler.
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
  if ( ! is_readable ( file_transfer_part_path ( $parameters["TransferID"])))
  {
    return array ( "code" => 500, "message" => "Transfer staging file missing.");
  }
  if ( filesize ( file_transfer_part_path ( $parameters["TransferID"])) != (int) $session["Size"])
  {
    file_transfer_cleanup_session ( $parameters["TransferID"]);
    return array ( "code" => 409, "message" => "Transfer size mismatch.");
  }
  if ( hash_file ( "sha256", file_transfer_part_path ( $parameters["TransferID"])) !== $session["Hash"])
  {
    file_transfer_cleanup_session ( $parameters["TransferID"]);
    return array ( "code" => 409, "message" => "Transfer hash mismatch.");
  }

  $payload = is_array ( $session["Metadata"]) ? $session["Metadata"] : array ();
  $payload["FilePath"] = file_transfer_part_path ( $parameters["TransferID"]);
  $payload["TransferID"] = $parameters["TransferID"];

  if ( ! framework_has_hook ( $session["TargetEvent"]))
  {
    file_transfer_cleanup_session ( $parameters["TransferID"]);
    return array ( "code" => 404, "message" => "Target event not found.");
  }

  if ( $_in["debug"])
  {
    file_put_contents ( $_in["eventsfile"], "File transfer target event: " . $session["TargetEvent"] . " (TransferID: " . $parameters["TransferID"] . ")\n" . print_r ( $payload, true) . "\n", FILE_APPEND);
  }

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

  file_transfer_cleanup_session ( $parameters["TransferID"]);
  return array ( "code" => 200, "message" => "Transfer aborted.");
}
?>
