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
 * VoIP Domain ranges actions module. This module add the Asterisk client actions
 * calls related to ranges.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Ranges
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "range_add", "range_add");
framework_add_hook ( "range_change", "range_change");
framework_add_hook ( "range_remove", "range_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "range_wipe", "range_wipe");
cleanup_register ( "Ranges", "range_wipe");

/**
 * Dialplan range hook
 */
framework_add_hook ( "dialplan_generate", "range_dialplan_generate");

/**
 * Function to create a new range.
 * Required parameters are: (int) ID, (int) Server, (int) Start, (int) Finish
 * Possible results:
 *   - 200: OK, range created (overwritten)
 *   - 201: OK, range created
 *   - 400: Range already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function range_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "range_add_start"))
  {
    $parameters = framework_call ( "range_add_start", $parameters);
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
  if ( ! array_key_exists ( "Server", $parameters))
  {
    $data["Server"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Server", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Server"]))
  {
    $data["Server"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Start", $parameters))
  {
    $data["Start"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Start", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Start"]))
  {
    $data["Start"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Finish", $parameters))
  {
    $data["Finish"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Finish", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Finish"]))
  {
    $data["Finish"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "range_add_validate"))
  {
    $data = framework_call ( "range_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "range_add_sanitize"))
  {
    $parameters = framework_call ( "range_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "range_add_pre"))
  {
    $parameters = framework_call ( "range_add_pre", $parameters, false, $parameters);
  }

  // If server ID is not our server, add the dialplan
  if ( $parameters["Server"] != fetch_config ( "daemon", "serverid"))
  {
    // Verify if range exist
    if ( check_config ( "config", "range-" . $parameters["ID"]))
    {
      return array ( "code" => 400, "message" => "Range already exist.");
    }

    // Create file structure
    $range = "; Server " . $parameters["Server"] . " range from " . $parameters["Start"] . " to " . $parameters["Finish"] . "\n" .
             " same => n,ExecIf(\$[\$[\"\${SERVER}\" != \"" . $parameters["Server"] . "\"] & \$[\"\${ARG1}\" >= \"" . $parameters["Start"] . "\"] & \$[\"\${ARG1}\" <= \"" . $parameters["Finish"] . "\"]]?Dial(SIP/\${ARG1}@vd_server_" . $parameters["Server"] . "))\n";

    // Write range file
    if ( ! write_config ( "config", "dialplan-range-" . $parameters["ID"], $range))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  }

  // Create range data file
  if ( ! write_config ( "datafile", "range-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "range_add_post") && ! framework_call ( "range_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  if ( $parameters["Server"] != fetch_config ( "daemon", "serverid"))
  {
    asterisk_exec ( "dialplan reload");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "range_add_finish"))
  {
    framework_call ( "range_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Range created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Range created.");
  }
}

/**
 * Function to change an existing range.
 * Required parameters are: (int) ID, (int) Server, (int) Start, (int) Finish
 * Possible results:
 *   - 200: OK, range changed
 *   - 404: Range doesn't exist
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
function range_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "range_change_start"))
  {
    $parameters = framework_call ( "range_change_start", $parameters);
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
  if ( ! array_key_exists ( "Server", $parameters))
  {
    $data["Server"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Server", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Server"]))
  {
    $data["Server"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Start", $parameters))
  {
    $data["Start"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Start", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Start"]))
  {
    $data["Start"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Finish", $parameters))
  {
    $data["Finish"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Finish", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Finish"]))
  {
    $data["Finish"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "range_change_validate"))
  {
    $data = framework_call ( "range_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "range_change_sanitize"))
  {
    $parameters = framework_call ( "range_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "range_change_pre"))
  {
    $parameters = framework_call ( "range_change_pre", $parameters, false, $parameters);
  }

  // Verify if range exist
  if ( ! check_config ( "datafile", "range-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Range doesn't exist.");
  }

  // Remove range datafile
  if ( ! unlink_config ( "datafile", "range-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // If server ID is not our server, remove the dialplan
  if ( $parameters["Server"] != fetch_config ( "daemon", "serverid"))
  {
    // Remove range file
    if ( ! unlink_config ( "config", "dialplan-range-" . $parameters["ID"]))
    {
      return array ( "code" => 500, "message" => "Error removing file.");
    }

    // Create file structure
    $range = "; Server " . $parameters["Server"] . " range from " . $parameters["Start"] . " to " . $parameters["Finish"] . "\n" .
             " same => n,ExecIf(\$[\$[\"\${SERVER}\" != \"" . $parameters["Server"] . "\"] & \$[\"\${ARG1}\" >= \"" . $parameters["Start"] . "\"] & \$[\"\${ARG1}\" <= \"" . $parameters["Finish"] . "\"]]?Dial(SIP/\${ARG1}@vd_server_" . $parameters["Server"] . "))\n";

    // Write range file
    if ( ! write_config ( "config", "dialplan-range-" . $parameters["ID"], $range))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  }

  // Create range data file
  if ( ! write_config ( "datafile", "range-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "range_change_post") && ! framework_call ( "range_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  if ( $parameters["Server"] != fetch_config ( "daemon", "serverid"))
  {
    asterisk_exec ( "dialplan reload");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "range_change_finish"))
  {
    framework_call ( "range_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Range changed.");
}

/**
 * Function to remove an existing range.
 * Required parameters are: (int) ID
 * Possible results:
 *   - 200: OK, range removed
 *   - 404: Range doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function range_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "range_remove_start"))
  {
    $parameters = framework_call ( "range_remove_start", $parameters);
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
  if ( framework_has_hook ( "range_remove_validate"))
  {
    $data = framework_call ( "range_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "range_remove_sanitize"))
  {
    $parameters = framework_call ( "range_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "range_remove_pre"))
  {
    $parameters = framework_call ( "range_remove_pre", $parameters, false, $parameters);
  }
  // Verify if range exist
  if ( ! check_config ( "datafile", "range-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Range doesn't exist.");
  }

  // Read datafile
  $range = read_config ( "datafile", "range-" . $parameters["ID"]);

  // Remove range datafile
  if ( ! unlink_config ( "datafile", "range-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Remove range files
  if ( $range["Server"] != fetch_config ( "daemon", "serverid") && ! unlink_config ( "config", "dialplan-range-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "range_remove_post") && ! framework_call ( "range_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  if ( $range["Server"] != fetch_config ( "daemon", "serverid"))
  {
    asterisk_exec ( "dialplan reload");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "range_remove_finish"))
  {
    framework_call ( "range_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Range removed.");
}

/**
 * Function to remove all existing ranges configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function range_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "range_wipe_start"))
  {
    $parameters = framework_call ( "range_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "range_wipe_pre"))
  {
    framework_call ( "range_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "range") as $filename)
  {
    unlink_config ( "config", "dialplan-range-" . (int) substr ( $filename, strrpos ( $filename, "-") + 1));
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "range_wipe_post"))
  {
    framework_call ( "range_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "range_wipe_finish"))
  {
    framework_call ( "range_wipe_finish", $parameters);
  }
}

/**
 * Function to add range dialplan.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Buffer with the manipulated dialplan
 */
function range_dialplan_generate ( $buffer, $parameters)
{
  // Add each range in the dialplan
  $ranges = array ();
  foreach ( list_config ( "datafile", "range") as $filename)
  {
    $range = read_config ( "datafile", $filename);
    $ranges[] = array ( "Start" => $range["Start"], "Finish" => $range["Finish"]);
  }

  // Redure ranges if possible
  for ( $x = 0; $x <= sizeof ( $ranges); $x++)
  {
    for ( $y = 0; $y <= sizeof ( $ranges); $y++)
    {
      if ( array_key_exists ( $x, $ranges) && array_key_exists ( $y, $ranges) && $ranges[$x]["Finish"] + 1 == $ranges[$y]["Start"])
      {
        $ranges[$y]["Start"] = $ranges[$x]["Start"];
        unset ( $ranges[$x]);
      }
    }
  }
  $ranges = array_values ( $ranges);

  // Generate ranges configuration
  $data = array ();
  foreach ( $ranges as $range)
  {
    $entries = parse_dialplan ( $range["Start"], $range["Finish"]);
    foreach ( $entires as $pattern)
    {
      $data[] = array ( "Pattern" => $pattern, "Label" => "Extensions", "Kind" => "Extensions", "Emergency" => false);
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
