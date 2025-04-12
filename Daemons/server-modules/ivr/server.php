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
 * VoIP Domain IVRs actions module. This module add the Asterisk client actions
 * calls related to IVRs.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage IVRs
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Function to apply allowed variables to a string.
 * Allowed variables:
 *  - {date}: Current date in YYYYMMDD format;
 *  - {time}: Current time in HHMMSS format;
 *  - {calledid}: Number of caller telephone (digits only);
 *  - {var_VARIABLE}: The content of IVR variable VARIABLE.
 *
 * @param string $string The string to be evaluated.
 * @param array $allowed An array with the allowed variables. Default allow all.
 * @return string The string with substitutions to Asterisk variables.
 */
function operator_apply_variables ( $string, $allowed = array ( "{date}", "{time}", "{callerid}", "{var_VARIABLE}"))
{
  /**
   * Allow generic variables
   */
  if ( in_array ( "{date}", $allowed))
  {
    $string = str_replace ( "{date}", "\${STRFTIME(\${EPOCH},,%Y%m%d)}", $string);
  }
  if ( in_array ( "{time}", $allowed))
  {
    $string = str_replace ( "{time}", "\${STRFTIME(\${EPOCH},,%H%M%S)}", $string);
  }
  if ( in_array ( "{callerid}", $allowed))
  {
    $string = str_replace ( "{callerid}", "\${FILTER(\"0-9\",\${CALLERID(num)})}", $string);
  }

  /**
   * Allow IVR variable
   */
  $result = "";
  if ( in_array ( "{var_VARIABLE}", $allowed))
  {
    while ( strpos ( $string, "{var_") !== false)
    {
      $result .= substr ( $string, strpos ( $string, "{var_"));
      $variable = substr ( $string, 5, strpos ( $string, "}") + 5);
      $string = substr ( $string, strpos ( $string, "}") + 1);
      if ( ! preg_match ( "/^[a-z0-9_]+$/", $variable))
      {
        writeLog ( "IVR with invalid variable \"" . $variable . "\", ignoring!", VoIP_LOG_WARNING);
      } else {
        $result .= "\${vd_ivr_var_" . $variable . "}";
      }
    }
  }
  $result .= $string;

  /**
   * Return result string
   */
  return $result;
}

/**
 * Include operator files
 */
foreach ( glob ( dirname ( __FILE__) . "/operator-*.php") as $filename)
{
  require_once ( $filename);
}

/**
 * Function to create an Asterisk IVR dialplan.
 *
 * @param string $name IVR name
 * @param string $description IVR description
 * @param int $revision IVR revision
 * @param array $workflow IVR workflow
 * @return string Asterisk IVR dialplan workflow
 */
function create_ivr_workflow ( $name, $description, $revision, $workflow)
{
  // Rearrange operators structure
  $operators = array ();
  foreach ( $workflow["Operators"] as $operator)
  {
    $id = ( (string) $operator["ID"] != "start" ? "op_" : "") . $operator["ID"];
    unset ( $operator["ID"]);
    $operators[$id] = $operator;
  }
  $workflow["Operators"] = $operators;

  // Rearrange links structure
  $links = array ();
  foreach ( $workflow["Links"] as $link)
  {
    $id = ( (string) $link["FromOperator"] != "start" ? "op_" : "") . $link["FromOperator"];
    if ( ! array_key_exists ( $id, $links))
    {
      $links[$id] = array ();
    }
    $links[$id][$link["FromConnector"]] = "op_" . $link["ToOperator"];
  }
  $workflow["Links"] = $links;

  // Create workflow dialplan
  $output = "[vd_ivr_" . $name . "_rev_" . $revision . "]\n";
  $output .= "exten => _X.,1,Set(CDR(vd_ivr)=[\${EPOCH}-" . $name . "-rev-" . $revision . ")\n";
  $output .= " same => n,GoTo(start)\n";
  foreach ( $workflow["Operators"] as $id => $operator)
  {
    $output .= "\n";
    $output .= " same => n(" . $id . "),Set(CDR(vd_ivr)=\${CDR(vd_ivr)},\${EPOCH}-" . $id . "_" . $operator["Operator"] . ")\n";
    if ( framework_has_hook ( "ivr_operator_" . $operator["Operator"]))
    {
      $output .= framework_call ( "ivr_operator_" . $operator["Operator"], array ( "Operator" => $operator["Operator"], "Properties" => $operator["Properties"], "Links" => $workflow["Links"][$id], "ID" => $id), false, "");
    } else {
      writeLog ( "IVR \"" . $operator["Operator"] . "\" module operator not found!", VoIP_LOG_WARNING);
    }
    $output .= " same => n,Set(CDR(vd_ivr)=\${CDR(vd_ivr)},\${EPOCH}-noout)\n";
    $output .= " same => n,HangUp()\n";
  }

  // Hangup extension (finish CDR variable)
  $output .= "\n";
  $output .= "exten => h,1,Set(CDR(vd_ivr)=\${CDR(vd_ivr)},\${EPOCH}-h])\n";

  return $output;
}

/**
 * API call's hook's
 */
framework_add_hook ( "ivr_add", "ivr_add");
framework_add_hook ( "ivr_change", "ivr_change");
framework_add_hook ( "ivr_remove", "ivr_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "ivr_wipe", "ivr_wipe");
cleanup_register ( "IVRs", "ivr_wipe");

/**
 * Function to create a new IVR.
 * Required parameters are: (string) Name, (string) Description, (array (array)) Workflow[]
 * Possible results:
 *   - 200: OK, IVR created (overwrite)
 *   - 201: OK, IVR created
 *   - 400: IVR already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function ivr_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ivr_add_start"))
  {
    $parameters = framework_call ( "ivr_add_start", $parameters);
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
  if ( ! array_key_exists ( "Workflow", $parameters))
  {
    $data["Workflow"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Workflow", $data) && ! is_array ( $parameters["Workflow"]))
  {
    $data["Workflow"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ivr_add_validate"))
  {
    $data = framework_call ( "ivr_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "ivr_add_sanitize"))
  {
    $parameters = framework_call ( "ivr_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ivr_add_pre"))
  {
    $parameters = framework_call ( "ivr_add_pre", $parameters, false, $parameters);
  }

  // Verify if IVR exist
  if ( check_config ( "config", "dialplan-ivr-" . $parameters["Name"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "IVR already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Create file structure
  $ivr = "[vd_ivr_" . $parameters["Name"] . "]\n" .
         "exten => _X.,1,GoTo(vd_ivr_" . $parameters["Name"] . "_rev_1)\n";

  // Write IVR file
  if ( ! write_config ( "config", "dialplan-ivr-" . $parameters["Name"], $ivr))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create IVR workflow structure
  $ivr = create_ivr_workflow ( $parameters["Name"], $parameters["Description"], 1, $parameters["Workflow"]);
  if ( ! write_config ( "config", "dialplan-ivr-" . $parameters["Name"] . "-rev-1", $ivr))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create IVR data file
  if ( ! write_config ( "datafile", "ivr-" . $parameters["Name"], array ( "Name" => $parameters["Name"], "Description" => $parameters["Description"], "Actual" => "rev_1", "Revisions" => array ( "rev_1" => $parameters))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ivr_add_post") && ! framework_call ( "ivr_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk IVRs
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ivr_add_finish"))
  {
    framework_call ( "ivr_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "IVR created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "IVR created.");
  }
}

/**
 * Function to change an existing IVR.
 * Required parameters are: (string) Name, (string) NewName, (string) Description, (int) Revision, (array (array)) Workflow[]
 * Possible results:
 *   - 200: OK, IVR changed
 *   - 404: IVR doesn't exist
 *   - 406: Invalid parameters
 *   - 409: IVR new name already exist
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function ivr_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ivr_change_start"))
  {
    $parameters = framework_call ( "ivr_change_start", $parameters);
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
  if ( ! array_key_exists ( "Revision", $parameters))
  {
    $data["Revision"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Revision", $data) && ! preg_match ( "/^[1-9]+[0-9]*$/", $parameters["Revision"]))
  {
    $data["Revision"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Workflow", $parameters))
  {
    $data["Workflow"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Workflow", $data) && ! is_array ( $parameters["Workflow"]))
  {
    $data["Workflow"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ivr_change_validate"))
  {
    $data = framework_call ( "ivr_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "ivr_change_sanitize"))
  {
    $parameters = framework_call ( "ivr_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ivr_change_pre"))
  {
    $parameters = framework_call ( "ivr_change_pre", $parameters, false, $parameters);
  }

  // Verify if IVR exist
  if ( ! check_config ( "config", "dialplan-ivr-" . $parameters["Name"]))
  {
    return array ( "code" => 404, "message" => "IVR doesn't exist.");
  }

  // Verify if new IVR exist
  if ( $parameters["Name"] != $parameters["NewName"] && check_config ( "config", "dialplan-ivr-" . $parameters["NewName"]))
  {
    return array ( "code" => 409, "message" => "IVR new name already exist.");
  }

  // Read datafile
  $data = read_config ( "datafile", "ivr-" . $parameters["Name"]);

  // Remove IVR files
  if ( ! unlink_config ( "config", "dialplan-ivr-" . $parameters["Name"]) || ! unlink_config ( "datafile", "ivr-" . $parameters["Name"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $ivr = "[vd_ivr_" . $parameters["NewName"] . "]\n" .
         "exten => _X.,1,GoTo(vd_ivr_" . $parameters["NewName"] . "_rev_" . $parameters["Revision"] . ")\n";

  // Write IVR file
  if ( ! write_config ( "config", "dialplan-ivr-" . $parameters["NewName"], $ivr))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create IVR workflow structure
  $ivr = create_ivr_workflow ( $parameters["NewName"], $parameters["Description"], $parameters["Revision"], $parameters["Workflow"]);
  if ( ! write_config ( "config", "dialplan-ivr-" . $parameters["NewName"] . "-rev-" . $parameters["Revision"], $ivr))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create ivr data file
  $data["Name"] = $parameters["NewName"];
  $data["Description"] = $parameters["Description"];
  $data["Actual"] = "rev_" . $parameters["Revision"];
  $parameters["Name"] = $parameters["NewName"];
  unset ( $parameters["NewName"]);
  unset ( $parameters["Revision"]);
  $data["Revisions"][$data["Actual"]] = $parameters;
  if ( ! write_config ( "datafile", "ivr-" . $parameters["Name"], $data))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ivr_change_post") && ! framework_call ( "ivr_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk IVRs
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ivr_change_finish"))
  {
    framework_call ( "ivr_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "IVR changed.");
}

/**
 * Function to remove an existing IVR.
 * Required parameters are: (string) Name
 * Possible results:
 *   - 200: OK, IVR removed
 *   - 404: IVR doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function ivr_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ivr_remove_start"))
  {
    $parameters = framework_call ( "ivr_remove_start", $parameters);
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
  if ( framework_has_hook ( "ivr_remove_validate"))
  {
    $data = framework_call ( "ivr_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "ivr_remove_sanitize"))
  {
    $parameters = framework_call ( "ivr_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ivr_remove_pre"))
  {
    $parameters = framework_call ( "ivr_remove_pre", $parameters, false, $parameters);
  }

  // Verify if IVR exist
  if ( ! check_config ( "config", "dialplan-ivr-" . $parameters["Name"]))
  {
    return array ( "code" => 404, "message" => "IVR doesn't exist.");
  }

  // Remove IVR files
  $data = read_config ( "datafile", "ivr-" . $parameters["Name"]);
  foreach ( $data["Revisions"] as $revision => $revisiondata)
  {
    if ( ! unlink_config ( "config", "dialplan-ivr-" . $revisiondata["Name"] . "-" . $revision))
    {
      return array ( "code" => 500, "message" => "Error removing file.");
    }
  }
  if ( ! unlink_config ( "config", "dialplan-ivr-" . $parameters["Name"]) || ! remove_config ( "datafile", "ivr-" . $parameters["Name"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ivr_remove_post") && ! framework_call ( "ivr_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk IVRs
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ivr_remove_finish"))
  {
    framework_call ( "ivr_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "IVR removed.");
}

/**
 * Function to remove all existing IVRs configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function ivr_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ivr_wipe_start"))
  {
    $parameters = framework_call ( "ivr_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ivr_wipe_pre"))
  {
    framework_call ( "ivr_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "ivr") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    foreach ( $data["Revisions"] as $revision => $revisiondata)
    {
      unlink_config ( "config", "dialplan-ivr-" . $revisiondata["Name"] . "-" . str_replace ( "_", "-", $revision));
    }
    unlink_config ( "config", "dialplan-ivr-" . $data["Name"]);
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ivr_wipe_post"))
  {
    framework_call ( "ivr_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ivr_wipe_finish"))
  {
    framework_call ( "ivr_wipe_finish", $parameters);
  }
}
?>
