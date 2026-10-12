<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain plugin daemon module sync handlers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Plugins
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

framework_add_hook ( "plugin_daemon_module_deploy", "plugin_daemon_module_deploy");
framework_add_hook ( "plugin_daemon_module_remove", "plugin_daemon_module_remove");
framework_add_hook ( "plugin_db_apply", "plugin_db_apply");

/**
 * Return the local daemons root directory path.
 *
 * @return string
 */
function plugin_client_get_daemons_path ()
{
  return dirname ( dirname ( dirname ( __FILE__))) . "/";
}

/**
 * Extract a plugin module archive into the daemons tree.
 *
 * @param string $archivepath Archive path
 * @param string $destinationparent Parent directory for extraction
 * @return boolean
 */
function plugin_client_extract_archive ( $archivepath, $destinationparent)
{
  if ( ! is_readable ( $archivepath))
  {
    return false;
  }
  if ( ! is_dir ( $destinationparent) && ! @mkdir ( $destinationparent, 0775, true))
  {
    return false;
  }
  if ( class_exists ( "PharData"))
  {
    $phar = new PharData ( $archivepath);
    $phar->extractTo ( $destinationparent, null, true);
    return true;
  }
  $command = "tar -xzf " . escapeshellarg ( $archivepath) . " -C " . escapeshellarg ( $destinationparent);
  exec ( $command, $output, $exitcode);
  return $exitcode === 0;
}

/**
 * Deploy a plugin daemon module archive received from the admin interface.
 *
 * @param string $buffer Buffer from plugin system if processed by other function before
 * @param array $parameters Optional parameters to the function
 * @return array
 */
function plugin_daemon_module_deploy ( $buffer, $parameters)
{
  $data = array ();
  foreach ( array ( "Plugin", "ModuleType", "Module") as $key)
  {
    if ( ! array_key_exists ( $key, $parameters) || $parameters[$key] == "")
    {
      $data[$key] = "Missing parameter";
    }
  }
  if ( ! array_key_exists ( "FilePath", $parameters) || ! is_readable ( $parameters["FilePath"]))
  {
    $data["FilePath"] = "Missing parameter";
  }
  if ( sizeof ( $data))
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  $type = basename ( $parameters["ModuleType"]);
  $module = basename ( $parameters["Module"]);
  $daemonsdir = plugin_client_get_daemons_path ();
  $destination = rtrim ( $daemonsdir, "/") . "/" . $type . "/" . $module;
  $parent = dirname ( $destination);

  if ( is_dir ( $destination))
  {
    unlink_recursive ( $destination);
  }
  if ( ! is_dir ( $parent) && ! @mkdir ( $parent, 0775, true))
  {
    return array ( "code" => 500, "message" => "Error creating module directory.");
  }
  if ( ! plugin_client_extract_archive ( $parameters["FilePath"], $parent))
  {
    return array ( "code" => 500, "message" => "Error extracting plugin module archive.");
  }
  @unlink ( $parameters["FilePath"]);

  return array ( "code" => 200, "message" => "Plugin daemon module deployed.");
}

/**
 * Remove a plugin daemon module from the local daemons tree.
 *
 * @param string $buffer Buffer from plugin system if processed by other function before
 * @param array $parameters Optional parameters to the function
 * @return array
 */
function plugin_daemon_module_remove ( $buffer, $parameters)
{
  $data = array ();
  foreach ( array ( "Plugin", "ModuleType", "Module") as $key)
  {
    if ( ! array_key_exists ( $key, $parameters) || $parameters[$key] == "")
    {
      $data[$key] = "Missing parameter";
    }
  }
  if ( sizeof ( $data))
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  $type = basename ( $parameters["ModuleType"]);
  $module = basename ( $parameters["Module"]);
  $destination = rtrim ( plugin_client_get_daemons_path (), "/") . "/" . $type . "/" . $module;
  if ( is_dir ( $destination))
  {
    unlink_recursive ( $destination);
  }

  return array ( "code" => 200, "message" => "Plugin daemon module removed.");
}

/**
 * Apply a plugin database manifest on the local client database.
 *
 * @param string $buffer Buffer from plugin system if processed by other function before
 * @param array $parameters Optional parameters to the function
 * @return array
 */
function plugin_db_apply ( $buffer, $parameters)
{
  $data = array ();
  foreach ( array ( "Plugin", "Hook") as $key)
  {
    if ( ! array_key_exists ( $key, $parameters) || $parameters[$key] == "")
    {
      $data[$key] = "Missing parameter";
    }
  }
  if ( sizeof ( $data))
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  $manifest = null;
  if ( array_key_exists ( "FilePath", $parameters) && is_readable ( $parameters["FilePath"]))
  {
    $manifest = json_decode ( file_get_contents ( $parameters["FilePath"]), true);
    @unlink ( $parameters["FilePath"]);
  } elseif ( array_key_exists ( "Data", $parameters))
  {
    $manifest = json_decode ( base64_decode ( $parameters["Data"]), true);
  }
  if ( ! is_array ( $manifest))
  {
    return array ( "code" => 406, "message" => "Invalid database manifest.");
  }

  $result = plugin_apply_db_manifest ( $manifest);
  if ( sizeof ( $result))
  {
    return array ( "code" => 500, "message" => "Error applying plugin database manifest.", "content" => $result);
  }

  return array ( "code" => 200, "message" => "Plugin database manifest applied.");
}
?>
