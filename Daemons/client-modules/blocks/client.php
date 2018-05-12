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
 * VoIP Domain blocks actions module. This module add the Asterisk client actions
 * calls related to blocks.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Blocks
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "block_add", "block_add");
framework_add_hook ( "block_change", "block_change");
framework_add_hook ( "block_remove", "block_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "block_wipe", "block_wipe");
cleanup_register ( "Blocks", "block_wipe");

/**
 * Function to create a new block (global number block list).
 * Required parameters are: (string) Number, (string) Description
 * Possible results:
 *   - 200: OK, block created (overwritten)
 *   - 201: OK, block created
 *   - 400: Block already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function block_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "block_add_start"))
  {
    $parameters = framework_call ( "block_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^\+[1-9][0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
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
  if ( framework_has_hook ( "block_add_validate"))
  {
    $data = framework_call ( "block_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "block_add_sanitize"))
  {
    $parameters = framework_call ( "block_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "block_add_pre"))
  {
    $parameters = framework_call ( "block_add_pre", $parameters, false, $parameters);
  }

  // Verify if block exist
  if ( check_config ( "config", "dialplan-block-" . substr ( $parameters["Number"], 1)))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Block already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Create file structure
  $block = "exten => _X./" . $parameters["Number"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Blocked: " . $parameters["Description"] . "))\n" .
           " same => n,GoSub(VoIPDomain-Tools,blocked,1)\n";

  // Write block file
  if ( ! write_config ( "config", "dialplan-block-" . substr ( $parameters["Number"], 1), $block))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create block data file
  if ( ! write_config ( "datafile", "block-" . substr ( $parameters["Number"], 1), $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "block_add_post") && ! framework_call ( "block_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "block_add_finish"))
  {
    framework_call ( "block_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Block created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Block created.");
  }
}

/**
 * Function to change an existing block (global number block list).
 * Required parameters are: (string) Number, (string) Description, (string) NewNumber
 * Possible results:
 *   - 200: OK, block changed
 *   - 404: Block doesn't exist
 *   - 406: Invalid parameters
 *   - 409: Block new number already exist
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function block_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "block_change_start"))
  {
    $parameters = framework_call ( "block_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^\+[1-9][0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NewNumber", $parameters))
  {
    $data["NewNumber"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NewNumber", $data) && ! preg_match ( "/^\+[1-9][0-9]+$/", $parameters["NewNumber"]))
  {
    $data["NewNumber"] = "Invalid content";
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
  if ( framework_has_hook ( "block_change_validate"))
  {
    $data = framework_call ( "block_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "block_change_sanitize"))
  {
    $parameters = framework_call ( "block_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "block_change_pre"))
  {
    $parameters = framework_call ( "block_change_pre", $parameters, false, $parameters);
  }

  // Verify if block exist
  if ( ! check_config ( "config", "dialplan-block-" . substr ( $parameters["Number"], 1)))
  {
    return array ( "code" => 404, "message" => "Block doesn't exist.");
  }

  // Verify if new block exist
  if ( $parameters["Number"] != $parameters["NewNumber"] && check_config ( "config", "dialplan-block-" . substr ( $parameters["NewNumber"], 1)))
  {
    return array ( "code" => 409, "message" => "Block new number already exist.");
  }

  // Remove block files
  if ( ! unlink_config ( "config", "dialplan-block-" . substr ( $parameters["Number"], 1)) || ! unlink_config ( "datafile", "block-" . substr ( $parameters["Number"], 1)))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $block = "exten => _X./" . $parameters["NewNumber"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Blocked: " . $parameters["Description"] . "))\n" .
           " same => n,GoSub(VoIPDomain-Tools,blocked,1)\n";

  // Write block file
  if ( ! write_config ( "config", "dialplan-block-" . substr ( $parameters["NewNumber"], 1), $block))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create block data file
  $parameters["Number"] = $parameters["NewNumber"];
  unset ( $parameters["NewNumber"]);
  if ( ! write_config ( "datafile", "block-" . substr ( $parameters["Number"], 1), $parameters))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "block_change_post") && ! framework_call ( "block_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "block_change_finish"))
  {
    framework_call ( "block_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Block changed.");
}

/**
 * Function to remove an existing block (global number block list).
 * Required parameters are: (string) Number
 * Possible results:
 *   - 200: OK, block removed
 *   - 404: Block doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function block_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "block_remove_start"))
  {
    $parameters = framework_call ( "block_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^\+[1-9][0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "block_remove_validate"))
  {
    $data = framework_call ( "block_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "block_remove_sanitize"))
  {
    $parameters = framework_call ( "block_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "block_remove_pre"))
  {
    $parameters = framework_call ( "block_remove_pre", $parameters, false, $parameters);
  }

  // Verify if block exist
  if ( ! check_config ( "config", "dialplan-block-" . substr ( $parameters["Number"], 1)))
  {
    return array ( "code" => 404, "message" => "Block doesn't exist.");
  }

  // Remove block files
  if ( ! unlink_config ( "config", "dialplan-block-" . substr ( $parameters["Number"], 1)) || ! unlink_config ( "datafile", "block-" . substr ( $parameters["Number"], 1)))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "block_remove_post") && ! framework_call ( "block_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "block_remove_finish"))
  {
    framework_call ( "block_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Block removed.");
}

/**
 * Function to remove all existing blocks configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function block_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agent_wipe_start"))
  {
    $parameters = framework_call ( "agent_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agent_wipe_pre"))
  {
    framework_call ( "agent_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "block") as $filename)
  {
    unlink_config ( "config", "dialplan-block-" . (int) substr ( $filename, strrpos ( $filename, "-") + 1));
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agent_wipe_post"))
  {
    framework_call ( "agent_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agent_wipe_finish"))
  {
    framework_call ( "agent_wipe_finish", $parameters);
  }
}
?>
