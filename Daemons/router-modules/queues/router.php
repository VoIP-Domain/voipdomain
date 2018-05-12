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
 * VoIP Domain queues routes module. This module add the monitoring routes events related
 * to queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "queues_bootup", "queues_bootup");
framework_add_hook ( "queues_unload", "queues_unload");
framework_add_hook ( "queues_member_add", "queues_member_add");
framework_add_hook ( "queues_member_remove", "queues_member_remove");
framework_add_hook ( "queues_member_update", "queues_member_update");
framework_add_hook ( "queues_caller_add", "queues_caller_add");
framework_add_hook ( "queues_caller_abandon", "queues_caller_abandon");
framework_add_hook ( "queues_caller_remove", "queues_caller_remove");
framework_add_hook ( "queues_summary", "queues_summary");

/**
 * Function to boot up the queues.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_bootup ( $buffer, $parameters)
{
  global $_in;

  // Empty queues from server:
  unset ( $_in["Queues"][$parameters["ServerID"]]);

  // Just load the queues into queues array:
  $_in["Queues"][$parameters["ServerID"]] = $parameters["Queues"];

  // Log event:
  writeLog ( "Queues loaded for server #" . $parameters["ServerID"] . ".");

  // End event:
  return $buffer;
}

/**
 * Function to unload queues.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_unload ( $buffer, $parameters)
{
  global $_in;

  // Empty queues from server:
  unset ( $_in["Queues"][$parameters["ServerID"]]);

  // Log event:
  writeLog ( "Queues unloaded from server #" . $parameters["ServerID"] . ".");

  // End event:
  return $buffer;
}

/**
 * Function to add new member to an existing queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_member_add ( $buffer, $parameters)
{
  global $_in;

  // Check if queue exist at server:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"][$parameters["ServerID"]]))
  {
    writeLog ( "Received new member from server #" . $parameters["ServerID"] . " into unknown queue \"" . $parameters["Queue"] . "\".", VoIP_LOG_WARNING);
    return false;
  }

  // Add new member:
  $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Name"]] = $parameters;

  // Log event:
  writeLog ( "Added new member to server #" . $parameters["ServerID"] . " queue \"" . $parameters["Queue"] . "\".");

  // Add new event notification:
  $buffer["events"][] = array (
    "event" => "queue_member_add",
    "parameters" => array (
      "ServerID" => $parameters["ServerID"],
      "Queue" => $parameters["Queue"],
      "Member" => $parameters["Name"],
      "Location" => $parameters["Location"],
      "Membership" => $parameters["Membership"],
      "Penalty" => $parameters["Penalty"],
      "CallsTaken" => $parameters["CallsTaken"],
      "LastCall" => $parameters["LastCall"],
      "LastPause" => $parameters["LastPause"],
      "InCall" => $parameters["InCall"],
      "Status" => $parameters["Status"],
      "Paused" => $parameters["Paused"],
      "PauseReason" => $parameters["PauseReason"],
      "RingInUse" => $parameters["RingInUse"],
      "WrapUpTime" => $parameters["WrapUpTime"]
    )
  );

  // End event:
  return $buffer;
}

/**
 * Function to update an existing member fron a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_member_update ( $buffer, $parameters)
{
  global $_in;

  // Check if queue exist at server:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"][$parameters["ServerID"]]))
  {
    writeLog ( "Received member update from server #" . $parameters["ServerID"] . " into unknown queue \"" . $parameters["Queue"] . "\".", VoIP_LOG_WARNING);
    return false;
  }

  // Update member:
  if ( array_key_exists ( "Penalty", $parameters))
  {
    $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Member"]]["Penalty"] = $parameters["Penalty"];
  }
  if ( array_key_exists ( "CallsTaken", $parameters))
  {
    $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Member"]]["CallsTaken"] = $parameters["CallsTaken"];
  }
  if ( array_key_exists ( "LastCall", $parameters))
  {
    $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Member"]]["LastCall"] = $parameters["LastCall"];
  }
  if ( array_key_exists ( "InCall", $parameters))
  {
    $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Member"]]["InCall"] = $parameters["InCall"];
  }
  if ( array_key_exists ( "Status", $parameters))
  {
    $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Member"]]["Status"] = $parameters["Status"];
  }
  if ( array_key_exists ( "Paused", $parameters))
  {
    $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Member"]]["Paused"] = $parameters["Paused"];
  }
  if ( array_key_exists ( "PauseReason", $parameters))
  {
    $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Member"]]["PauseReason"] = $parameters["PauseReason"];
  }
  if ( array_key_exists ( "RingInUse", $parameters))
  {
    $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Member"]]["RingInUse"] = $parameters["RingInUse"];
  }

  // Log event:
  writeLog ( "Updated member \"" . $parameters["Member"] . "\" to server #" . $parameters["ServerID"] . " queue \"" . $parameters["Queue"] . "\".");

  // End event:
  return $buffer;
}

/**
 * Function to remove an existing member fron a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_member_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if queue exist at server:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"][$parameters["ServerID"]]))
  {
    writeLog ( "Received member removal from server #" . $parameters["ServerID"] . " into unknown queue \"" . $parameters["Queue"] . "\".", VoIP_LOG_WARNING);
    return false;
  }

  // Remove member:
  unset ( $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Members"][$parameters["Name"]]);

  // Log event:
  writeLog ( "Removed member \"" . $parameters["Name"] . "\" to server #" . $parameters["ServerID"] . " queue \"" . $parameters["Queue"] . "\".");

  // End event:
  return $buffer;
}

/**
 * Function to add new caller to an existing queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_caller_add ( $buffer, $parameters)
{
  global $_in;

  // Check if queue exist at server:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"][$parameters["ServerID"]]))
  {
    writeLog ( "Received new caller from server #" . $parameters["ServerID"] . " into unknown queue \"" . $parameters["Queue"] . "\".", VoIP_LOG_WARNING);
    return false;
  }

  // Add new caller:
  $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Callers"][$parameters["Caller"]["Channel"]] = $parameters["Caller"];

  // Log event:
  writeLog ( "Added new caller to server #" . $parameters["ServerID"] . " queue \"" . $parameters["Queue"] . "\".");

  // End event:
  return $buffer;
}

/**
 * Function to update an existing caller fron a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_caller_update ( $buffer, $parameters)
{
  global $_in;

  // Check if queue exist at server:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"][$parameters["ServerID"]]))
  {
    writeLog ( "Received caller update from server #" . $parameters["ServerID"] . " into unknown queue \"" . $parameters["Queue"] . "\".", VoIP_LOG_WARNING);
    return false;
  }

  // Update caller:
  $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Callers"][$parameters["Channel"]]["Position"] = $parameters["Position"];
  $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Callers"][$parameters["Channel"]]["OriginalPosition"] = $parameters["OriginalPosition"];
  $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Callers"][$parameters["Channel"]]["HoldTime"] = $parameters["HoldTime"];

  // Log event:
  writeLog ( "Updated caller \"" . $parameters["Channel"] . "\" to server #" . $parameters["ServerID"] . " queue \"" . $parameters["Queue"] . "\".");

  // End event:
  return $buffer;
}

/**
 * Function to remove an existing caller fron a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_caller_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if queue exist at server:
  if ( ! array_key_exists ( $parameters["Queue"], $_in["Queues"][$parameters["ServerID"]]))
  {
    writeLog ( "Received caller removal from server #" . $parameters["ServerID"] . " into unknown queue \"" . $parameters["Queue"] . "\".", VoIP_LOG_WARNING);
    return false;
  }

  // Remove caller:
  unset ( $_in["Queues"][$parameters["ServerID"]][$parameters["Queue"]]["Callers"][$parameters["Channel"]]);

  // Log event:
  writeLog ( "Removed caller \"" . $parameters["Channel"] . "\" to server #" . $parameters["ServerID"] . " queue \"" . $parameters["Queue"] . "\".");

  // End event:
  return $buffer;
}

/**
 * Function to retrieve a summary of current queues.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_summary ( $buffer, $parameters)
{
  global $_in;

  // Get status of all queues at system:
  $queues = array ();
  foreach ( $_in["Queues"] as $serverid => $queue)
  {
    $queues[$queue["Queue"]] = $queue;
  }

echo "Summary response: " . sizeof ( $queues) . " buffer: " . sizeof ( $buffer) . "\n";

  // End event:
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $queues);
}
?>
