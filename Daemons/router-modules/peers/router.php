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
 * VoIP Domain peers routes module. This module add the monitoring routes events related
 * to peers.
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
framework_add_hook ( "peers_bootup", "peers_bootup");
framework_add_hook ( "peers_unload", "peers_unload");
framework_add_hook ( "peer_status", "peer_status");

/**
 * Function to boot up the peers.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function peers_bootup ( $buffer, $parameters)
{
  global $_in;

  // Empty peers from server:
  unset ( $_in["Peers"][$parameters["ServerID"]]);

  // Just load the peers into peers array:
  $_in["Peers"][$parameters["ServerID"]] = $parameters["Peers"];

  // Log event:
  writeLog ( "Peers loaded for server #" . $parameters["ServerID"] . ".");

  // End event:
  return $buffer;
}

/**
 * Function to unload peers.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function peers_unload ( $buffer, $parameters)
{
  global $_in;

  // Empty peers from server:
  unset ( $_in["Peers"][$parameters["ServerID"]]);

  // Log event:
  writeLog ( "Peers unloaded from server #" . $parameters["ServerID"] . ".");

  // End event:
  return $buffer;
}

/**
 * Function to change a peer status.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function peer_status ( $buffer, $parameters)
{
  global $_in;

  // Check if peer exist at server:
  if ( ! array_key_exists ( $parameters["Peer"], $_in["Peers"][$parameters["ServerID"]]))
  {
    $_in["Peers"][$parameters["ServerID"]][$parameters["Peer"]] = array ();
  }

  // Get last variables:
  $laststatus = $_in["Peers"][$parameters["ServerID"]][$parameters["Peer"]]["Status"];
  $lastaddress = $_in["Peers"][$parameters["ServerID"]][$parameters["Peer"]]["Address"];

  // Change peer status:
  $_in["Peers"][$parameters["ServerID"]][$parameters["Peer"]]["Status"] = $parameters["Status"];
  $_in["Peers"][$parameters["ServerID"]][$parameters["Peer"]]["Address"] = $parameters["Address"];

  // Log event:
  writeLog ( "Updated peer status from server #" . $parameters["ServerID"] . " peer \"" . $parameters["Peer"] . "\".");

  // Add new event notification:
  $buffer["events"][] = array (
    "event" => "peer_status",
    "parameters" => array (
      "ServerID" => $parameters["ServerID"],
      "Peer" => $parameters["Peer"],
      "Status" => $parameters["Status"],
      "LastStatus" => $laststatus,
      "Address" => $parameters["Address"],
      "LastAddress" => $lastaddress
    )
  );

  // End event:
  return $buffer;
}
?>
