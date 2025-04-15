<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2025 Ernani José Camargo Azevedo
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
 * VoIP Domain audios actions module. This module add the Asterisk client actions
 * calls related to audio files.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Audios
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "audio_add", "audio_add");
framework_add_hook ( "audio_change", "audio_change");
framework_add_hook ( "audio_remove", "audio_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "audio_wipe", "audio_wipe");
cleanup_register ( "Audios", "audio_wipe");

/**
 * Function to create a new audio.
 * Required parameters are: (int) ID, (string) Filename, (blob) Data
 * Possible results:
 *   - 200: OK, audio created (overwritten)
 *   - 201: OK, audio created
 *   - 400: Audio already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function audio_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audio_add_start"))
  {
    $parameters = framework_call ( "audio_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters))
  {
    $data["ID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "ID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["ID"]))
  {
    $data["ID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Filename", $parameters))
  {
    $data["Filename"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Filename", $data) && $parameters["Filename"] != basename ( $parameters["Filename"]))
  {
    $data["Filename"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Data", $parameters)) 
  { 
    $data["Data"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Data", $data) && base64_encode ( base64_decode ( $parameters["Data"], true)) !== $parameters["Data"])
  {
    $data["Data"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "audio_add_validate"))
  {
    $data = framework_call ( "audio_add_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "audio_add_sanitize"))
  {
    $parameters = framework_call ( "audio_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audio_add_pre"))
  {
    $parameters = framework_call ( "audio_add_pre", $parameters, false, $parameters);
  }

  // Verify if audio exist
  if ( check_config ( "audio", "audio-" . $parameters["ID"] . "/" . $parameters["Filename"] . "-originalfile.bin"))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Audio already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Write audio file
  if ( ! write_config ( "audio", "audio-" . $parameters["ID"] . "/" . $parameters["Filename"] . "-originalfile.bin", base64_decode ( $parameters["Data"])))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // If it's a MP3, convert file to wav, otherwise just copy the file
  if ( strtolower ( substr ( $parameters["Filename"], strrpos ( $parameters["Filename"], ".") + 1)) == "mp3")
  {
    if ( ! mp3towav ( "audio-" . $parameters["ID"] . "/" . addslashes ( $parameters["Filename"]) . "-originalfile.bin", "audio-" . $parameters["ID"] . "/audio.wav"))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  } else {
    copy_config ( "audio-" . $parameters["ID"] . "/" . $parameters["Filename"] . "-originalfile.bin", "audio-" . $parameters["ID"] . "/audio.wav");
  }

  // Then, convert to raw
  if ( ! audiotoraw ( "audio-" . $parameters["ID"] . "/audio.wav", "audio-" . $parameters["ID"] . "/audio.raw"))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }
  unlink_config ( "audio", "audio-" . $parameters["ID"] . "/audio.wav");

  // Convert audio file using Asterisk server to all available formats
  $filename = substr ( $parameters["Filename"], 0, strrpos ( $parameters["Filename"], "."));
  $formats = array ( "WAV", "wav16", "wav", "vox", "sln192", "sln96", "sln48", "sln44", "sln32", "sln24", "sln16", "sln12", "sln", "siren7", "siren14", "g722", "ulaw", "alaw", "ogg", "ilbc", "gsm", "g729", "g726-16", "g726-32", "g723");
  $parameters["AvailableFormats"] = array ();
  foreach ( $formats as $format)
  {
    exec ( "asterisk -rx \"file convert voipdomain/audio-" . $parameters["ID"] . "/audio.raw voipdomain/audio-" . $parameters["ID"] . "/audio." . $format . "\" 1>/dev/null 2>&1");
    if ( rename_config ( "audio", "audio-" . $parameters["ID"] . "/audio." . $format, "audio-" . $parameters["ID"] . "/" . $filename . "." . $format))
    {
      $parameters["AvailableFormats"][] = $format;
    }
  }
  unlink_config ( "audio", "audio-" . $parameters["ID"] . "/audio.raw");

  // Create Music on Hold for this audio file
  $moh = "[vd_moh_" . $parameters["ID"] . "]\n" .
         "mode=files\n" .
         "directory=voipdomain/audio-" . $parameters["ID"] . "\n";

  // Write Music on Hold configuration file
  if ( ! write_config ( "config", "moh-" . $parameters["ID"], $moh))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create audio data file
  unset ( $parameters["Data"]);
  if ( ! write_config ( "datafile", "audio-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audio_add_post") && ! framework_call ( "audio_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk Music on Hold
  asterisk_exec ( "moh reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audio_add_finish"))
  {
    framework_call ( "audio_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Audio created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Audio created.");
  }
}

/**
 * Function to change an existing audio.
 * Required parameters are: (int) ID, (string) Filename, (string) NewFilename, (blob) Data
 * Possible results:
 *   - 200: OK, audio changed
 *   - 404: Audio doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function audio_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audio_change_start"))
  {
    $parameters = framework_call ( "audio_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters))
  {
    $data["ID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "ID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["ID"]))
  {
    $data["ID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Filename", $parameters))
  {
    $data["Filename"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Filename", $data) && $parameters["Filename"] != basename ( $parameters["Filename"]))
  {
    $data["Filename"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NewFilename", $parameters))
  {
    $data["NewFilename"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NewFilename", $data) && $parameters["NewFilename"] != basename ( $parameters["NewFilename"]))
  {
    $data["NewFilename"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Data", $parameters))
  {
    $data["Data"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Data", $data) && base64_encode ( base64_decode ( $parameters["Data"], true)) !== $parameters["Data"])
  {
    $data["Data"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "audio_change_validate"))
  {
    $data = framework_call ( "audio_change_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "audio_change_sanitize"))
  {
    $parameters = framework_call ( "audio_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audio_change_pre"))
  {
    $parameters = framework_call ( "audio_change_pre", $parameters, false, $parameters);
  }

  // Verify if audio exist
  if ( ! check_config ( "audio", "audio-" . $parameters["ID"] . "/" . $parameters["Filename"] . "-originalfile.bin"))
  {
    return array ( "code" => 400, "message" => "Audio doesn't exist.");
  }

  // Remove audio files
  if ( ! unlink_config ( "audio", "audio-" . $parameters["ID"] . "/" . $parameters["Filename"] . "-originalfile.bin"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }
  $olddata = read_config ( "datafile", "audio-" . $parameters["ID"]);
  foreach ( $olddata["AvailableFormats"] as $format)
  {
    if ( ! unlink_config ( "audio", "audio-" . $parameters["ID"] . "/" . str_replace ( "[", "\\[", str_replace ( "]", "\\]", str_replace ( "\\", "\\\\", substr ( $parameters["Filename"], 0, strrpos ( $parameters["Filename"], "."))))) . "." . $format))
    {
      return array ( "code" => 500, "message" => "Error removing file.");
    }
  }
  @unlink_config ( "audiodir", "audio-" . $parameters["ID"]);

  // Write audio file
  if ( ! write_config ( "audio", "audio-" . $parameters["ID"] . "/" . $parameters["NewFilename"] . "-originalfile.bin", base64_decode ( $parameters["Data"])))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // If it's a MP3, convert file to wav, otherwise just copy the file
  if ( strtolower ( substr ( $parameters["NewFilename"], strrpos ( $parameters["NewFilename"], ".") + 1)) == "mp3")
  {
    if ( ! mp3towav ( "audio-" . $parameters["ID"] . "/" . addslashes ( $parameters["NewFilename"]) . "-originalfile.bin", "audio-" . $parameters["ID"] . "/audio.wav"))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  } else {
    copy_config ( "audio", "audio-" . $parameters["ID"] . "/" . $parameters["NewFilename"] . "-originalfile.bin", "audio-" . $parameters["ID"] . "/audio.wav");
  }

  // Then, covert to raw
  if ( ! audiotoraw ( "audio-" . $parameters["ID"] . "/audio.wav", "audio-" . $parameters["ID"] . "/audio.raw"))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }
  unlink_config ( "audio", "audio-" . $parameters["ID"] . "/audio.wav");

  // Convert audio file using Asterisk server to all available formats
  $filename = substr ( $parameters["NewFilename"], 0, strrpos ( $parameters["NewFilename"], "."));
  $formats = array ( "WAV", "wav16", "wav", "vox", "sln192", "sln96", "sln48", "sln44", "sln32", "sln24", "sln16", "sln12", "sln", "siren7", "siren14", "g722", "ulaw", "alaw", "ogg", "ilbc", "gsm", "g729", "g726-16", "g726-32", "g723");
  $parameters["AvailableFormats"] = array ();
  foreach ( $formats as $format)
  {
    exec ( "asterisk -rx \"file convert voipdomain/audio-" . $parameters["ID"] . "/audio.raw voipdomain/audio-" . $parameters["ID"] . "/audio." . $format . "\" 1>/dev/null 2>&1");
    if ( rename_config ( "audio", "audio-" . $parameters["ID"] . "/audio." . $format, "audio-" . $parameters["ID"] . "/" . $filename . "." . $format))
    {
      $parameters["AvailableFormats"][] = $format;
    }
  }
  unlink_config ( "audio", "audio-" . $parameters["ID"] . "/audio.raw");

  // Create audio data file
  $parameters["Filename"] = $parameters["NewFilename"];
  unset ( $parameters["NewFilename"]);
  unset ( $parameters["Data"]);
  if ( ! write_config ( "datafile", "audio-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audio_change_post") && ! framework_call ( "audio_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk Music on Hold
  asterisk_exec ( "moh reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audio_change_finish"))
  {
    framework_call ( "audio_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Audio changed.");
}

/**
 * Function to remove an existing audio.
 * Required parameters are: (int) ID
 * Possible results:
 *   - 200: OK, audio removed
 *   - 404: Audio doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function audio_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audio_remove_start"))
  {
    $parameters = framework_call ( "audio_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters))
  {
    $data["ID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "ID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["ID"]))
  {
    $data["ID"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "audio_remove_validate"))
  {
    $data = framework_call ( "audio_remove_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "audio_remove_sanitize"))
  {
    $parameters = framework_call ( "audio_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audio_remove_pre"))
  {
    $parameters = framework_call ( "audio_remove_pre", $parameters, false, $parameters);
  }

  // Verify if audio exist
  $data = read_config ( "datafile", "audio-" . $parameters["ID"]);
  if ( ! check_config ( "audio", "audio-" . $parameters["ID"] . "/" . $data["Filename"] . "-originalfile.bin"))
  {
    return array ( "code" => 404, "message" => "Audio doesn't exist.");
  }

  // Remove audio files
  if ( ! unlink_config ( "audio", "audio-" . $parameters["ID"] . "/" . $data["Filename"] . "-originalfile.bin"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }
  foreach ( $data["AvailableFormats"] as $format)
  {
    if ( ! unlink_config ( "audio", "audio-" . $parameters["ID"] . "/" . str_replace ( "[", "\\[", str_replace ( "]", "\\]", str_replace ( "\\", "\\\\", substr ( $data["Filename"], 0, strrpos ( $data["Filename"], "."))))) . "." . $format))
    {
      return array ( "code" => 500, "message" => "Error removing file.");
    }
  }
  @unlink_config ( "audiodir", "audio-" . $parameters["ID"]);

  // Remove datafile
  if ( ! unlink_config ( "datafile", "audio-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audio_remove_post") && ! framework_call ( "audio_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk Music on Hold
  asterisk_exec ( "moh reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audio_remove_finish"))
  {
    framework_call ( "audio_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Audio removed.");
}

/**
 * Function to remove all existing audios configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function audio_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audio_wipe_start"))
  {
    $parameters = framework_call ( "audio_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audio_wipe_pre"))
  { 
    framework_call ( "audio_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "audio") as $filename)
  {
    $entryid = (int) substr ( $filename, strrpos ( $filename, "-") + 1);
    $data = read_config ( "datafile", $filename);
    unlink_config ( "audio", "audio-" . $data["ID"] . "/" . $data["Filename"] . "-originalfile.bin");
    foreach ( $data["AvailableFormats"] as $format)
    {
      unlink_config ( "audio", "audio-" . $data["ID"] . "/" . str_replace ( "[", "\\[", str_replace ( "]", "\\]", str_replace ( "\\", "\\\\", substr ( $data["Filename"], 0, strrpos ( $data["Filename"], "."))))) . "." . $format);
    }
    unlink_config ( "audiodir", "audio-" . $entryid);
    unlink_config ( "config", "audio-" . $entryid);
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audio_wipe_post"))
  {
    framework_call ( "audio_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk Music on Hold
  asterisk_exec ( "moh reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audio_wipe_finish"))
  {
    framework_call ( "audio_wipe_finish", $parameters);
  }
}
?>
