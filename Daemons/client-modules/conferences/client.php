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
 * VoIP Domain conferences actions module. This module add the Asterisk client
 * actions calls related to conferences.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Conferences
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call hooks
 */
framework_add_hook ( "conference_add", "conference_add");
framework_add_hook ( "conference_change", "conference_change");
framework_add_hook ( "conference_remove", "conference_remove");

/**
 * Bootstrap hook: confbridge configuration includes
 */
framework_add_hook ( "asterisk_confbridge_confbridge", "conference_confbridge_hook");

/**
 * Cleanup functions
 */
framework_add_hook ( "conference_wipe", "conference_wipe");
cleanup_register ( "Conferences", "conference_wipe");

/**
 * Bootstrap hook: include generated confbridge configuration files.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function conference_confbridge_hook ( $buffer, $parameters)
{
  $buffer .= "#include voipdomain/configs/confbridge-*.conf\n";
  return $buffer;
}

/**
 * Function to create a new conference.
 * Required parameters are: (string) Name, (string) Description, (string) PIN,
 * (string) AdminPIN, (int) MaxMembers, (string) MOHClass,
 * (enum['Y','N']) RecordConference, (enum['Y','N']) AnnounceJoinLeave,
 * (enum['Y','N']) WaitForAdmin
 *
 * Possible results:
 *   - 200: OK, conference created (overwritten)
 *   - 201: OK, conference created
 *   - 400: Conference already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function conference_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "conference_add_start"))
  {
    $parameters = framework_call ( "conference_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "conference_add_validate"))
  {
    $data = framework_call ( "conference_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "conference_add_sanitize"))
  {
    $parameters = framework_call ( "conference_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "conference_add_pre"))
  {
    $parameters = framework_call ( "conference_add_pre", $parameters, false, $parameters);
  }

  // Verify if conference exist
  if ( check_config ( "config", "confbridge-" . $parameters["Name"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Conference already exists.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Create file structure
  $conference = "# VoIP Domain - Conference " . $parameters["Name"] . " - " . $parameters["Description"] . "\n" .
                "[vd_conf_" . $parameters["Name"] . "]\n" .
                "type=bridge\n";
  if ( (int) $parameters["MaxMembers"] > 0)
  {
    $conference .= "max_members=" . (int) $parameters["MaxMembers"] . "\n";
  }
  $conference .= "record_conference=" . ( $parameters["RecordConference"] == "Y" ? "yes" : "no") . "\n" .
                 "sound_join=confbridge-join\n" .
                 "sound_leave=confbridge-leave\n" .
                 "\n" .
                 "[vd_conf_" . $parameters["Name"] . "_user]\n" .
                 "type=user\n";
  if ( ! empty ( $parameters["PIN"]))
  {
    $conference .= "pin=" . $parameters["PIN"] . "\n";
  }
  $conference .= "announce_join_leave=" . ( $parameters["AnnounceJoinLeave"] == "Y" ? "yes" : "no") . "\n" .
                 "music_on_hold_when_empty=yes\n";
  if ( ! empty ( $parameters["MOHClass"]))
  {
    $conference .= "music_on_hold_class=" . $parameters["MOHClass"] . "\n";
  }
  if ( $parameters["WaitForAdmin"] == "Y")
  {
    $conference .= "wait_marked=yes\n";
  }
  $conference .= "\n" .
                 "[vd_conf_" . $parameters["Name"] . "_admin]\n" .
                 "type=user\n";
  if ( ! empty ( $parameters["AdminPIN"]))
  {
    $conference .= "pin=" . $parameters["AdminPIN"] . "\n";
  }
  $conference .= "admin=yes\n" .
                 "marked=yes\n" .
                 "announce_join_leave=" . ( $parameters["AnnounceJoinLeave"] == "Y" ? "yes" : "no") . "\n";

  // Write conference file
  if ( ! write_config ( "config", "confbridge-" . $parameters["Name"], $conference))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create conference data file
  if ( ! write_config ( "datafile", "conference-" . $parameters["Name"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "conference_add_post") && ! framework_call ( "conference_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk ConfBridge module
  asterisk_exec ( "module reload app_confbridge.so");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "conference_add_finish"))
  {
    framework_call ( "conference_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Conference created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Conference created.");
  }
}

/**
 * Function to change an existing conference.
 * Required parameters are: (string) Name, (string) NewName,
 * (string) Description, (string) PIN, (string) AdminPIN, (int) MaxMembers,
 * (string) MOHClass, (enum['Y','N']) RecordConference,
 * (enum['Y','N']) AnnounceJoinLeave, (enum['Y','N']) WaitForAdmin
 *
 * Possible results:
 *   - 200: OK, conference changed
 *   - 404: Conference doesn't exist
 *   - 406: Invalid parameters
 *   - 409: Conference new name already exist
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function conference_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "conference_change_start"))
  {
    $parameters = framework_call ( "conference_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NewName", $parameters))
  {
    $data["NewName"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NewName", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["NewName"]))
  {
    $data["NewName"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "conference_change_validate"))
  {
    $data = framework_call ( "conference_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "conference_change_sanitize"))
  {
    $parameters = framework_call ( "conference_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "conference_change_pre"))
  {
    $parameters = framework_call ( "conference_change_pre", $parameters, false, $parameters);
  }

  // Verify if conference exist
  if ( ! check_config ( "config", "confbridge-" . $parameters["Name"]))
  {
    return array ( "code" => 404, "message" => "Conference doesn't exist.");
  }

  // Verify if new conference exist
  if ( $parameters["Name"] != $parameters["NewName"] && check_config ( "config", "confbridge-" . $parameters["NewName"]))
  {
    return array ( "code" => 409, "message" => "New conference already exists.");
  }

  // Remove conference files
  if ( ! unlink_config ( "config", "confbridge-" . $parameters["Name"]) || ! unlink_config ( "datafile", "conference-" . $parameters["Name"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $conference = "# VoIP Domain - Conference " . $parameters["NewName"] . " - " . $parameters["Description"] . "\n" .
                "[vd_conf_" . $parameters["NewName"] . "]\n" .
                "type=bridge\n";
  if ( (int) $parameters["MaxMembers"] > 0)
  {
    $conference .= "max_members=" . (int) $parameters["MaxMembers"] . "\n";
  }
  $conference .= "record_conference=" . ( $parameters["RecordConference"] == "Y" ? "yes" : "no") . "\n" .
                 "sound_join=confbridge-join\n" .
                 "sound_leave=confbridge-leave\n" .
                 "\n" .
                 "[vd_conf_" . $parameters["NewName"] . "_user]\n" .
                 "type=user\n";
  if ( ! empty ( $parameters["PIN"]))
  {
    $conference .= "pin=" . $parameters["PIN"] . "\n";
  }
  $conference .= "announce_join_leave=" . ( $parameters["AnnounceJoinLeave"] == "Y" ? "yes" : "no") . "\n" .
                 "music_on_hold_when_empty=yes\n";
  if ( ! empty ( $parameters["MOHClass"]))
  {
    $conference .= "music_on_hold_class=" . $parameters["MOHClass"] . "\n";
  }
  if ( $parameters["WaitForAdmin"] == "Y")
  {
    $conference .= "wait_marked=yes\n";
  }
  $conference .= "\n" .
                 "[vd_conf_" . $parameters["NewName"] . "_admin]\n" .
                 "type=user\n";
  if ( ! empty ( $parameters["AdminPIN"]))
  {
    $conference .= "pin=" . $parameters["AdminPIN"] . "\n";
  }
  $conference .= "admin=yes\n" .
                 "marked=yes\n" .
                 "announce_join_leave=" . ( $parameters["AnnounceJoinLeave"] == "Y" ? "yes" : "no") . "\n";

  // Write conference file
  if ( ! write_config ( "config", "confbridge-" . $parameters["NewName"], $conference))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create conference data file
  $parameters["Name"] = $parameters["NewName"];
  unset ( $parameters["NewName"]);
  if ( ! write_config ( "datafile", "conference-" . $parameters["Name"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "conference_change_post") && ! framework_call ( "conference_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk ConfBridge module
  asterisk_exec ( "module reload app_confbridge.so");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "conference_change_finish"))
  {
    framework_call ( "conference_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Conference changed.");
}

/**
 * Function to remove an existing conference.
 * Required parameters are: (string) Name
 * Possible results:
 *   - 200: OK, conference removed
 *   - 404: Conference doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function conference_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "conference_remove_start"))
  {
    $parameters = framework_call ( "conference_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "conference_remove_validate"))
  {
    $data = framework_call ( "conference_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "conference_remove_sanitize"))
  {
    $parameters = framework_call ( "conference_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "conference_remove_pre"))
  {
    $parameters = framework_call ( "conference_remove_pre", $parameters, false, $parameters);
  }

  // Verify if conference exist
  if ( ! check_config ( "config", "confbridge-" . $parameters["Name"]))
  {
    return array ( "code" => 404, "message" => "Conference doesn't exist.");
  }

  // Remove conference files
  if ( ! unlink_config ( "config", "confbridge-" . $parameters["Name"]) || ! unlink_config ( "datafile", "conference-" . $parameters["Name"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "conference_remove_post") && ! framework_call ( "conference_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk ConfBridge module
  asterisk_exec ( "module reload app_confbridge.so");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "conference_remove_finish"))
  {
    framework_call ( "conference_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Conference removed.");
}

/**
 * Function to remove all existing conferences configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function conference_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "conference_wipe_start"))
  {
    $parameters = framework_call ( "conference_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "conference_wipe_pre"))
  {
    framework_call ( "conference_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "conference") as $filename)
  {
    unlink_config ( "config", "confbridge-" . substr ( $filename, strrpos ( $filename, "-") + 1));
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "conference_wipe_post"))
  {
    framework_call ( "conference_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk ConfBridge module
  asterisk_exec ( "module reload app_confbridge.so");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "conference_wipe_finish"))
  {
    framework_call ( "conference_wipe_finish", $parameters);
  }
}
?>
