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
 * VoIP Domain extensions actions module. This module add the Asterisk client
 * actions calls related to extensions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Phones
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "extension_phone_add", "extension_phone_add");
framework_add_hook ( "extension_phone_change", "extension_phone_change");
framework_add_hook ( "extension_phone_remove", "extension_phone_remove");
framework_add_hook ( "account_add", "account_add");
framework_add_hook ( "account_change", "account_change");
framework_add_hook ( "account_remove", "account_remove");
framework_add_hook ( "hint_add", "hint_add");
framework_add_hook ( "hint_remove", "hint_remove");
framework_add_hook ( "ap_add", "ap_add");
framework_add_hook ( "ap_change", "ap_change");
framework_add_hook ( "ap_remove", "ap_remove");

/**
 * Cleanup functions
 */
cleanup_register ( "Extensions", "extension_wipe");
framework_add_hook ( "extension_wipe", "extension_wipe");
cleanup_register ( "Extensions-Phone", "extension_phone_wipe", array ( "Before" => "Extensions"));
framework_add_hook ( "extension_phone_wipe", "extension_phone_wipe");
cleanup_register ( "Extensions-Phone-Hint", "hint_wipe", array ( "Before" => "Extensions-Phone"));
framework_add_hook ( "hint_wipe", "hint_wipe");
cleanup_register ( "Accounts", "account_wipe", array ( "Before" => "Extensions-Phone"));
framework_add_hook ( "account_wipe", "account_wipe");

/**
 * Include countries files
 */
foreach ( glob ( dirname ( __FILE__) . "/country-*.php") as $filename)
{
  require_once ( $filename);
}

/**
 * Function to create a new extension.
 * Required parameters are: (int) Number, (int) UID, (string) Password, (int) Group, (array (int)) Captures[], (string) Name, (array (int)) Transhipments[], (int) CostCenter?, (boolean) VoiceMail, (int) VolTX, (int) VolRX, (boolean) Monitor, (array (array (enum['y','n','p'] Local, enum['y','n','p'] Interstate, enum['y','n','p'] International)) Landline[], (array (enum['y','n','p'] Local, enum['y','n','p'] Interstate, enum['y','n','p'] International)) Mobile[], (array (enum['y','n','p'] Local, enum['y','n','p'] Interstate, enum['y','n','p'] International)) Marine[], (array (enum['y','n','p'] Local, enum['y','n','p'] International)) Tollfree[], (array (enum['y','n','p'] Local, enum['y','n','p'] International)) PRN[], (array (enum['y','n','p'] Local, enum['y','n','p'] International)) Satellite[]) Permissions[], (string) Email?
 * Possible results:
 *   - 200: OK, extension created (overwritten)
 *   - 201: OK, extension created
 *   - 400: Extension already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function extension_phone_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_phone_add_start"))
  {
    $parameters = framework_call ( "extension_phone_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "UID", $parameters))
  {
    $data["UID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "UID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["UID"]))
  {
    $data["UID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[0-9]{6}$/", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Group", $parameters))
  {
    $data["Group"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Group", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Group"]))
  {
    $data["Group"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Group", $data) && ! check_config ( "config", "sip-group-" . $parameters["Group"]))
  {
    $data["Group"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Captures", $parameters))
  {
    $data["Captures"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Captures", $data) && ! is_array ( $parameters["Captures"]))
  {
    $data["Captures"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Captures", $data))
  {
    foreach ( $parameters["Captures"] as $capture)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $capture))
      {
        $data["Captures"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Transhipments", $parameters))
  {
    $data["Transhipments"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Transhipments", $data) && ! is_array ( $parameters["Transhipments"]))
  {
    $data["Transhipments"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Transhipments", $data))
  {
    foreach ( $parameters["Transhipments"] as $capture)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $capture))
      {
        $data["Transhipments"] = "Invalid content";
      }
    }
  }
  if ( array_key_exists ( "CostCenter", $parameters) && ! preg_match ( "/^[0-9]+$/", $parameters["CostCenter"]))
  {
    $data["CostCenter"] = "Invalid content";
  }
  if ( ! array_key_exists ( "VoiceMail", $parameters))
  {
    $data["VoiceMail"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "VoiceMail", $data) && ! is_bool ( $parameters["VoiceMail"]))
  {
    $data["VoiceMail"] = "Invalid content";
  }
  if ( ! array_key_exists ( "VolTX", $parameters))
  {
    $data["VolTX"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "VolTX", $data) && ! preg_match ( "/^(-)?([0-9]|10)$/", $parameters["VolTX"]))
  {
    $data["VolTX"] = "Invalid content";
  }
  if ( ! array_key_exists ( "VolRX", $parameters))
  {
    $data["VolRX"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "VolRX", $data) && ! preg_match ( "/^(-)?([0-9]|10)$/", $parameters["VolRX"]))
  {
    $data["VolRX"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Monitor", $parameters))
  {
    $data["Monitor"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Monitor", $data) && ! is_bool ( $parameters["Monitor"]))
  {
    $data["Monitor"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Permissions", $parameters))
  {
    $data["Permissions"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Permissions", $data) && ! is_array ( $parameters["Permissions"]))
  {
    $data["Permissions"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Landline", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Landline"]) || ! array_key_exists ( "Interstate", $parameters["Permissions"]["Landline"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Landline"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Landline"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Landline"]["Interstate"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Landline"]["International"])) || ! array_key_exists ( "Mobile", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Mobile"]) || ! array_key_exists ( "Interstate", $parameters["Permissions"]["Mobile"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Mobile"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Mobile"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Mobile"]["Interstate"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Mobile"]["International"])) || ! array_key_exists ( "Marine", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Marine"]) || ! array_key_exists ( "Interstate", $parameters["Permissions"]["Marine"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Marine"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Marine"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Marine"]["Interstate"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Marine"]["International"])) || ! array_key_exists ( "Tollfree", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Tollfree"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Tollfree"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Tollfree"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Tollfree"]["International"])) || ! array_key_exists ( "PRN", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["PRN"]) || ! array_key_exists ( "International", $parameters["Permissions"]["PRN"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["PRN"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["PRN"]["International"])) || ! array_key_exists ( "Satellite", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Satellite"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Satellite"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Satellite"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Satellite"]["International"])))
  {
    $data["Permissions"] = "Invalid content";
  }
  if ( array_key_exists ( "Email", $parameters) && ! validate_email ( $parameters["Email"]))
  {
    $data["Email"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_phone_add_validate"))
  {
    $data = framework_call ( "extension_phone_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_phone_add_sanitize"))
  {
    $parameters = framework_call ( "extension_phone_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_phone_add_pre"))
  {
    $parameters = framework_call ( "extension_phone_add_pre", $parameters, false, $parameters);
  }

  // Verify if extension exist
  if ( check_config ( "config", "sip-extension-" . $parameters["Number"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Extension already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Verify if voicemail exist
  if ( $parameters["VoiceMail"] == true && config_check ( "config", "voicemail-" . $parameters["Number"]) && ! check_overwrite ())
  {
    return array ( "code" => 400, "message" => "Voicemail already exist.");
  }

  // Create file structure
  $extension = "; VoIP Domain - Extension " . $parameters["Number"] . " - " . $parameters["Name"] . "\n" .
               "[vd_extension_" . $parameters["Number"] . "](!,vd_group_" . $parameters["Group"] . ")\n" .
               "callerid=\"" . addslashes ( $parameters["Name"]) . "\" <" . $parameters["Number"] . ">\n" .
               "named_pickup_group=" . implode ( ",", $parameters["Captures"]) . "\n";
  if ( $parameters["VoiceMail"] == true)
  {
    $extension .= "mailboxes=" . $parameters["Number"] . "@vd_mailbox\n";
  }
  if ( $parameters["CostCenter"])
  {
    $extension .= "accountcode=" . $parameters["CostCenter"] . "\n";
  }

  // Write extension file
  if ( ! write_config ( "config", "sip-extension-" . $parameters["Number"], $extension))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create voicemail file if requested
  if ( $parameters["VoiceMail"] == true)
  {
    // Create file structure
    $voicemail = $parameters["Number"] . " = " . $parameters["Password"] . "," . $parameters["Name"] . "," . $parameters["Email"] . ",,delete=yes\n";

    // Write voicemail file
    if ( ! write_config ( "config", "voicemail-" . $parameters["Number"], $voicemail))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  }

  // Create extension file
  $dialplan = "; VoIP Domain - Extension " . $parameters["Number"] . " - " . $parameters["Name"] . "\n" .
              "exten => " . $parameters["Number"] . ",1,GoSub(VoIPDomain-Tools,call_extension,1(\${EXTEN})\n" .
              "exten => t_" . $parameters["Number"] . ",1,GoSub(VoIPDomain-Tools,call_transhipment,1(\${EXTEN:2}))\n";
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["Number"], $dialplan))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create dialplan database file
  $vars = array (
    "dialstring" => "",
    "uid" => $parameters["UID"],
    "group" => $parameters["Group"],
    "password" => $parameters["Password"],
    "p_landline_local" => $parameters["Permissions"]["Landline"]["Local"],
    "p_landline_interstate" => $parameters["Permissions"]["Landline"]["Interstate"],
    "p_landline_international" => $parameters["Permissions"]["Landline"]["International"],
    "p_mobile_local" => $parameters["Permissions"]["Mobile"]["Local"],
    "p_mobile_interstate" => $parameters["Permissions"]["Mobile"]["Interstate"],
    "p_mobile_international" => $parameters["Permissions"]["Mobile"]["International"],
    "p_marine_local" => $parameters["Permissions"]["Marine"]["Local"],
    "p_marine_interstate" => $parameters["Permissions"]["Marine"]["Interstate"],
    "p_marine_international" => $parameters["Permissions"]["Marine"]["International"],
    "p_tollfree_local" => $parameters["Permissions"]["Tollfree"]["Local"],
    "p_tollfree_international" => $parameters["Permissions"]["Tollfree"]["International"],
    "p_prn_local" => $parameters["Permissions"]["PRN"]["Local"],
    "p_prn_international" => $parameters["Permissions"]["PRN"]["International"],
    "p_satellite_local" => $parameters["Permissions"]["Satellite"]["Local"],
    "p_satellite_international" => $parameters["Permissions"]["Satellite"]["International"],
    "voltx" => (int) $parameters["Permissions"]["VolTX"],
    "volrx" => (int) $parameters["Permissions"]["VolRX"],
    "monitor" => $parameters["Permissions"]["Monitor"] == true ? "yes" : "no"
  );
  if ( sizeof ( $parameters["Transhipments"]) != 0)
  {
    $vars["transhipment"] = implode ( ",", $parameters["Transhipments"]);
  }
  if ( ! write_config ( "configdb", "extension-" . $parameters["Number"], array ( "name" => "extension_" . $parameters["Number"], "data" => $vars)))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension file
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], array_merge_recursive ( $parameters, array ( "Type" => "phone", "Accounts" => array ()))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_phone_add_post") && ! framework_call ( "extension_phone_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP and dialplan configurations
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  // Reload Asterisk voicemail configuration
  if ( $parameters["VoiceMail"] == true)
  {
    asterisk_exec ( "voicemail reload");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_phone_add_finish"))
  {
    framework_call ( "extension_phone_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Extension created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Extension created.");
  }
}

/**
 * Function to change an existing extension.
 * Required parameters are: (int) Number, (int) NewNumber, (int) UID, (string) Password, (int) Group, (array (int)) Captures[], (string) Name, (array (int)) Transhipments[], (int) CostCenter?, (boolean) VoiceMail, (int) VolTX, (int) VolRX, (boolean) Monitor, (array (array (enum['y','n','p'] Local, enum['y','n','p'] Interstate, enum['y','n','p'] International)) Landline[], (array (enum['y','n','p'] Local, enum['y','n','p'] Interstate, enum['y','n','p'] International)) Mobile[], (array (enum['y','n','p'] Local, enum['y','n','p'] Interstate, enum['y','n','p'] International)) Marine[], (array (enum['y','n','p'] Local, enum['y','n','p'] International)) Tollfree[], (array (enum['y','n','p'] Local, enum['y','n','p'] International)) PRN[], (array (enum['y','n','p'] Local, enum['y','n','p'] International)) Satellite[]) Permissions[], (string) Email?
 * Possible results:
 *   - 200: OK, extension changed
 *   - 404: Extension doesn't exist
 *   - 406: Invalid parameters
 *   - 409: Extension new number already exist
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function extension_phone_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_phone_change_start"))
  {
    $parameters = framework_call ( "extension_phone_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "UID", $parameters))
  {
    $data["UID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "UID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["UID"]))
  {
    $data["UID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[0-9]{6}$/", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Group", $parameters))
  {
    $data["Group"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Group", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Group"]))
  {
    $data["Group"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Group", $data) && ! check_config ( "config", "sip-group-" . $parameters["Group"]))
  {
    $data["Group"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Captures", $parameters))
  {
    $data["Captures"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Captures", $data) && ! is_array ( $parameters["Captures"]))
  {
    $data["Captures"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Captures", $data))
  {
    foreach ( $parameters["Captures"] as $capture)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $capture))
      {
        $data["Captures"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Transhipments", $parameters))
  {
    $data["Transhipments"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Transhipments", $data) && ! is_array ( $parameters["Transhipments"]))
  {
    $data["Transhipments"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Transhipments", $data))
  {
    foreach ( $parameters["Transhipments"] as $capture)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $capture))
      {
        $data["Transhipments"] = "Invalid content";
      }
    }
  }
  if ( array_key_exists ( "CostCenter", $parameters) && ! preg_match ( "/^[0-9]+$/", $parameters["CostCenter"]))
  {
    $data["CostCenter"] = "Invalid content";
  }
  if ( ! array_key_exists ( "VoiceMail", $parameters))
  {
    $data["VoiceMail"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "VoiceMail", $data) && ! is_bool ( $parameters["VoiceMail"]))
  {
    $data["VoiceMail"] = "Invalid content";
  }
  if ( ! array_key_exists ( "VolTX", $parameters))
  {
    $data["VolTX"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "VolTX", $data) && ! preg_match ( "/^(-)?([0-9]|10)$/", $parameters["VolTX"]))
  {
    $data["VolTX"] = "Invalid content";
  }
  if ( ! array_key_exists ( "VolRX", $parameters))
  {
    $data["VolRX"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "VolRX", $data) && ! preg_match ( "/^(-)?([0-9]|10)$/", $parameters["VolRX"]))
  {
    $data["VolRX"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Monitor", $parameters))
  {
    $data["Monitor"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Monitor", $data) && ! is_bool ( $parameters["Monitor"]))
  {
    $data["Monitor"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Permissions", $parameters))
  {
    $data["Permissions"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Permissions", $data) && ! is_array ( $parameters["Permissions"]))
  {
    $data["Permissions"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Landline", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Landline"]) || ! array_key_exists ( "Interstate", $parameters["Permissions"]["Landline"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Landline"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Landline"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Landline"]["Interstate"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Landline"]["International"])) || ! array_key_exists ( "Mobile", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Mobile"]) || ! array_key_exists ( "Interstate", $parameters["Permissions"]["Mobile"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Mobile"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Mobile"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Mobile"]["Interstate"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Mobile"]["International"])) || ! array_key_exists ( "Marine", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Marine"]) || ! array_key_exists ( "Interstate", $parameters["Permissions"]["Marine"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Marine"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Marine"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Marine"]["Interstate"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Marine"]["International"])) || ! array_key_exists ( "Tollfree", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Tollfree"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Tollfree"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Tollfree"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Tollfree"]["International"])) || ! array_key_exists ( "PRN", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["PRN"]) || ! array_key_exists ( "International", $parameters["Permissions"]["PRN"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["PRN"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["PRN"]["International"])) || ! array_key_exists ( "Satellite", $parameters["Permissions"]) || ! array_key_exists ( "Local", $parameters["Permissions"]["Satellite"]) || ! array_key_exists ( "International", $parameters["Permissions"]["Satellite"]) || ( ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Satellite"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Permissions"]["Satellite"]["International"])))
  {
    $data["Permissions"] = "Invalid content";
  }
  if ( array_key_exists ( "Email", $parameters) && ! validate_email ( $parameters["Email"]))
  {
    $data["Email"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_phone_change_validate"))
  {
    $data = framework_call ( "extension_phone_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_phone_change_sanitize"))
  {
    $parameters = framework_call ( "extension_phone_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_phone_change_pre"))
  {
    $parameters = framework_call ( "extension_phone_change_pre", $parameters, false, $parameters);
  }

  // Verify if extension exist
  if ( ! check_config ( "config", "sip-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 404, "message" => "Extension doesn't exist.");
  }

  // Verify if new extension exist
  if ( $parameters["Number"] != $parameters["NewNumber"] && ( check_config ( "config", "sip-extension-" . $parameters["NewNumber"]) || check_config ( "datafile", "extension-" . $parameters["NewNumber"]) || check_config ( "config", "voicemail-" . $parameters["NewNumber"])))
  {
    return array ( "code" => 409, "message" => "Extension new number already exist.");
  }

  // Set hint variable
  $hashint = check_config ( "config", "dialplan-hint-" . $parameters["Number"]);

  // Remove old extension files first
  if ( ! unlink_config ( "config", "sip-extension-" . $parameters["Number"]) || ! unlink_config ( "config", "dialplan-extension-" . $parameters["Number"]) || ! unlink_config ( "configdb", "extension-" . $parameters["Number"]) || ( check_config ( "config", "voicemail-" . $parameters["Number"]) && ( ! unlink_config ( "config", "voicemail-" . $parameters["Number"]) || ! unlink_config ( "datafile", "voicemail-" . $parameters["Number"]))) || ( check_config ( "config", "dialplan-hint-" . $parameters["Number"]) && ! unlink_config ( "config", "dialplan-hint-" . $parameters["Number"])) || ! rename_config ( "datafile", "extension-" . $parameters["Number"], "extension-" . $parameters["NewNumber"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $extension = "; VoIP Domain - Extension " . $parameters["NewNumber"] . " - " . $parameters["Name"] . "\n" .
               "[vd_extension_" . $parameters["NewNumber"] . "](!,vd_group_" . $parameters["Group"] . ")\n" .
               "callerid=\"" . addslashes ( $parameters["Name"]) . "\" <" . $parameters["NewNumber"] . ">\n" .
               "named_pickup_group=" . implode ( ",", $parameters["Captures"]) . "\n";
  if ( $parameters["VoiceMail"] == true)
  {
    $extension .= "mailboxes=" . $parameters["NewNumber"] . "@vd_mailbox\n";
  }
  if ( $parameters["CostCenter"])
  {
    $extension .= "accountcode=" . $parameters["CostCenter"] . "\n";
  }

  // Write extension file
  if ( ! write_config ( "config", "sip-extension-" . $parameters["NewNumber"], $extension))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Read extension data
  $extension = read_config ( "datafile", "extension-" . $parameters["NewNumber"]);

  // If number changed, replace number at each extension account file and account data file
  if ( $parameters["Number"] != $parameters["NewNumber"] && sizeof ( $extension["Accounts"]))
  {
    foreach ( $extension["Accounts"] as $account)
    {
      $content = read_config ( "config", "sip-account-" . str_replace ( "PJSIP/", "", $account));
      write_config ( "config", "sip-account-" . str_replace ( "PJSIP/", "", $account), str_replace ( "vd_extension_" . $parameters["Number"], "vd_extension_" . $parameters["NewNumber"], $content));
      $content = read_config ( "datafile", "account-" . str_replace ( "PJSIP/", "", $account));
      $content["Number"] = $parameters["NewNumber"];
      unlink_config ( "datafile", "account-" . str_replace ( "PJSIP/", "", $account));
      write_config ( "datafile", "account-" . str_replace ( "PJSIP/", "", $account), $content);
    }
  }

  // Create voicemail file if requested
  if ( $parameters["VoiceMail"] == true)
  {
    // Create file structure
    $voicemail = $parameters["NewNumber"] . " = " . $parameters["Password"] . "," . $parameters["Name"] . "," . $parameters["Email"] . ",,delete=yes\n";

    // Write voicemail file
    if ( ! write_config ( "config", "voicemail-" . $parameters["NewNumber"], $voicemail))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  }

  // Create extension dialplan file
  $dialplan = "; VoIP Domain - Extension " . $parameters["NewNumber"] . " - " . $parameters["Name"] . "\n" .
              "exten => " . $parameters["NewNumber"] . ",1,GoSub(VoIPDomain-Tools,call_extension,1(\${EXTEN})\n" .
              "exten => t_" . $parameters["NewNumber"] . ",1,GoSub(VoIPDomain-Tools,call_transhipment,1(\${EXTEN:2}))\n";
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["NewNumber"], $content))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create dialplan database file
  $vars = array (
    "dialstring" => "",
    "uid" => $parameters["UID"],
    "group" => $parameters["Group"],
    "password" => $parameters["Password"],
    "p_landline_local" => $parameters["Permissions"]["Landline"]["Local"],
    "p_landline_interstate" => $parameters["Permissions"]["Landline"]["Interstate"],
    "p_landline_international" => $parameters["Permissions"]["Landline"]["International"],
    "p_mobile_local" => $parameters["Permissions"]["Mobile"]["Local"],
    "p_mobile_interstate" => $parameters["Permissions"]["Mobile"]["Interstate"],
    "p_mobile_international" => $parameters["Permissions"]["Mobile"]["International"],
    "p_marine_local" => $parameters["Permissions"]["Marine"]["Local"],
    "p_marine_interstate" => $parameters["Permissions"]["Marine"]["Interstate"],
    "p_marine_international" => $parameters["Permissions"]["Marine"]["International"],
    "p_tollfree_local" => $parameters["Permissions"]["Tollfree"]["Local"],
    "p_tollfree_international" => $parameters["Permissions"]["Tollfree"]["International"],
    "p_prn_local" => $parameters["Permissions"]["PRN"]["Local"],
    "p_prn_international" => $parameters["Permissions"]["PRN"]["International"],
    "p_satellite_local" => $parameters["Permissions"]["Satellite"]["Local"],
    "p_satellite_international" => $parameters["Permissions"]["Satellite"]["International"],
    "voltx" => (int) $parameters["Permissions"]["VolTX"],
    "volrx" => (int) $parameters["Permissions"]["VolRX"],
    "monitor" => $parameters["Permissions"]["Monitor"] == true ? "yes" : "no"
  );
  if ( sizeof ( $parameters["Transhipments"]) != 0)
  {
    $vars["transhipment"] = implode ( ",", $parameters["Transhipments"]);
  }
  if ( ! write_config ( "configdb", "extension-" . $parameters["NewNumber"], array ( "name" => "extension_" . $parameters["NewNumber"], "data" => $vars)))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Rebuild extension hint (if required)
  if ( $hashint)
  {
    if ( ! write_config ( "config", "dialplan-hint-" . $parameters["NewNumber"], "exten => " . $parameters["NewNumber"] . ",hint," . implode ( "&", $extension["Accounts"]) . "\n"))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  }

  // Update database if number changed
  if ( $parameters["Number"] != $parameters["NewNumber"])
  {
    // **TODO**: Need to migrate used variables to the new number. This could be implemented through a filter, so the modules implements the handle of their variables.

    // Remove Last CalledID from database
    asterisk_exec ( "database del lastcalledid " . $parameters["Number"]);
  }

  // Create extension data file
  unlink_config ( "datafile", "extension-" . $parameters["Number"]);
  $parameters["Number"] = $parameters["NewNumber"];
  unset ( $parameters["NewNumber"]);
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], array_merge_recursive ( $parameters, array ( "Type" => "phone", "Accounts" => $extension["Accounts"]))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_phone_change_post") && ! framework_call ( "extension_phone_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "pjsip reload");

  // Reload Asterisk voicemail configuration
  asterisk_exec ( "voicemail reload");

  // Reload Asterisk dialplan configuration
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_phone_change_finish"))
  {
    framework_call ( "extension_phone_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Extension changed.");
}

/**
 * Function to remove an existing extension.
 * Required parameters are: (int) Number
 * Possible results:
 *   - 200: OK, extension removed
 *   - 404: Extension doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function extension_phone_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_phone_remove_start"))
  {
    $parameters = framework_call ( "extension_phone_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_phone_remove_validate"))
  {
    $data = framework_call ( "extension_phone_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_phone_remove_sanitize"))
  {
    $parameters = framework_call ( "extension_phone_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_phone_remove_pre"))
  {
    $parameters = framework_call ( "extension_phone_remove_pre", $parameters, false, $parameters);
  }

  // Verify if extension exist
  if ( ! check_config ( "config", "sip-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 404, "message" => "Extension doesn't exist.");
  }

  // Remove extension accounts
  $extension = read_config ( "datafile", "extension-" . $parameters["Number"]);
  foreach ( $extension["Accounts"] as $account)
  {
    unlink_config ( "config", "sip-account-" . str_replace ( "PJSIP/", "", $account));
    unlink_config ( "datafile", "account-" . str_replace ( "PJSIP/", "", $account));
  }

  // Remove extension files
  if ( ! unlink_config ( "config", "sip-extension-" . $parameters["Number"]) || ! unlink_config ( "config", "dialplan-extension-" . $parameters["Number"]) || ! unlink_config ( "configdb", "extension-" . $parameters["Number"]) || ( check_config ( "config", "voicemail-" . $parameters["Number"]) && ! unlink_config ( "config", "voicemail-" . $parameters["Number"])) || ( check_config ( "config", "dialplan-hint-" . $parameters["Number"]) && ! unlink_config ( "config", "dialplan-hint-" . $parameters["Number"])) || ! unlink_config ( "datafile", "extension-" . $parameters["Number"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_phone_remove_post") && ! framework_call ( "extension_phone_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "pjsip reload");

  // Reload Asterisk dialplan configuration
  asterisk_exec ( "dialplan reload");

  // Reload Asterisk voicemail configuration
  asterisk_exec ( "voicemail reload");

  // Remove Last CalledID from database
  asterisk_exec ( "database del lastcalledid " . $parameters["Number"]);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_phone_remove_finish"))
  {
    framework_call ( "extension_phone_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Extension removed.");
}

/**
 * Function to create a new extension account.
 * Required parameters are: (int) Number, (string) Username, (string) Password, (string) Type
 * Possible results:
 *   - 200: OK, account created (overwritten)
 *   - 201: OK, account created
 *   - 400: Account already exist
 *   - 404: Account extension not found
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function account_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "account_add_start"))
  {
    $parameters = framework_call ( "account_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Username", $parameters))
  {
    $data["Username"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Username", $data) && ! preg_match ( "/^[0-9\-zA-z\-_]+$/", $parameters["Username"]))
  {
    $data["Username"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Type", $parameters))
  {
    $data["Type"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Type", $data) && ! framework_has_hook ( "account_type_" . $parameters["Type"]))
  {
    $data["Type"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "account_add_validate"))
  {
    $data = framework_call ( "account_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "account_add_sanitize"))
  {
    $parameters = framework_call ( "account_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "account_add_pre"))
  {
    $parameters = framework_call ( "account_add_pre", $parameters, false, $parameters);
  }

  // Verify if account exist
  if ( check_config ( "config", "sip-account-" . $parameters["Username"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Account already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Verify if extension exist
  if ( ! check_config ( "config", "sip-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 404, "message" => "Account extension not found.");
  }

  // Create account data file
  if ( ! write_config ( "datafile", "account-" . $parameters["Username"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create account file
  $account = framework_call ( "account_type_" . $parameters["Type"], $parameters);

  // Write account file
  if ( ! write_config ( "config", "sip-account-" . $parameters["Username"], $account))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Load extension accounts
  $extension = read_config ( "datafile", "extension-" . $parameters["Number"]);
  $extension["Accounts"][] = "PJSIP/" . $parameters["Username"];
  unlink_config ( "datafile", "extension-" . $parameters["Number"]);
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], $extension))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension file
  $content = "exten => " . $parameters["Number"] . ",1,GoSub(VoIPDomain-Tools,call_extension,1(\${EXTEN},\${CALLERID(num)}," . implode ( "&", $extension["Accounts"]) . "))\n";
  $content .= "exten => t_" . $parameters["Number"] . ",1,GoSub(VoIPDomain-Tools,call_transhipment,1(\${EXTEN:2},\${CALLERID(num)}," . implode ( "&", $extension["Accounts"]) . "))\n";
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["Number"], $content))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Check if extension hint exist (and update it)
  if ( check_config ( "config", "dialplan-hint-" . $parameters["Number"]))
  {
    if ( ! write_config ( "config", "dialplan-hint-" . $parameters["Number"], "exten => " . $parameters["Number"] . ",hint," . implode ( "&", $extension["Accounts"]) . "\n"))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  }

  // Update extension database file
  if ( $db = read_config ( "configdb", "extension-" . $parameters["Number"]))
  {
    $db["data"]["dialstring"] = implode ( "&", array_merge ( explode ( "&", $db["data"]["dialstring"]), $extension["Accounts"]));
    write_config ( "configdb", "extension-" . $parameters["Number"], $db);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "account_add_post") && ! framework_call ( "account_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "pjsip reload");

  // Reload Asterisk dialplan configuration
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "account_add_finish"))
  {
    framework_call ( "account_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Account created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Account created.");
  }
}

/**
 * Function to change an extension account.
 * Required parameters are: (int) Number, (string) Username, (string) NewUsername, (string) Password, (string) Type
 * Possible results:
 *   - 200: OK, account changed
 *   - 404: Account doesn't exist
 *   - 404: Account extension not found
 *   - 406: Invalid parameters
 *   - 409: New account already exist
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function account_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "account_change_start"))
  {
    $parameters = framework_call ( "account_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Username", $parameters))
  {
    $data["Username"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Username", $data) && ! preg_match ( "/^[0-9\-zA-z\-_]+$/", $parameters["Username"]))
  {
    $data["Username"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NewUsername", $parameters))
  {
    $data["NewUsername"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NewUsername", $data) && ! preg_match ( "/^[0-9\-zA-z\-_]+$/", $parameters["NewUsername"]))
  {
    $data["NewUsername"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Type", $parameters))
  {
    $data["Type"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Type", $data) && ! framework_has_hook ( "account_type_" . $parameters["Type"]))
  {
    $data["Type"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "account_change_validate"))
  {
    $data = framework_call ( "account_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "account_change_sanitize"))
  {
    $parameters = framework_call ( "account_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "account_change_pre"))
  {
    $parameters = framework_call ( "account_change_pre", $parameters, false, $parameters);
  }

  // Verify if account exist
  if ( ! check_config ( "config", "sip-account-" . $parameters["Username"]))
  {
    return array ( "code" => 404, "message" => "Account doesn't exist.");
  }

  // Verify if new account exists
  if ( $parameters["Username"] != $parameters["NewUsername"] && check_config ( "config", "sip-account-" . $parameters["NewUsername"]))
  {
    return array ( "code" => 409, "message" => "New account already exist.");
  }

  // Verify if extension exist
  if ( ! check_config ( "config", "sip-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 404, "message" => "Account extension not found.");
  }

  // Remove old account file and database file if username changed
  if ( $parameters["Username"] != $parameters["NewUsername"] && ( ! unlink_config ( "config", "sip-account-" . $parameters["Username"]) || ! unlink_config ( "datafile", "account-" . $parameters["Username"])))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create account data file
  $writeparameters = $parameters;
  $writeparameters["Username"] = $writeparameters["NewUsername"];
  unset ( $writeparameters["NewUsername"]);
  if ( ! write_config ( "datafile", "account-" . $parameters["NewUsername"], $writeparameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create account file
  $account = framework_call ( "account_type_" . $parameters["Type"], array ( "Number" => $parameters["Number"], "Username" => $parameters["NewUsername"], "Password" => $parameters["Password"], "Type" => $parameters["Type"]));

  // Write account file
  if ( ! write_config ( "config", "sip-account-" . $parameters["NewUsername"], $account))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Update extension accounts data file if extension username changed
  if ( $parameters["Username"] != $parameters["NewUsername"])
  {
    // Load extension accounts
    $extension = read_config ( "datafile", "extension-" . $parameters["Number"]);

    // Remove old account
    if ( ( $key = array_search ( "PJSIP/" . $parameters["Username"], $extension["Accounts"])) !== false)
    {
      unset ( $extensions["Accounts"][$key]);
    }
    $extension["Accounts"][] = "PJSIP/" . $parameters["NewUsername"];

    // Write extesion accounts
    unlink_config ( "datafile", "extension-" . $parameters["Number"]);
    if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], $extension))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }

    // Create extension file
    $content = "exten => " . $parameters["NewNumber"] . ",1,GoSub(VoIPDomain-Tools,call_extension,1(\${EXTEN},\${CALLERID(num)},\"" . implode ( "&", $extension["Accounts"]) . "\"))\n";
    $content .= "exten => t_" . $parameters["NewNumber"] . ",1,GoSub(VoIPDomain-Tools,call_transhipment,1(\${EXTEN:2},\${CALLERID(num)},\"" . implode ( "&", $extension["Accounts"]) . "\"))\n";
    if ( ! write_config ( "config", "dialplan-extension-" . $parameters["Number"], $content))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }

    // Check if extension hint exist (and update it)
    if ( check_config ( "config", "dialplan-hint-" . $parameters["Number"]))
    {
      if ( ! write_config ( "config", "dialplan-hint-" . $parameters["Number"], "exten => " . $parameters["Number"] . ",hint," . implode ( "&", $extension["Accounts"]) . "\n"))
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
    }

    // Update extension database file
    if ( $db = read_config ( "configdb", "extension-" . $parameters["Number"]))
    {
      $db["data"]["dialstring"] = implode ( "&", array_merge ( explode ( "&", $db["data"]["dialstring"]), $extension["Accounts"]));
      write_config ( "configdb", "extension-" . $parameters["Number"], $db);
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "account_change_post") && ! framework_call ( "account_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan configuration
  if ( $parameters["Username"] != $parameters["NewUsername"])
  {
    asterisk_exec ( "dialplan reload");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "pjsip reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "account_change_finish"))
  {
    framework_call ( "account_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Account changed.");
}

/**
 * Function to remove an extension account.
 * Required parameters are: (int) Number, (string) Username
 * Possible results:
 *   - 200: OK, account removed
 *   - 404: Account doesn't exist
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
function account_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "account_remove_start"))
  {
    $parameters = framework_call ( "account_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Username", $parameters))
  {
    $data["Username"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Username", $data) && ! preg_match ( "/^[0-9\-zA-z\-_]+$/", $parameters["Username"]))
  {
    $data["Username"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "account_remove_validate"))
  {
    $data = framework_call ( "account_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "account_remove_sanitize"))
  {
    $parameters = framework_call ( "account_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "account_remove_pre"))
  {
    $parameters = framework_call ( "account_remove_pre", $parameters, false, $parameters);
  }

  // Verify if account exist
  if ( ! check_config ( "config", "sip-account-" . $parameters["Username"]))
  {
    return array ( "code" => 404, "message" => "Account doesn't exist.");
  }

  // Remove account file and account data file
  if ( ! unlink_config ( "config", "sip-account-" . $parameters["Username"]) || ! unlink_config ( "datafile", "account-" . $parameters["Username"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Update extension accounts data file
  $extension = read_config ( "datafile", "extension-" . $parameters["Number"]);

  // Remove old account
  if ( ( $key = array_search ( "PJSIP/" . $parameters["Username"], $extension["Accounts"])) !== false)
  {
    unset ( $extensions["Accounts"][$key]);
  }

  // Write extesion accounts
  unlink_config ( "datafile", "extension-" . $parameters["Number"]);
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], $extension))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension file
  if ( sizeof ( $extension["Accounts"]))
  {
    $content = "exten => " . $parameters["NewNumber"] . ",1,GoSub(VoIPDomain-Tools,call_extension,1(\${EXTEN},\${CALLERID(num)},\"" . implode ( "&", $extension["Accounts"]) . "\"))\n";
    $content .= "exten => t_" . $parameters["NewNumber"] . ",1,GoSub(VoIPDomain-Tools,call_transhipment,1(\${EXTEN:2},\${CALLERID(num)},\"" . implode ( "&", $extension["Accounts"]) . "\"))\n";
  } else {
    $content = "exten => " . $parameters["NewNumber"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: VoIPDomain extension - Call to extension \${EXTEN} without configured account\")\n";
  }
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["Number"], $content))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Check if extension hint exist (and update it)
  if ( check_config ( "config", "dialplan-hint-" . $parameters["Number"]))
  {
    if ( ! write_config ( "config", "dialplan-hint-" . $parameters["Number"], "exten => " . $parameters["Number"] . ",hint," . implode ( "&", $extension["Accounts"]) . "\n"))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  }

  // Update extension database file
  if ( $db = read_config ( "configdb", "extension-" . $parameters["Number"]))
  {
    $db["data"]["dialstring"] = implode ( "&", array_merge ( explode ( "&", $db["data"]["dialstring"]), $extension["Accounts"]));
    write_config ( "configdb", "extension-" . $parameters["Number"], $db);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "account_remove_post") && ! framework_call ( "account_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "pjsip reload");

  // Reload Asterisk dialplan configuration
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "account_remove_finish"))
  {
    framework_call ( "account_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Account removed.");
}

/**
 * Function to create an extension hint.
 * Required parameters are: (int) Number
 * Possible results:
 *   - 200: OK, hint created (overwritten)
 *   - 201: OK, hint created
 *   - 400: Hint already exist
 *   - 404: Number doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function hint_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "hint_add_start"))
  {
    $parameters = framework_call ( "hint_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "hint_add_validate"))
  {
    $data = framework_call ( "hint_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "hint_add_sanitize"))
  {
    $parameters = framework_call ( "hint_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "hint_add_pre"))
  {
    $parameters = framework_call ( "hint_add_pre", $parameters, false, $parameters);
  }

  // Verify if hint exist
  if ( check_config ( "config", "extension-hint-" . $parameters["Number"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Hint already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Check if extension data exists
  if ( ! check_config ( "datafile", "extension-" . $parameters["Number"]))
  {
    return array ( "code" => 404, "message" => "Number doesn't exist.");
  }

  // Load extension accounts
  $extension = read_config ( "datafile", "extension-" . $parameters["Number"]);

  // Create hint content:
  $hint = "; Hint file for extension " . $parameters["Number"] . " (" . $extension["Name"] . ")\n";
  $hint .= "exten => " . $parameters["Number"] . ",hint," . implode ( "&", $extension["Accounts"]) . "\n";

  // Create hint file
  if ( ! write_config ( "config", "dialplan-hint-" . $parameters["Number"], $hint))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create hint data file
  if ( ! write_config ( "datafile", "hint-" . $parameters["Number"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "hint_add_post") && ! framework_call ( "hint_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan configuration
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "hint_add_finish"))
  {
    framework_call ( "hint_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Hint created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Hint created.");
  }
}

/**
 * Function to remove an extension hint.
 * Required parameters are: (int) Number
 * Possible results:
 *   - 200: OK, hint removed
 *   - 404: Hint doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function hint_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "hint_change_start"))
  {
    $parameters = framework_call ( "hint_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "hint_change_validate"))
  {
    $data = framework_call ( "hint_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "hint_change_sanitize"))
  {
    $parameters = framework_call ( "hint_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "hint_change_pre"))
  {
    $parameters = framework_call ( "hint_change_pre", $parameters, false, $parameters);
  }

  // Verify if hint exist
  if ( ! check_config ( "config", "dialplan-hint-" . $parameters["Number"]))
  {
    return array ( "code" => 404, "message" => "Hint doesn't exist.");
  }

  // Remove hint file
  if ( ! unlink_config ( "config", "dialplan-hint-" . $parameters["Number"]) || ! unlink_config ( "datafile", "hint-" . $parameters["Number"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "hint_change_post") && ! framework_call ( "hint_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan configuration
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "hint_change_finish"))
  {
    framework_call ( "hint_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Hint removed.");
}

/**
 * Function to change an extension auto provision file.
 * Required parameters are: (int) Number, (string) Username, (string) Password, (string) Name, (string) MAC, (string) Type, (string) Domain, (array (string)) NTP[], (string) Country, (string) TimeZone, (float) Offset, (int) Prefix, (boolean) EmergencyShortcut, (array (string)) Hints
 * Possible results:
 *   - 200: OK, AP changed
 *   - 404: AP doesn't exist
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
function ap_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ap_change_start"))
  {
    $parameters = framework_call ( "ap_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Username", $parameters))
  {
    $data["Username"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Username", $data) && ! preg_match ( "/^[0-9\-zA-z\-_]+$/", $parameters["Username"]))
  {
    $data["Username"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "MAC", $parameters))
  {
    $data["MAC"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "MAC", $data) && ! preg_match ( "/^[0-9a-fA-F]{12}$/i", $parameters["MAC"]))
  {
    $data["MAC"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Type", $parameters))
  {
    $data["Type"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Type", $data) && ( ! framework_has_hook ( "account_type_" . $parameters["Type"]) || ! framework_has_hook ( "ap_type_" . $parameters["Type"])))
  {
    $data["Type"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Domain", $parameters))
  {
    $data["Domain"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Domain", $data) && ! is_domainname ( $parameters["Domain"]))
  {
    $data["Domain"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NTP", $parameters))
  {
    $data["NTP"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NTP", $data) && ! is_array ( $parameters["NTP"]))
  {
    $data["NTP"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NTP", $data))
  {
    foreach ( $parameters["NTP"] as $ntp)
    {
      if ( ! is_domainname ( $ntp))
      {
        $data["NTP"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Country", $parameters))
  {
    $data["Country"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Country", $data) && ! framework_has_hook ( "dialplan_country_" . $parameters["Country"]))
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
  if ( ! array_key_exists ( "Prefix", $parameters))
  {
    $data["Prefix"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Prefix", $data) && ! preg_match ( "/^[0-9]$/", $parameters["Prefix"]))
  {
    $data["Prefix"] = "Invalid content";
  }
  if ( ! array_key_exists ( "EmergencyShortcut", $parameters))
  {
    $data["EmergencyShortcut"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "EmergencyShortcut", $data) && ! is_bool ( $parameters["EmergencyShortcut"]))
  {
    $data["EmergencyShortcut"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Hints", $parameters))
  {
    $data["Hints"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Hints", $data) && ! is_array ( $parameters["Hints"]))
  {
    $data["Hints"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Hints", $data))
  {
    foreach ( $parameters["Hints"] as $hint)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $hint))
      {
        $data["Hints"] = "Invalid content";
      }
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ap_change_validate"))
  {
    $data = framework_call ( "ap_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "ap_change_sanitize"))
  {
    $parameters = framework_call ( "ap_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ap_change_pre"))
  {
    $parameters = framework_call ( "ap_change_pre", $parameters, false, $parameters);
  }

  // Verify if ap exist
  if ( ! check_config ( "datafile", "ap-" . strtolower ( $parameters["MAC"])))
  {
    return array ( "code" => 404, "message" => "AP doesn't exist.");
  }

  // Create dialplan:
  $dialplan = array ();

  // Add internal commands:
  $dialplan[] = array ( "Pattern" => "*8", "Label" => "Features: Capture", "Type" => "Internal", "Kind" => "Features", "Emergency" => false);

  // Add internal extensions range:
  $dialplan[] = array ( "Pattern" => "1XXX", "Label" => "Internal", "Type" => "Internal", "Kind" => "Extensions", "Emergency" => false);

  // Check if there's dialplan generate hook's:
  if ( framework_has_hook ( "dialplan_generate"))
  {
    $dialplan = framework_call ( "dialplan_generate", $parameters, false, $dialplan);
  }

  // Add country dialplan:
  $countrydialplan = framework_call ( "dialplan_country_" . $parameters["Country"], $parameters);
  foreach ( $countrydialplan as $entry)
  {
    $entry["Type"] = "External";
    $dialplan[] = $entry;
  }

  // Set dialplan as global parameter:
  $parameters["DialPlan"] = $dialplan;

  // Call auto provision file through specific hook
  $result = framework_call ( "ap_type_" . $parameters["Type"], $parameters);

  // If call failed, return error
  if ( $result["code"] < 200 || $result["code"] > 299)
  {
    return $result;
  }

  // Create ap data file
  if ( ! write_config ( "datafile", "ap-" . strtolower ( $parameters["MAC"]), $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ap_change_post") && ! framework_call ( "ap_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ap_change_finish"))
  {
    framework_call ( "ap_change_finish", $parameters);
  }

  // Finish event
  return $result;
}

/**
 * Function to create an extension auto provision file.
 * Required parameters are: (int) Number, (string) Username, (string) Password, (string) Name, (string) MAC, (string) Type, (string) Domain, (array (string)) NTP[], (string) Country, (string) TimeZone, (float) Offset, (int) Prefix, (boolean) EmergencyShortcut, (array (string)) Hints
 * Possible results:
 *   - 200: OK, AP created (overwritten)
 *   - 201: OK, AP created
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function ap_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ap_add_start"))
  {
    $parameters = framework_call ( "ap_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Username", $parameters))
  {
    $data["Username"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Username", $data) && ! preg_match ( "/^[0-9\-zA-z\-_]+$/", $parameters["Username"]))
  {
    $data["Username"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "MAC", $parameters))
  {
    $data["MAC"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "MAC", $data) && ! preg_match ( "/^[0-9a-fA-F]{12}$/i", $parameters["MAC"]))
  {
    $data["MAC"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Type", $parameters))
  {
    $data["Type"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Type", $data) && ( ! framework_has_hook ( "account_type_" . $parameters["Type"]) || ! framework_has_hook ( "ap_type_" . $parameters["Type"])))
  {
    $data["Type"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Domain", $parameters))
  {
    $data["Domain"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Domain", $data) && ! is_domainname ( $parameters["Domain"]))
  {
    $data["Domain"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NTP", $parameters))
  {
    $data["NTP"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NTP", $data) && ! is_array ( $parameters["NTP"]))
  {
    $data["NTP"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NTP", $data))
  {
    foreach ( $parameters["NTP"] as $ntp)
    {
      if ( ! is_domainname ( $ntp))
      {
        $data["NTP"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Country", $parameters))
  {
    $data["Country"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Country", $data) && ! framework_has_hook ( "dialplan_country_" . $parameters["Country"]))
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
  if ( ! array_key_exists ( "Prefix", $parameters))
  {
    $data["Prefix"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Prefix", $data) && ! preg_match ( "/^[0-9]$/", $parameters["Prefix"]))
  {
    $data["Prefix"] = "Invalid content";
  }
  if ( ! array_key_exists ( "EmergencyShortcut", $parameters))
  {
    $data["EmergencyShortcut"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "EmergencyShortcut", $data) && ! is_bool ( $parameters["EmergencyShortcut"]))
  {
    $data["EmergencyShortcut"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Hints", $parameters))
  {
    $data["Hints"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Hints", $data) && ! is_array ( $parameters["Hints"]))
  {
    $data["Hints"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Hints", $data))
  {
    foreach ( $parameters["Hints"] as $hint)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $hint))
      {
        $data["Hints"] = "Invalid content";
      }
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ap_add_validate"))
  {
    $data = framework_call ( "ap_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "ap_add_sanitize"))
  {
    $parameters = framework_call ( "ap_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ap_add_pre"))
  {
    $parameters = framework_call ( "ap_add_pre", $parameters, false, $parameters);
  }

  // Create dialplan:
  $dialplan = array ();

  // Add internal commands:
  $dialplan[] = array ( "Pattern" => "*8", "Label" => "Features: Capture", "Type" => "Internal", "Kind" => "Features", "Emergency" => false);

  // Add internal extensions range:
  $dialplan[] = array ( "Pattern" => "1XXX", "Label" => "Internal", "Type" => "Internal", "Kind" => "Extensions", "Emergency" => false);

  // Check if there's dialplan generate hook's:
  if ( framework_has_hook ( "dialplan_generate"))
  {
    $dialplan = framework_call ( "dialplan_generate", $parameters, false, $dialplan);
  }

  // Add country dialplan:
  $countrydialplan = framework_call ( "dialplan_country_" . $parameters["Country"], $parameters);
  foreach ( $countrydialplan as $entry)
  {
    $entry["Type"] = "External";
    $dialplan[] = $entry;
  }

  // Set dialplan as global parameter:
  $parameters["DialPlan"] = $dialplan;

  // Call auto provision file through specific hook
  $result = framework_call ( "ap_type_" . $parameters["Type"], $parameters);

  // If call failed, return error
  if ( $result["code"] < 200 || $result["code"] > 299)
  {
    return $result;
  }

  // Create ap data file
  if ( ! write_config ( "datafile", "ap-" . strtolower ( $parameters["MAC"]), $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ap_add_post") && ! framework_call ( "ap_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ap_add_finish"))
  {
    framework_call ( "ap_add_finish", $parameters);
  }

  // Finish event
  return $result;
}

/**
 * Function to remove an existing auto provisioning file.
 * Required parameters are: (string) MAC, (string) Type
 * Possible results:
 *   - 200: OK, AP file removed
 *   - 404: AP doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function ap_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ap_remove_start"))
  {
    $parameters = framework_call ( "ap_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "MAC", $parameters))
  {
    $data["MAC"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "MAC", $data) && ! preg_match ( "/^[0-9a-fA-F]{12}$/i", $parameters["MAC"]))
  {
    $data["MAC"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Type", $parameters))
  {
    $data["Type"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Type", $data) && ( ! framework_has_hook ( "account_type_" . $parameters["Type"]) || ! framework_has_hook ( "ap_type_" . $parameters["Type"])))
  {
    $data["Type"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ap_remove_validate"))
  {
    $data = framework_call ( "ap_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "ap_remove_sanitize"))
  {
    $parameters = framework_call ( "ap_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ap_remove_pre"))
  {
    $parameters = framework_call ( "ap_remove_pre", $parameters, false, $parameters);
  }

  // Call auto provision file through specific hook
  $result = framework_call ( "ap_type_" . $parameters["Type"] . "_remove", $parameters);

  // Remove ap files
  if ( ! unlink_config ( "datafile", "ap-" . strtolower ( $parameters["MAC"])))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ap_remove_post") && ! framework_call ( "ap_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ap_remove_finish"))
  {
    framework_call ( "ap_remove_finish", $parameters);
  }

  // Finish event
  return $result;
}

/**
 * Function to remove all existing extensions configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function extension_wipe ( $buffer, $parameters)
{
  // There's nothing to do here. All extensions will be removed by the type wipe handler.
}

/**
 * Function to remove all existing extensions of phone type configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function extension_phone_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_phone_wipe_start"))
  {
    $parameters = framework_call ( "extension_phone_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_phone_wipe_pre"))
  {
    framework_call ( "extension_phone_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "extension") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( $data["Type"] == "phone")
    {
      unlink_config ( "config", "dialplan-extension-" . $data["Number"]);
      unlink_config ( "config", "sip-extension-" . $data["Number"]);
      unlink_config ( "configdb", "extension-" . $data["Number"]);
      if ( $data["Voicemail"])
      {
        unlink_config ( "config", "voicemail-" . $data["Number"]);
      }
      unlink_config ( "datafile", $filename);
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_phone_wipe_post"))
  {
    framework_call ( "extension_phone_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk configurations
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_phone_wipe_finish"))
  {
    framework_call ( "extension_phone_wipe_finish", $parameters);
  }
}

/**
 * Function to remove all existing phone hint.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function hint_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "hint_wipe_start"))
  {
    $parameters = framework_call ( "hint_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "hint_wipe_pre"))
  {
    framework_call ( "hint_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "hint") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    unlink_config ( "config", "dialplan-hint-" . $data["Number"]);
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "hint_wipe_post"))
  {
    framework_call ( "hint_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk configuration
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "hint_wipe_finish"))
  {
    framework_call ( "hint_wipe_finish", $parameters);
  }
}

/**
 * Function to remove all existing phone account.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function account_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "account_wipe_start"))
  {
    $parameters = framework_call ( "account_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "account_wipe_pre"))
  {
    framework_call ( "account_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "account") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    unlink_config ( "config", "sip-account-" . $data["Username"]);
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "account_wipe_post"))
  {
    framework_call ( "account_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk configuration
  asterisk_exec ( "pjsip reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "account_wipe_finish"))
  {
    framework_call ( "account_wipe_finish", $parameters);
  }
}
?>
