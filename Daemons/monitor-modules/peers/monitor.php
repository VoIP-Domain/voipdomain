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
 * VoIP Domain peers Asterisk AMI events module. This module add the Asterisk
 * monitoring events related to peers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Peers
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "FullyBooted", "peers_bootup");
framework_add_hook ( "Load", "peers_load");
framework_add_hook ( "Reload", "peers_reload");
framework_add_hook ( "Unload", "peers_unload");
framework_add_hook ( "Shutdown", "peers_shutdown");
framework_add_hook ( "SIPpeerstatusComplete", "peers_status_complete");
framework_add_hook ( "PeerStatus", "peer_status");

/**
 * Function to boot up the peers.
 *
 * @global array $_in Framework global configuration variable
 * @global object $ami Asterisk AMI connection object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function peers_bootup ( $buffer, $parameters)
{
  global $_in, $ami;

  // Setup peers variables:
  $_in["Peers"] = array ();
  $_in["Status"]["Peers"] = array ( "BootUp" => true, "Reload" => false);

  // Request all peers status:
  $ami->request ( "SIPpeerstatus", array ());

  // End event:
  return $buffer;
}

/**
 * Function to load peers if needed.
 *
 * @global array $_in Framework global configuration variable
 * @global object $ami Asterisk AMI connection object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function peers_load ( $buffer, $parameters)
{
  global $_in, $ami;

  // Execute only if loaded specific peers module:
  if ( $parameters["Module"] == "app_peer.so" || $parameters["Module"] == "app_peer")
  {
    // Setup peers variables:
    $_in["Peers"] = array ();
    $_in["Status"]["Peers"] = array ( "BootUp" => true, "Reload" => false);

    // Request all peers status:
    $ami->request ( "SIPpeerstatus", array ());
  }

  // End event:
  return $buffer;
}

/**
 * Function to reload peers if needed.
 *
 * @global array $_in Framework global configuration variable
 * @global object $ami Asterisk AMI connection object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function peers_reload ( $buffer, $parameters)
{
  global $_in, $ami;

  // Execute only if reloaded all modules or specific peers module:
  if ( $parameters["Module"] == "All" || $parameters["Module"] == "chan_sip.so" || $parameters["Module"] == "chan_sip")
  {
    // Create backup and set peers variables:
    $_in["Status"]["Peers"]["Reload"] = true;
    $_in["Status"]["Peers"]["Backup"] = $_in["Peers"];
    $_in["Peers"] = array ();

    // Request all peers status:
    $ami->request ( "SIPpeerstatus", array ());
  }

  // End event:
  return $buffer;
}

/**
 * Function to unload peers if needed.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function peers_unload ( $buffer, $parameters)
{
  global $_in;

  // Execute only if unloaded specific peers module:
  if ( $parameters["Module"] == "chan_sip.so" || $parameters["Module"] == "chan_sip")
  {
    // Remove peers:
    unset ( $_in["Peers"]);
    unset ( $_in["Status"]["Peers"]);

    // Notify router of event:
    $gm->doBackground ( "peers_unload", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"])));
  }

  // End event:
  return $buffer;
}

/**
 * Function to unload peers on shutdown.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function peers_shutdown ( $buffer, $parameters)
{
  global $_in;

  // Remove peers:
  unset ( $_in["Peers"]);
  unset ( $_in["Status"]["Peers"]);

  // End event:
  return $buffer;
}

/**
 * Function to process peer status complete response to action "SIPpeerstatus".
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function peers_status_complete ( $buffer, $parameters)
{
  global $_in, $gm;

  if ( $_in["Status"]["Peers"]["BootUp"] == true)
  {
    $_in["Status"]["Peers"]["BootUp"] = false;
  }
  if ( $_in["Status"]["Peers"]["Reload"] == true)
  {
    $_in["Status"]["Peers"]["Reload"] = false;
  }

  $gm->doBackground ( "peers_bootup", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Peers" => $_in["Peers"])));

  // End event:
  return $buffer;
}

/**
 * Function to change a peer status.
 *
 * @global array $_in Framework global configuration variable
 * @global object $gm Gearman client object
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return [mixed] $buffer Hook buffer
 */
function peer_status ( $buffer, $parameters)
{
  global $_in, $gm;

  // Check if peer exist on table:
  if ( ! array_key_exists ( $parameters["Peer"], $_in["Peers"]))
  {
    $_in["Peers"][$parameters["Peer"]] = array ();
  }

  // Check if peer status are same:
  if ( $_in["Peers"][$parameters["Peer"]]["Status"] == $parameters["PeerStatus"] && $_in["Peers"][$parameters["Peer"]]["Address"] == $parameters["Address"])
  {
    return $buffer;
  }

  // Change peer status:
  $_in["Peers"][$parameters["Peer"]]["Status"] = $parameters["PeerStatus"];
  $_in["Peers"][$parameters["Peer"]]["Address"] = $parameters["Address"];

  // Notify router of event (if not at bootup or update):
  if ( $_in["Status"]["Peers"]["BootUp"] == false && $_in["Status"]["Peers"]["Reload"] == false)
  {
    $gm->doBackground ( "peer_status", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Peer" => $parameters["Peer"], "Status" => $parameters["PeerStatus"], "Address" => $parameters["Address"])));
  }

  // End event:
  return $buffer;
}
?>
