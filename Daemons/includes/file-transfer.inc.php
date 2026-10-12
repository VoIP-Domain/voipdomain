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
 * VoIP Domain remote file transfer protocol (receiver side).
 *
 * Temporary files use UUID-based names. Each transfer session persists a .data
 * JSON sidecar with chunk hashes (SHA1), file metadata, and progress state.
 * On daemon startup, pending .data files are reloaded into memory so interrupted
 * transfers can resume.
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
 * In-memory index of active transfer sessions (keyed by TransferID).
 */
$GLOBALS["_vdft_sessions"] = array ();

/**
 * Return the directory used to stage incoming file transfers.
 *
 * @global array $_in Framework global configuration variable
 * @return string
 */
function file_transfer_staging_dir ()
{
  global $_in;

  if ( array_key_exists ( "general", $_in) && array_key_exists ( "transferdir", $_in["general"]) && is_dir ( $_in["general"]["transferdir"]))
  {
    return rtrim ( $_in["general"]["transferdir"], "/") . "/";
  }
  if ( is_dir ( "/var/spool/voipdomain/file-transfer"))
  {
    return "/var/spool/voipdomain/file-transfer/";
  }
  return sys_get_temp_dir () . "/voipdomain-file-transfer/";
}

/**
 * Generate a UUID v4.
 *
 * @return string
 */
function file_transfer_uuid ()
{
  $data = random_bytes ( 16);
  $data[6] = chr ( ord ( $data[6]) & 0x0f | 0x40);
  $data[8] = chr ( ord ( $data[8]) & 0x3f | 0x80);
  return vsprintf ( "%s%s-%s-%s-%s-%s%s%s", str_split ( bin2hex ( $data), 4));
}

/**
 * Return the .data sidecar path for a session UUID.
 *
 * @param string $uuid Session file UUID
 * @return string
 */
function file_transfer_data_path ( $uuid)
{
  return file_transfer_staging_dir () . $uuid . ".data";
}

/**
 * Return the temporary file path for a session UUID.
 *
 * @param string $uuid Session file UUID
 * @return string
 */
function file_transfer_tmp_path ( $uuid)
{
  return file_transfer_staging_dir () . $uuid . ".tmp";
}

/**
 * Load a session from memory cache. Falls back to disk if not cached.
 *
 * @param string $transferid Transfer session identifier
 * @return array|false
 */
function file_transfer_load_session ( $transferid)
{
  if ( array_key_exists ( $transferid, $GLOBALS["_vdft_sessions"]))
  {
    return $GLOBALS["_vdft_sessions"][$transferid];
  }
  return false;
}

/**
 * Persist session to both memory and disk (.data JSON sidecar).
 *
 * @param array $session Session data (must contain TransferID and UUID)
 * @return boolean
 */
function file_transfer_save_session ( $session)
{
  if ( ! array_key_exists ( "TransferID", $session) || ! array_key_exists ( "UUID", $session))
  {
    return false;
  }
  if ( ! is_dir ( file_transfer_staging_dir ()))
  {
    mkdir ( file_transfer_staging_dir (), 0755, true);
  }
  $GLOBALS["_vdft_sessions"][$session["TransferID"]] = $session;
  $datapath = file_transfer_data_path ( $session["UUID"]);
  $tmpwrite = $datapath . ".new";
  if ( file_put_contents ( $tmpwrite, json_encode ( $session, JSON_PRETTY_PRINT)) === false)
  {
    return false;
  }
  return rename ( $tmpwrite, $datapath);
}

/**
 * Remove transfer session from memory and disk.
 *
 * @param string $transferid Transfer session identifier
 * @return void
 */
function file_transfer_cleanup_session ( $transferid)
{
  if ( ! array_key_exists ( $transferid, $GLOBALS["_vdft_sessions"]))
  {
    return;
  }
  $session = $GLOBALS["_vdft_sessions"][$transferid];
  $uuid = $session["UUID"];
  if ( is_file ( file_transfer_data_path ( $uuid)))
  {
    unlink ( file_transfer_data_path ( $uuid));
  }
  if ( is_file ( file_transfer_data_path ( $uuid) . ".new"))
  {
    @unlink ( file_transfer_data_path ( $uuid) . ".new");
  }
  if ( is_file ( file_transfer_tmp_path ( $uuid)))
  {
    unlink ( file_transfer_tmp_path ( $uuid));
  }
  unset ( $GLOBALS["_vdft_sessions"][$transferid]);
}

/**
 * Validate a transfer session identifier (SHA256 hex string).
 *
 * @param string $transferid Transfer session identifier
 * @return boolean
 */
function file_transfer_valid_id ( $transferid)
{
  return is_string ( $transferid) && preg_match ( "/^[a-f0-9]{64}$/i", $transferid);
}

/**
 * Maximum age for a transfer session in seconds (24 hours).
 */
define ( "FILE_TRANSFER_SESSION_TTL", 86400);

/**
 * Scan the staging directory for .data sidecar files and reload pending
 * transfer sessions into memory. Called once at daemon startup.
 * Removes stale sessions that exceed the TTL.
 *
 * @return int Number of sessions restored
 */
function file_transfer_restore_sessions ()
{
  $dir = file_transfer_staging_dir ();
  if ( ! is_dir ( $dir))
  {
    return 0;
  }
  $restored = 0;
  $now = time ();
  foreach ( glob ( $dir . "*.data") as $datafile)
  {
    $json = file_get_contents ( $datafile);
    if ( $json === false)
    {
      @unlink ( $datafile);
      continue;
    }
    $session = json_decode ( $json, true);
    if ( ! is_array ( $session) || ! array_key_exists ( "TransferID", $session) || ! array_key_exists ( "UUID", $session))
    {
      @unlink ( $datafile);
      continue;
    }
    if ( ! is_file ( file_transfer_tmp_path ( $session["UUID"])))
    {
      unlink ( $datafile);
      continue;
    }
    if ( array_key_exists ( "CreatedAt", $session) && ( $now - strtotime ( $session["CreatedAt"])) > FILE_TRANSFER_SESSION_TTL)
    {
      @unlink ( file_transfer_tmp_path ( $session["UUID"]));
      @unlink ( $datafile);
      continue;
    }
    $GLOBALS["_vdft_sessions"][$session["TransferID"]] = $session;
    $restored++;
  }

  foreach ( glob ( $dir . "*.tmp") as $tmpfile)
  {
    $uuid = basename ( $tmpfile, ".tmp");
    if ( ! is_file ( $dir . $uuid . ".data"))
    {
      @unlink ( $tmpfile);
    }
  }

  foreach ( glob ( $dir . "*.data.new") as $stale)
  {
    @unlink ( $stale);
  }

  return $restored;
}

/**
 * Pre-allocate a file to the given size by writing null bytes.
 *
 * @param string $path File path
 * @param int $size Target size in bytes
 * @return boolean
 */
function file_transfer_preallocate ( $path, $size)
{
  $handle = fopen ( $path, "wb");
  if ( ! $handle)
  {
    return false;
  }
  if ( $size > 0)
  {
    if ( fseek ( $handle, $size - 1) !== 0)
    {
      fclose ( $handle);
      return false;
    }
    if ( fwrite ( $handle, "\0") === false)
    {
      fclose ( $handle);
      return false;
    }
  }
  fclose ( $handle);
  return true;
}
?>
