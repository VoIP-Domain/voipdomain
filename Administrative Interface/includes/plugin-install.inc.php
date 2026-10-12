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
    "alters" => array (),
    "triggers" => array (),
    "data" => array (),
    "raw" => array ()
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
 * Function to add an ALTER TABLE statement to the system database installation.
 *
 * @global array $_in Framework global configuration variable
 * @param string $name A unique identifier for this alteration (e.g., "MyTable_add_col")
 * @param string $data The ALTER TABLE SQL statement
 * @param array $deps[optional] Array with names of other alter entries that must run first
 * @return boolean If the alter was added
 */
function install_add_db_alter ( $name, $data, $deps = array ())
{
  global $_in;

  if ( array_key_exists ( $name, $_in["install"]["db"]["alters"]))
  {
    return false;
  }
  $_in["install"]["db"]["alters"][$name] = array ( "data" => $data, "deps" => $deps);
  return true;
}

/**
 * Function to add arbitrary SQL to the system database installation. This is an
 * escape hatch for complex migrations that cannot be expressed via the structured
 * functions. Raw SQL runs after alters and before triggers.
 *
 * @global array $_in Framework global configuration variable
 * @param string $name A unique identifier for this raw statement
 * @param string $data The SQL statement(s) to execute
 * @param array $deps[optional] Array with names of other raw entries that must run first
 * @return boolean If the raw SQL was added
 */
function install_add_db_raw ( $name, $data, $deps = array ())
{
  global $_in;

  if ( array_key_exists ( $name, $_in["install"]["db"]["raw"]))
  {
    return false;
  }
  $_in["install"]["db"]["raw"][$name] = array ( "data" => $data, "deps" => $deps);
  return true;
}

/**
 * Function to register a DROP statement for database cleanup during uninstall
 * or schema migration.
 *
 * @global array $_in Framework global configuration variable
 * @param string $name The object name to drop
 * @param string $type Object type: "table", "trigger", "procedure"
 * @param array $deps[optional] Array with names of other raw entries that must run first
 * @return boolean If the drop was added
 */
function install_add_db_drop ( $name, $type, $deps = array ())
{
  global $_in;

  $types = array ( "table" => "DROP TABLE IF EXISTS", "trigger" => "DROP TRIGGER IF EXISTS", "procedure" => "DROP PROCEDURE IF EXISTS");
  if ( ! array_key_exists ( $type, $types))
  {
    return false;
  }
  $sql = $types[$type] . " `" . $name . "`";
  return install_add_db_raw ( "drop_" . $type . "_" . $name, $sql, $deps);
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
            if ( ! array_key_exists ( $dep, $_in["install"]["db"]["procedures"]))
            {
              continue;
            } else if ( $_in["install"]["db"]["procedures"][$dep]["installed"] != true)
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
            if ( ! array_key_exists ( $dep, $_in["install"]["db"]["tables"]))
            {
              $check = @$_in["mysql"]["id"]->query ( "SHOW TABLES LIKE '" . $_in["mysql"]["id"]->real_escape_string ( $dep) . "'");
              if ( $check && $check->num_rows > 0)
              {
                continue;
              }
              $depsok = false;
            } else if ( $_in["install"]["db"]["tables"][$dep]["installed"] != true)
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
  if ( sizeof ( $_in["install"]["db"]["alters"]))
  {
    foreach ( $_in["install"]["db"]["alters"] as $name => $data)
    {
      $_in["install"]["db"]["alters"][$name]["installed"] = false;
    }
    $toinstall = sizeof ( $_in["install"]["db"]["alters"]);
    while ( $toinstall > 0)
    {
      $lastinst = $toinstall;
      $pending = false;
      foreach ( $_in["install"]["db"]["alters"] as $name => $data)
      {
        if ( $data["installed"] == false)
        {
          $depsok = true;
          foreach ( $data["deps"] as $dep)
          {
            if ( array_key_exists ( $dep, $_in["install"]["db"]["alters"]) && $_in["install"]["db"]["alters"][$dep]["installed"] != true)
            {
              $depsok = false;
            }
          }
          if ( $depsok)
          {
            $toinstall--;
            if ( @$_in["mysql"]["id"]->query ( $data["data"]))
            {
              $_in["install"]["db"]["alters"][$name]["installed"] = true;
            } else {
              if ( ! array_key_exists ( "Alters", $return))
              {
                $return["Alters"] = array ();
              }
              $return["Alters"][] = sprintf ( __ ( "Error executing database alter \"%s\": %s", true, false), $name, $_in["mysql"]["id"]->error);
            }
          } else {
            $pending = true;
          }
        }
      }
      if ( $lastinst == $toinstall && $pending == true)
      {
        $return["Alters"][] = __ ( "There are pending alters not executed due to dependency error.");
        $toinstall = 0;
      }
    }
  }

  /**
   * Execute raw SQL statements
   */
  if ( sizeof ( $_in["install"]["db"]["raw"]))
  {
    foreach ( $_in["install"]["db"]["raw"] as $name => $data)
    {
      $_in["install"]["db"]["raw"][$name]["installed"] = false;
    }
    $toinstall = sizeof ( $_in["install"]["db"]["raw"]);
    while ( $toinstall > 0)
    {
      $lastinst = $toinstall;
      $pending = false;
      foreach ( $_in["install"]["db"]["raw"] as $name => $data)
      {
        if ( $data["installed"] == false)
        {
          $depsok = true;
          foreach ( $data["deps"] as $dep)
          {
            if ( array_key_exists ( $dep, $_in["install"]["db"]["raw"]) && $_in["install"]["db"]["raw"][$dep]["installed"] != true)
            {
              $depsok = false;
            }
          }
          if ( $depsok)
          {
            $toinstall--;
            if ( @$_in["mysql"]["id"]->query ( $data["data"]))
            {
              $_in["install"]["db"]["raw"][$name]["installed"] = true;
            } else {
              if ( ! array_key_exists ( "Raw", $return))
              {
                $return["Raw"] = array ();
              }
              $return["Raw"][] = sprintf ( __ ( "Error executing raw SQL \"%s\": %s", true, false), $name, $_in["mysql"]["id"]->error);
            }
          } else {
            $pending = true;
          }
        }
      }
      if ( $lastinst == $toinstall && $pending == true)
      {
        $return["Raw"][] = __ ( "There are pending raw SQL statements not executed due to dependency error.");
        $toinstall = 0;
      }
    }
  }

  /**
   * Create database triggers (after alters and raw SQL)
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
            if ( ! array_key_exists ( $dep, $_in["install"]["db"]["triggers"]))
            {
              continue;
            } else if ( $_in["install"]["db"]["triggers"][$dep]["installed"] != true)
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
            if ( ! array_key_exists ( $dep, $_in["install"]["db"]["data"]))
            {
              continue;
            } else if ( $_in["install"]["db"]["data"][$dep]["installed"] != true)
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
  return array ( "client-plugins", "fastagi-plugins", "monitor-plugins", "router-plugins");
}

/**
 * Validate and parse a plugin.json manifest file.
 *
 * @param string $filepath Full path to the plugin.json file
 * @return array|false Parsed manifest array on success, false on validation failure
 */
function plugin_validate_manifest ( $filepath )
{
  if ( ! is_readable ( $filepath))
  {
    return false;
  }
  $json = file_get_contents ( $filepath);
  $manifest = json_decode ( $json, true);
  if ( ! is_array ( $manifest))
  {
    return false;
  }

  // Required fields
  $required = array ( "name", "display_name", "version", "author", "description", "license");
  foreach ( $required as $field)
  {
    if ( ! array_key_exists ( $field, $manifest) || empty ( $manifest[$field]))
    {
      return false;
    }
  }

  // Author must have at least name
  if ( ! is_array ( $manifest["author"]) || ! array_key_exists ( "name", $manifest["author"]) || empty ( $manifest["author"]["name"]))
  {
    return false;
  }

  // Version must be valid semver-like
  if ( ! preg_match ( '/^\d+\.\d+\.\d+/', $manifest["version"]))
  {
    return false;
  }

  // Normalize optional fields
  if ( ! array_key_exists ( "requires", $manifest) || ! is_array ( $manifest["requires"]))
  {
    $manifest["requires"] = array ();
  }
  if ( ! array_key_exists ( "conflicts", $manifest) || ! is_array ( $manifest["conflicts"]))
  {
    $manifest["conflicts"] = array ();
  }
  if ( ! array_key_exists ( "daemons", $manifest) || ! is_array ( $manifest["daemons"]))
  {
    $manifest["daemons"] = array ();
  }
  if ( ! array_key_exists ( "hooks", $manifest) || ! is_array ( $manifest["hooks"]))
  {
    $manifest["hooks"] = array ();
  }
  if ( ! array_key_exists ( "config", $manifest) || ! is_array ( $manifest["config"]))
  {
    $manifest["config"] = array ();
  }
  if ( ! array_key_exists ( "signature", $manifest) || ! is_array ( $manifest["signature"]))
  {
    $manifest["signature"] = array ();
  }
  if ( ! array_key_exists ( "published", $manifest))
  {
    $manifest["published"] = null;
  }
  if ( ! array_key_exists ( "support", $manifest) || ! is_array ( $manifest["support"]))
  {
    $manifest["support"] = array ();
  }

  return $manifest;
}

/**
 * Parse a plugin manifest into the internal config format expected by the
 * import pipeline. Bridges the gap between plugin.json structure and the
 * legacy $parameters["config"] format.
 *
 * @param array $manifest Validated manifest from plugin_validate_manifest()
 * @return array Config array compatible with plugin import pipeline
 */
function plugin_manifest_to_config ( $manifest )
{
  $config = array ();
  $config["name"] = $manifest["display_name"];
  $config["version"] = $manifest["version"];
  $config["description"] = $manifest["description"];
  $config["author"] = $manifest["author"]["name"];
  $config["email"] = array_key_exists ( "email", $manifest["author"]) ? $manifest["author"]["email"] : "";
  $config["license"] = $manifest["license"];
  $config["daemons"] = $manifest["daemons"];
  $config["published"] = $manifest["published"];
  $config["support_url"] = array_key_exists ( "url", $manifest["support"]) ? $manifest["support"]["url"] : "";
  $config["support_email"] = array_key_exists ( "email", $manifest["support"]) ? $manifest["support"]["email"] : "";
  $config["signature"] = $manifest["signature"];
  $config["conflicts"] = $manifest["conflicts"];

  // Convert requires from {"core": ">=1.0.0"} to ["core >= 1.0.0"]
  $config["require"] = array ();
  if ( is_array ( $manifest["requires"]))
  {
    foreach ( $manifest["requires"] as $module => $constraint)
    {
      if ( preg_match ( '/^(>=|<=|>|<|=|!=)\s*(.+)$/', $constraint, $m))
      {
        $config["require"][] = $module . " " . $m[1] . " " . $m[2];
      } else {
        $config["require"][] = $module . " >= " . $constraint;
      }
    }
  }

  return $config;
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
  foreach ( array ( "procedures", "tables", "alters", "triggers", "data", "raw") as $key)
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
 * @param array $parameters Version context passed to hook functions
 * @return array|null
 */
function plugin_collect_db_script ( $plugindir, $pluginname, $script, $hook, $parameters = array ())
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
      call_user_func ( $function, "", $parameters);
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
 * @global array $_in Framework global configuration variable
 * @param string $plugindir Plugin directory path
 * @param string $pluginname Plugin directory name
 * @param string $script Script file name
 * @param string $hook Database hook name
 * @param array $parameters Version context passed to hook functions
 * @return array
 */
function plugin_run_db_script ( $plugindir, $pluginname, $script, $hook, $parameters = array ())
{
  global $_in;

  $manifest = plugin_collect_db_script ( $plugindir, $pluginname, $script, $hook, $parameters);
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
 * @global array $_in Framework global configuration variable
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

    $daemon_type = str_replace ( "-plugins", "", $type);

    if ( $action == "remove")
    {
      $notify = array ( "Plugin" => $pluginname, "ModuleType" => $type, "Module" => $module, "DaemonType" => $daemon_type);
      if ( $daemon_type == "client")
      {
        if ( ! notify_server ( $serverid, "plugin_daemon_module_remove", $notify))
        {
          $result["errors"][] = $entry;
        } else {
          $result["synced"][] = $entry;
        }
      } else {
        if ( ! plugin_gearman_dispatch ( $serverid, $daemon_type, "plugin_daemon_module_remove", $notify))
        {
          $result["errors"][] = $entry;
        } else {
          $result["synced"][] = $entry;
        }
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
    $metadata = array ( "Plugin" => $pluginname, "ModuleType" => $type, "Module" => $module, "DaemonType" => $daemon_type);
    if ( $daemon_type == "client")
    {
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
    } else {
      if ( ! file_transfer_send ( $serverid, $archive, array (
        "TargetEvent" => "plugin_daemon_module_deploy",
        "Context" => "plugin",
        "Metadata" => $metadata,
        "TargetDaemon" => $daemon_type
      )))
      {
        $result["errors"][] = $entry;
      } else {
        $result["synced"][] = $entry;
      }
    }
    @unlink ( $archive);
  }
  return $result;
}

/**
 * Dispatch a Gearman task to a specific daemon type on a server.
 *
 * @global array $_in Framework global configuration variable
 * @param int $serverid Target server ID (0 = all servers)
 * @param string $daemon_type Daemon type (fastagi, monitor, router)
 * @param string $hook Hook/event name to call
 * @param array $payload Data to pass
 * @return boolean Success
 */
function plugin_gearman_dispatch ( $serverid, $daemon_type, $hook, $payload)
{
  global $_in;

  $gearman_host = ! empty ( $_in["gearman"]["hostname"]) ? $_in["gearman"]["hostname"] : "127.0.0.1";
  $gearman_port = ! empty ( $_in["gearman"]["port"]) ? $_in["gearman"]["port"] : 4730;
  try
  {
    $gm = new GearmanClient ();
    $gm->addServer ( $gearman_host, $gearman_port);
  } catch ( Exception $e) {
    return false;
  }

  $workload = json_encode ( array ( "hook" => $hook, "data" => $payload));

  if ( $daemon_type == "router")
  {
    @$gm->doBackground ( "vd_router_task", $workload);
    return $gm->returnCode () == GEARMAN_SUCCESS;
  }

  if ( $serverid == 0)
  {
    $servers = array ();
    $result = @$_in["mysql"]["id"]->query ( "SELECT `ID` FROM `Servers`");
    if ( $result)
    {
      while ( $row = $result->fetch_assoc ())
      {
        $servers[] = $row["ID"];
      }
    }
    $success = true;
    foreach ( $servers as $sid)
    {
      $function_name = "vd_" . $daemon_type . "_" . $sid . "_task";
      $ret = @$gm->doBackground ( $function_name, $workload);
      if ( $gm->returnCode () != GEARMAN_SUCCESS)
      {
        $success = false;
      }
    }
    return $success;
  }

  $function_name = "vd_" . $daemon_type . "_" . $serverid . "_task";
  @$gm->doBackground ( $function_name, $workload);
  return $gm->returnCode () == GEARMAN_SUCCESS;
}

/**
 * Sync a plugin database manifest to remote servers.
 *
 * @global array $_in Framework global configuration variable
 * @param array $manifest Database manifest collected from install/update/uninstall
 * @param string $pluginname Plugin directory name
 * @param string $hook Database hook name
 * @param int $serverid Server ID (0 = all servers)
 * @param array $version_info Version context (version, version_from, version_to, type)
 * @return array
 */
function plugin_sync_db_manifest_remote ( $manifest, $pluginname, $hook, $serverid = 0, $version_info = array ())
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
  $payload = array ( "manifest" => $manifest);
  if ( sizeof ( $version_info) > 0)
  {
    $payload["version_info"] = $version_info;
  }
  $jsonfile = $spooldir . "/db-" . $pluginname . "-" . $hook . "-" . md5 ( microtime ( true)) . ".json";
  if ( ! @file_put_contents ( $jsonfile, json_encode ( $payload)))
  {
    return array ( "errors" => array ( "manifest"));
  }
  $synced = file_transfer_send ( $serverid, $jsonfile, array (
    "TargetEvent" => "plugin_db_apply",
    "Context" => "plugin",
    "Metadata" => array ( "Plugin" => $pluginname, "Hook" => $hook, "VersionInfo" => $version_info)
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
  $pluginversion = array_key_exists ( "config", $parameters) && array_key_exists ( "version", $parameters["config"]) ? $parameters["config"]["version"] : "1.0.0";
  $currentversion = array_key_exists ( "ORIGINAL", $parameters) && array_key_exists ( "Version", $parameters["ORIGINAL"]) ? $parameters["ORIGINAL"]["Version"] : null;

  $lifecycle = array (
    "name" => $pluginname,
    "config" => $parameters["config"],
    "upgrade" => $upgrade
  );
  if ( $upgrade && array_key_exists ( "ORIGINAL", $parameters))
  {
    $lifecycle["original"] = $parameters["ORIGINAL"];
  }

  $db_parameters = array (
    "type" => $upgrade ? "upgrade" : "install",
    "version" => $pluginversion
  );
  if ( $upgrade && $currentversion !== null)
  {
    $db_parameters["version_from"] = $currentversion;
    $db_parameters["version_to"] = $pluginversion;
  }

  if ( $upgrade && is_readable ( $plugindir . "/update.php"))
  {
    $dbresult = plugin_run_db_script ( $plugindir, $pluginname, "update.php", "upgrade_db", $db_parameters);
    $result = array_merge_recursive ( $result, $dbresult);
    if ( array_key_exists ( "db_manifest", $dbresult))
    {
      $result["remote_db"] = plugin_sync_db_manifest_remote ( $dbresult["db_manifest"], $pluginname, "upgrade_db", 0, $db_parameters);
      unset ( $result["db_manifest"]);
    }
    plugin_call_lifecycle ( $pluginname, "upgrade", $lifecycle);
  } else {
    if ( is_readable ( $plugindir . "/install.php"))
    {
      $dbresult = plugin_run_db_script ( $plugindir, $pluginname, "install.php", "install_db", $db_parameters);
      $result = array_merge_recursive ( $result, $dbresult);
      if ( array_key_exists ( "db_manifest", $dbresult))
      {
        $result["remote_db"] = plugin_sync_db_manifest_remote ( $dbresult["db_manifest"], $pluginname, "install_db", 0, $db_parameters);
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

  /**
   * Sync plugin configuration to target daemons via datadir
   */
  if ( array_key_exists ( "manifest", $parameters) && array_key_exists ( "config", $parameters["manifest"]) && sizeof ( $parameters["manifest"]["config"]) > 0)
  {
    $config_result = plugin_sync_config_to_daemons ( $pluginname, $parameters["manifest"]["config"], $parameters["manifest"]["daemons"]);
    if ( sizeof ( $config_result["errors"]) > 0)
    {
      $result["config_sync_errors"] = $config_result["errors"];
    }
  }

  return $result;
}

/**
 * Sync plugin configuration data to all target daemons' datadir.
 * Creates/updates /var/lib/voipdomain/data/plugin-{name}.json on each server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $pluginname Plugin directory name
 * @param array $config Plugin configuration data
 * @param array $daemons List of target daemon types
 * @return array Result with synced/errors arrays
 */
function plugin_sync_config_to_daemons ( $pluginname, $config, $daemons = array ())
{
  global $_in;

  $result = array ( "synced" => array (), "errors" => array ());
  $payload = json_encode ( $config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  $filename = "plugin-" . $pluginname . ".json";

  if ( sizeof ( $daemons) == 0)
  {
    $daemons = array ( "client");
  }

  $servers = array ();
  $query = @$_in["mysql"]["id"]->query ( "SELECT `ID` FROM `Servers`");
  if ( $query)
  {
    while ( $row = $query->fetch_assoc ())
    {
      $servers[] = $row["ID"];
    }
  }

  foreach ( $servers as $serverid)
  {
    foreach ( $daemons as $daemon)
    {
      $notify_data = array (
        "Plugin" => $pluginname,
        "Filename" => $filename,
        "Content" => base64_encode ( $payload),
        "DaemonType" => $daemon
      );
      if ( $daemon == "client")
      {
        if ( ! notify_server ( $serverid, "plugin_config_sync", $notify_data))
        {
          $result["errors"][] = $daemon . "@server-" . $serverid;
        } else {
          $result["synced"][] = $daemon . "@server-" . $serverid;
        }
      } else {
        if ( ! plugin_gearman_dispatch ( $serverid, $daemon, "plugin_config_sync", $notify_data))
        {
          $result["errors"][] = $daemon . "@server-" . $serverid;
        } else {
          $result["synced"][] = $daemon . "@server-" . $serverid;
        }
      }
    }
  }
  return $result;
}

/**
 * Remove plugin configuration from all target daemons' datadir.
 *
 * @global array $_in Framework global configuration variable
 * @param string $pluginname Plugin directory name
 * @param array $daemons List of target daemon types
 * @return array Result with synced/errors arrays
 */
function plugin_remove_config_from_daemons ( $pluginname, $daemons = array ())
{
  global $_in;

  $result = array ( "synced" => array (), "errors" => array ());
  $filename = "plugin-" . $pluginname . ".json";

  if ( sizeof ( $daemons) == 0)
  {
    $daemons = array ( "client");
  }

  $servers = array ();
  $query = @$_in["mysql"]["id"]->query ( "SELECT `ID` FROM `Servers`");
  if ( $query)
  {
    while ( $row = $query->fetch_assoc ())
    {
      $servers[] = $row["ID"];
    }
  }

  foreach ( $servers as $serverid)
  {
    foreach ( $daemons as $daemon)
    {
      $notify_data = array (
        "Plugin" => $pluginname,
        "Filename" => $filename,
        "DaemonType" => $daemon
      );
      if ( $daemon == "client")
      {
        if ( ! notify_server ( $serverid, "plugin_config_remove", $notify_data))
        {
          $result["errors"][] = $daemon . "@server-" . $serverid;
        } else {
          $result["synced"][] = $daemon . "@server-" . $serverid;
        }
      } else {
        if ( ! plugin_gearman_dispatch ( $serverid, $daemon, "plugin_config_remove", $notify_data))
        {
          $result["errors"][] = $daemon . "@server-" . $serverid;
        } else {
          $result["synced"][] = $daemon . "@server-" . $serverid;
        }
      }
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

  /**
   * Remove plugin configuration from daemons' datadir
   */
  $daemons = array ();
  if ( array_key_exists ( "ORIGINAL", $parameters) && ! empty ( $parameters["ORIGINAL"]["Daemons"]))
  {
    $daemons = explode ( ",", $parameters["ORIGINAL"]["Daemons"]);
  }
  if ( sizeof ( $daemons) == 0)
  {
    $daemons = array ( "client");
  }
  plugin_remove_config_from_daemons ( $pluginname, $daemons);

  return $result;
}

/**
 * Check if any active plugins depend on the given plugin.
 *
 * @global array $_in Framework global configuration variable
 * @param string $pluginname Plugin directory name to check dependents for
 * @return array List of plugin names that depend on this plugin
 */
function plugin_check_dependents ( $pluginname)
{
  global $_in;

  $dependents = array ();
  $result = @$_in["mysql"]["id"]->query ( "SELECT `Dirname`, `Name`, `Requires` FROM `Plugins` WHERE `Status` = 'A' AND `Dirname` != '" . $_in["mysql"]["id"]->real_escape_string ( $pluginname) . "'");
  if ( ! $result)
  {
    return $dependents;
  }
  while ( $row = $result->fetch_assoc ())
  {
    $requires = json_decode ( $row["Requires"], true);
    if ( ! is_array ( $requires))
    {
      continue;
    }
    foreach ( $requires as $reqstring)
    {
      if ( preg_match ( "/^(\w[\w-]*)/", trim ( $reqstring), $m))
      {
        if ( $m[1] === $pluginname)
        {
          $dependents[] = $row["Name"];
          break;
        }
      }
    }
  }
  return $dependents;
}
?>
