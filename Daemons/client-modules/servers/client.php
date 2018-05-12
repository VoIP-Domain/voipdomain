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
 * VoIP Domain servers actions module. This module add the Asterisk client actions
 * calls related to servers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Servers
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "server_add", "server_add");
framework_add_hook ( "server_change", "server_change");
framework_add_hook ( "server_remove", "server_remove");
framework_add_hook ( "server_rebuild", "server_rebuild");

/**
 * Cleanup functions
 */
framework_add_hook ( "server_wipe", "server_wipe");
cleanup_register ( "Servers", "server_wipe");

/**
 * Function to add a new server.
 * Required parameters are: (int) ID, (string) Description, (string) Address, (int) Port, (string) Password
 * Possible results:
 *   - 200: OK, server added (overwritten)
 *   - 201: OK, server added
 *   - 400: Server already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function server_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "server_add_start"))
  {
    $parameters = framework_call ( "server_add_start", $parameters);
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
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Address", $parameters))
  {
    $data["Address"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Address", $data) && ! preg_match ( "/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\$/", $parameters["Address"]))
  {
    $data["Address"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Port", $parameters))
  {
    $data["Port"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Port", $data) && ( $parameters["Port"] < 0 || $parameters["Port"] > 65535))
  {
    $data["Port"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "server_add_validate"))
  {
    $data = framework_call ( "server_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "server_add_sanitize"))
  {
    $parameters = framework_call ( "server_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "server_add_pre"))
  {
    $parameters = framework_call ( "server_add_pre", $parameters, false, $parameters);
  }

  // Verify if server exist
  if ( check_config ( "config", "sip-server-" . $parameters["ID"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Server already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Load VoIP Domain servers list
  $servers = read_config ( "datafile", "vd-servers");

  // Add new server IP
  if ( ! array_key_exist ( $parameters["ID"], $servers))
  {
    $servers[$parameters["ID"]] = array ( "Address" => $parameters["Address"], "Port" => (int) $parameters["Port"], "Description" => $parameters["Description"]);
    if ( ! write_config ( "datafile", "vd-servers", $servers))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
    }
  }

  // Overwrite Asterisk servers list
  $serverlist = "; VoIP Domain servers IP list\n" .
                "[vd_servers_ips](!)\n";
  foreach ( $servers as $server)
  {
    $serverlist .= "match=" . $server["Address"] . "\n";
  }

  // Write Asterisk server list file
  if ( ! write_config ( "config", "sip-vd-serverlist", $serverlist))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create file structure
  $server = "; VoIP Domain network server " . $parameters["ID"] . ": " . $parameters["Description"] . "\n" .
            "[vd_server_" . $parameters["ID"] . "]\n" .
            "type=endpoint\n" .
            "allow=all\n" .
            "direct_media=no\n" .
            "ice_support=yes\n" .
            "rtp_symmetric=no\n" .
            "force_rport=yes\n" .
            "rewrite_contact=no\n" .
            "outbound_auth=vd_server_" . $parameters["ID"] . "_auth\n" .
            "aors=vd_server_" . $parameters["ID"] . "_aor\n" .
            "\n" .
            "[vd_server_" . $parameters["ID"] . "_aor]\n" .
            "type=aor\n" .
            "contact=sip:" . $parameters["Address"] . ":" . $parameters["Port"] . "\n" .
            "\n" .
            "[vd_server_" . $parameters["ID"] . "_auth]\n" .
            "type=auth\n" .
            "auth_type=userpass\n" .
            "username=vd_server_" . $parameters["ID"] . "\n" .
            "password=" . $parameters["Password"] . "\n" .
            "\n" .
            "[vd_server_" . $parameters["ID"] . "_registration]\n" .
            "type=registration\n" .
            "transport=transport-udp\n" .
            "outbound_auth=vd_server_" . $parameters["ID"] . "_auth\n" .
            "server_uri=sip:" . $parameters["Address"] . "\n" .
            "client_uri=sip:vd_server_" . $parameters["ID"] . "@" . $_in["general"]["address"] . "\n" .
            "contact_user=vd_server_" . $parameters["ID"] . "\n" .
            "retry_interval=60\n";

  // Write server file
  if ( ! write_config ( "config", "sip-server-" . $parameters["ID"], $server))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "server_add_post") && ! framework_call ( "server_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create server data file
  if ( ! write_config ( "datafile", "server-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "pjsip reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "server_add_finish"))
  {
    framework_call ( "server_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Server added (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Server added.");
  }
}

/**
 * Function to edit an existing server.
 * Required parameters are: (int) ID, (string) Description, (string) Address, (int) Port, (string) Password
 * Possible results:
 *   - 200: OK, server edited
 *   - 404: Server doesn't exist
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
function server_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "server_change_start"))
  {
    $parameters = framework_call ( "server_change_start", $parameters);
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
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Address", $parameters))
  {
    $data["Address"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Address", $data) && ! preg_match ( "/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\$/", $parameters["Address"]))
  {
    $data["Address"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Port", $parameters))
  {
    $data["Port"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Port", $data) && ( $parameters["Port"] < 0 || $parameters["Port"] > 65535))
  {
    $data["Port"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "server_change_validate"))
  {
    $data = framework_call ( "server_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "server_change_sanitize"))
  {
    $parameters = framework_call ( "server_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "server_change_pre"))
  {
    $parameters = framework_call ( "server_change_pre", $parameters, false, $parameters);
  }

  // Verify if server exist
  if ( ! check_config ( "config", "sip-server-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Server doesn't exist.");
  }

  // Load VoIP Domain servers list
  $servers = read_config ( "datafile", "vd-servers");

  // Override server IP
  $servers[$parameters["ID"]] = array ( "Address" => $parameters["Address"], "Port" => (int) $parameters["Port"], "Description" => $parameters["Description"]);

  // Write VoIP Domain servers list
  if ( ! write_config ( "datafile", "vd-servers", $servers))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Overwrite Asterisk servers list
  $serverlist = "; VoIP Domain servers IP list\n" .
                "[vd_servers_ips](!)\n";
  foreach ( $servers as $server)
  {
    $serverlist .= "match=" . $server["Address"] . "\n";
  }

  // Write Asterisk server list file
  if ( ! write_config ( "config", "sip-vd-serverlist", $serverlist))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Remove current server files
  if ( ! unlink_config ( "config", "sip-server-" . $parameters["ID"]) || ! remove_config ( "datafile", "server-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $server = "; VoIP Domain network server " . $parameters["ID"] . ": " . $parameters["Description"] . "\n" .
            "[vd_server_" . $parameters["ID"] . "]\n" .
            "type=endpoint\n" .
            "allow=all\n" .
            "direct_media=no\n" .
            "ice_support=yes\n" .
            "rtp_symmetric=no\n" .
            "force_rport=yes\n" .
            "rewrite_contact=no\n" .
            "outbound_auth=vd_server_" . $parameters["ID"] . "_auth\n" .
            "aors=vd_server_" . $parameters["ID"] . "_aor\n" .
            "\n" .
            "[vd_server_" . $parameters["ID"] . "_aor]\n" .
            "type=aor\n" .
            "contact=sip:" . $parameters["Address"] . ":" . $parameters["Port"] . "\n" .
            "\n" .
            "[vd_server_" . $parameters["ID"] . "_auth]\n" .
            "type=auth\n" .
            "auth_type=userpass\n" .
            "username=vd_server_" . $parameters["ID"] . "\n" .
            "password=" . $parameters["Password"] . "\n" .
            "\n" .
            "[vd_server_" . $parameters["ID"] . "_registration]\n" .
            "type=registration\n" .
            "transport=transport-udp\n" .
            "outbound_auth=vd_server_" . $parameters["ID"] . "_auth\n" .
            "server_uri=sip:" . $parameters["Address"] . "\n" .
            "client_uri=sip:vd_server_" . $parameters["ID"] . "@" . $_in["general"]["address"] . "\n" .
            "contact_user=vd_server_" . $parameters["ID"] . "\n" .
            "retry_interval=60\n";

  // Write server file
  if ( ! write_config ( "config", "sip-server-" . $parameters["ID"], $server))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create server data file
  if ( ! write_config ( "datafile", "server-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "server_change_post") && ! framework_call ( "server_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "pjsip reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "server_change_finish"))
  {
    framework_call ( "server_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Server edited.");
}

/**
 * Function to remove an existing server.
 * Required parameters are: (int) ID
 * Possible results:
 *   - 200: OK, server removed
 *   - 404: Server doesn't exist
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
function server_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "server_remove_start"))
  {
    $parameters = framework_call ( "server_remove_start", $parameters);
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
  if ( framework_has_hook ( "server_remove_validate"))
  {
    $data = framework_call ( "server_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "server_remove_sanitize"))
  {
    $parameters = framework_call ( "server_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "server_remove_pre"))
  {
    $parameters = framework_call ( "server_remove_pre", $parameters, false, $parameters);
  }

  // Verify if server exist
  if ( ! check_config ( "config", "sip-server-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Server doesn't exist.");
  }

  // Remove server files
  if ( ! unlink_config ( "config", "sip-server-" . $parameters["ID"]) || ! remove_config ( "datafile", "server-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Load VoIP Domain servers list
  $servers = read_config ( "datafile", "vd-servers");

  // Override server IP
  unset ( $servers[$parameters["ID"]]);

  // Write VoIP Domain servers list
  if ( ! write_config ( "datafile", "vd-servers", $servers))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Overwrite Asterisk servers list
  $serverlist = "; VoIP Domain servers IP list\n" .
                "[vd_servers_ips](!)\n";
  foreach ( $servers as $server)
  {
    $serverlist .= "match=" . $server["Address"] . "\n";
  }

  // Write Asterisk server list file
  if ( ! write_config ( "config", "sip-vd-serverlist", $serverlist))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "server_remove_post") && ! framework_call ( "server_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "pjsip reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "server_remove_finish"))
  {
    framework_call ( "server_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Server removed.");
}

/**
 * Function to remove all existing server configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function server_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "server_wipe_start"))
  {
    $parameters = framework_call ( "server_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "server_wipe_pre"))
  {
    framework_call ( "server_wipe_pre", $parameters, false, true);
  }

  // Load VoIP Domain servers list
  $servers = read_config ( "datafile", "vd-servers");

  // Remove server files
  foreach ( $servers as $serverid => $server)
  {
    if ( ! unlink_config ( "config", "server-" . $serverid) || ! unlink_config ( "datafile", "server-" . $serverid))
    {
      return array ( "code" => 500, "message" => "Error removing file.");
    }

    // Remove server information
    unset ( $servers[$serverid]);
  }

  // Write VoIP Domain servers list
  if ( ! write_config ( "datafile", "vd-servers", $servers))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Overwrite Asterisk servers list
  $serverlist = "; VoIP Domain servers IP list\n" .
                "[vd_servers_ips](!)\n";
  foreach ( $servers as $server)
  {
    $serverlist .= "match=" . $server["Address"] . "\n";
  }

  // Write Asterisk server list file
  if ( ! write_config ( "config", "sip-vd-serverlist", $serverlist))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "server_wipe_post"))
  {
    framework_call ( "server_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "pjsip reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "server_wipe_finish"))
  {
    framework_call ( "server_wipe_finish", $parameters);
  }
}

/**
 * Function to rebuild all server configuration.
 * Required parameters are: (boolean) CleanUp
 * Possible results:
 *   - 200: OK, server rebuilded
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
function server_rebuild ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "server_rebuild_start"))
  {
    $parameters = framework_call ( "server_rebuild_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "CleanUp", $parameters))
  {
    $data["CleanUp"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "CleanUp", $data) && ! is_bool ( $parameters["CleanUp"]))
  {
    $data["CleanUp"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "server_rebuild_validate"))
  {
    $data = framework_call ( "server_rebuild_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "server_rebuild_sanitize"))
  {
    $parameters = framework_call ( "server_rebuild_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "server_rebuild_pre"))
  {
    $parameters = framework_call ( "server_rebuild_pre", $parameters, false, $parameters);
  }

  // If cleanup requested, wipe all server configuration:
  if ( $parameters["CleanUp"])
  {
    asterisk_exec_enable_buffer ();
    check_cleanup_registers ();
    // Execute all registered cleanup handlers that doesn't have dependency in loop (until all hooks has been called):
    $lastexecuted = array ();
    $executed = array ();
    $cleanup = fetch_config ( "cleanup", "entries");
    while ( sizeof ( $cleanup))
    {
      if ( sizeof ( $lastexecuted) != 0 && $lastexecuted == $executed)
      {
        $pending = array ();
        foreach ( $cleanup as $handler => $data)
        {
          $pending[] = $handler;
        }
        writeLog ( "Server rebuild: Wipe ended with pending hooks (" . implode ( $pending, ",") . ")!", VoIP_LOG_WARNING);
        $cleanup = array ();
        continue;
      }
      $lastexecuted = $executed;
      $executed = array ();
      foreach ( $cleanup as $handler => $data)
      {
        if ( sizeof ( $data["Deps"]) == 0)
        {
          $executed[] = $handler;
          framework_call ( $data["Hook"], array ());
          foreach ( $cleanup as $checkhandler => $checkdata)
          {
            if ( ( $key = array_search ( $handler, $checkdata["Deps"])) !== false)
            {
              unset ( $cleanup[$checkhandler]["Deps"][$key]);
            }
          }
          unset ( $cleanup[$handler]);
        }
      }
    }
    asterisk_exec_flush_buffer ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "server_rebuild_post") && ! framework_call ( "server_rebuild_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "server_rebuild_finish"))
  {
    framework_call ( "server_rebuild_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Server rebuilded.");
}
?>
