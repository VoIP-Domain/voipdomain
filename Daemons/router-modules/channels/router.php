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
 * VoIP Domain channels routes module. This module add the monitoring routes events related
 * to channels.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Channels
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "channels_bootup", "channels_bootup");
framework_add_hook ( "channels_unload", "channels_unload");
framework_add_hook ( "channels_create", "channels_create");
framework_add_hook ( "channels_state", "channels_state");
framework_add_hook ( "channels_callerid", "channels_callerid");
framework_add_hook ( "channels_destroy", "channels_destroy");

/**
 * Function to boot up the channels.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function channels_bootup ( $buffer, $parameters)
{
  global $_in;

  // Empty channels from server:
  unset ( $_in["Channels"][$parameters["ServerID"]]);

  // Just load the channels into channels array:
  $_in["Channels"][$parameters["ServerID"]] = $parameters["Channels"];

  // Log event:
  writeLog ( "Channels loaded for server #" . $parameters["ServerID"] . ".");

  // End event:
  return $buffer;
}

/**
 * Function to unload channels.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function channels_unload ( $buffer, $parameters)
{
  global $_in;

  // Empty channels from server:
  unset ( $_in["Channels"][$parameters["ServerID"]]);

  // Log event:
  writeLog ( "Channels unloaded from server #" . $parameters["ServerID"] . ".");

  // End event:
  return $buffer;
}

/**
 * Function to add new channel.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function channels_create ( $buffer, $parameters)
{
  global $_in;

  // Add new channel:
  $_in["Channels"][$parameters["ServerID"]][$parameters["Channel"]] = $parameters["Data"];

  // Log event:
  writeLog ( "Added new channel to server #" . $parameters["ServerID"] . " channel \"" . $parameters["Channel"] . "\".");

  // End event:
  return $buffer;
}

/**
 * Function to update an existing channel state.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function channels_state ( $buffer, $parameters)
{
  global $_in;

  // Check if channel exist at server:
  if ( ! array_key_exists ( $parameters["Channel"], $_in["Channels"][$parameters["ServerID"]]))
  {
    writeLog ( "Received channel update from server #" . $parameters["ServerID"] . " into unknown channel \"" . $parameters["Channel"] . "\".", VoIP_LOG_WARNING);
    return false;
  }

  // Update channel:
  $_in["Channels"][$parameters["ServerID"]][$parameters["Channel"]]["State"] = $parameters["State"];

  // Log event:
  writeLog ( "Updated channel \"" . $parameters["Channel"] . "\" to server #" . $parameters["ServerID"] . " status \"" . $parameters["Status"] . "\".");

  // End event:
  return $buffer;
}

/**
 * Function to remove an existing channel.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function channels_destroy ( $buffer, $parameters)
{
  global $_in;

  // Check if channel exist at server:
  if ( ! array_key_exists ( $parameters["Channel"], $_in["Channels"][$parameters["ServerID"]]))
  {
    writeLog ( "Received channel removal from server #" . $parameters["ServerID"] . " into unknown channel \"" . $parameters["Channel"] . "\".", VoIP_LOG_WARNING);
    return false;
  }

  // Remove member:
  unset ( $_in["Channels"][$parameters["ServerID"]][$parameters["Channel"]]);

  // Log event:
  writeLog ( "Removed channel \"" . $parameters["Channel"] . "\" to server #" . $parameters["ServerID"] . " cause \"" . $parameters["Cause"] . "\".");

  // End event:
  return $buffer;
}

/**
 * Function to add new caller to an existing channel.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function channels_callerid ( $buffer, $parameters)
{
  global $_in;

  // Check if channel exist at server:
  if ( ! array_key_exists ( $parameters["Channel"], $_in["Channels"][$parameters["ServerID"]]))
  {
    writeLog ( "Received new caller from server #" . $parameters["ServerID"] . " into unknown channel \"" . $parameters["Channel"] . "\".", VoIP_LOG_WARNING);
    return false;
  }

  // Add new caller id:
  $_in["Channels"][$parameters["ServerID"]][$parameters["Channel"]]["CallerID"] = $parameters["CallerID"];
  $_in["Channels"][$parameters["ServerID"]][$parameters["Channel"]]["ConnectedLine"] = $parameters["ConnectedLine"];

  // Log event:
  writeLog ( "Updated channel caller id from server #" . $parameters["ServerID"] . " channel \"" . $parameters["Channel"] . "\".");

  // End event:
  return $buffer;
}
?>
