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
 * VoIP Domain profiles actions module. This module add the Asterisk client actions
 * calls related to profiles.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Profiles
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "profile_add", "profile_add");
framework_add_hook ( "profile_change", "profile_change");
framework_add_hook ( "profile_remove", "profile_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "profile_wipe", "profile_wipe");
cleanup_register ( "Profiles", "profile_wipe");

/**
 * Include countries files
 */
foreach ( glob ( dirname ( __FILE__) . "/country-*.php") as $filename)
{
  require_once ( $filename);
}

/**
 * Function to create a new profile.
 * Required parameters are: (int) ID, (string) Description, (string) Domain, (string) Country, (string) TimeZone, (float) Offset, (int) AreaCode, (string) Language, (int) Prefix, (int) MOH, (boolean) EmergencyShortcut, (int) NGGW, (array (int)) Gateways[], (array (int)) Blockeds[]
 * Possible results:
 *   - 200: OK, profile created (overwritten)
 *   - 201: OK, profile created
 *   - 400: Profile already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function profile_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "profile_add_start"))
  {
    $parameters = framework_call ( "profile_add_start", $parameters);
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
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Domain", $parameters))
  {
    $data["Domain"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Domain", $data) && ! is_domainname ( $parameters["Domain"]))
  {
    $data["Domain"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Country", $parameters))
  {
    $data["Country"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Country", $data) && ! preg_match ( "/^[A-Z]{2}$/", $parameters["Country"]))
  {
    $data["Country"] = "Invalid content";
  }
  if ( ! array_key_exists ( "TimeZone", $parameters))
  {
    $data["TimeZone"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "TimeZone", $data) && ! preg_match ( "/^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$/", $parameters["TimeZone"]))
  {
    $data["TimeZone"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Offset", $parameters))
  {
    $data["Offset"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Offset", $data) && ( $parameters["Offset"] < -13 || $parameters["Offset"] > 13))
  {
    $data["Offset"] = "Invalid content";
  }
  if ( ! array_key_exists ( "AreaCode", $parameters))
  {
    $data["AreaCode"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "AreaCode", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["AreaCode"]))
  {
    $data["AreaCode"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Language", $parameters))
  {
    $data["Language"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Language", $data) && ! preg_match ( "/^[a-zA-Z]{2}(_[a-zA-Z]{2})?$/", $parameters["Language"]))
  {
    $data["Language"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Prefix", $parameters))
  {
    $data["Prefix"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Prefix", $data) && ! preg_match ( "/^[0-9]$/", $parameters["Prefix"]))
  {
    $data["Prefix"] = "Invalid content";
  }
  if ( ! array_key_exists ( "MOH", $parameters))
  {
    $data["MOH"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "MOH", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["MOH"]))
  {
    $data["MOH"] = "Invalid content";
  }
  if ( ! array_key_exists ( "EmergencyShortcut", $parameters))
  {
    $data["EmergencyShortcut"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "EmergencyShortcut", $data) && ! is_bool ( $parameters["EmergencyShortcut"]))
  {
    $data["EmergencyShortcut"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NGGW", $parameters))
  {
    $data["NGGW"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NGGW", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["NGGW"]))
  {
    $data["NGGW"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Gateways", $parameters))
  {
    $data["Gateways"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Gateways", $data) && ! is_array ( $parameters["Gateways"]))
  {
    $data["Gateways"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Gateways", $data))
  {
    foreach ( $parameters["Gateways"] as $gateway)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $gateway))
      {
        $data["Gateways"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Blockeds", $parameters))
  {
    $data["Blockeds"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Blockeds", $data) && ! is_array ( $parameters["Blockeds"]))
  {
    $data["Blockeds"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Blockeds", $data))
  {
    foreach ( $parameters["Blockeds"] as $gateway)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $gateway))
      {
        $data["Blockeds"] = "Invalid content";
      }
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "profile_add_validate"))
  {
    $data = framework_call ( "profile_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "profile_add_sanitize"))
  {
    $parameters = framework_call ( "profile_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "profile_add_pre"))
  {
    $parameters = framework_call ( "profile_add_pre", $parameters, false, $parameters);
  }

  // Verify if profile exist
  if ( check_config ( "config", "dialplan-profile-" . $parameters["ID"]) || check_config ( "config", "sip-profile-" . $parameters["ID"]) || check_config ( "configdb", "profile-" . $parameters["ID"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Profile already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Create profile structure
  $profile = "; Dialplan profile #" . $parameters["ID"] . ": " . $parameters["Description"] . "\n" .
             "\n" .
             "[VoIPDomain-Profile-" . $parameters["ID"] . "]\n" .
             "include => VoIPDomain-Services\n" .
             "include => VoIPDomain-Hints\n" .
             "include => VoIPDomain-Extensions\n" .
             "\n";

  // Here we add call dialplan specific to each number type depending on country
  if ( framework_has_hook ( "profile_country_" . $parameters["Country"]))
  {
    $profile .= framework_call ( "profile_country_" . $parameters["Country"], $parameters);
  } else {
    $profile .= "; WARNING: Profile country code \"" . $parameters["Country"] . "\" not found!\n";
    writeLog ( "New profile #" . $parameters["ID"] . " with unknown country \"" . $parameters["Country"] . "\"!", VoIP_LOG_WARNING);
  }

  // Write profile file
  if ( ! write_config ( "config", "dialplan-profile-" . $parameters["ID"], $profile))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Write dialplan database file
  if ( ! write_config ( "configdb", "profile-" . $parameters["ID"], array ( "name" => "profile_" . $parameters["ID"], "data" => array ( "nggw" => $parameters["NGGW"], "defaultgw" => implode ( ",", $parameters["Gateways"]), "blockedgw" => implode ( ",", $parameters["Blockeds"])))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create SIP profile structure
  $profile = "[vd_profile_" . $parameters["ID"] . "](!)\n" .
             "context=VoIPDomain-Profile-" . $parameters["ID"] . "\n" .
             "language=" . $parameters["Language"] . "\n";
  if ( $parameters["MOH"])
  {
    $profile .= "moh_suggest=vd_moh_" . $parameters["MOH"] . "\n";
  }

  // Write profile SIP file
  if ( ! write_config ( "config", "sip-profile-" . $parameters["ID"], $profile))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create profile data file
  if ( ! write_config ( "datafile", "profile-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "profile_add_post") && ! framework_call ( "profile_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP and dialplan
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "profile_add_finish"))
  {
    framework_call ( "profile_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Profile created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Profile created.");
  }
}

/**
 * Function to change an existing profile.
 * Required parameters are: (int) ID, (string) Description, (string) Domain, (string) Country, (string) TimeZone, (float) Offset, (int) AreaCode, (string) Language, (int) Prefix, (int) MOH, (boolean) EmergencyShortcut, (int) NGGW, (array (int)) Gateways[], (array (int)) Blockeds[]
 * Possible results:
 *   - 200: OK, profile changed
 *   - 404: Profile doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function profile_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "profile_change_start"))
  {
    $parameters = framework_call ( "profile_change_start", $parameters);
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
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Domain", $parameters))
  {
    $data["Domain"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Domain", $data) && ! is_domainname ( $parameters["Domain"]))
  {
    $data["Domain"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Country", $parameters))
  {
    $data["Country"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Country", $data) && ! preg_match ( "/^[A-Z]{2}$/", $parameters["Country"]))
  {
    $data["Country"] = "Invalid content";
  }
  if ( ! array_key_exists ( "TimeZone", $parameters))
  {
    $data["TimeZone"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "TimeZone", $data) && ! preg_match ( "/^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$/", $parameters["TimeZone"]))
  {
    $data["TimeZone"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Offset", $parameters))
  {
    $data["Offset"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Offset", $data) && ( $parameters["Offset"] < -13 || $parameters["Offset"] > 13))
  {
    $data["Offset"] = "Invalid content";
  }
  if ( ! array_key_exists ( "AreaCode", $parameters))
  {
    $data["AreaCode"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "AreaCode", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["AreaCode"]))
  {
    $data["AreaCode"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Language", $parameters))
  {
    $data["Language"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Language", $data) && ! preg_match ( "/^[a-zA-Z]{2}(_[a-zA-Z]{2})?$/", $parameters["Language"]))
  {
    $data["Language"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Prefix", $parameters))
  {
    $data["Prefix"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Prefix", $data) && ! preg_match ( "/^[0-9]$/", $parameters["Prefix"]))
  {
    $data["Prefix"] = "Invalid content";
  }
  if ( ! array_key_exists ( "MOH", $parameters))
  {
    $data["MOH"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "MOH", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["MOH"]))
  {
    $data["MOH"] = "Invalid content";
  }
  if ( ! array_key_exists ( "EmergencyShortcut", $parameters))
  {
    $data["EmergencyShortcut"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "EmergencyShortcut", $data) && ! is_bool ( $parameters["EmergencyShortcut"]))
  {
    $data["EmergencyShortcut"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NGGW", $parameters))
  {
    $data["NGGW"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NGGW", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["NGGW"]))
  {
    $data["NGGW"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Gateways", $parameters))
  {
    $data["Gateways"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Gateways", $data) && ! is_array ( $parameters["Gateways"]))
  {
    $data["Gateways"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Gateways", $data))
  {
    foreach ( $parameters["Gateways"] as $gateway)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $gateway))
      {
        $data["Gateways"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Blockeds", $parameters))
  {
    $data["Blockeds"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Blockeds", $data) && ! is_array ( $parameters["Blockeds"]))
  {
    $data["Blockeds"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Blockeds", $data))
  {
    foreach ( $parameters["Blockeds"] as $gateway)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $gateway))
      {
        $data["Blockeds"] = "Invalid content";
      }
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "profile_change_validate"))
  {
    $data = framework_call ( "profile_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "profile_change_sanitize"))
  {
    $parameters = framework_call ( "profile_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "profile_change_pre"))
  {
    $parameters = framework_call ( "profile_change_pre", $parameters, false, $parameters);
  }

  // Verify if profile exist
  if ( ! check_config ( "config", "dialplan-profile-" . $parameters["ID"]) || ! check_config ( "config", "sip-profile-" . $parameters["ID"]) || ! check_config ( "configdb" , "profile-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Profile doesn't exist.");
  }

  // Remove current profile files
  if ( ! unlink_config ( "config", "dialplan-profile-" . $parameters["ID"]) || ! unlink_config ( "config", "sip-profile-" . $parameters["ID"]) || ! unlink_config ( "configdb", "profile-" . $parameters["ID"]) || ! unlink_config ( "datafile", "profile-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create profile structure
  $profile = "; Dialplan profile #" . $parameters["ID"] . ": " . $parameters["Description"] . "\n" .
             "\n" .
             "[VoIPDomain-Profile-" . $parameters["ID"] . "]\n" .
             "include => VoIPDomain-Services\n" .
             "include => VoIPDomain-Hints\n" .
             "include => VoIPDomain-Extensions\n" .
             "\n";

  // Here we add call dialplan specific to each number type depending on country
  if ( framework_has_hook ( "profile_country_" . $parameters["Country"]))
  {
    $profile .= framework_call ( "profile_country_" . $parameters["Country"], $parameters);
  } else {
    $profile .= "; WARNING: Profile country code \"" . $parameters["Country"] . "\" not found!\n";
    writeLog ( "New profile #" . $parameters["ID"] . " with unknown country \"" . $parameters["Country"] . "\"!", VoIP_LOG_WARNING);
  }

  // Write profile file
  if ( ! write_config ( "config", "dialplan-profile-" . $parameters["ID"], $profile))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Write dialplan database file
  if ( ! write_config ( "configdb", "profile-" . $parameters["ID"], array ( "name" => "profile_" . $parameters["ID"], "data" => array ( "nggw" => $parameters["NGGW"], "defaultgw" => implode ( ",", $parameters["Gateways"]), "blockedgw" => implode ( ",", $parameters["Blockeds"])))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create SIP profile structure
  $profile = "[vd_profile_" . $parameters["ID"] . "](!)\n" .
             "context=VoIPDomain-Profile-" . $parameters["ID"] . "\n" .
             "language=" . $parameters["Language"] . "\n";
  if ( $parameters["MOH"])
  {
    $profile .= "moh_suggest=vd_moh_" . $parameters["MOH"] . "\n";
  }

  // Write profile SIP file
  if ( ! write_config ( "config", "sip-profile-" . $parameters["ID"], $profile))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create profile data file
  if ( ! write_config ( "datafile", "profile-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }


  // Reload Asterisk SIP and dialplan
  asterisk_exec ( "dialplan reload");
  asterisk_exec ( "pjsip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Profile changed.");
}

/**
 * Function to remove an existing profile.
 * Required parameters are: (int) ID
 * Possible results:
 *   - 200: OK, profile removed
 *   - 404: Profile doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function profile_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "profile_remove_start"))
  {
    $parameters = framework_call ( "profile_remove_start", $parameters);
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
  if ( framework_has_hook ( "profile_remove_validate"))
  {
    $data = framework_call ( "profile_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "profile_remove_sanitize"))
  {
    $parameters = framework_call ( "profile_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "profile_remove_pre"))
  {
    $parameters = framework_call ( "profile_remove_pre", $parameters, false, $parameters);
  }

  // Verify if profile exist
  if ( ! check_config ( "config", "dialplan-profile-" . $parameters["ID"]) || ! check_config ( "config", "sip-profile-" . $parameters["ID"]) || ! check_config ( "configdb", "profile-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Profile doesn't exist.");
  }

  // Remove current profile files
  if ( ! unlink_config ( "config", "dialplan-profile-" . $parameters["ID"]) || ! unlink_config ( "config", "sip-profile-" . $parameters["ID"]) || ! unlink_config ( "configdb", "profile-" . $parameters["ID"]) || ! unlink_config ( "datafile", "profile-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "profile_remove_post") && ! framework_call ( "profile_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP and dialplan
  asterisk_exec ( "dialplan reload");
  asterisk_exec ( "pjsip reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "profile_remove_finish"))
  {
    framework_call ( "profile_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Profile removed.");
}

/**
 * Function to remove all existing profiles configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function profile_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "profile_wipe_start"))
  {
    $parameters = framework_call ( "profile_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "profile_wipe_pre"))
  {
    framework_call ( "profile_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "profile") as $filename)
  {
    $entryid = (int) substr ( $filename, strrpos ( $filename, "-") + 1);
    unlink_config ( "config", "dialplan-profile-" . $entryid);
    unlink_config ( "config", "sip-profile-" . $entryid);
    unlink_config ( "configdb", "profile-" . $entryid);
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "profile_wipe_post"))
  {
    framework_call ( "profile_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk SIP and dialplan
  asterisk_exec ( "dialplan reload");
  asterisk_exec ( "pjsip reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "profile_wipe_finish"))
  {
    framework_call ( "profile_wipe_finish", $parameters);
  }
}
?>
