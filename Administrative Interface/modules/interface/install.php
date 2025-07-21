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
 * VoIP Domain main framework interface module API. This module has all basic
 * system API call implementations to install the system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Interface
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to check permissions required to install the system
 */
framework_add_hook (
  "install_check",
  "install_check",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object with permissions check results."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Result" => array (
              "type" => "boolean",
              "description" => __ ( "The result of permissions check. True if permissions are okay to proceed installation, otherwise false."),
              "example" => true
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the result."),
              "example" => __ ( "All permissions are okay.")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/install/check",
  "Read",
  "install_check",
  array (
    "permissions" => array ( "Install"),
    "title" => __ ( "Interface permissions check."),
    "description" => __ ( "Check for all permissions required to install the software. ")
  )
);

/**
 * Function to check installation permissions requirements.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function install_check ( $buffer, $parameters)
{
  /**
   * Check for filesystem permissions
   */
  $result = array ();
  if ( ! file_exists ( "/etc/voipdomain/webserver.conf") && is_writable ( "/etc/voipdomain/"))
  {
    $result["Result"] = true;
    $result["Description"] = __ ( "All permissions are okay.");
  } else {
    $result["Result"] = false;
    $result["Description"] = __ ( "Filesystem permissions missing.");
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $result);
}

/**
 * API call to deploy system database
 */
framework_add_hook (
  "install_populate",
  "install_populate",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object with database installation results."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Result" => array (
              "type" => "boolean",
              "description" => __ ( "The result of database installation. True if installation was finished, otherwise false."),
              "example" => true
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the result."),
              "example" => __ ( "Installation finished okay.")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/install/db",
  "Create",
  "install_populate",
  array (
    "permissions" => array ( "Install"),
    "title" => __ ( "Interface database install."),
    "description" => __ ( "Install the system database.")
  )
);

/**
 * Function to install and populate system database.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function install_populate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Extend script execution time (up to 10 minutes)
   */
  set_time_limit ( 600);

  /**
   * Check for required parameters
   */
  $return = array ();
  if ( empty ( $parameters["Hostname"]))
  {
    $return["Hostname"] = __ ( "The hostname is required.");
  }
  if ( strpos ( $parameters["Hostname"], ":") !== false)
  {
    $parameters["Port"] = (int) substr ( $parameters["Hostname"], strpos ( $parameters["Hostname"], ":") + 1);
    $parameters["Hostname"] = substr ( $parameters["Hostname"], 0, strpos ( $parameters["Hostname"], ":"));
  }
  if ( ! array_key_exists ( "Hostname", $return) && gethostbyname ( $parameters["Hostname"]) == "")
  {
    $return["Hostname"] = __ ( "Invalid hostname.");
  }
  if ( empty ( $parameters["Username"]))
  {
    $return["Username"] = __ ( "The username is required.");
  }
  if ( empty ( $parameters["Password"]))
  {
    $return["Password"] = __ ( "The password is required.");
  }
  if ( sizeof ( $return) != 0)
  {
    $return["Result"] = false;
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $return);
  }

  /**
   * Populate framework database connection array
   */
  if ( ! array_key_exists ( "mysql", $_in))
  {
    $_in["mysql"] = array ();
  }
  $_in["mysql"]["hostname"] = $parameters["Hostname"];
  $_in["mysql"]["username"] = $parameters["Username"];
  $_in["mysql"]["password"] = $parameters["Password"];

  /**
   * Check database server connection
   */
  $_in["mysql"]["id"] = @new mysqli ( $_in["mysql"]["hostname"] . ( ! empty ( $_in["mysql"]["port"]) ? ":" . $_in["mysql"]["port"] : ""), $_in["mysql"]["username"], $_in["mysql"]["password"], $_in["mysql"]["database"]);
  if ( $_in["mysql"]["id"]->connect_errno)
  {
    $return["Username"] = __ ( "Username or password invalid.");
  }
  if ( sizeof ( $return) != 0)
  {
    $return["Result"] = false;
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $return);
  }

  /**
   * Create VoIP Domain database (default database name will be `vd`)
   */
  $_in["mysql"]["database"] = "vd";
  if ( ! @$_in["mysql"]["id"]->query ( "CREATE DATABASE `" . $_in["mysql"]["database"] . "`") || ! @$_in["mysql"]["id"]->select_db ( $_in["mysql"]["database"]))
  {
    $return["Hostname"] = sprintf ( __ ( "Cannot create VoIP Domain (%s) database."), $_in["mysql"]["database"]);
  }
  if ( sizeof ( $return) != 0)
  {
    $return["Result"] = false;
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $return);
  }

  /**
   * Create database variable structure
   */
  $_in["install"] = array ();
  $_in["install"]["db"] = array (
    "procedures" => array (),
    "tables" => array (),
    "triggers" => array (),
    "data" => array ()
  );

  /**
   * Database installation methods
   */

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

    /**
     * Check if name already exist
     */
    if ( array_key_exists ( $name, $_in["install"]["db"]["procedures"]))
    {
      return false;
    }

    /**
     * Add procedure to internal variable
     */
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

    /**
     * Check if name already exist
     */
    if ( array_key_exists ( $name, $_in["install"]["db"]["tables"]))
    {
      return false;
    }

    /**
     * Add table to internal variable
     */
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

    /**
     * Check if name already exist
     */
    if ( array_key_exists ( $name, $_in["install"]["db"]["triggers"]))
    {
      return false;
    }

    /**
     * Add trigger to internal variable
     */
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

    /**
     * If table name already exist, merge data, otherwise create table data
     */
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
   * Add basic system procedures
   */
  install_add_db_procedure ( "UpdateCache", "CREATE PROCEDURE `UpdateCache` (IN TableName VARCHAR(255))\n" .
                                            "BEGIN\n" .
                                            "  INSERT INTO `Cache` (`Table`, `Updated`) VALUES (TableName, NOW()) ON DUPLICATE KEY UPDATE `Updated` = NOW();\n" .
                                            "END;\n");

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Cache", "CREATE TABLE `Cache` (\n" .
                                  "  `Table` varchar(255) NOT NULL,\n" .
                                  "  `Updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',\n" .
                                  "  PRIMARY KEY (`Table`)\n" .
                                  ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Internal database table cache timers';\n");
  install_add_db_table ( "Config", "CREATE TABLE `Config` (\n" .
                                   "  `Key` varchar(255) NOT NULL,\n" .
                                   "  `Tenant` bigint(20) unsigned NOT NULL,\n" .
                                   "  `Data` longblob NOT NULL,\n" .
                                   "  UNIQUE KEY `Key_key` (`Tenant`,`Key`),\n" .
                                   "  KEY `Config_ibfk_1` (`Tenant`),\n" . 
                                   "  CONSTRAINT `Config_ibfk_1` FOREIGN KEY (`Tenant`) REFERENCES `Tenants` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" . 
                                   ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='System configuration entries';\n", array ( "Tenants"));
  install_add_db_table ( "Files", "CREATE TABLE `Files` (\n" .
                                  "  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,\n" .
                                  "  `Type` enum('fares') NOT NULL,\n" .
                                  "  `Name` varchar(255) DEFAULT NULL,\n" .
                                  "  `Content` longblob,\n" .
                                  "  PRIMARY KEY (`ID`)\n" .
                                  ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='System configuration template files';\n");
  install_add_db_table ( "Plugins", "CREATE TABLE `Plugins` (\n" .
                                    "  `Dirname` varchar(255) NOT NULL,\n" .
                                    "  `Name` varchar(255) NOT NULL,\n" .
                                    "  `Version` float unsigned NOT NULL,\n" .
                                    "  `Author` varchar(255) NOT NULL,\n" .
                                    "  `Description` varchar(255) NOT NULL,\n" .
                                    "  `License` varchar(255) NOT NULL,\n" .
                                    "  `Status` enum('A','I') NOT NULL DEFAULT 'A',\n" .
                                    "  `Requires` mediumblob,\n" .
                                    "  PRIMARY KEY (`Name`),\n" .
                                    "  UNIQUE KEY `Dirname` (`Dirname`)\n" .
                                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Installed plugins information';\n");
  install_add_db_table ( "cdr", "CREATE TABLE `cdr` (\n" .
                                "  `Tenant` bigint(20) unsigned NOT NULL,\n" .
                                "  `calldate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',\n" .
                                "  `clid` varchar(80) NOT NULL DEFAULT '',\n" .
                                "  `src` varchar(80) NOT NULL DEFAULT '',\n" .
                                "  `dst` varchar(80) NOT NULL DEFAULT '',\n" .
                                "  `dcontext` varchar(80) NOT NULL DEFAULT '',\n" .
                                "  `channel` varchar(80) NOT NULL DEFAULT '',\n" .
                                "  `dstchannel` varchar(80) NOT NULL DEFAULT '',\n" .
                                "  `lastapp` varchar(80) NOT NULL DEFAULT '',\n" .
                                "  `lastdata` varchar(80) NOT NULL DEFAULT '',\n" .
                                "  `duration` int(11) unsigned NOT NULL DEFAULT '0',\n" .
                                "  `billsec` int(11) unsigned NOT NULL DEFAULT '0',\n" .
                                "  `disposition` varchar(45) NOT NULL DEFAULT '',\n" .
                                "  `amaflags` int(11) unsigned NOT NULL DEFAULT '0',\n" .
                                "  `accountcode` varchar(20) NOT NULL DEFAULT '',\n" .
                                "  `userfield` varchar(255) NOT NULL DEFAULT '',\n" .
                                "  `uniqueid` varchar(32) NOT NULL DEFAULT '',\n" .
                                "  `linkedid` varchar(32) NOT NULL DEFAULT '',\n" .
                                "  `sequence` varchar(32) NOT NULL DEFAULT '',\n" .
                                "  `peeraccount` varchar(32) NOT NULL DEFAULT '',\n" .
                                "  `server` int(11) unsigned NOT NULL DEFAULT '0',\n" .
                                "  `sourcetype` smallint(2) unsigned NOT NULL DEFAULT '0',\n" .
                                "  `calltype` smallint(2) unsigned NOT NULL DEFAULT '0',\n" .
                                "  `gateway` int(11) unsigned NOT NULL DEFAULT '0',\n" .
                                "  `value` double(10,6) unsigned DEFAULT NULL,\n" .
                                "  `processed` boolean NOT NULL DEFAULT false,\n" .
                                "  `nativecodec` varchar(64) NOT NULL DEFAULT '',\n" .
                                "  `readcodec` varchar(64) NOT NULL DEFAULT '',\n" .
                                "  `writecodec` varchar(64) NOT NULL DEFAULT '',\n" .
                                "  `QOS` text NOT NULL DEFAULT '',\n" .
                                "  `WhoHungUp` enum('Caller','Called'),\n" .
                                "  `monitor` varchar(255) NOT NULL DEFAULT '',\n" .
                                "  `userfieldextra` varchar(255) DEFAULT NULL,\n" .
                                "  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n" .
                                "  `SIPID` varchar(255) DEFAULT '',\n" .
                                "  `flags` text NOT NULL DEFAULT '',\n" .
                                "  `srcid` int(10) unsigned DEFAULT NULL,\n" .
                                "  `dstid` int(10) unsigned DEFAULT NULL,\n" .
                                "  `ccid` int(10) unsigned DEFAULT NULL,\n" .
                                "  KEY `date` (`calldate`,`insertdate`),\n" .
                                "  KEY `src` (`src`),\n" .
                                "  KEY `dst` (`dst`),\n" .
                                "  KEY `srcid` (`srcid`),\n" .
                                "  KEY `dstid` (`dstid`),\n" .
                                "  KEY `ccid` (`ccid`),\n" .
                                "  KEY `duration` (`duration`,`billsec`),\n" .
                                "  KEY `uniqueid` (`uniqueid`),\n" .
                                "  KEY `server` (`server`),\n" .
                                "  KEY `gateway` (`gateway`),\n" .
                                "  KEY `processed` (`processed`),\n" .
                                "  KEY `cdr_ibfk_1` (`Tenant`),\n" . 
                                "  CONSTRAINT `cdr_ibfk_1` FOREIGN KEY (`Tenant`) REFERENCES `Tenants` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" . 
                                ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Call records';\n", array ( "Tenants"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "FilesInsert", "CREATE TRIGGER `FilesInsert` AFTER INSERT ON `Files` FOR EACH ROW CALL UpdateCache('Files')");
  install_add_db_trigger ( "FilesUpdate", "CREATE TRIGGER `FilesUpdate` AFTER UPDATE ON `Files` FOR EACH ROW CALL UpdateCache('Files')");
  install_add_db_trigger ( "FilesDelete", "CREATE TRIGGER `FilesDelete` AFTER DELETE ON `Files` FOR EACH ROW CALL UpdateCache('Files')");
  install_add_db_trigger ( "PluginsInsert", "CREATE TRIGGER `PluginsInsert` AFTER INSERT ON `Plugins` FOR EACH ROW CALL UpdateCache('Plugins')");
  install_add_db_trigger ( "PluginsUpdate", "CREATE TRIGGER `PluginsUpdate` AFTER UPDATE ON `Plugins` FOR EACH ROW CALL UpdateCache('Plugins')");
  install_add_db_trigger ( "PluginsDelete", "CREATE TRIGGER `PluginsDelete` AFTER DELETE ON `Plugins` FOR EACH ROW CALL UpdateCache('Plugins')");
  install_add_db_trigger ( "cdrExtensionActivity", "CREATE TRIGGER `cdrExtensionActivity` BEFORE INSERT ON `cdr`\n" .
                                                   "  FOR EACH ROW\n" .
                                                   "  BEGIN\n" .
                                                   "    DECLARE sid, did, ccid INT(10) UNSIGNED;\n" .
                                                   "    SELECT `ID` INTO sid FROM `Extensions` WHERE `Number` = NEW.src;\n" .
                                                   "    IF sid > 0 THEN\n" .
                                                   "      INSERT INTO `ExtensionActivity` (`UID`, `LastDialed`) VALUES (sid, NEW.calldate) ON DUPLICATE KEY UPDATE `LastDialed` = NEW.calldate;\n" .
                                                   "      SET NEW.srcid = sid;\n" .
                                                   "    END IF;\n" .
                                                   "    SELECT `ID` INTO did FROM `Extensions` WHERE `Number` = NEW.dst;\n" .
                                                   "    IF did > 0 THEN\n" .
                                                   "      INSERT INTO `ExtensionActivity` (`UID`, `LastReceived`) VALUES (did, NEW.calldate) ON DUPLICATE KEY UPDATE `LastReceived` = NEW.calldate;\n" .
                                                   "      SET NEW.dstid = did;\n" .
                                                   "    END IF;\n" .
                                                   "    SELECT `ID` INTO ccid FROM `CostCenters` WHERE `Code` = NEW.accountcode;\n" .
                                                   "    IF ccid > 0 THEN\n" .
                                                   "      SET NEW.ccid = ccid;\n" .
                                                   "    END IF;\n" .
                                                   "  END\n");

  /**
   * Call install database hooks to populate database installation
   */
  if ( framework_has_hook ( "install_db"))
  {
    framework_call ( "install_db", array ());
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

  /**
   * Create unprivileged VoIP Domain user
   */
  $_in["mysql"]["vdpassword"] = random_password ();
  if ( ! @$_in["mysql"]["id"]->query ( "CREATE USER 'vd'@'" . ( $_in["mysql"]["hostname"] == "127.0.0.1" || strtolower ( $_in["mysql"]["hostname"]) == "localhost" ? "localhost" : "%") . "' IDENTIFIED BY '" . $_in["mysql"]["id"]->real_escape_string ( $_in["mysql"]["vdpassword"]) . "'"))
  {
    $return["Message"] = __ ( "Unable to create unprivileged database user!");
  } else {
    if ( ! @$_in["mysql"]["id"]->query ( "GRANT SELECT, INSERT, UPDATE, DELETE ON `vd`.* TO 'vd'@'" . ( $_in["mysql"]["hostname"] == "127.0.0.1" || strtolower ( $_in["mysql"]["hostname"]) == "localhost" ? "localhost" : "%") . "'"))
    {
      $return["Message"] = __ ( "Unable to grant privileges to 'vd' database user!", true, false);
    }
  }

  /**
   * Generate server private and public key (if not already created)
   */
  if ( ! file_exists ( "/etc/voipdomain/master-certificate.key") || ! file_exists ( "/etc/voipdomain/master-certificate.pub"))
  {
    $keyconf = array (
      "digest_alg" => "sha512",
      "private_key_bits" => 4096,
      "private_key_type" => OPENSSL_KEYTYPE_RSA
    );
    $ssl = openssl_pkey_new ( $keyconf);

    // Create the private and public key
    $res = openssl_pkey_new ( $keyconf);

    // Write private key file
    if ( ! openssl_pkey_export_to_file ( $res, "/etc/voipdomain/master-certificate.key"))
    {
      $return["Message"] = __ ( "Unable to write server private key!");
    }
    chmod ( "/etc/voipdomain/master-certificate.key", 0600);

    // Write public key file
    if ( ! file_put_contents ( "/etc/voipdomain/master-certificate.pub", openssl_pkey_get_details ( $res)["key"]))
    {
      $return["Message"] = __ ( "Unable to write server public key!");
    }
  }

  /**
   * If installed successfully, create configuration file
   */
  if ( sizeof ( $return) == 0)
  {
    $config = ";\n";
    $config .= ";    ___ ___       ___ _______     ______                        __\n";
    $config .= ";   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.\n";
    $config .= ";   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |\n";
    $config .= ";   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|\n";
    $config .= ";   |:  1   |     |:  |:  |       |:  1    /\n";
    $config .= ";    \:.. ./      |::.|::.|       |::.. . /\n";
    $config .= ";     `---'       `---`---'       `------'\n";
    $config .= ";\n";
    $config .= "; Copyright (C) 2016-2025 Ernani José Camargo Azevedo\n";
    $config .= ";\n";
    $config .= "; This program is free software: you can redistribute it and/or modify\n";
    $config .= "; it under the terms of the GNU General Public License as published by\n";
    $config .= "; the Free Software Foundation, either version 3 of the License, or\n";
    $config .= "; (at your option) any later version.\n";
    $config .= ";\n";
    $config .= "; This program is distributed in the hope that it will be useful,\n";
    $config .= "; but WITHOUT ANY WARRANTY; without even the implied warranty of\n";
    $config .= "; MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n";
    $config .= "; GNU General Public License for more details.\n";
    $config .= ";\n";
    $config .= "; You should have received a copy of the GNU General Public License\n";
    $config .= "; along with this program.  If not, see <https://www.gnu.org/licenses/>.\n";
    $config .= ";\n";
    $config .= "\n";
    $config .= ";\n";
    $config .= "; VoIP Domain main interface options file.\n";
    $config .= ";\n";
    $config .= "; @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>\n";
    $config .= "; @version    1.0\n";
    $config .= "; @package    VoIP Domain\n";
    $config .= "; @subpackage Interface\n";
    $config .= "; @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.\n";
    $config .= "; @license    https://www.gnu.org/licenses/gpl-3.0.en.html\n";
    $config .= ";\n";
    $config .= "\n";
    $config .= "[general]\n";
    $config .= "language = en_US\n";
    $config .= "charset = UTF-8\n";
    $config .= "title = VoIP Domain\n";
    $config .= "domain = voipdomain.io\n";
    $config .= "favicon = /img/phone.png\n";
    $config .= "baseurl = " . $_SERVER["HTTP_HOST"] . "\n";
    $config .= "masterhostname = " . $_SERVER["HTTP_HOST"] . "\n";
    $config .= "contact = azevedo@voipdomain.io\n";
    $config .= "spooldir = /var/spool/voipdomain\n";
    $config .= "tempdir = /var/www/tmp\n";
    $config .= "soundsdir = /var/lib/asterisk/sounds/voipdomain\n";
    $config .= "storagedir = /var/lib/voipdomain/storage\n";
    $config .= "installdate = " . date ( "Y-m-d") . "\n";
    $config .= "timeout = 1800\n";
    $config .= "debug = false\n";
    $config .= "defaultcurrency = USD\n";
    $config .= "\n";
    $config .= "[logo]\n";
    $config .= "filename = /img/phone.png\n";
    $config .= "width = 35\n";
    $config .= "height = 35\n";
    $config .= "\n";
    $config .= "[mysql]\n";
    $config .= "hostname = " . $_in["mysql"]["hostname"] . "\n";
    $config .= "username = vd\n";
    $config .= "password = " . $_in["mysql"]["vdpassword"] . "\n";
    $config .= "database = vd\n";
    $config .= "\n";
    $config .= "[api]\n";
    $config .= "baseurl = " . $_SERVER["HTTP_REFERER"] . "api\n";
    $config .= "baseuri = /api\n";
    $config .= "\n";
    $config .= "[security]\n";
    $config .= "iterations = 40000\n";
    $config .= "loginformautocomplete = false\n";
    $config .= "totprange = 1\n";
    $config .= "\n";
    $config .= "[gearman]\n";
    $config .= "servers = localhost\n";
    if ( ! file_put_contents ( "/etc/voipdomain/webinterface.conf", $config))
    {
      $return["Message"] = __ ( "Unable to write configuration file!");
    }
  }

  /**
   * Format result array to return
   */
  if ( sizeof ( $return) != 0)
  {
    $return["Result"] = false;
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
  } else {
    $return["Result"] = true;
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $return);
}
?>
