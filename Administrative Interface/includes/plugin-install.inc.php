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
 * VoIP Domain plugin installation helper library.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Return the administrative interface plugins directory path.
 *
 * @return string
 */
function plugin_get_plugins_path ()
{
  return dirname ( __FILE__) . "/../plugins/";
}

/**
 * Return the VoIP Domain daemons directory path.
 *
 * @global array $_in Framework global configuration variable
 * @return string
 */
function plugin_get_daemons_path ()
{
  global $_in;

  if ( array_key_exists ( "general", $_in) && array_key_exists ( "daemonsdir", $_in["general"]) && is_dir ( $_in["general"]["daemonsdir"]))
  {
    return rtrim ( $_in["general"]["daemonsdir"], "/") . "/";
  }
  foreach ( array ( "/usr/libexec/voipdomain/daemons", "/var/lib/voipdomain/daemons", dirname ( __FILE__) . "/../../Daemons") as $path)
  {
    if ( is_dir ( $path))
    {
      return rtrim ( $path, "/") . "/";
    }
  }
  return dirname ( __FILE__) . "/../../Daemons/";
}

/**
 * Initialize the plugin database installation structure.
 *
 * @global array $_in Framework global configuration variable
 * @return void
 */
function plugin_install_db_init ()
{
  global $_in;

  if ( ! array_key_exists ( "install", $_in) || ! is_array ( $_in["install"]))
  {
    $_in["install"] = array ();
  }
  $_in["install"]["db"] = array (
    "procedures" => array (),
    "tables" => array (),
    "triggers" => array (),
    "data" => array ()
  );
}

/**
 * Function to add a procedure to the system database installation.
 *
 * @global array $_in Framework global configuration variable
 * @param string $name The name of the procedure
 * @param string $data The procedure itself
 * @param array $deps[optional] Array with name of dependencies
 * @return boolean If the procedure was added
 */
function install_add_db_procedure ( $name, $data, $deps = array ())
{
  global $_in;

  if ( array_key_exists ( $name, $_in["install"]["db"]["procedures"]))
  {
    return false;
  }
  $_in["install"]["db"]["procedures"][$name] = array ( "data" => $data, "deps" => $deps);
  return true;
}

/**
 * Function to add a table to the system database installation.
 *
 * @global array $_in Framework global configuration variable
 * @param string $name The name of the table
 * @param string $data The table itself
 * @param array $deps[optional] Array with name of dependencies
 * @return boolean If the table was added
 */
function install_add_db_table ( $name, $data, $deps = array ())
{
  global $_in;

  if ( array_key_exists ( $name, $_in["install"]["db"]["tables"]))
  {
    return false;
  }
  $_in["install"]["db"]["tables"][$name] = array ( "data" => $data, "deps" => $deps);
  return true;
}

/**
 * Function to add a triggers to the system database installation.
 *
 * @global array $_in Framework global configuration variable
 * @param string $name The name of the trigger
 * @param string $data The trigger itself
 * @param array $deps[optional] Array with name of dependencies
 * @return boolean If the trigger was added
 */
function install_add_db_trigger ( $name, $data, $deps = array ())
{
  global $_in;

  if ( array_key_exists ( $name, $_in["install"]["db"]["triggers"]))
  {
    return false;
  }
  $_in["install"]["db"]["triggers"][$name] = array ( "data" => $data, "deps" => $deps);
  return true;
}

/**
 * Function to add a table data to the system database installation.
 *
 * @global array $_in Framework global configuration variable
 * @param string $name The name of the table
 * @param array $data The data itself
 * @param array $deps[optional] Array with name of dependencies
 * @return boolean If the table data was added
 */
function install_add_db_data ( $name, $data, $deps = array ())
{
  global $_in;

  if ( array_key_exists ( $name, $_in["install"]["db"]["data"]))
  {
    $_in["install"]["db"]["data"][$name]["data"] = array_merge_recursive ( $_in["install"]["db"]["data"][$name]["data"], $data);
    foreach ( $deps as $dep)
    {
      if ( ! in_array ( $dep, $_in["install"]["db"]["data"][$name]["deps"]))
      {
        $_in["install"]["db"]["data"][$name]["deps"][] = $dep;
      }
    }
  } else {
    $_in["install"]["db"]["data"][$name] = array ( "data" => $data, "deps" => $deps);
  }
  return true;
}

/**
 * Process pending database objects collected through install hooks.
 *
 * @global array $_in Framework global configuration variable
 * @param array $return Existing return array to append errors
 * @return array
 */
function plugin_process_db ( $return = array ())
{
  global $_in;

  if ( ! array_key_exists ( "install", $_in) || ! array_key_exists ( "db", $_in["install"]))
  {
    return $return;
  }

  /**
   * Create database procedures
   */
  if ( sizeof ( $_in["install"]["db"]["procedures"]))
  {
    foreach ( $_in["install"]["db"]["procedures"] as $name => $data)
    {
      $_in["install"]["db"]["procedures"][$name]["installed"] = false;
    }
    $toinstall = sizeof ( $_in["install"]["db"]["procedures"]);
    while ( $toinstall > 0)
    {
      $lastinst = $toinstall;
      $pending = false;
      foreach ( $_in["install"]["db"]["procedures"] as $name => $data)
      {
        if ( $data["installed"] == false)
        {
          $depsok = true;
          foreach ( $data["deps"] as $dep)
          {
            if ( $_in["install"]["db"]["procedures"][$dep]["installed"] != true)
            {
              $depsok = false;
            }
          }
          if ( $depsok)
          {
            $toinstall--;
            if ( @$_in["mysql"]["id"]->query ( $data["data"]))
            {
              $_in["install"]["db"]["procedures"][$name]["installed"] = true;
            } else {
              if ( ! array_key_exists ( "Procedures", $return))
              {
                $return["Procedures"] = array ();
              }
              $return["Procedures"][] = sprintf ( __ ( "Error installing database procedure \"%s\"!", true, false), $name);
            }
          } else {
            $pending = true;
          }
        }
      }
      if ( $lastinst == $toinstall && $pending == true)
      {
        $return["Procedures"][] = __ ( "There are pending procedures not installed due to dependency error.");
        $toinstall = 0;
      }
    }
  }

  /**
   * Create database tables
   */
  if ( sizeof ( $_in["install"]["db"]["tables"]))
  {
    foreach ( $_in["install"]["db"]["tables"] as $name => $data)
    {
      $_in["install"]["db"]["tables"][$name]["installed"] = false;
    }
    $toinstall = sizeof ( $_in["install"]["db"]["tables"]);
    while ( $toinstall > 0)
    {
      $lastinst = $toinstall;
      $pending = false;
      foreach ( $_in["install"]["db"]["tables"] as $name => $data)
      {
        if ( $data["installed"] == false)
        {
          $depsok = true;
          foreach ( $data["deps"] as $dep)
          {
            if ( $_in["install"]["db"]["tables"][$dep]["installed"] != true)
            {
              $depsok = false;
            }
          }
          if ( $depsok)
          {
            $toinstall--;
            if ( @$_in["mysql"]["id"]->query ( $data["data"]))
            {
              $_in["install"]["db"]["tables"][$name]["installed"] = true;
            } else {
              if ( ! array_key_exists ( "Tables", $return))
              {
                $return["Tables"] = array ();
              }
              $return["Tables"][] = sprintf ( __ ( "Error installing database table \"%s\"!", true, false), $name);
            }
          } else {
            $pending = true;
          }
        }
      }
      if ( $lastinst == $toinstall && $pending == true)
      {
        $return["Tables"][] = __ ( "There are pending tables not installed due to dependency error.");
        $toinstall = 0;
      }
    }
  }

  /**
   * Create database triggers
   */
  if ( sizeof ( $_in["install"]["db"]["triggers"]))
  {
    foreach ( $_in["install"]["db"]["triggers"] as $name => $data)
    {
      $_in["install"]["db"]["triggers"][$name]["installed"] = false;
    }
    $toinstall = sizeof ( $_in["install"]["db"]["triggers"]);
    while ( $toinstall > 0)
    {
      $lastinst = $toinstall;
      $pending = false;
      foreach ( $_in["install"]["db"]["triggers"] as $name => $data)
      {
        if ( $data["installed"] == false)
        {
          $depsok = true;
          foreach ( $data["deps"] as $dep)
          {
            if ( $_in["install"]["db"]["triggers"][$dep]["installed"] != true)
            {
              $depsok = false;
            }
          }
          if ( $depsok)
          {
            $toinstall--;
            if ( @$_in["mysql"]["id"]->query ( $data["data"]))
            {
              $_in["install"]["db"]["triggers"][$name]["installed"] = true;
            } else {
              if ( ! array_key_exists ( "Triggers", $return))
              {
                $return["Triggers"] = array ();
              }
              $return["Triggers"][] = sprintf ( __ ( "Error installing database trigger \"%s\"!", true, false), $name);
            }
          } else {
            $pending = true;
          }
        }
      }
      if ( $lastinst == $toinstall && $pending == true)
      {
        $return["Triggers"][] = __ ( "There are pending triggers not installed due to dependency error.");
        $toinstall = 0;
      }
    }
  }

  /**
   * Create database table data
   */
  if ( sizeof ( $_in["install"]["db"]["data"]))
  {
    foreach ( $_in["install"]["db"]["data"] as $name => $data)
    {
      $_in["install"]["db"]["data"][$name]["installed"] = false;
    }
    $toinstall = sizeof ( $_in["install"]["db"]["data"]);
    while ( $toinstall > 0)
    {
      $lastinst = $toinstall;
      $pending = false;
      foreach ( $_in["install"]["db"]["data"] as $name => $data)
      {
        if ( $data["installed"] == false)
        {
          $depsok = true;
          foreach ( $data["deps"] as $dep)
          {
            if ( $_in["install"]["db"]["data"][$dep]["installed"] != true)
            {
              $depsok = false;
            }
          }
          if ( $depsok)
          {
            $toinstall--;
            $failed = false;
            $query = "";
            $order = array ();
            foreach ( $data["data"] as $entry)
            {
              if ( $query == "")
              {
                $query = "INSERT INTO `" . $name . "` (";
                foreach ( $entry as $key => $value)
                {
                  $query .= "`" . $key . "`, ";
                  $order[] = $key;
                }
                $query = substr ( $query, 0, -2) . ") VALUES";
              } else {
                $query .= ",";
              }
              $query .= " (";
              foreach ( $order as $key)
              {
                if ( is_bool ( $entry[$key]))
                {
                  $query .= ( $entry[$key] ? "true" : "false") . ", ";
                } else {
                  $query .= "'" . $_in["mysql"]["id"]->real_escape_string ( $entry[$key]) . "', ";
                }
              }
              $query = substr ( $query, 0, -2) . ")";
            }
            if ( ! @$_in["mysql"]["id"]->query ( $query) && $failed == false)
            {
              if ( ! array_key_exists ( "Data", $return))
              {
                $return["Data"] = array ();
              }
              $return["Data"][] = sprintf ( __ ( "Error inserting database table \"%s\" data!", true, false), $name);
              $failed = true;
            }
            $_in["install"]["db"]["data"][$name]["installed"] = true;
          } else {
            $pending = true;
          }
        }
      }
      if ( $lastinst == $toinstall && $pending == true)
      {
        $return["Data"][] = __ ( "There are pending table data not installed due to dependency error.");
        $toinstall = 0;
      }
    }
  }

  return $return;
}

/**
 * Copy a file or directory recursively.
 *
 * @param string $source Source path
 * @param string $destination Destination path
 * @return boolean
 */
function plugin_copy_recursive ( $source, $destination)
{
  if ( is_file ( $source))
  {
    if ( ! is_dir ( dirname ( $destination)))
    {
      mkdir ( dirname ( $destination), 0755, true);
    }
    return copy ( $source, $destination);
  }
  if ( ! is_dir ( $source))
  {
    return false;
  }
  if ( ! is_dir ( $destination))
  {
    mkdir ( $destination, 0755, true);
  }
  foreach ( scandir ( $source) as $file)
  {
    if ( $file == "." || $file == "..")
    {
      continue;
    }
    if ( ! plugin_copy_recursive ( $source . "/" . $file, $destination . "/" . $file))
    {
      return false;
    }
  }
  return true;
}

/**
 * Return daemon module types that can be shipped inside a plugin package.
 *
 * @return array
 */
function plugin_get_daemon_module_types ()
{
  return array ( "client-modules", "fastagi-modules", "monitor-modules", "router-modules");
}

/**
 * Include a plugin script file safely.
 *
 * @global array $_in Framework global configuration variable
 * @param string $plugindir Plugin directory path
 * @param string $pluginname Plugin directory name
 * @param string $script Script file name
 * @return boolean
 */
function plugin_include_script ( $plugindir, $pluginname, $script)
{
  global $_in;

  $filepath = rtrim ( $plugindir, "/") . "/" . $script;
  if ( ! is_readable ( $filepath))
  {
    return false;
  }
  $current = $_in["module"];
  $_in["module"] = $pluginname;
  include ( $filepath);
  $_in["module"] = $current;
  return true;
}

/**
 * Check whether a database manifest contains pending objects.
 *
 * @param array $manifest Database manifest
 * @return boolean
 */
function plugin_db_manifest_has_content ( $manifest)
{
  if ( ! is_array ( $manifest))
  {
    return false;
  }
  foreach ( array ( "procedures", "tables", "triggers", "data") as $key)
  {
    if ( array_key_exists ( $key, $manifest) && is_array ( $manifest[$key]) && sizeof ( $manifest[$key]))
    {
      return true;
    }
  }
  return false;
}

/**
 * Collect database objects declared by a plugin install script.
 *
 * @global array $_plugins Plugin system global variable
 * @global array $_in Framework global configuration variable
 * @param string $plugindir Plugin directory path
 * @param string $pluginname Plugin directory name
 * @param string $script Script file name
 * @param string $hook Database hook name
 * @return array|null
 */
function plugin_collect_db_script ( $plugindir, $pluginname, $script, $hook)
{
  global $_plugins, $_in;

  plugin_install_db_init ();
  $beforehooks = array_key_exists ( $hook, $_plugins["hooks"]) && is_array ( $_plugins["hooks"][$hook]) ? $_plugins["hooks"][$hook] : array ();
  if ( ! plugin_include_script ( $plugindir, $pluginname, $script))
  {
    return null;
  }
  $afterhooks = array_key_exists ( $hook, $_plugins["hooks"]) && is_array ( $_plugins["hooks"][$hook]) ? $_plugins["hooks"][$hook] : array ();
  foreach ( array_diff ( $afterhooks, $beforehooks) as $function)
  {
    if ( function_exists ( $function))
    {
      call_user_func ( $function, "", array ());
    }
  }
  if ( ! array_key_exists ( "install", $_in) || ! array_key_exists ( "db", $_in["install"]))
  {
    return null;
  }
  return $_in["install"]["db"];
}

/**
 * Run plugin database hooks from install.php or update.php.
 *
 * @global array $_plugins Plugin system global variable
 * @param string $plugindir Plugin directory path
 * @param string $pluginname Plugin directory name
 * @param string $script Script file name
 * @param string $hook Database hook name
 * @return array
 */
function plugin_run_db_script ( $plugindir, $pluginname, $script, $hook)
{
  global $_in;

  $manifest = plugin_collect_db_script ( $plugindir, $pluginname, $script, $hook);
  if ( $manifest === null)
  {
    return array ();
  }
  $_in["install"]["db"] = $manifest;
  $result = plugin_process_db ( array ());
  if ( plugin_db_manifest_has_content ( $manifest))
  {
    $result["db_manifest"] = $manifest;
  }
  return $result;
}

/**
 * Call plugin lifecycle hook if registered.
 *
 * @param string $pluginname Plugin directory name
 * @param string $action install, upgrade or remove
 * @param array $parameters Hook parameters
 * @return mixed
 */
function plugin_call_lifecycle ( $pluginname, $action, $parameters = array ())
{
  $hook = "plugin_" . $pluginname . "_" . $action;
  if ( framework_has_hook ( $hook))
  {
    return framework_call ( $hook, $parameters);
  }
  return null;
}

/**
 * Create a tar.gz archive from a directory.
 *
 * @param string $sourcedir Source directory
 * @param string $archivepath Destination archive path
 * @return boolean
 */
function plugin_archive_directory ( $sourcedir, $archivepath)
{
  if ( ! is_dir ( $sourcedir))
  {
    return false;
  }
  if ( file_exists ( $archivepath))
  {
    @unlink ( $archivepath);
  }
  $tardir = dirname ( $archivepath);
  if ( ! is_dir ( $tardir))
  {
    @mkdir ( $tardir, 0755, true);
  }
  if ( class_exists ( "PharData"))
  {
    $tarpath = preg_replace ( '/\.gz$/', '', $archivepath);
    if ( file_exists ( $tarpath))
    {
      @unlink ( $tarpath);
    }
    $tar = new PharData ( $tarpath);
    $tar->buildFromDirectory ( $sourcedir);
    if ( substr ( $archivepath, -3) == ".gz")
    {
      $tar->compress ( Phar::GZ, basename ( $archivepath));
      unset ( $tar);
      @unlink ( $tarpath);
    }
    return is_readable ( $archivepath);
  }
  $command = "tar -czf " . escapeshellarg ( $archivepath) . " -C " . escapeshellarg ( dirname ( $sourcedir)) . " " . escapeshellarg ( basename ( $sourcedir));
  exec ( $command, $output, $exitcode);
  return $exitcode === 0 && is_readable ( $archivepath);
}

/**
 * Sync deployed daemon module directories to remote servers.
 *
 * @param string $plugindir Plugin directory path
 * @param string $pluginname Plugin directory name
 * @param array $modules Deployed module entries (type/module)
 * @param string $action deploy or remove
 * @param int $serverid Server ID (0 = all servers)
 * @return array
 */
function plugin_sync_daemon_modules_remote ( $plugindir, $pluginname, $modules, $action, $serverid = 0)
{
  global $_in;

  $result = array ( "synced" => array (), "errors" => array ());
  foreach ( $modules as $entry)
  {
    if ( strpos ( $entry, "/") === false)
    {
      continue;
    }
    list ( $type, $module) = explode ( "/", $entry, 2);
    if ( $action == "remove")
    {
      $notify = array ( "Plugin" => $pluginname, "ModuleType" => $type, "Module" => $module);
      if ( ! notify_server ( $serverid, "plugin_daemon_module_remove", $notify))
      {
        $result["errors"][] = $entry;
      } else {
        $result["synced"][] = $entry;
      }
      continue;
    }
    $sourcedir = rtrim ( $plugindir, "/") . "/" . $type . "/" . $module;
    if ( ! is_dir ( $sourcedir))
    {
      continue;
    }
    $archive = rtrim ( $_in["general"]["spooldir"], "/") . "/plugins/sync-" . $pluginname . "-" . $type . "-" . $module . "-" . md5 ( microtime ( true)) . ".tar.gz";
    if ( ! plugin_archive_directory ( $sourcedir, $archive))
    {
      $result["errors"][] = $entry;
      continue;
    }
    $metadata = array ( "Plugin" => $pluginname, "ModuleType" => $type, "Module" => $module);
    if ( ! file_transfer_send ( $serverid, $archive, array (
      "TargetEvent" => "plugin_daemon_module_deploy",
      "Context" => "plugin",
      "Metadata" => $metadata
    )))
    {
      $result["errors"][] = $entry;
    } else {
      $result["synced"][] = $entry;
    }
    @unlink ( $archive);
  }
  return $result;
}

/**
 * Sync a plugin database manifest to remote servers.
 *
 * @param array $manifest Database manifest collected from install/update/uninstall
 * @param string $pluginname Plugin directory name
 * @param string $hook Database hook name
 * @param int $serverid Server ID (0 = all servers)
 * @return array
 */
function plugin_sync_db_manifest_remote ( $manifest, $pluginname, $hook, $serverid = 0)
{
  global $_in;

  if ( ! plugin_db_manifest_has_content ( $manifest))
  {
    return array ( "skipped" => true);
  }
  $spooldir = rtrim ( $_in["general"]["spooldir"], "/") . "/plugins";
  if ( ! is_dir ( $spooldir))
  {
    @mkdir ( $spooldir, 0755, true);
  }
  $jsonfile = $spooldir . "/db-" . $pluginname . "-" . $hook . "-" . md5 ( microtime ( true)) . ".json";
  if ( ! @file_put_contents ( $jsonfile, json_encode ( $manifest)))
  {
    return array ( "errors" => array ( "manifest"));
  }
  $synced = file_transfer_send ( $serverid, $jsonfile, array (
    "TargetEvent" => "plugin_db_apply",
    "Context" => "plugin",
    "Metadata" => array ( "Plugin" => $pluginname, "Hook" => $hook)
  ));
  @unlink ( $jsonfile);
  return array ( "synced" => $synced ? true : false, "hook" => $hook);
}

/**
 * Request daemon reload locally and on remote servers.
 *
 * @param array $units Systemd unit suffixes
 * @return array
 */
function plugin_request_daemon_reload_all ( $units)
{
  $result = array (
    "local" => systemd_request_daemon_reload ( $units),
    "remote" => systemd_request_daemon_reload ( $units, 0)
  );
  return $result;
}

/**
 * Deploy daemon module files shipped inside a plugin package.
 *
 * @param string $plugindir Plugin directory path
 * @param string $pluginname Plugin directory name
 * @return array
 */
function plugin_deploy_daemons ( $plugindir, $pluginname)
{
  $errors = array ();
  $daemonsdir = plugin_get_daemons_path ();
  $deployed = array ();

  foreach ( plugin_get_daemon_module_types () as $type)
  {
    $sourcedir = rtrim ( $plugindir, "/") . "/" . $type;
    if ( ! is_dir ( $sourcedir))
    {
      continue;
    }
    foreach ( scandir ( $sourcedir) as $module)
    {
      if ( $module == "." || $module == "..")
      {
        continue;
      }
      $source = $sourcedir . "/" . $module;
      if ( ! is_dir ( $source))
      {
        continue;
      }
      $destination = rtrim ( $daemonsdir, "/") . "/" . $type . "/" . $module;
      if ( is_dir ( $destination))
      {
        unlink_recursive ( $destination);
      }
      if ( ! plugin_copy_recursive ( $source, $destination))
      {
        $errors[] = sprintf ( __ ( "Error deploying daemon module \"%s\" from plugin \"%s\".", true, false), $type . "/" . $module, $pluginname);
      } else {
        $deployed[] = $type . "/" . $module;
      }
    }
  }

  return array ( "deployed" => $deployed, "errors" => $errors, "daemonsdir" => $daemonsdir);
}

/**
 * Remove daemon module files deployed for a plugin.
 *
 * @param string $plugindir Plugin directory path
 * @param string $pluginname Plugin directory name
 * @return array
 */
function plugin_undeploy_daemons ( $plugindir, $pluginname)
{
  $removed = array ();
  $daemonsdir = plugin_get_daemons_path ();

  foreach ( plugin_get_daemon_module_types () as $type)
  {
    $sourcedir = rtrim ( $plugindir, "/") . "/" . $type;
    if ( is_dir ( $sourcedir))
    {
      foreach ( scandir ( $sourcedir) as $module)
      {
        if ( $module == "." || $module == "..")
        {
          continue;
        }
        $target = rtrim ( $daemonsdir, "/") . "/" . $type . "/" . $module;
        if ( is_dir ( $target))
        {
          unlink_recursive ( $target);
          $removed[] = $type . "/" . $module;
        }
      }
      continue;
    }
    $target = rtrim ( $daemonsdir, "/") . "/" . $type . "/" . $pluginname;
    if ( is_dir ( $target))
    {
      unlink_recursive ( $target);
      $removed[] = $type . "/" . $pluginname;
    }
  }

  return array ( "removed" => $removed, "daemonsdir" => $daemonsdir);
}

/**
 * Install or upgrade a plugin after its files were moved to the plugins directory.
 *
 * @param string $plugindir Plugin directory path
 * @param string $pluginname Plugin directory name
 * @param array $parameters Plugin import parameters
 * @return array
 */
function plugin_apply_installation ( $plugindir, $pluginname, $parameters)
{
  $result = array ();
  $upgrade = array_key_exists ( "upgrade", $parameters) && $parameters["upgrade"] == true;
  $lifecycle = array (
    "name" => $pluginname,
    "config" => $parameters["config"],
    "upgrade" => $upgrade
  );
  if ( $upgrade && array_key_exists ( "ORIGINAL", $parameters))
  {
    $lifecycle["original"] = $parameters["ORIGINAL"];
  }

  if ( $upgrade && is_readable ( $plugindir . "/update.php"))
  {
    $dbresult = plugin_run_db_script ( $plugindir, $pluginname, "update.php", "upgrade_db");
    $result = array_merge_recursive ( $result, $dbresult);
    if ( array_key_exists ( "db_manifest", $dbresult))
    {
      $result["remote_db"] = plugin_sync_db_manifest_remote ( $dbresult["db_manifest"], $pluginname, "upgrade_db", 0);
      unset ( $result["db_manifest"]);
    }
    plugin_call_lifecycle ( $pluginname, "upgrade", $lifecycle);
  } else {
    if ( is_readable ( $plugindir . "/install.php"))
    {
      $dbresult = plugin_run_db_script ( $plugindir, $pluginname, "install.php", "install_db");
      $result = array_merge_recursive ( $result, $dbresult);
      if ( array_key_exists ( "db_manifest", $dbresult))
      {
        $result["remote_db"] = plugin_sync_db_manifest_remote ( $dbresult["db_manifest"], $pluginname, "install_db", 0);
        unset ( $result["db_manifest"]);
      }
    }
    plugin_call_lifecycle ( $pluginname, "install", $lifecycle);
  }

  $deploy = plugin_deploy_daemons ( $plugindir, $pluginname);
  if ( sizeof ( $deploy["errors"]))
  {
    $result["Daemons"] = $deploy["errors"];
  }
  if ( sizeof ( $deploy["deployed"]))
  {
    $result["deployed"] = $deploy["deployed"];
    $reloadunits = systemd_units_from_deployed_modules ( $deploy["deployed"]);
    $result["remote"] = plugin_sync_daemon_modules_remote ( $plugindir, $pluginname, $deploy["deployed"], "deploy", 0);
    if ( sizeof ( $reloadunits))
    {
      $result["reloaded"] = plugin_request_daemon_reload_all ( $reloadunits);
    }
  }

  return $result;
}

/**
 * Remove a plugin runtime resources before deleting its files.
 *
 * @param string $plugindir Plugin directory path
 * @param string $pluginname Plugin directory name
 * @param array $parameters Plugin remove parameters
 * @return array
 */
function plugin_apply_removal ( $plugindir, $pluginname, $parameters)
{
  $result = array ();
  $lifecycle = array (
    "name" => $pluginname,
    "original" => $parameters["ORIGINAL"]
  );

  if ( is_readable ( $plugindir . "/uninstall.php"))
  {
    $dbresult = plugin_run_db_script ( $plugindir, $pluginname, "uninstall.php", "uninstall_db");
    $result = array_merge_recursive ( $result, $dbresult);
    if ( array_key_exists ( "db_manifest", $dbresult))
    {
      $result["remote_db"] = plugin_sync_db_manifest_remote ( $dbresult["db_manifest"], $pluginname, "uninstall_db", 0);
      unset ( $result["db_manifest"]);
    }
  }
  plugin_call_lifecycle ( $pluginname, "remove", $lifecycle);
  $undeploy = plugin_undeploy_daemons ( $plugindir, $pluginname);
  if ( sizeof ( $undeploy["removed"]))
  {
    $result["removed"] = $undeploy["removed"];
    $reloadunits = systemd_units_from_deployed_modules ( $undeploy["removed"]);
    $result["remote"] = plugin_sync_daemon_modules_remote ( $plugindir, $pluginname, $undeploy["removed"], "remove", 0);
    if ( sizeof ( $reloadunits))
    {
      $result["reloaded"] = plugin_request_daemon_reload_all ( $reloadunits);
    }
  }
  return $result;
}
?>
