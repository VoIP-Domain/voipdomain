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
 * VoIP Domain plugin local database installation helpers (client daemon).
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Initialize plugin database installation structure.
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
 * Process pending database objects from a plugin manifest.
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
              $return["Procedures"][] = "Error installing database procedure \"" . $name . "\".";
            }
          } else {
            $pending = true;
          }
        }
      }
      if ( $lastinst == $toinstall && $pending == true)
      {
        $return["Procedures"][] = "There are pending procedures not installed due to dependency error.";
        $toinstall = 0;
      }
    }
  }

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
              $return["Tables"][] = "Error installing database table \"" . $name . "\".";
            }
          } else {
            $pending = true;
          }
        }
      }
      if ( $lastinst == $toinstall && $pending == true)
      {
        $return["Tables"][] = "There are pending tables not installed due to dependency error.";
        $toinstall = 0;
      }
    }
  }

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
              $return["Triggers"][] = "Error installing database trigger \"" . $name . "\".";
            }
          } else {
            $pending = true;
          }
        }
      }
      if ( $lastinst == $toinstall && $pending == true)
      {
        $return["Triggers"][] = "There are pending triggers not installed due to dependency error.";
        $toinstall = 0;
      }
    }
  }

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
              $return["Data"][] = "Error inserting database table \"" . $name . "\" data.";
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
        $return["Data"][] = "There are pending table data not installed due to dependency error.";
        $toinstall = 0;
      }
    }
  }

  return $return;
}

/**
 * Apply a plugin database manifest on the local client database.
 *
 * @param array $manifest Database manifest
 * @return array
 */
function plugin_apply_db_manifest ( $manifest)
{
  global $_in;

  plugin_install_db_init ();
  $_in["install"]["db"] = $manifest;
  return plugin_process_db ( array ());
}
?>
