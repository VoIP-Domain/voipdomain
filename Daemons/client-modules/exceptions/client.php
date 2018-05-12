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
 * VoIP Domain exceptions actions module. This module add the Asterisk client actions
 * calls related to exceptions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Exceptions
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "exception_add", "exception_add");
framework_add_hook ( "exception_change", "exception_change");
framework_add_hook ( "exception_remove", "exception_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "exception_wipe", "exception_wipe");
cleanup_register ( "Exceptions", "exception_wipe");

/**
 * Function to create a new exception (global number allow list).
 * Required parameters are: (string) Number, (string) Description
 * Possible results:
 *   - 200: OK, exception created (overwritten)
 *   - 201: OK, exception created
 *   - 400: Exception already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function exception_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "exception_add_start"))
  {
    $parameters = framework_call ( "exception_add_start", $parameters);
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
  if ( framework_has_hook ( "exception_add_validate"))
  {
    $data = framework_call ( "exception_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "exception_add_sanitize"))
  {
    $parameters = framework_call ( "exception_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "exception_add_pre"))
  {
    $parameters = framework_call ( "exception_add_pre", $parameters, false, $parameters);
  }

  // Verify if exception exist
  if ( check_config ( "config", "dialplan-exception-" . substr ( $parameters["Number"], 1)))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Exception already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Create file structure
  $exception = "exten => " . $parameters["Number"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Exception: " . $parameters["Description"] . "))\n";

  // Write exception file
  if ( ! write_config ( "config", "dialplan-exception-" . substr ( $parameters["Number"], 1), $exception))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create exception data file
  if ( ! write_config ( "datafile", "exception-" . substr ( $parameters["Number"], 1), $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "exception_add_post") && ! framework_call ( "exception_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "exception_add_finish"))
  {
    framework_call ( "exception_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Exception created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Exception created.");
  }
}

/**
 * Function to change an existing exception (global number allow list).
 * Required parameters are: (string) Number, (string) NewNumber, (string) Description
 * Possible results:
 *   - 200: OK, exception changed
 *   - 404: Exception doesn't exist
 *   - 406: Invalid parameters
 *   - 409: Exception new number already exist
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function exception_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "exception_change_start"))
  {
    $parameters = framework_call ( "exception_change_start", $parameters);
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
  if ( framework_has_hook ( "exception_change_validate"))
  {
    $data = framework_call ( "exception_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "exception_change_sanitize"))
  {
    $parameters = framework_call ( "exception_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "exception_change_pre"))
  {
    $parameters = framework_call ( "exception_change_pre", $parameters, false, $parameters);
  }

  // Verify if exception exist
  if ( ! check_config ( "config", "dialplan-exception-" . substr ( $parameters["Number"], 1)))
  {
    return array ( "code" => 404, "message" => "Exception doesn't exist.");
  }

  // Verify if new exception exist
  if ( $parameters["Number"] != $parameters["NewNumber"] && check_config ( "config", "dialplan-exception-" . substr ( $parameters["NewNumber"], 1)))
  {
    return array ( "code" => 409, "message" => "Exception new number already exist.");
  }

  // Remove exception file
  if ( ! unlink_config ( "config", "dialplan-exception-" . substr ( $parameters["Number"], 1)) || ! unlink_config ( "datafile", "exception-" . substr ( $parameters["Number"], 1)))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $exception = "exten => " . $parameters["NewNumber"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Exception: " . $parameters["Description"] . "))\n";

  // Write exception file
  if ( ! write_config ( "config", "dialplan-exception-" . substr ( $parameters["NewNumber"], 1), $exception))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create exception data file
  $parameters["Number"] = $parameters["NewNumber"];
  unset ( $parameters["NewNumber"]);
  if ( ! write_config ( "datafile", "exception-" . substr ( $parameters["Number"], 1), $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "exception_change_post") && ! framework_call ( "exception_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "exception_change_finish"))
  {
    framework_call ( "exception_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Exception changed.");
}

/**
 * Function to remove an existing exception (global number allow list).
 * Required parameters are: (string) Number
 * Possible results:
 *   - 200: OK, exception removed
 *   - 404: Exception doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function exception_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "exception_remove_start"))
  {
    $parameters = framework_call ( "exception_remove_start", $parameters);
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
  if ( framework_has_hook ( "exception_remove_validate"))
  {
    $data = framework_call ( "exception_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "exception_remove_sanitize"))
  {
    $parameters = framework_call ( "exception_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "exception_remove_pre"))
  {
    $parameters = framework_call ( "exception_remove_pre", $parameters, false, $parameters);
  }

  // Verify if exception exist
  if ( ! check_config ( "config", "dialplan-exception-" . substr ( $parameters["Number"], 1)))
  {
    return array ( "code" => 404, "message" => "Exception doesn't exist.");
  }

  // Remove exception file
  if ( ! unlink_config ( "config", "dialplan-exception-" . substr ( $parameters["Number"], 1)) || ! unlink_config ( "datafile", "exception-" . substr ( $parameters["Number"], 1)))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "exception_remove_post") && ! framework_call ( "exception_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "exception_remove_finish"))
  {
    framework_call ( "exception_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Exception removed.");
}

/**
 * Function to remove all existing exceptions configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function exception_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "exception_wipe_start"))
  {
    $parameters = framework_call ( "exception_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "exception_wipe_pre"))
  {
    framework_call ( "exception_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "exception") as $filename)
  {
    unlink_config ( "config", "dialplan-exception-" . (int) substr ( $filename, strrpos ( $filename, "-") + 1));
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "exception_wipe_post"))
  {
    framework_call ( "exception_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "exception_wipe_finish"))
  {
    framework_call ( "exception_wipe_finish", $parameters);
  }
}
?>
