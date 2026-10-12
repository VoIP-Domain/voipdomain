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
 * Return the meta file path for a transfer session.
 *
 * @param string $transferid Transfer session identifier
 * @return string
 */
function file_transfer_meta_path ( $transferid)
{
  return file_transfer_staging_dir () . basename ( $transferid) . ".meta";
}

/**
 * Return the partial file path for a transfer session.
 *
 * @param string $transferid Transfer session identifier
 * @return string
 */
function file_transfer_part_path ( $transferid)
{
  return file_transfer_staging_dir () . basename ( $transferid) . ".part";
}

/**
 * Load transfer session metadata from disk.
 *
 * @param string $transferid Transfer session identifier
 * @return array|false
 */
function file_transfer_load_session ( $transferid)
{
  $path = file_transfer_meta_path ( $transferid);
  if ( ! is_readable ( $path))
  {
    return false;
  }
  $session = json_decode ( file_get_contents ( $path), true);
  if ( ! is_array ( $session))
  {
    return false;
  }
  return $session;
}

/**
 * Persist transfer session metadata to disk.
 *
 * @param array $session Session data
 * @return boolean
 */
function file_transfer_save_session ( $session)
{
  if ( ! array_key_exists ( "TransferID", $session))
  {
    return false;
  }
  if ( ! is_dir ( file_transfer_staging_dir ()))
  {
    mkdir ( file_transfer_staging_dir (), 0755, true);
  }
  return file_put_contents ( file_transfer_meta_path ( $session["TransferID"]), json_encode ( $session)) !== false;
}

/**
 * Remove transfer session files from disk.
 *
 * @param string $transferid Transfer session identifier
 * @return void
 */
function file_transfer_cleanup_session ( $transferid)
{
  if ( is_readable ( file_transfer_meta_path ( $transferid)))
  {
    unlink ( file_transfer_meta_path ( $transferid));
  }
  if ( is_readable ( file_transfer_part_path ( $transferid)))
  {
    unlink ( file_transfer_part_path ( $transferid));
  }
}

/**
 * Validate a transfer session identifier.
 *
 * @param string $transferid Transfer session identifier
 * @return boolean
 */
function file_transfer_valid_id ( $transferid)
{
  return is_string ( $transferid) && preg_match ( "/^[a-f0-9]{64}$/i", $transferid);
}
?>
