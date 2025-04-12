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
 * VoIP Domain channels Asterisk AMI events module. This module add the Asterisk
 * monitoring events related to channels.
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
framework_add_hook ( "FullyBooted", "channels_bootup");
framework_add_hook ( "Load", "channels_load");
framework_add_hook ( "Reload", "channels_reload");
framework_add_hook ( "Unload", "channels_unload");
framework_add_hook ( "Shutdown", "channels_shutdown");
framework_add_hook ( "CoreShowChannel", "channels_show");
framework_add_hook ( "CoreShowChannelsComplete", "channels_status_complete");
framework_add_hook ( "Newchannel", "channels_new");
framework_add_hook ( "Newstate", "channels_state");
framework_add_hook ( "Hangup", "channels_hangup");
framework_add_hook ( "NewCallerid", "channels_callerid");

/**
 * Function to boot up the channels.
 *
 * @global array $_in Framework global configuration variable
 * @global object $ami Asterisk AMI connection object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_bootup ( $buffer, $parameters)
{
  global $_in, $ami;

  // Setup channels variables:
  $_in["Channels"] = array ();
  $_in["Status"]["Channels"] = array ( "BootUp" => true, "Reload" => false);

  // Request all channels status:
  $ami->request ( "CoreShowChannels", array ());

  // End event:
  return $buffer;
}

/**
 * Function to load channels if needed.
 *
 * @global array $_in Framework global configuration variable
 * @global object $ami Asterisk AMI connection object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_load ( $buffer, $parameters)
{
  global $_in, $ami;

  // Execute only if loaded specific channels module:
  if ( $parameters["Module"] == "app_channel.so" || $parameters["Module"] == "app_channel")
  {
    // Setup channels variables:
    $_in["Channels"] = array ();
    $_in["Status"]["Channels"] = array ( "BootUp" => true, "Reload" => false);

    // Request all channels status:
    $ami->request ( "CoreShowChannels", array ());
  }

  // End event:
  return $buffer;
}

/**
 * Function to reload channels if needed.
 *
 * @global array $_in Framework global configuration variable
 * @global object $ami Asterisk AMI connection object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_reload ( $buffer, $parameters)
{
  global $_in, $ami;

  // Execute only if reloaded all modules or specific channels module:
  if ( $parameters["Module"] == "All" || $parameters["Module"] == "app_channel.so" || $parameters["Module"] == "app_channel")
  {
    // Create backup and set channels variables:
    $_in["Status"]["Channels"]["Reload"] = true;
    $_in["Status"]["Channels"]["Backup"] = $_in["Channels"];
    $_in["Channels"] = array ();

    // Request all channels status:
    $ami->request ( "CoreShowChannels", array ());
  }

  // End event:
  return $buffer;
}

/**
 * Function to unload channels if needed.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_unload ( $buffer, $parameters)
{
  global $_in;

  // Execute only if unloaded specific channels module:
  if ( $parameters["Module"] == "app_channel.so" || $parameters["Module"] == "app_channel")
  {
    // Remove channels:
    unset ( $_in["Channels"]);
    unset ( $_in["Status"]["Channels"]);

    // Notify router of event:
    $gm->doBackground ( "channels_unload", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"])));
  }

  // End event:
  return $buffer;
}

/**
 * Function to unload channels on shutdown.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_shutdown ( $buffer, $parameters)
{
  global $_in;

  // Remove channels:
  unset ( $_in["Channels"]);
  unset ( $_in["Status"]["Channels"]);

  // End event:
  return $buffer;
}

/**
 * Function to process channel entry response to action "CoreShowChannels".
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_show ( $buffer, $parameters)
{
  global $_in;

  if ( array_key_exists ( $parameters["Channel"], $_in["Channels"]))
  {
    writeLog ( "Channel description received for existing channel \"" . $parameters["Channel"] . "\"!", VoIP_LOG_WARNING);
    unset ( $_in["Channels"][$parameters["Channel"]]);
  }
  $_in["Channels"][$parameters["Channel"]] = array ();
  $_in["Channels"][$parameters["Channel"]]["Channel"] = $parameters["Channel"];
  $_in["Channels"][$parameters["Channel"]]["State"] = $parameters["ChannelState"];
  $_in["Channels"][$parameters["Channel"]]["CallerID"] = array ( "Number" => ( $parameters["CallerIDNum"] == "<unknown>" ? "" : $parameters["CallerIDNum"]), "Name" => ( $parameters["CallerIDName"] == "<unknown>" ? "" : $parameters["CallerIDName"]));
  $_in["Channels"][$parameters["Channel"]]["ConnectedLine"] = array ( "Number" => ( $parameters["ConnectedLineNum"] == "<unknown>" ? "" : $parameters["ConnectedLineNum"]), "Name" => ( $parameters["ConnectedLineName"] == "<unknown>" ? "" : $parameters["ConnectedLineName"]));
  $_in["Channels"][$parameters["Channel"]]["Language"] = $parameters["Language"];
  $_in["Channels"][$parameters["Channel"]]["AccountCode"] = ( $parameters["AccountCode"] === false ? NULL : $parameters["AccountCode"]);
  $_in["Channels"][$parameters["Channel"]]["UniqueID"] = $parameters["Uniqueid"];
  $_in["Channels"][$parameters["Channel"]]["LinkedID"] = $parameters["Linkedid"];

  // End event:
  return $buffer;
}

/**
 * Function to process channel status complete response to action "CoreShowChannels".
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_status_complete ( $buffer, $parameters)
{
  global $_in, $gm;

  if ( $_in["Status"]["Channels"]["BootUp"] == true)
  {
    $_in["Status"]["Channels"]["BootUp"] = false;
    $gm->doBackground ( "channels_bootup", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Channels" => $_in["Channels"])));
  }
  if ( $_in["Status"]["Channels"]["Reload"] == true)
  {
    $_in["Status"]["Channels"]["Reload"] = false;
    unset ( $_in["Status"]["Channels"]["Backup"]);
// TODO: Tem que comparar o backup com o resultado atual e enviar "diferenças"...
  }

  // End event:
  return $buffer;
}

/**
 * Function to add a new channel.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_new ( $buffer, $parameters)
{
  global $_in, $gm;

  if ( array_key_exists ( $parameters["Channel"], $_in["Channels"]))
  {
    writeLog ( "New channel received for existing channel \"" . $parameters["Channel"] . "\"!", VoIP_LOG_WARNING);
    unset ( $_in["Channels"][$parameters["Channel"]]);
  }
  $_in["Channels"][$parameters["Channel"]] = array ();
  $_in["Channels"][$parameters["Channel"]]["Channel"] = $parameters["Channel"];
  $_in["Channels"][$parameters["Channel"]]["State"] = $parameters["ChannelState"];
  $_in["Channels"][$parameters["Channel"]]["CallerID"] = array ( "Number" => ( $parameters["CallerIDNum"] == "<unknown>" ? "" : $parameters["CallerIDNum"]), "Name" => ( $parameters["CallerIDName"] == "<unknown>" ? "" : $parameters["CallerIDName"]));
  $_in["Channels"][$parameters["Channel"]]["ConnectedLine"] = array ( "Number" => ( $parameters["ConnectedLineNum"] == "<unknown>" ? "" : $parameters["ConnectedLineNum"]), "Name" => ( $parameters["ConnectedLineName"] == "<unknown>" ? "" : $parameters["ConnectedLineName"]));
  $_in["Channels"][$parameters["Channel"]]["Language"] = $parameters["Language"];
  $_in["Channels"][$parameters["Channel"]]["AccountCode"] = ( $parameters["AccountCode"] === false ? NULL : $parameters["AccountCode"]);
  $_in["Channels"][$parameters["Channel"]]["UniqueID"] = $parameters["Uniqueid"];
  $_in["Channels"][$parameters["Channel"]]["LinkedID"] = $parameters["Linkedid"];
  $gm->doBackground ( "channels_create", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Channel" => $parameters["Channel"], "Data" => $_in["Channels"][$parameters["Channel"]])));

  // End event:
  return $buffer;
}

/**
 * Function to change channel state.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_state ( $buffer, $parameters)
{
  global $_in, $gm;

  if ( ! array_key_exists ( $parameters["Channel"], $_in["Channels"]))
  {
    writeLog ( "New state received for unexisting channel \"" . $parameters["Channel"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }
  $_in["Channels"][$parameters["Channel"]]["State"] = $parameters["ChannelState"];
  $gm->doBackground ( "channels_state", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Channel" => $parameters["Channel"], "State" => $parameters["ChannelState"])));

  // End event:
  return $buffer;
}

/**
 * Function to remove a channel (hangup).
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_hangup ( $buffer, $parameters)
{
  global $_in, $gm;

  if ( ! array_key_exists ( $parameters["Channel"], $_in["Channels"]))
  {
    writeLog ( "Hangup received for unexisting channel \"" . $parameters["Channel"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }
  $gm->doBackground ( "channels_destroy", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Channel" => $parameters["Channel"], "Cause" => $parameters["Cause"])));
  unset ( $_in["Channels"][$parameters["Channel"]]);

  // End event:
  return $buffer;
}

/**
 * Function to change channel caller ID.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function channels_callerid ( $buffer, $parameters)
{
  global $_in, $gm;

  if ( ! array_key_exists ( $parameters["Channel"], $_in["Channels"]))
  {
    writeLog ( "New caller ID received for unexisting channel \"" . $parameters["Channel"] . "\"!", VoIP_LOG_WARNING);
    return $buffer;
  }
  $_in["Channels"][$parameters["Channel"]]["CallerID"] = array ( "Number" => ( $parameters["CallerIDNum"] == "<unknown>" ? "" : $parameters["CallerIDNum"]), "Name" => ( $parameters["CallerIDName"] == "<unknown>" ? "" : $parameters["CallerIDName"]));
  $_in["Channels"][$parameters["Channel"]]["ConnectedLine"] = array ( "Number" => ( $parameters["ConnectedLineNum"] == "<unknown>" ? "" : $parameters["ConnectedLineNum"]), "Name" => ( $parameters["ConnectedLineName"] == "<unknown>" ? "" : $parameters["ConnectedLineName"]));
  $gm->doBackground ( "channels_callerid", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Channel" => $parameters["Channel"], "CallerID" => $_in["Channels"][$parameters["Channel"]]["CallerID"], "ConnectedLine" => $_in["Channels"][$parameters["Channel"]]["ConnectedLine"])));

  // End event:
  return $buffer;
}
?>
