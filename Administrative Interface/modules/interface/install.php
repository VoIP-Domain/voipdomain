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
    "permissions" => array ( "install"),
    "title" => __ ( "Interface permissions check."),
    "description" => __ ( "Check for all permissions required to install the software. ")
  )
);

/**
 * Function to check installation permissions requirements.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function install_check ( $buffer, $parameters)
{
  global $_in;

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
    "permissions" => array ( "install"),
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
                                  ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "Config", "CREATE TABLE `Config` (\n" .
                                   "  `Key` varchar(255) NOT NULL,\n" .
                                   "  `Data` longblob NOT NULL,\n" .
                                   "  UNIQUE KEY `Key_key` (`Key`)\n" .
                                   ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "Currencies", "CREATE TABLE `Currencies` (\n" .
                                       "  `ISO4217` int(2) unsigned NOT NULL,\n" .
                                       "  `Code` char(3) NOT NULL,\n" .
                                       "  `Name` varchar(255) NOT NULL,\n" .
                                       "  `Demonym` varchar(255) NOT NULL,\n" .
                                       "  `Symbol` varchar(20) NOT NULL,\n" .
                                       "  `NativeSymbol` varchar(20) NOT NULL,\n" .
                                       "  `MajorSingle` varchar(255) NOT NULL,\n" .
                                       "  `MinorSingle` varchar(255) NOT NULL,\n" .
                                       "  `MajorPlural` varchar(255) NOT NULL,\n" .
                                       "  `MinorPlural` varchar(255) NOT NULL,\n" .
                                       "  `Digits` int(1) unsigned NOT NULL,\n" .
                                       "  `Decimals` int(1) unsigned NOT NULL,\n" .
                                       "  `NumToBasic` int(1) unsigned NOT NULL,\n" .
                                       "  PRIMARY KEY (`ISO4217`,`Code`),\n" .
                                       "  UNIQUE KEY `ISO4217` (`ISO4217`),\n" .
                                       "  UNIQUE KEY `Code` (`Code`)\n" .
                                       ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "CurrenciesRates", "CREATE TABLE `CurrenciesRates` (\n" .
                                            "  `SourceCurrency` int(2) unsigned NOT NULL,\n" .
                                            "  `TargetCurrency` int(2) unsigned NOT NULL,\n" .
                                            "  `Date` date NOT NULL,\n" .
                                            "  `Value` double(24,12) UNSIGNED NOT NULL,\n" .
                                            "  PRIMARY KEY (`SourceCurrency`,`TargetCurrency`,`Date`),\n" .
                                            "  KEY `SourceCurrency` (`SourceCurrency`),\n" .
                                            "  KEY `TargetCurrency` (`TargetCurrency`),\n" .
                                            "  KEY `Date` (`Date`),\n" .
                                            "  CONSTRAINT `CurrenciesRates_ibfk_1` FOREIGN KEY (`SourceCurrency`) REFERENCES `Currencies` (`ISO4217`) ON DELETE CASCADE ON UPDATE CASCADE,\n" .
                                            "  CONSTRAINT `CurrenciesRates_ibfk_2` FOREIGN KEY (`TargetCurrency`) REFERENCES `Currencies` (`ISO4217`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                            ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Currencies"));
  install_add_db_table ( "Countries", "CREATE TABLE `Countries` (\n" .
                                      "  `Code` int(2) unsigned NOT NULL,\n" .
                                      "  `Name` varchar(255) NOT NULL,\n" .
                                      "  `Alpha2` char(2) NOT NULL,\n" .
                                      "  `Alpha3` char(3) NOT NULL,\n" .
                                      "  `Region` varchar(255) NOT NULL,\n" .
                                      "  `RegionCode` smallint(2) unsigned NOT NULL,\n" .
                                      "  `SubRegion` varchar(255) NOT NULL,\n" .
                                      "  `SubRegionCode` smallint(2) unsigned NOT NULL,\n" .
                                      "  `ISO3166-2` char(2) NOT NULL,\n" .
                                      "  `Currency` int(2) unsigned NOT NULL,\n" .
                                      "  PRIMARY KEY (`Code`),\n" .
                                      "  KEY `Name` (`Name`,`ISO3166-2`),\n" .
                                      "  KEY `Region` (`Region`,`SubRegion`),\n" .
                                      "  KEY `RegionCode` (`RegionCode`,`SubRegionCode`)\n" .
                                      ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "CountryCodes", "CREATE TABLE `CountryCodes` (\n" .
                                         "  `Country` int(2) unsigned NOT NULL,\n" .
                                         "  `Prefix` varchar(255) NOT NULL,\n" .
                                         "  `Code` varchar(6) NOT NULL,\n" .
                                         "  KEY `Prefix` (`Prefix`),\n" .
                                         "  KEY `Code` (`Code`),\n" .
                                         "  KEY `CountryCodes_ibfk_1` (`Country`),\n" .
                                         "  CONSTRAINT `CountryCodes_ibfk_1` FOREIGN KEY (`Country`) REFERENCES `Countries` (`Code`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                         ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Countries"));
  install_add_db_table ( "Files", "CREATE TABLE `Files` (\n" .
                                  "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                  "  `Type` enum('fares') NOT NULL,\n" .
                                  "  `Name` varchar(255) DEFAULT NULL,\n" .
                                  "  `Content` longblob,\n" .
                                  "  PRIMARY KEY (`ID`)\n" .
                                  ") ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "Locales", "CREATE TABLE `Locales` (\n" .
                                    "  `Code` varchar(32) NOT NULL,\n" .
                                    "  `Name` varchar(255) NOT NULL,\n" .
                                    "  PRIMARY KEY (`Code`),\n" .
                                    "  UNIQUE KEY `Code` (`Code`)\n" .
                                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
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
                                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "cdr", "CREATE TABLE `cdr` (\n" .
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
                                "  KEY `processed` (`processed`)\n" .
                                ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "CurrenciesInsert", "CREATE TRIGGER `CurrenciesInsert` AFTER INSERT ON `Currencies` FOR EACH ROW CALL UpdateCache('Currencies')");
  install_add_db_trigger ( "CurrenciesUpdate", "CREATE TRIGGER `CurrenciesUpdate` AFTER UPDATE ON `Currencies` FOR EACH ROW CALL UpdateCache('Currencies')");
  install_add_db_trigger ( "CurrenciesDelete", "CREATE TRIGGER `CurrenciesDelete` AFTER DELETE ON `Currencies` FOR EACH ROW CALL UpdateCache('Currencies')");
  install_add_db_trigger ( "CurrenciesRatesInsert", "CREATE TRIGGER `CurrenciesRatesInsert` AFTER INSERT ON `CurrenciesRates` FOR EACH ROW CALL UpdateCache('CurrenciesRates')");
  install_add_db_trigger ( "CurrenciesRatesUpdate", "CREATE TRIGGER `CurrenciesRatesUpdate` AFTER UPDATE ON `CurrenciesRates` FOR EACH ROW CALL UpdateCache('CurrenciesRates')");
  install_add_db_trigger ( "CurrenciesRatesDelete", "CREATE TRIGGER `CurrenciesRatesDelete` AFTER DELETE ON `CurrenciesRates` FOR EACH ROW CALL UpdateCache('CurrenciesRates')");
  install_add_db_trigger ( "CountriesInsert", "CREATE TRIGGER `CountriesInsert` AFTER INSERT ON `Countries` FOR EACH ROW CALL UpdateCache('Countries')");
  install_add_db_trigger ( "CountriesUpdate", "CREATE TRIGGER `CountriesUpdate` AFTER UPDATE ON `Countries` FOR EACH ROW CALL UpdateCache('Countries')");
  install_add_db_trigger ( "CountriesDelete", "CREATE TRIGGER `CountriesDelete` AFTER DELETE ON `Countries` FOR EACH ROW CALL UpdateCache('Countries')");
  install_add_db_trigger ( "CountryCodesInsert", "CREATE TRIGGER `CountryCodesInsert` AFTER INSERT ON `CountryCodes` FOR EACH ROW CALL UpdateCache('CountryCodes')");
  install_add_db_trigger ( "CountryCodesUpdate", "CREATE TRIGGER `CountryCodesUpdate` AFTER UPDATE ON `CountryCodes` FOR EACH ROW CALL UpdateCache('CountryCodes')");
  install_add_db_trigger ( "CountryCodesDelete", "CREATE TRIGGER `CountryCodesDelete` AFTER DELETE ON `CountryCodes` FOR EACH ROW CALL UpdateCache('CountryCodes')");
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
   * Add basic system tables data
   */
  install_add_db_data ( "Currencies", array (
    array ( "ISO4217" => 8, "Code" => "ALL", "Name" => "Albanian Lek", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Lek", "MinorSingle" => "Qindarka", "MajorPlural" => "Lekë", "MinorPlural" => "Qindarka", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 12, "Code" => "DZD", "Name" => "Algerian Dinar", "Symbol" => "DA", "NativeSymbol" => "د.ج.", "MajorSingle" => "Dinar", "MinorSingle" => "Santeem", "MajorPlural" => "Dinars", "MinorPlural" => "Santeems", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 32, "Code" => "ARS", "Name" => "Argentine Peso", "Symbol" => "AR$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 36, "Code" => "AUD", "Name" => "Australian Dollar", "Symbol" => "AU$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 44, "Code" => "BSD", "Name" => "Bahamian Dollar", "Symbol" => "$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 48, "Code" => "BHD", "Name" => "Bahraini Dinar", "Symbol" => "BD", "NativeSymbol" => "د.ب.", "MajorSingle" => "Dinar", "MinorSingle" => "Fils", "MajorPlural" => "Dinars", "MinorPlural" => "Fils", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 50, "Code" => "BDT", "Name" => "Bangladeshi Taka", "Symbol" => "৳", "NativeSymbol" => "৳", "MajorSingle" => "Taka", "MinorSingle" => "Poisha", "MajorPlural" => "Taka", "MinorPlural" => "Poisha", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 51, "Code" => "AMD", "Name" => "Armenian Dram", "Symbol" => "֏", "NativeSymbol" => "դր", "MajorSingle" => "Dram", "MinorSingle" => "Luma", "MajorPlural" => "Dram", "MinorPlural" => "Luma", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 52, "Code" => "BBD", "Name" => "Barbadian Dollar", "Symbol" => "BBD$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 60, "Code" => "BMD", "Name" => "Bermudian Dollar", "Symbol" => "$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 64, "Code" => "BTN", "Name" => "Bhutanese Ngultrum", "Symbol" => "Nu.", "NativeSymbol" => "Nu.", "MajorSingle" => "Ngultrum", "MinorSingle" => "Chetrum", "MajorPlural" => "Ngultrums", "MinorPlural" => "Chetrums", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 68, "Code" => "BOB", "Name" => "Bolivian Boliviano", "Symbol" => "Bs.", "NativeSymbol" => "Bs.", "MajorSingle" => "Boliviano", "MinorSingle" => "Centavo", "MajorPlural" => "Bolivianos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 72, "Code" => "BWP", "Name" => "Botswana Pula", "Symbol" => "P", "NativeSymbol" => "P", "MajorSingle" => "Pula", "MinorSingle" => "Thebe", "MajorPlural" => "Pula", "MinorPlural" => "Thebe", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 84, "Code" => "BZD", "Name" => "Belize Dollar", "Symbol" => "BZ$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 90, "Code" => "SBD", "Name" => "Solomon Islands Dollar", "Symbol" => "SI$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 96, "Code" => "BND", "Name" => "Brunei Dollar", "Symbol" => "B$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 104, "Code" => "MMK", "Name" => "Myanmar Kyat", "Symbol" => "Ks", "NativeSymbol" => "Ks", "MajorSingle" => "Kyat", "MinorSingle" => "Pya", "MajorPlural" => "Kyat", "MinorPlural" => "Pya", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 108, "Code" => "BIF", "Name" => "Burundian Franc", "Symbol" => "FBu", "NativeSymbol" => "FBu", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 116, "Code" => "KHR", "Name" => "Cambodian Riel", "Symbol" => "៛", "NativeSymbol" => "៛", "MajorSingle" => "Riel", "MinorSingle" => "Sen", "MajorPlural" => "Riels", "MinorPlural" => "Sen", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 124, "Code" => "CAD", "Name" => "Canadian Dollar", "Symbol" => "CA$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 132, "Code" => "CVE", "Name" => "Cabo Verdean Escudo", "Symbol" => "CV$", "NativeSymbol" => "$", "MajorSingle" => "Escudo", "MinorSingle" => "Centavo", "MajorPlural" => "Escudo", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 136, "Code" => "KYD", "Name" => "Cayman Islands Dollar", "Symbol" => "CI$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 144, "Code" => "LKR", "Name" => "Sri Lankan Rupee", "Symbol" => "Rs.", "NativeSymbol" => "රු or ரூ", "MajorSingle" => "Rupee", "MinorSingle" => "Cent", "MajorPlural" => "Rupees", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 152, "Code" => "CLP", "Name" => "Chilean Peso", "Symbol" => "CL$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 0, "Decimals" => 0, "NumToBasic" => 100),
    array ( "ISO4217" => 156, "Code" => "CNY", "Name" => "Chinese Yuan", "Symbol" => "CN¥", "NativeSymbol" => "¥元", "MajorSingle" => "Yuan", "MinorSingle" => "Fen", "MajorPlural" => "Yuan", "MinorPlural" => "Fen", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 170, "Code" => "COP", "Name" => "Colombian Peso", "Symbol" => "CO$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 174, "Code" => "KMF", "Name" => "Comorian Franc", "Symbol" => "CF", "NativeSymbol" => "CF", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 188, "Code" => "CRC", "Name" => "Costa Rican Colon", "Symbol" => "₡", "NativeSymbol" => "₡", "MajorSingle" => "Colón", "MinorSingle" => "Centimo", "MajorPlural" => "Colones", "MinorPlural" => "Centimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 191, "Code" => "HRK", "Name" => "Croatian Kuna", "Symbol" => "kn", "NativeSymbol" => "kn", "MajorSingle" => "Kuna", "MinorSingle" => "Lipa", "MajorPlural" => "Kuna", "MinorPlural" => "Lipa", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 192, "Code" => "CUP", "Name" => "Cuban Peso", "Symbol" => "$MN", "NativeSymbol" => "₱", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 203, "Code" => "CZK", "Name" => "Czech Koruna", "Symbol" => "Kč", "NativeSymbol" => "Kč", "MajorSingle" => "Koruna", "MinorSingle" => "Haléř", "MajorPlural" => "Koruny", "MinorPlural" => "Haléř", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 208, "Code" => "DKK", "Name" => "Danish Krone", "Symbol" => "kr.", "NativeSymbol" => "kr.", "MajorSingle" => "Krone", "MinorSingle" => "Øre", "MajorPlural" => "Kroner", "MinorPlural" => "Øre", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 214, "Code" => "DOP", "Name" => "Dominican Peso", "Symbol" => "RD$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 222, "Code" => "SVC", "Name" => "Salvadoran Colón", "Symbol" => "₡", "NativeSymbol" => "₡", "MajorSingle" => "Colón", "MinorSingle" => "Centavo", "MajorPlural" => "Colones", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 230, "Code" => "ETB", "Name" => "Ethiopian Birr", "Symbol" => "Br", "NativeSymbol" => "ብር", "MajorSingle" => "Birr", "MinorSingle" => "Santim", "MajorPlural" => "Birr", "MinorPlural" => "Santim", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 232, "Code" => "ERN", "Name" => "Eritrean Nakfa", "Symbol" => "Nkf", "NativeSymbol" => "ناكفا", "MajorSingle" => "Nakfa", "MinorSingle" => "Cent", "MajorPlural" => "Nakfa", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 238, "Code" => "FKP", "Name" => "Falkland Islands Pound", "Symbol" => "FK£", "NativeSymbol" => "£", "MajorSingle" => "Pound", "MinorSingle" => "Penny", "MajorPlural" => "Pounds", "MinorPlural" => "Pence", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 242, "Code" => "FJD", "Name" => "Fijian Dollar", "Symbol" => "FJ$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 262, "Code" => "DJF", "Name" => "Djiboutian Franc", "Symbol" => "Fdj", "NativeSymbol" => "ف.ج.", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 270, "Code" => "GMD", "Name" => "Gambian Dalasi", "Symbol" => "D", "NativeSymbol" => "D", "MajorSingle" => "Dalasi", "MinorSingle" => "Butut", "MajorPlural" => "Dalasis", "MinorPlural" => "Bututs", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 292, "Code" => "GIP", "Name" => "Gibraltar Pound", "Symbol" => "£", "NativeSymbol" => "£", "MajorSingle" => "Pound", "MinorSingle" => "Penny", "MajorPlural" => "Pounds", "MinorPlural" => "Pence", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 320, "Code" => "GTQ", "Name" => "Guatemalan Quetzal", "Symbol" => "Q", "NativeSymbol" => "$", "MajorSingle" => "Quetzal", "MinorSingle" => "Centavo", "MajorPlural" => "Quetzales", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 324, "Code" => "GNF", "Name" => "Guinean Franc", "Symbol" => "FG", "NativeSymbol" => "FG", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 328, "Code" => "GYD", "Name" => "Guyanese Dollar", "Symbol" => "G$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 332, "Code" => "HTG", "Name" => "Haitian Gourde", "Symbol" => "G", "NativeSymbol" => "G", "MajorSingle" => "Gourde", "MinorSingle" => "Centime", "MajorPlural" => "Gourdes", "MinorPlural" => "Centimes", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 340, "Code" => "HNL", "Name" => "Honduran Lempira", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Lempira", "MinorSingle" => "Centavo", "MajorPlural" => "Lempiras", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 344, "Code" => "HKD", "Name" => "Hong Kong Dollar", "Symbol" => "HK$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 348, "Code" => "HUF", "Name" => "Hungarian Forint", "Symbol" => "Ft", "NativeSymbol" => "Ft", "MajorSingle" => "Forint", "MinorSingle" => "fillér", "MajorPlural" => "Forint", "MinorPlural" => "fillér", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 352, "Code" => "ISK", "Name" => "Icelandic Krona", "Symbol" => "kr", "NativeSymbol" => "kr", "MajorSingle" => "Krona", "MinorSingle" => "Aurar", "MajorPlural" => "Krónur", "MinorPlural" => "Aurar", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 356, "Code" => "INR", "Name" => "Indian Rupee", "Symbol" => "Rs.", "NativeSymbol" => "₹", "MajorSingle" => "Rupee", "MinorSingle" => "Paisa", "MajorPlural" => "Rupees", "MinorPlural" => "Paise", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 360, "Code" => "IDR", "Name" => "Indonesian Rupiah", "Symbol" => "Rp", "NativeSymbol" => "Rp", "MajorSingle" => "Rupiah", "MinorSingle" => "Sen", "MajorPlural" => "Rupiah", "MinorPlural" => "Sen", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 364, "Code" => "IRR", "Name" => "Iranian Rial", "Symbol" => "﷼", "NativeSymbol" => "﷼", "MajorSingle" => "Rial", "MinorSingle" => "Dinar", "MajorPlural" => "Rials", "MinorPlural" => "Dinars", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 368, "Code" => "IQD", "Name" => "Iraqi Dinar", "Symbol" => "د.ع.", "NativeSymbol" => "د.ع.", "MajorSingle" => "Dinar", "MinorSingle" => "Fils", "MajorPlural" => "Dinars", "MinorPlural" => "Fils", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 376, "Code" => "ILS", "Name" => "Israeli new Shekel", "Symbol" => "₪", "NativeSymbol" => "₪", "MajorSingle" => "Shekel", "MinorSingle" => "Agora", "MajorPlural" => "Shekels", "MinorPlural" => "Agoras", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 388, "Code" => "JMD", "Name" => "Jamaican Dollar", "Symbol" => "J$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 392, "Code" => "JPY", "Name" => "Japanese Yen", "Symbol" => "¥", "NativeSymbol" => "¥", "MajorSingle" => "Yen", "MinorSingle" => "Sen", "MajorPlural" => "Yen", "MinorPlural" => "Sen", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 398, "Code" => "KZT", "Name" => "Kazakhstani Tenge", "Symbol" => "₸", "NativeSymbol" => "₸", "MajorSingle" => "Tenge", "MinorSingle" => "Tıyn", "MajorPlural" => "Tenge", "MinorPlural" => "Tıyn", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 400, "Code" => "JOD", "Name" => "Jordanian Dinar", "Symbol" => "JD", "NativeSymbol" => "د.أ.", "MajorSingle" => "Dinar", "MinorSingle" => "Fils", "MajorPlural" => "Dinars", "MinorPlural" => "Fils", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 404, "Code" => "KES", "Name" => "Kenyan Shilling", "Symbol" => "KSh", "NativeSymbol" => "KSh", "MajorSingle" => "Shilling", "MinorSingle" => "Cent", "MajorPlural" => "Shillings", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 408, "Code" => "KPW", "Name" => "North Korean Won", "Symbol" => "₩", "NativeSymbol" => "₩", "MajorSingle" => "Won", "MinorSingle" => "Chon", "MajorPlural" => "Won", "MinorPlural" => "Chon", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 410, "Code" => "KRW", "Name" => "South Korean Won", "Symbol" => "₩", "NativeSymbol" => "₩", "MajorSingle" => "Won", "MinorSingle" => "Jeon", "MajorPlural" => "Won", "MinorPlural" => "Jeon", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 414, "Code" => "KWD", "Name" => "Kuwaiti Dinar", "Symbol" => "KD", "NativeSymbol" => "د.ك.", "MajorSingle" => "Dinar", "MinorSingle" => "Fils", "MajorPlural" => "Dinars", "MinorPlural" => "Fils", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 417, "Code" => "KGS", "Name" => "Kyrgyzstani Som", "Symbol" => "с", "NativeSymbol" => "с", "MajorSingle" => "Som", "MinorSingle" => "Tyiyn", "MajorPlural" => "Som", "MinorPlural" => "Tyiyn", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 418, "Code" => "LAK", "Name" => "Lao Kip", "Symbol" => "₭N", "NativeSymbol" => "₭", "MajorSingle" => "Kip", "MinorSingle" => "Att", "MajorPlural" => "Kip", "MinorPlural" => "Att", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 422, "Code" => "LBP", "Name" => "Lebanese Pound", "Symbol" => "LL.", "NativeSymbol" => "ل.ل.", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 426, "Code" => "LSL", "Name" => "Lesotho Loti", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Loti", "MinorSingle" => "Sente", "MajorPlural" => "maLoti", "MinorPlural" => "Lisente", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 430, "Code" => "LRD", "Name" => "Liberian Dollar", "Symbol" => "L$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 434, "Code" => "LYD", "Name" => "Libyan Dinar", "Symbol" => "LD", "NativeSymbol" => "ل.د.", "MajorSingle" => "Dinar", "MinorSingle" => "Dirham", "MajorPlural" => "Dinars", "MinorPlural" => "Dirhams", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 446, "Code" => "MOP", "Name" => "Macanese Pataca", "Symbol" => "MOP$", "NativeSymbol" => "MOP$", "MajorSingle" => "Pataca", "MinorSingle" => "Avo", "MajorPlural" => "Patacas", "MinorPlural" => "Avos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 454, "Code" => "MWK", "Name" => "Malawian Kwacha", "Symbol" => "MK", "NativeSymbol" => "MK", "MajorSingle" => "Kwacha", "MinorSingle" => "Tambala", "MajorPlural" => "Kwacha", "MinorPlural" => "Tambala", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 458, "Code" => "MYR", "Name" => "Malaysian Ringgit", "Symbol" => "RM", "NativeSymbol" => "RM", "MajorSingle" => "Ringgit", "MinorSingle" => "Sen", "MajorPlural" => "Ringgit", "MinorPlural" => "Sen", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 462, "Code" => "MVR", "Name" => "Maldivian Rufiyaa", "Symbol" => "MRf", "NativeSymbol" => ".ރ", "MajorSingle" => "Rufiyaa", "MinorSingle" => "laari", "MajorPlural" => "Rufiyaa", "MinorPlural" => "laari", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 480, "Code" => "MUR", "Name" => "Mauritian Rupee", "Symbol" => "Rs.", "NativeSymbol" => "रु ", "MajorSingle" => "Rupee", "MinorSingle" => "Cent", "MajorPlural" => "Rupees", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 484, "Code" => "MXN", "Name" => "Mexican Peso", "Symbol" => "MX$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 496, "Code" => "MNT", "Name" => "Mongolian Tögrög", "Symbol" => "₮", "NativeSymbol" => "₮", "MajorSingle" => "Tögrög", "MinorSingle" => "möngö", "MajorPlural" => "Tögrög", "MinorPlural" => "möngö", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 498, "Code" => "MDL", "Name" => "Moldovan Leu", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Leu", "MinorSingle" => "Ban", "MajorPlural" => "Lei", "MinorPlural" => "Bani", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 504, "Code" => "MAD", "Name" => "Moroccan Dirham", "Symbol" => "DH", "NativeSymbol" => "د.م.", "MajorSingle" => "Dirham", "MinorSingle" => "Centime", "MajorPlural" => "Dirhams", "MinorPlural" => "Centimes", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 512, "Code" => "OMR", "Name" => "Omani Rial", "Symbol" => "OR", "NativeSymbol" => "ر.ع.", "MajorSingle" => "Rial", "MinorSingle" => "Baisa", "MajorPlural" => "Rials", "MinorPlural" => "Baisa", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 516, "Code" => "NAD", "Name" => "Namibian Dollar", "Symbol" => "N$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 524, "Code" => "NPR", "Name" => "Nepalese Rupee", "Symbol" => "Rs.", "NativeSymbol" => "रू", "MajorSingle" => "Rupee", "MinorSingle" => "Paisa", "MajorPlural" => "Rupees", "MinorPlural" => "Paise", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 532, "Code" => "ANG", "Name" => "Netherlands Antillean Guilder", "Symbol" => "ƒ", "NativeSymbol" => "ƒ", "MajorSingle" => "Guilder", "MinorSingle" => "Cent", "MajorPlural" => "Guilders", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 533, "Code" => "AWG", "Name" => "Aruban Florin", "Symbol" => "ƒ", "NativeSymbol" => "ƒ", "MajorSingle" => "Florin", "MinorSingle" => "Cent", "MajorPlural" => "Florin", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 548, "Code" => "VUV", "Name" => "Vanuatu Vatu", "Symbol" => "VT", "NativeSymbol" => "VT", "MajorSingle" => "Vatu", "MinorSingle" => "", "MajorPlural" => "Vatu", "MinorPlural" => "", "Digits" => 0, "Decimals" => 0, "NumToBasic" => 0),
    array ( "ISO4217" => 554, "Code" => "NZD", "Name" => "New Zealand Dollar", "Symbol" => "NZ$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 558, "Code" => "NIO", "Name" => "Nicaraguan Córdoba", "Symbol" => "C$", "NativeSymbol" => "C$", "MajorSingle" => "Córdoba Oro", "MinorSingle" => "Centavo", "MajorPlural" => "Córdoba Oro", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 566, "Code" => "NGN", "Name" => "Nigerian Naira", "Symbol" => "₦", "NativeSymbol" => "₦", "MajorSingle" => "Naira", "MinorSingle" => "Kobo", "MajorPlural" => "Naira", "MinorPlural" => "Kobo", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 578, "Code" => "NOK", "Name" => "Norwegian Krone", "Symbol" => "kr", "NativeSymbol" => "kr", "MajorSingle" => "Krone", "MinorSingle" => "øre", "MajorPlural" => "Kroner", "MinorPlural" => "øre", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 586, "Code" => "PKR", "Name" => "Pakistani Rupee", "Symbol" => "Rs.", "NativeSymbol" => "Rs", "MajorSingle" => "Rupee", "MinorSingle" => "Paisa", "MajorPlural" => "Rupees", "MinorPlural" => "Paise", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 590, "Code" => "PAB", "Name" => "Panamanian Balboa", "Symbol" => "B/.", "NativeSymbol" => "B/.", "MajorSingle" => "Balboa", "MinorSingle" => "Centésimo", "MajorPlural" => "Balboa", "MinorPlural" => "Centésimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 598, "Code" => "PGK", "Name" => "Papua New Guinean Kina", "Symbol" => "K", "NativeSymbol" => "K", "MajorSingle" => "Kina", "MinorSingle" => "Toea", "MajorPlural" => "Kina", "MinorPlural" => "Toea", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 600, "Code" => "PYG", "Name" => "Paraguayan Guaraní", "Symbol" => "₲", "NativeSymbol" => "₲", "MajorSingle" => "Guaraní", "MinorSingle" => "Centimo", "MajorPlural" => "Guaraníes", "MinorPlural" => "Centimos", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 604, "Code" => "PEN", "Name" => "Peruvian Sol", "Symbol" => "S/.", "NativeSymbol" => "S/.", "MajorSingle" => "Sol", "MinorSingle" => "Céntimo", "MajorPlural" => "Soles", "MinorPlural" => "Céntimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 608, "Code" => "PHP", "Name" => "Philippine Peso", "Symbol" => "₱", "NativeSymbol" => "₱", "MajorSingle" => "Peso", "MinorSingle" => "Sentimo", "MajorPlural" => "Pesos", "MinorPlural" => "Sentimo", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 634, "Code" => "QAR", "Name" => "Qatari Riyal", "Symbol" => "QR", "NativeSymbol" => "ر.ق.", "MajorSingle" => "Riyal", "MinorSingle" => "Dirham", "MajorPlural" => "Riyals", "MinorPlural" => "Dirhams", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 643, "Code" => "RUB", "Name" => "Russian Ruble", "Symbol" => "₽", "NativeSymbol" => "₽", "MajorSingle" => "Ruble", "MinorSingle" => "Kopek", "MajorPlural" => "Rubles", "MinorPlural" => "Kopeks", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 646, "Code" => "RWF", "Name" => "Rwandan Franc", "Symbol" => "FRw", "NativeSymbol" => "R₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 654, "Code" => "SHP", "Name" => "Saint Helena Pound", "Symbol" => "£", "NativeSymbol" => "£", "MajorSingle" => "Pound", "MinorSingle" => "Penny", "MajorPlural" => "Pounds", "MinorPlural" => "Pence", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 682, "Code" => "SAR", "Name" => "Saudi Riyal", "Symbol" => "SR", "NativeSymbol" => "ر.س.", "MajorSingle" => "Riyal", "MinorSingle" => "Halalah", "MajorPlural" => "Riyals", "MinorPlural" => "Halalahs", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 690, "Code" => "SCR", "Name" => "Seychellois Rupee", "Symbol" => "Rs.", "NativeSymbol" => "Rs", "MajorSingle" => "Rupee", "MinorSingle" => "Cent", "MajorPlural" => "Rupees", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 694, "Code" => "SLL", "Name" => "Sierra Leonean Leone", "Symbol" => "Le", "NativeSymbol" => "Le", "MajorSingle" => "Leone", "MinorSingle" => "Cent", "MajorPlural" => "Leones", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 702, "Code" => "SGD", "Name" => "Singapore Dollar", "Symbol" => "S$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 704, "Code" => "VND", "Name" => "Vietnamese Dong", "Symbol" => "₫", "NativeSymbol" => "₫", "MajorSingle" => "Dong", "MinorSingle" => "Hào", "MajorPlural" => "Dong", "MinorPlural" => "Hào", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 10),
    array ( "ISO4217" => 706, "Code" => "SOS", "Name" => "Somali Shilling", "Symbol" => "Sh.So.", "NativeSymbol" => "Ssh", "MajorSingle" => "Shilling", "MinorSingle" => "Senti", "MajorPlural" => "Shillings", "MinorPlural" => "Senti", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 710, "Code" => "ZAR", "Name" => "South African Rand", "Symbol" => "R", "NativeSymbol" => "R", "MajorSingle" => "Rand", "MinorSingle" => "Cent", "MajorPlural" => "Rand", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 728, "Code" => "SSP", "Name" => "South Sudanese Pound", "Symbol" => "SS£", "NativeSymbol" => "SS£", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 748, "Code" => "SZL", "Name" => "Swazi Lilangeni", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Lilangeni", "MinorSingle" => "Cent", "MajorPlural" => "Emalangeni", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 752, "Code" => "SEK", "Name" => "Swedish Krona", "Symbol" => "kr", "NativeSymbol" => "kr", "MajorSingle" => "Krona", "MinorSingle" => "Öre", "MajorPlural" => "Kronor", "MinorPlural" => "Öre", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 756, "Code" => "CHF", "Name" => "Swiss Franc", "Symbol" => "Fr.", "NativeSymbol" => "₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 760, "Code" => "SYP", "Name" => "Syrian Pound", "Symbol" => "LS", "NativeSymbol" => "ل.س.", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 764, "Code" => "THB", "Name" => "Thai Baht", "Symbol" => "฿", "NativeSymbol" => "฿", "MajorSingle" => "Baht", "MinorSingle" => "Satang", "MajorPlural" => "Baht", "MinorPlural" => "Satang", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 776, "Code" => "TOP", "Name" => "Tongan Paʻanga", "Symbol" => "T$", "NativeSymbol" => "PT", "MajorSingle" => "Pa'anga", "MinorSingle" => "Seniti", "MajorPlural" => "Pa'anga", "MinorPlural" => "Seniti", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 780, "Code" => "TTD", "Name" => "Trinidad and Tobago Dollar", "Symbol" => "TT$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 784, "Code" => "AED", "Name" => "United Arab Emirates Dirham", "Symbol" => "د.إ.", "NativeSymbol" => "د.إ.", "MajorSingle" => "Dirham", "MinorSingle" => "Fils", "MajorPlural" => "Dirhams", "MinorPlural" => "Fils", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 788, "Code" => "TND", "Name" => "Tunisian Dinar", "Symbol" => "DT", "NativeSymbol" => "د.ت.", "MajorSingle" => "Dinar", "MinorSingle" => "Millime", "MajorPlural" => "Dinars", "MinorPlural" => "Millime", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 800, "Code" => "UGX", "Name" => "Ugandan Shilling", "Symbol" => "USh", "NativeSymbol" => "Sh", "MajorSingle" => "Shilling", "MinorSingle" => "Cent", "MajorPlural" => "Shillings", "MinorPlural" => "Cents", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 807, "Code" => "MKD", "Name" => "Macedonian Denar", "Symbol" => "den", "NativeSymbol" => "ден", "MajorSingle" => "Denar", "MinorSingle" => "Deni", "MajorPlural" => "Denars", "MinorPlural" => "Deni", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 818, "Code" => "EGP", "Name" => "Egyptian Pound", "Symbol" => "E£", "NativeSymbol" => "ج.م.", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 826, "Code" => "GBP", "Name" => "Pound Sterling", "Symbol" => "£", "NativeSymbol" => "£", "MajorSingle" => "Pound", "MinorSingle" => "Penny", "MajorPlural" => "Pounds", "MinorPlural" => "Pence", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 834, "Code" => "TZS", "Name" => "Tanzanian Shilling", "Symbol" => "TSh", "NativeSymbol" => "TSh", "MajorSingle" => "Shilling", "MinorSingle" => "Senti", "MajorPlural" => "Shillings", "MinorPlural" => "Senti", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 840, "Code" => "USD", "Name" => "United States Dollar", "Symbol" => "$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 858, "Code" => "UYU", "Name" => "Uruguayan Peso", "Symbol" => "$U", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centésimo", "MajorPlural" => "Pesos", "MinorPlural" => "Centésimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 860, "Code" => "UZS", "Name" => "Uzbekistani Som", "Symbol" => "сум", "NativeSymbol" => "сум", "MajorSingle" => "Som", "MinorSingle" => "Tiyin", "MajorPlural" => "Som", "MinorPlural" => "Tiyin", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 882, "Code" => "WST", "Name" => "Samoan Tala", "Symbol" => "T", "NativeSymbol" => "ST", "MajorSingle" => "Tala", "MinorSingle" => "Sene", "MajorPlural" => "Tala", "MinorPlural" => "Sene", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 886, "Code" => "YER", "Name" => "Yemeni Rial", "Symbol" => "YR", "NativeSymbol" => "ر.ي.", "MajorSingle" => "Rial", "MinorSingle" => "Fils", "MajorPlural" => "Rials", "MinorPlural" => "Fils", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 901, "Code" => "TWD", "Name" => "New Taiwan Dollar", "Symbol" => "NT$", "NativeSymbol" => "圓", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 928, "Code" => "VES", "Name" => "Venezuelan Bolívar Soberano", "Symbol" => "Bs.F", "NativeSymbol" => "Bs.F", "MajorSingle" => "Bolívar", "MinorSingle" => "Centimo", "MajorPlural" => "Bolívares", "MinorPlural" => "Centimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 929, "Code" => "MRU", "Name" => "Mauritanian Ouguiya", "Symbol" => "UM", "NativeSymbol" => "أ.م.", "MajorSingle" => "Ouguiya", "MinorSingle" => "Khoums", "MajorPlural" => "Ouguiya", "MinorPlural" => "Khoums", "Digits" => 2, "Decimals" => 0, "NumToBasic" => 5),
    array ( "ISO4217" => 930, "Code" => "STN", "Name" => "Sao Tome and Príncipe Dobra", "Symbol" => "Db", "NativeSymbol" => "Db", "MajorSingle" => "Dobra", "MinorSingle" => "Centimo", "MajorPlural" => "Dobras", "MinorPlural" => "Centimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 931, "Code" => "CUC", "Name" => "Cuban convertible Peso", "Symbol" => "CUC$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 932, "Code" => "ZWL", "Name" => "Zimbabwean Dollar", "Symbol" => "Z$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 933, "Code" => "BYN", "Name" => "Belarusian Ruble", "Symbol" => "Br", "NativeSymbol" => "руб.", "MajorSingle" => "Ruble", "MinorSingle" => "Kapiejka", "MajorPlural" => "Rubles", "MinorPlural" => "Kapiejka", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 934, "Code" => "TMT", "Name" => "Turkmenistan Manat", "Symbol" => "m.", "NativeSymbol" => "T", "MajorSingle" => "Manat", "MinorSingle" => "Tenge", "MajorPlural" => "Manat", "MinorPlural" => "Tenge", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 936, "Code" => "GHS", "Name" => "Ghanaian Cedi", "Symbol" => "GH₵", "NativeSymbol" => "₵", "MajorSingle" => "Cedi", "MinorSingle" => "Pesewa", "MajorPlural" => "Cedis", "MinorPlural" => "Pesewas", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 938, "Code" => "SDG", "Name" => "Sudanese Pound", "Symbol" => "£SD", "NativeSymbol" => "ج.س.", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 941, "Code" => "RSD", "Name" => "Serbian Dinar", "Symbol" => "din", "NativeSymbol" => "дин", "MajorSingle" => "Dinar", "MinorSingle" => "Para", "MajorPlural" => "Dinars", "MinorPlural" => "Para", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 943, "Code" => "MZN", "Name" => "Mozambican Metical", "Symbol" => "MTn", "NativeSymbol" => "MT", "MajorSingle" => "Metical", "MinorSingle" => "Centavo", "MajorPlural" => "Meticais", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 944, "Code" => "AZN", "Name" => "Azerbaijani Manat", "Symbol" => "ман", "NativeSymbol" => "₼", "MajorSingle" => "Manat", "MinorSingle" => "Qapik", "MajorPlural" => "Manat", "MinorPlural" => "Qapik", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 946, "Code" => "RON", "Name" => "Romanian Leu", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Leu", "MinorSingle" => "Ban", "MajorPlural" => "Lei", "MinorPlural" => "Bani", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 949, "Code" => "TRY", "Name" => "Turkish Lira", "Symbol" => "TL", "NativeSymbol" => "₺", "MajorSingle" => "Lira", "MinorSingle" => "Kuruş", "MajorPlural" => "Lira", "MinorPlural" => "Kuruş", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 950, "Code" => "XAF", "Name" => "Central African CFA Franc BEAC", "Symbol" => "Fr", "NativeSymbol" => "Fr.", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 951, "Code" => "XCD", "Name" => "East Caribbean Dollar", "Symbol" => "$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 952, "Code" => "XOF", "Name" => "West African CFA Franc BCEAO", "Symbol" => "₣", "NativeSymbol" => "₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 953, "Code" => "XPF", "Name" => "CFP Franc (Franc Pacifique)", "Symbol" => "₣", "NativeSymbol" => "₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 0, "NumToBasic" => 100),
    array ( "ISO4217" => 967, "Code" => "ZMW", "Name" => "Zambian Kwacha", "Symbol" => "ZK", "NativeSymbol" => "ZK", "MajorSingle" => "Kwacha", "MinorSingle" => "Ngwee", "MajorPlural" => "Kwacha", "MinorPlural" => "Ngwee", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 968, "Code" => "SRD", "Name" => "Surinamese Dollar", "Symbol" => "Sr$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 969, "Code" => "MGA", "Name" => "Malagasy Ariary", "Symbol" => "Ar", "NativeSymbol" => "Ar", "MajorSingle" => "Ariary", "MinorSingle" => "Iraimbilanja", "MajorPlural" => "Ariary", "MinorPlural" => "Iraimbilanja", "Digits" => 2, "Decimals" => 0, "NumToBasic" => 5),
    array ( "ISO4217" => 971, "Code" => "AFN", "Name" => "Afghan Afghani", "Symbol" => "Af", "NativeSymbol" => "؋", "MajorSingle" => "Afghani", "MinorSingle" => "Pul", "MajorPlural" => "Afghani", "MinorPlural" => "Pul", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 972, "Code" => "TJS", "Name" => "Tajikistani Somoni", "Symbol" => "SM", "NativeSymbol" => "SM", "MajorSingle" => "Somoni", "MinorSingle" => "Diram", "MajorPlural" => "Somoni", "MinorPlural" => "Diram", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 973, "Code" => "AOA", "Name" => "Angolan Kwanza", "Symbol" => "Kz", "NativeSymbol" => "Kz", "MajorSingle" => "Kwanza", "MinorSingle" => "Centimo", "MajorPlural" => "Kwanza", "MinorPlural" => "Centimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 975, "Code" => "BGN", "Name" => "Bulgarian Lev", "Symbol" => "лв.", "NativeSymbol" => "лв.", "MajorSingle" => "Lev", "MinorSingle" => "Stotinka", "MajorPlural" => "Leva", "MinorPlural" => "Stotinki", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 976, "Code" => "CDF", "Name" => "Congolese Franc", "Symbol" => "FC", "NativeSymbol" => "₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 977, "Code" => "BAM", "Name" => "Bosnia and Herzegovina Convertible Mark", "Symbol" => "KM", "NativeSymbol" => "КМ", "MajorSingle" => "Convertible Mark", "MinorSingle" => "Fening", "MajorPlural" => "Marks", "MinorPlural" => "Fening", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 978, "Code" => "EUR", "Name" => "Euro", "Symbol" => "€", "NativeSymbol" => "€", "MajorSingle" => "Euro", "MinorSingle" => "Cent", "MajorPlural" => "Euros", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 980, "Code" => "UAH", "Name" => "Ukrainian Hryvnia", "Symbol" => "₴", "NativeSymbol" => "грн", "MajorSingle" => "Hryvnia", "MinorSingle" => "Kopiyka", "MajorPlural" => "Hryvnias", "MinorPlural" => "kopiyky", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 981, "Code" => "GEL", "Name" => "Georgian Lari", "Symbol" => "₾", "NativeSymbol" => "₾", "MajorSingle" => "Lari", "MinorSingle" => "Tetri", "MajorPlural" => "Lari", "MinorPlural" => "Tetri", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 985, "Code" => "PLN", "Name" => "Polish Zloty", "Symbol" => "zł", "NativeSymbol" => "zł", "MajorSingle" => "Zloty", "MinorSingle" => "Grosz", "MajorPlural" => "Zlotys", "MinorPlural" => "Groszy", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 986, "Code" => "BRL", "Name" => "Brazilian Real", "Symbol" => "R$", "NativeSymbol" => "R$", "MajorSingle" => "Real", "MinorSingle" => "Centavo", "MajorPlural" => "Reais", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100)
  ));
  install_add_db_data ( "Countries", array (
    array ( "Code" => 4, "Name" => "Afghanistan", "Alpha2" => "AF", "Alpha3" => "AFG", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Southern Asia", "SubRegionCode" => 34, "ISO3166-2" => "AF", "Currency" => 971),
    array ( "Code" => 8, "Name" => "Albania", "Alpha2" => "AL", "Alpha3" => "ALB", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "AL", "Currency" => 8),
    array ( "Code" => 10, "Name" => "Antarctica", "Alpha2" => "AQ", "Alpha3" => "ATA", "Region" => "", "RegionCode" => 0, "SubRegion" => "", "SubRegionCode" => 0, "ISO3166-2" => "AQ", "Currency" => 0),
    array ( "Code" => 12, "Name" => "Algeria", "Alpha2" => "DZ", "Alpha3" => "DZA", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Northern Africa", "SubRegionCode" => 15, "ISO3166-2" => "DZ", "Currency" => 12),
    array ( "Code" => 16, "Name" => "American Samoa", "Alpha2" => "AS", "Alpha3" => "ASM", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "AS", "Currency" => 840),
    array ( "Code" => 20, "Name" => "Andorra", "Alpha2" => "AD", "Alpha3" => "AND", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "AD", "Currency" => 978),
    array ( "Code" => 24, "Name" => "Angola", "Alpha2" => "AO", "Alpha3" => "AGO", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Middle Africa", "SubRegionCode" => 17, "ISO3166-2" => "AO", "Currency" => 973),
    array ( "Code" => 28, "Name" => "Antigua and Barbuda", "Alpha2" => "AG", "Alpha3" => "ATG", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "AG", "Currency" => 951),
    array ( "Code" => 31, "Name" => "Azerbaijan", "Alpha2" => "AZ", "Alpha3" => "AZE", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "AZ", "Currency" => 944),
    array ( "Code" => 32, "Name" => "Argentina", "Alpha2" => "AR", "Alpha3" => "ARG", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "AR", "Currency" => 32),
    array ( "Code" => 36, "Name" => "Australia", "Alpha2" => "AU", "Alpha3" => "AUS", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Australia and New Zealand", "SubRegionCode" => 53, "ISO3166-2" => "AU", "Currency" => 36),
    array ( "Code" => 40, "Name" => "Austria", "Alpha2" => "AT", "Alpha3" => "AUT", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Western Europe", "SubRegionCode" => 155, "ISO3166-2" => "AT", "Currency" => 978),
    array ( "Code" => 44, "Name" => "Bahamas", "Alpha2" => "BS", "Alpha3" => "BHS", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "BS", "Currency" => 44),
    array ( "Code" => 48, "Name" => "Bahrain", "Alpha2" => "BH", "Alpha3" => "BHR", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "BH", "Currency" => 48),
    array ( "Code" => 50, "Name" => "Bangladesh", "Alpha2" => "BD", "Alpha3" => "BGD", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Southern Asia", "SubRegionCode" => 34, "ISO3166-2" => "BD", "Currency" => 50),
    array ( "Code" => 51, "Name" => "Armenia", "Alpha2" => "AM", "Alpha3" => "ARM", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "AM", "Currency" => 51),
    array ( "Code" => 52, "Name" => "Barbados", "Alpha2" => "BB", "Alpha3" => "BRB", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "BB", "Currency" => 52),
    array ( "Code" => 56, "Name" => "Belgium", "Alpha2" => "BE", "Alpha3" => "BEL", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Western Europe", "SubRegionCode" => 155, "ISO3166-2" => "BE", "Currency" => 978),
    array ( "Code" => 60, "Name" => "Bermuda", "Alpha2" => "BM", "Alpha3" => "BMU", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Northern America", "SubRegionCode" => 21, "ISO3166-2" => "BM", "Currency" => 60),
    array ( "Code" => 64, "Name" => "Bhutan", "Alpha2" => "BT", "Alpha3" => "BTN", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Southern Asia", "SubRegionCode" => 34, "ISO3166-2" => "BT", "Currency" => 64),
    array ( "Code" => 68, "Name" => "Bolivia (Plurinational State of)", "Alpha2" => "BO", "Alpha3" => "BOL", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "BO", "Currency" => 68),
    array ( "Code" => 70, "Name" => "Bosnia and Herzegovina", "Alpha2" => "BA", "Alpha3" => "BIH", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "BA", "Currency" => 977),
    array ( "Code" => 72, "Name" => "Botswana", "Alpha2" => "BW", "Alpha3" => "BWA", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Southern Africa", "SubRegionCode" => 18, "ISO3166-2" => "BW", "Currency" => 72),
    array ( "Code" => 74, "Name" => "Bouvet Island", "Alpha2" => "BV", "Alpha3" => "BVT", "Region" => "", "RegionCode" => 0, "SubRegion" => "", "SubRegionCode" => 0, "ISO3166-2" => "BV", "Currency" => 578),
    array ( "Code" => 76, "Name" => "Brazil", "Alpha2" => "BR", "Alpha3" => "BRA", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "BR", "Currency" => 986),
    array ( "Code" => 84, "Name" => "Belize", "Alpha2" => "BZ", "Alpha3" => "BLZ", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Central America", "SubRegionCode" => 13, "ISO3166-2" => "BZ", "Currency" => 84),
    array ( "Code" => 86, "Name" => "British Indian Ocean Territory", "Alpha2" => "IO", "Alpha3" => "IOT", "Region" => "", "RegionCode" => 0, "SubRegion" => "", "SubRegionCode" => 0, "ISO3166-2" => "IO", "Currency" => 840),
    array ( "Code" => 90, "Name" => "Solomon Islands", "Alpha2" => "SB", "Alpha3" => "SLB", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Melanesia", "SubRegionCode" => 54, "ISO3166-2" => "SB", "Currency" => 90),
    array ( "Code" => 92, "Name" => "Virgin Islands (British)", "Alpha2" => "VG", "Alpha3" => "VGB", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "VG", "Currency" => 840),
    array ( "Code" => 96, "Name" => "Brunei Darussalam", "Alpha2" => "BN", "Alpha3" => "BRN", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "BN", "Currency" => 96),
    array ( "Code" => 100, "Name" => "Bulgaria", "Alpha2" => "BG", "Alpha3" => "BGR", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "BG", "Currency" => 975),
    array ( "Code" => 104, "Name" => "Myanmar", "Alpha2" => "MM", "Alpha3" => "MMR", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "MM", "Currency" => 104),
    array ( "Code" => 108, "Name" => "Burundi", "Alpha2" => "BI", "Alpha3" => "BDI", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "BI", "Currency" => 108),
    array ( "Code" => 112, "Name" => "Belarus", "Alpha2" => "BY", "Alpha3" => "BLR", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "BY", "Currency" => 933),
    array ( "Code" => 116, "Name" => "Cambodia", "Alpha2" => "KH", "Alpha3" => "KHM", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "KH", "Currency" => 116),
    array ( "Code" => 120, "Name" => "Cameroon", "Alpha2" => "CM", "Alpha3" => "CMR", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Middle Africa", "SubRegionCode" => 17, "ISO3166-2" => "CM", "Currency" => 950),
    array ( "Code" => 124, "Name" => "Canada", "Alpha2" => "CA", "Alpha3" => "CAN", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Northern America", "SubRegionCode" => 21, "ISO3166-2" => "CA", "Currency" => 124),
    array ( "Code" => 132, "Name" => "Cabo Verde", "Alpha2" => "CV", "Alpha3" => "CPV", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "CV", "Currency" => 132),
    array ( "Code" => 136, "Name" => "Cayman Islands", "Alpha2" => "KY", "Alpha3" => "CYM", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "KY", "Currency" => 136),
    array ( "Code" => 140, "Name" => "Central African Republic", "Alpha2" => "CF", "Alpha3" => "CAF", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Middle Africa", "SubRegionCode" => 17, "ISO3166-2" => "CF", "Currency" => 950),
    array ( "Code" => 144, "Name" => "Sri Lanka", "Alpha2" => "LK", "Alpha3" => "LKA", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Southern Asia", "SubRegionCode" => 34, "ISO3166-2" => "LK", "Currency" => 144),
    array ( "Code" => 148, "Name" => "Chad", "Alpha2" => "TD", "Alpha3" => "TCD", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Middle Africa", "SubRegionCode" => 17, "ISO3166-2" => "TD", "Currency" => 950),
    array ( "Code" => 152, "Name" => "Chile", "Alpha2" => "CL", "Alpha3" => "CHL", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "CL", "Currency" => 152),
    array ( "Code" => 156, "Name" => "China", "Alpha2" => "CN", "Alpha3" => "CHN", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Eastern Asia", "SubRegionCode" => 30, "ISO3166-2" => "CN", "Currency" => 156),
    array ( "Code" => 158, "Name" => "Taiwan, Province of China", "Alpha2" => "TW", "Alpha3" => "TWN", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Eastern Asia", "SubRegionCode" => 30, "ISO3166-2" => "TW", "Currency" => 901),
    array ( "Code" => 162, "Name" => "Christmas Island", "Alpha2" => "CX", "Alpha3" => "CXR", "Region" => "", "RegionCode" => 0, "SubRegion" => "", "SubRegionCode" => 0, "ISO3166-2" => "CX", "Currency" => 36),
    array ( "Code" => 166, "Name" => "Cocos (Keeling) Islands", "Alpha2" => "CC", "Alpha3" => "CCK", "Region" => "", "RegionCode" => 0, "SubRegion" => "", "SubRegionCode" => 0, "ISO3166-2" => "CC", "Currency" => 36),
    array ( "Code" => 170, "Name" => "Colombia", "Alpha2" => "CO", "Alpha3" => "COL", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "CO", "Currency" => 170),
    array ( "Code" => 174, "Name" => "Comoros", "Alpha2" => "KM", "Alpha3" => "COM", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "KM", "Currency" => 174),
    array ( "Code" => 175, "Name" => "Mayotte", "Alpha2" => "YT", "Alpha3" => "MYT", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "YT", "Currency" => 978),
    array ( "Code" => 178, "Name" => "Congo", "Alpha2" => "CG", "Alpha3" => "COG", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Middle Africa", "SubRegionCode" => 17, "ISO3166-2" => "CG", "Currency" => 950),
    array ( "Code" => 180, "Name" => "Congo (Democratic Republic of the)", "Alpha2" => "CD", "Alpha3" => "COD", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Middle Africa", "SubRegionCode" => 17, "ISO3166-2" => "CD", "Currency" => 976),
    array ( "Code" => 184, "Name" => "Cook Islands", "Alpha2" => "CK", "Alpha3" => "COK", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "CK", "Currency" => 554),
    array ( "Code" => 188, "Name" => "Costa Rica", "Alpha2" => "CR", "Alpha3" => "CRI", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Central America", "SubRegionCode" => 13, "ISO3166-2" => "CR", "Currency" => 188),
    array ( "Code" => 191, "Name" => "Croatia", "Alpha2" => "HR", "Alpha3" => "HRV", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "HR", "Currency" => 978),
    array ( "Code" => 192, "Name" => "Cuba", "Alpha2" => "CU", "Alpha3" => "CUB", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "CU", "Currency" => 931),
    array ( "Code" => 196, "Name" => "Cyprus", "Alpha2" => "CY", "Alpha3" => "CYP", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "CY", "Currency" => 978),
    array ( "Code" => 203, "Name" => "Czech Republic", "Alpha2" => "CZ", "Alpha3" => "CZE", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "CZ", "Currency" => 203),
    array ( "Code" => 204, "Name" => "Benin", "Alpha2" => "BJ", "Alpha3" => "BEN", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "BJ", "Currency" => 952),
    array ( "Code" => 208, "Name" => "Denmark", "Alpha2" => "DK", "Alpha3" => "DNK", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "DK", "Currency" => 208),
    array ( "Code" => 212, "Name" => "Dominica", "Alpha2" => "DM", "Alpha3" => "DMA", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "DM", "Currency" => 951),
    array ( "Code" => 214, "Name" => "Dominican Republic", "Alpha2" => "DO", "Alpha3" => "DOM", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "DO", "Currency" => 214),
    array ( "Code" => 218, "Name" => "Ecuador", "Alpha2" => "EC", "Alpha3" => "ECU", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "EC", "Currency" => 840),
    array ( "Code" => 222, "Name" => "El Salvador", "Alpha2" => "SV", "Alpha3" => "SLV", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Central America", "SubRegionCode" => 13, "ISO3166-2" => "SV", "Currency" => 840),
    array ( "Code" => 226, "Name" => "Equatorial Guinea", "Alpha2" => "GQ", "Alpha3" => "GNQ", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Middle Africa", "SubRegionCode" => 17, "ISO3166-2" => "GQ", "Currency" => 950),
    array ( "Code" => 231, "Name" => "Ethiopia", "Alpha2" => "ET", "Alpha3" => "ETH", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "ET", "Currency" => 230),
    array ( "Code" => 232, "Name" => "Eritrea", "Alpha2" => "ER", "Alpha3" => "ERI", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "ER", "Currency" => 232),
    array ( "Code" => 233, "Name" => "Estonia", "Alpha2" => "EE", "Alpha3" => "EST", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "EE", "Currency" => 978),
    array ( "Code" => 234, "Name" => "Faroe Islands", "Alpha2" => "FO", "Alpha3" => "FRO", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "FO", "Currency" => 208),
    array ( "Code" => 238, "Name" => "Falkland Islands (Malvinas)", "Alpha2" => "FK", "Alpha3" => "FLK", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "FK", "Currency" => 238),
    array ( "Code" => 239, "Name" => "South Georgia and the South Sandwich Islands", "Alpha2" => "GS", "Alpha3" => "SGS", "Region" => "", "RegionCode" => 0, "SubRegion" => "", "SubRegionCode" => 0, "ISO3166-2" => "GS", "Currency" => 0),
    array ( "Code" => 242, "Name" => "Fiji", "Alpha2" => "FJ", "Alpha3" => "FJI", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Melanesia", "SubRegionCode" => 54, "ISO3166-2" => "FJ", "Currency" => 242),
    array ( "Code" => 246, "Name" => "Finland", "Alpha2" => "FI", "Alpha3" => "FIN", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "FI", "Currency" => 978),
    array ( "Code" => 248, "Name" => "Åland Islands", "Alpha2" => "AX", "Alpha3" => "ALA", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "AX", "Currency" => 978),
    array ( "Code" => 250, "Name" => "France", "Alpha2" => "FR", "Alpha3" => "FRA", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Western Europe", "SubRegionCode" => 155, "ISO3166-2" => "FR", "Currency" => 978),
    array ( "Code" => 254, "Name" => "French Guiana", "Alpha2" => "GF", "Alpha3" => "GUF", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "GF", "Currency" => 978),
    array ( "Code" => 258, "Name" => "French Polynesia", "Alpha2" => "PF", "Alpha3" => "PYF", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "PF", "Currency" => 953),
    array ( "Code" => 260, "Name" => "French Southern Territories", "Alpha2" => "TF", "Alpha3" => "ATF", "Region" => "", "RegionCode" => 0, "SubRegion" => "", "SubRegionCode" => 0, "ISO3166-2" => "TF", "Currency" => 978),
    array ( "Code" => 262, "Name" => "Djibouti", "Alpha2" => "DJ", "Alpha3" => "DJI", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "DJ", "Currency" => 262),
    array ( "Code" => 266, "Name" => "Gabon", "Alpha2" => "GA", "Alpha3" => "GAB", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Middle Africa", "SubRegionCode" => 17, "ISO3166-2" => "GA", "Currency" => 950),
    array ( "Code" => 268, "Name" => "Georgia", "Alpha2" => "GE", "Alpha3" => "GEO", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "GE", "Currency" => 981),
    array ( "Code" => 270, "Name" => "Gambia", "Alpha2" => "GM", "Alpha3" => "GMB", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "GM", "Currency" => 270),
    array ( "Code" => 275, "Name" => "Palestine, State of", "Alpha2" => "PS", "Alpha3" => "PSE", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "PS", "Currency" => 0),
    array ( "Code" => 276, "Name" => "Germany", "Alpha2" => "DE", "Alpha3" => "DEU", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Western Europe", "SubRegionCode" => 155, "ISO3166-2" => "DE", "Currency" => 978),
    array ( "Code" => 288, "Name" => "Ghana", "Alpha2" => "GH", "Alpha3" => "GHA", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "GH", "Currency" => 936),
    array ( "Code" => 292, "Name" => "Gibraltar", "Alpha2" => "GI", "Alpha3" => "GIB", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "GI", "Currency" => 292),
    array ( "Code" => 296, "Name" => "Kiribati", "Alpha2" => "KI", "Alpha3" => "KIR", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Micronesia", "SubRegionCode" => 57, "ISO3166-2" => "KI", "Currency" => 36),
    array ( "Code" => 300, "Name" => "Greece", "Alpha2" => "GR", "Alpha3" => "GRC", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "GR", "Currency" => 978),
    array ( "Code" => 304, "Name" => "Greenland", "Alpha2" => "GL", "Alpha3" => "GRL", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Northern America", "SubRegionCode" => 21, "ISO3166-2" => "GL", "Currency" => 208),
    array ( "Code" => 308, "Name" => "Grenada", "Alpha2" => "GD", "Alpha3" => "GRD", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "GD", "Currency" => 951),
    array ( "Code" => 312, "Name" => "Guadeloupe", "Alpha2" => "GP", "Alpha3" => "GLP", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "GP", "Currency" => 978),
    array ( "Code" => 316, "Name" => "Guam", "Alpha2" => "GU", "Alpha3" => "GUM", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Micronesia", "SubRegionCode" => 57, "ISO3166-2" => "GU", "Currency" => 840),
    array ( "Code" => 320, "Name" => "Guatemala", "Alpha2" => "GT", "Alpha3" => "GTM", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Central America", "SubRegionCode" => 13, "ISO3166-2" => "GT", "Currency" => 320),
    array ( "Code" => 324, "Name" => "Guinea", "Alpha2" => "GN", "Alpha3" => "GIN", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "GN", "Currency" => 324),
    array ( "Code" => 328, "Name" => "Guyana", "Alpha2" => "GY", "Alpha3" => "GUY", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "GY", "Currency" => 328),
    array ( "Code" => 332, "Name" => "Haiti", "Alpha2" => "HT", "Alpha3" => "HTI", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "HT", "Currency" => 840),
    array ( "Code" => 334, "Name" => "Heard Island and McDonald Islands", "Alpha2" => "HM", "Alpha3" => "HMD", "Region" => "", "RegionCode" => 0, "SubRegion" => "", "SubRegionCode" => 0, "ISO3166-2" => "HM", "Currency" => 36),
    array ( "Code" => 336, "Name" => "Holy See", "Alpha2" => "VA", "Alpha3" => "VAT", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "VA", "Currency" => 978),
    array ( "Code" => 340, "Name" => "Honduras", "Alpha2" => "HN", "Alpha3" => "HND", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Central America", "SubRegionCode" => 13, "ISO3166-2" => "HN", "Currency" => 340),
    array ( "Code" => 344, "Name" => "Hong Kong", "Alpha2" => "HK", "Alpha3" => "HKG", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Eastern Asia", "SubRegionCode" => 30, "ISO3166-2" => "HK", "Currency" => 344),
    array ( "Code" => 348, "Name" => "Hungary", "Alpha2" => "HU", "Alpha3" => "HUN", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "HU", "Currency" => 348),
    array ( "Code" => 352, "Name" => "Iceland", "Alpha2" => "IS", "Alpha3" => "ISL", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "IS", "Currency" => 352),
    array ( "Code" => 356, "Name" => "India", "Alpha2" => "IN", "Alpha3" => "IND", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Southern Asia", "SubRegionCode" => 34, "ISO3166-2" => "IN", "Currency" => 356),
    array ( "Code" => 360, "Name" => "Indonesia", "Alpha2" => "ID", "Alpha3" => "IDN", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "ID", "Currency" => 360),
    array ( "Code" => 364, "Name" => "Iran (Islamic Republic of)", "Alpha2" => "IR", "Alpha3" => "IRN", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Southern Asia", "SubRegionCode" => 34, "ISO3166-2" => "IR", "Currency" => 364),
    array ( "Code" => 368, "Name" => "Iraq", "Alpha2" => "IQ", "Alpha3" => "IRQ", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "IQ", "Currency" => 368),
    array ( "Code" => 372, "Name" => "Ireland", "Alpha2" => "IE", "Alpha3" => "IRL", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "IE", "Currency" => 978),
    array ( "Code" => 376, "Name" => "Israel", "Alpha2" => "IL", "Alpha3" => "ISR", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "IL", "Currency" => 376),
    array ( "Code" => 380, "Name" => "Italy", "Alpha2" => "IT", "Alpha3" => "ITA", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "IT", "Currency" => 978),
    array ( "Code" => 384, "Name" => "Côte d'Ivoire", "Alpha2" => "CI", "Alpha3" => "CIV", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "CI", "Currency" => 952),
    array ( "Code" => 388, "Name" => "Jamaica", "Alpha2" => "JM", "Alpha3" => "JAM", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "JM", "Currency" => 388),
    array ( "Code" => 392, "Name" => "Japan", "Alpha2" => "JP", "Alpha3" => "JPN", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Eastern Asia", "SubRegionCode" => 30, "ISO3166-2" => "JP", "Currency" => 392),
    array ( "Code" => 398, "Name" => "Kazakhstan", "Alpha2" => "KZ", "Alpha3" => "KAZ", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Central Asia", "SubRegionCode" => 143, "ISO3166-2" => "KZ", "Currency" => 398),
    array ( "Code" => 400, "Name" => "Jordan", "Alpha2" => "JO", "Alpha3" => "JOR", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "JO", "Currency" => 400),
    array ( "Code" => 404, "Name" => "Kenya", "Alpha2" => "KE", "Alpha3" => "KEN", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "KE", "Currency" => 404),
    array ( "Code" => 408, "Name" => "Korea (Democratic People's Republic of)", "Alpha2" => "KP", "Alpha3" => "PRK", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Eastern Asia", "SubRegionCode" => 30, "ISO3166-2" => "KP", "Currency" => 408),
    array ( "Code" => 410, "Name" => "Korea (Republic of)", "Alpha2" => "KR", "Alpha3" => "KOR", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Eastern Asia", "SubRegionCode" => 30, "ISO3166-2" => "KR", "Currency" => 410),
    array ( "Code" => 414, "Name" => "Kuwait", "Alpha2" => "KW", "Alpha3" => "KWT", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "KW", "Currency" => 414),
    array ( "Code" => 417, "Name" => "Kyrgyzstan", "Alpha2" => "KG", "Alpha3" => "KGZ", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Central Asia", "SubRegionCode" => 143, "ISO3166-2" => "KG", "Currency" => 417),
    array ( "Code" => 418, "Name" => "Lao People's Democratic Republic", "Alpha2" => "LA", "Alpha3" => "LAO", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "LA", "Currency" => 418),
    array ( "Code" => 422, "Name" => "Lebanon", "Alpha2" => "LB", "Alpha3" => "LBN", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "LB", "Currency" => 422),
    array ( "Code" => 426, "Name" => "Lesotho", "Alpha2" => "LS", "Alpha3" => "LSO", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Southern Africa", "SubRegionCode" => 18, "ISO3166-2" => "LS", "Currency" => 710),
    array ( "Code" => 428, "Name" => "Latvia", "Alpha2" => "LV", "Alpha3" => "LVA", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "LV", "Currency" => 978),
    array ( "Code" => 430, "Name" => "Liberia", "Alpha2" => "LR", "Alpha3" => "LBR", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "LR", "Currency" => 430),
    array ( "Code" => 434, "Name" => "Libya", "Alpha2" => "LY", "Alpha3" => "LBY", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Northern Africa", "SubRegionCode" => 15, "ISO3166-2" => "LY", "Currency" => 434),
    array ( "Code" => 438, "Name" => "Liechtenstein", "Alpha2" => "LI", "Alpha3" => "LIE", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Western Europe", "SubRegionCode" => 155, "ISO3166-2" => "LI", "Currency" => 756),
    array ( "Code" => 440, "Name" => "Lithuania", "Alpha2" => "LT", "Alpha3" => "LTU", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "LT", "Currency" => 978),
    array ( "Code" => 442, "Name" => "Luxembourg", "Alpha2" => "LU", "Alpha3" => "LUX", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Western Europe", "SubRegionCode" => 155, "ISO3166-2" => "LU", "Currency" => 978),
    array ( "Code" => 446, "Name" => "Macao", "Alpha2" => "MO", "Alpha3" => "MAC", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Eastern Asia", "SubRegionCode" => 30, "ISO3166-2" => "MO", "Currency" => 446),
    array ( "Code" => 450, "Name" => "Madagascar", "Alpha2" => "MG", "Alpha3" => "MDG", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "MG", "Currency" => 969),
    array ( "Code" => 454, "Name" => "Malawi", "Alpha2" => "MW", "Alpha3" => "MWI", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "MW", "Currency" => 454),
    array ( "Code" => 458, "Name" => "Malaysia", "Alpha2" => "MY", "Alpha3" => "MYS", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "MY", "Currency" => 458),
    array ( "Code" => 462, "Name" => "Maldives", "Alpha2" => "MV", "Alpha3" => "MDV", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Southern Asia", "SubRegionCode" => 34, "ISO3166-2" => "MV", "Currency" => 462),
    array ( "Code" => 466, "Name" => "Mali", "Alpha2" => "ML", "Alpha3" => "MLI", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "ML", "Currency" => 952),
    array ( "Code" => 470, "Name" => "Malta", "Alpha2" => "MT", "Alpha3" => "MLT", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "MT", "Currency" => 978),
    array ( "Code" => 474, "Name" => "Martinique", "Alpha2" => "MQ", "Alpha3" => "MTQ", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "MQ", "Currency" => 978),
    array ( "Code" => 478, "Name" => "Mauritania", "Alpha2" => "MR", "Alpha3" => "MRT", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "MR", "Currency" => 929),
    array ( "Code" => 480, "Name" => "Mauritius", "Alpha2" => "MU", "Alpha3" => "MUS", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "MU", "Currency" => 480),
    array ( "Code" => 484, "Name" => "Mexico", "Alpha2" => "MX", "Alpha3" => "MEX", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Central America", "SubRegionCode" => 13, "ISO3166-2" => "MX", "Currency" => 484),
    array ( "Code" => 492, "Name" => "Monaco", "Alpha2" => "MC", "Alpha3" => "MCO", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Western Europe", "SubRegionCode" => 155, "ISO3166-2" => "MC", "Currency" => 978),
    array ( "Code" => 496, "Name" => "Mongolia", "Alpha2" => "MN", "Alpha3" => "MNG", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Eastern Asia", "SubRegionCode" => 30, "ISO3166-2" => "MN", "Currency" => 496),
    array ( "Code" => 498, "Name" => "Moldova (Republic of)", "Alpha2" => "MD", "Alpha3" => "MDA", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "MD", "Currency" => 498),
    array ( "Code" => 499, "Name" => "Montenegro", "Alpha2" => "ME", "Alpha3" => "MNE", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "ME", "Currency" => 978),
    array ( "Code" => 500, "Name" => "Montserrat", "Alpha2" => "MS", "Alpha3" => "MSR", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "MS", "Currency" => 951),
    array ( "Code" => 504, "Name" => "Morocco", "Alpha2" => "MA", "Alpha3" => "MAR", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Northern Africa", "SubRegionCode" => 15, "ISO3166-2" => "MA", "Currency" => 504),
    array ( "Code" => 508, "Name" => "Mozambique", "Alpha2" => "MZ", "Alpha3" => "MOZ", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "MZ", "Currency" => 943),
    array ( "Code" => 512, "Name" => "Oman", "Alpha2" => "OM", "Alpha3" => "OMN", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "OM", "Currency" => 512),
    array ( "Code" => 516, "Name" => "Namibia", "Alpha2" => "NA", "Alpha3" => "NAM", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Southern Africa", "SubRegionCode" => 18, "ISO3166-2" => "NA", "Currency" => 710),
    array ( "Code" => 520, "Name" => "Nauru", "Alpha2" => "NR", "Alpha3" => "NRU", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Micronesia", "SubRegionCode" => 57, "ISO3166-2" => "NR", "Currency" => 36),
    array ( "Code" => 524, "Name" => "Nepal", "Alpha2" => "NP", "Alpha3" => "NPL", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Southern Asia", "SubRegionCode" => 34, "ISO3166-2" => "NP", "Currency" => 524),
    array ( "Code" => 528, "Name" => "Netherlands", "Alpha2" => "NL", "Alpha3" => "NLD", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Western Europe", "SubRegionCode" => 155, "ISO3166-2" => "NL", "Currency" => 978),
    array ( "Code" => 531, "Name" => "Curaçao", "Alpha2" => "CW", "Alpha3" => "CUW", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "CW", "Currency" => 532),
    array ( "Code" => 533, "Name" => "Aruba", "Alpha2" => "AW", "Alpha3" => "ABW", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "AW", "Currency" => 533),
    array ( "Code" => 534, "Name" => "Sint Maarten (Dutch part)", "Alpha2" => "SX", "Alpha3" => "SXM", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "SX", "Currency" => 532),
    array ( "Code" => 535, "Name" => "Bonaire, Sint Eustatius and Saba", "Alpha2" => "BQ", "Alpha3" => "BES", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "BQ", "Currency" => 840),
    array ( "Code" => 540, "Name" => "New Caledonia", "Alpha2" => "NC", "Alpha3" => "NCL", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Melanesia", "SubRegionCode" => 54, "ISO3166-2" => "NC", "Currency" => 953),
    array ( "Code" => 548, "Name" => "Vanuatu", "Alpha2" => "VU", "Alpha3" => "VUT", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Melanesia", "SubRegionCode" => 54, "ISO3166-2" => "VU", "Currency" => 548),
    array ( "Code" => 554, "Name" => "New Zealand", "Alpha2" => "NZ", "Alpha3" => "NZL", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Australia and New Zealand", "SubRegionCode" => 53, "ISO3166-2" => "NZ", "Currency" => 554),
    array ( "Code" => 558, "Name" => "Nicaragua", "Alpha2" => "NI", "Alpha3" => "NIC", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Central America", "SubRegionCode" => 13, "ISO3166-2" => "NI", "Currency" => 558),
    array ( "Code" => 562, "Name" => "Niger", "Alpha2" => "NE", "Alpha3" => "NER", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "NE", "Currency" => 952),
    array ( "Code" => 566, "Name" => "Nigeria", "Alpha2" => "NG", "Alpha3" => "NGA", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "NG", "Currency" => 566),
    array ( "Code" => 570, "Name" => "Niue", "Alpha2" => "NU", "Alpha3" => "NIU", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "NU", "Currency" => 554),
    array ( "Code" => 574, "Name" => "Norfolk Island", "Alpha2" => "NF", "Alpha3" => "NFK", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Australia and New Zealand", "SubRegionCode" => 53, "ISO3166-2" => "NF", "Currency" => 36),
    array ( "Code" => 578, "Name" => "Norway", "Alpha2" => "NO", "Alpha3" => "NOR", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "NO", "Currency" => 578),
    array ( "Code" => 580, "Name" => "Northern Mariana Islands", "Alpha2" => "MP", "Alpha3" => "MNP", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Micronesia", "SubRegionCode" => 57, "ISO3166-2" => "MP", "Currency" => 840),
    array ( "Code" => 581, "Name" => "United States Minor Outlying Islands", "Alpha2" => "UM", "Alpha3" => "UMI", "Region" => "", "RegionCode" => 0, "SubRegion" => "", "SubRegionCode" => 0, "ISO3166-2" => "UM", "Currency" => 840),
    array ( "Code" => 583, "Name" => "Micronesia (Federated States of)", "Alpha2" => "FM", "Alpha3" => "FSM", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Micronesia", "SubRegionCode" => 57, "ISO3166-2" => "FM", "Currency" => 840),
    array ( "Code" => 584, "Name" => "Marshall Islands", "Alpha2" => "MH", "Alpha3" => "MHL", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Micronesia", "SubRegionCode" => 57, "ISO3166-2" => "MH", "Currency" => 840),
    array ( "Code" => 585, "Name" => "Palau", "Alpha2" => "PW", "Alpha3" => "PLW", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Micronesia", "SubRegionCode" => 57, "ISO3166-2" => "PW", "Currency" => 840),
    array ( "Code" => 586, "Name" => "Pakistan", "Alpha2" => "PK", "Alpha3" => "PAK", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Southern Asia", "SubRegionCode" => 34, "ISO3166-2" => "PK", "Currency" => 586),
    array ( "Code" => 591, "Name" => "Panama", "Alpha2" => "PA", "Alpha3" => "PAN", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Central America", "SubRegionCode" => 13, "ISO3166-2" => "PA", "Currency" => 840),
    array ( "Code" => 598, "Name" => "Papua New Guinea", "Alpha2" => "PG", "Alpha3" => "PNG", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Melanesia", "SubRegionCode" => 54, "ISO3166-2" => "PG", "Currency" => 598),
    array ( "Code" => 600, "Name" => "Paraguay", "Alpha2" => "PY", "Alpha3" => "PRY", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "PY", "Currency" => 600),
    array ( "Code" => 604, "Name" => "Peru", "Alpha2" => "PE", "Alpha3" => "PER", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "PE", "Currency" => 604),
    array ( "Code" => 608, "Name" => "Philippines", "Alpha2" => "PH", "Alpha3" => "PHL", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "PH", "Currency" => 608),
    array ( "Code" => 612, "Name" => "Pitcairn", "Alpha2" => "PN", "Alpha3" => "PCN", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "PN", "Currency" => 554),
    array ( "Code" => 616, "Name" => "Poland", "Alpha2" => "PL", "Alpha3" => "POL", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "PL", "Currency" => 985),
    array ( "Code" => 620, "Name" => "Portugal", "Alpha2" => "PT", "Alpha3" => "PRT", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "PT", "Currency" => 978),
    array ( "Code" => 624, "Name" => "Guinea-Bissau", "Alpha2" => "GW", "Alpha3" => "GNB", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "GW", "Currency" => 952),
    array ( "Code" => 626, "Name" => "Timor-Leste", "Alpha2" => "TL", "Alpha3" => "TLS", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "TL", "Currency" => 840),
    array ( "Code" => 630, "Name" => "Puerto Rico", "Alpha2" => "PR", "Alpha3" => "PRI", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "PR", "Currency" => 840),
    array ( "Code" => 634, "Name" => "Qatar", "Alpha2" => "QA", "Alpha3" => "QAT", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "QA", "Currency" => 634),
    array ( "Code" => 638, "Name" => "Réunion", "Alpha2" => "RE", "Alpha3" => "REU", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "RE", "Currency" => 978),
    array ( "Code" => 642, "Name" => "Romania", "Alpha2" => "RO", "Alpha3" => "ROU", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "RO", "Currency" => 946),
    array ( "Code" => 643, "Name" => "Russian Federation", "Alpha2" => "RU", "Alpha3" => "RUS", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "RU", "Currency" => 643),
    array ( "Code" => 646, "Name" => "Rwanda", "Alpha2" => "RW", "Alpha3" => "RWA", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "RW", "Currency" => 646),
    array ( "Code" => 652, "Name" => "Saint Barthélemy", "Alpha2" => "BL", "Alpha3" => "BLM", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "BL", "Currency" => 978),
    array ( "Code" => 654, "Name" => "Saint Helena, Ascension and Tristan da Cunha", "Alpha2" => "SH", "Alpha3" => "SHN", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "SH", "Currency" => 654),
    array ( "Code" => 659, "Name" => "Saint Kitts and Nevis", "Alpha2" => "KN", "Alpha3" => "KNA", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "KN", "Currency" => 951),
    array ( "Code" => 660, "Name" => "Anguilla", "Alpha2" => "AI", "Alpha3" => "AIA", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "AI", "Currency" => 951),
    array ( "Code" => 662, "Name" => "Saint Lucia", "Alpha2" => "LC", "Alpha3" => "LCA", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "LC", "Currency" => 951),
    array ( "Code" => 663, "Name" => "Saint Martin (French part)", "Alpha2" => "MF", "Alpha3" => "MAF", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "MF", "Currency" => 978),
    array ( "Code" => 666, "Name" => "Saint Pierre and Miquelon", "Alpha2" => "PM", "Alpha3" => "SPM", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Northern America", "SubRegionCode" => 21, "ISO3166-2" => "PM", "Currency" => 978),
    array ( "Code" => 670, "Name" => "Saint Vincent and the Grenadines", "Alpha2" => "VC", "Alpha3" => "VCT", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "VC", "Currency" => 951),
    array ( "Code" => 674, "Name" => "San Marino", "Alpha2" => "SM", "Alpha3" => "SMR", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "SM", "Currency" => 978),
    array ( "Code" => 678, "Name" => "Sao Tome and Principe", "Alpha2" => "ST", "Alpha3" => "STP", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Middle Africa", "SubRegionCode" => 17, "ISO3166-2" => "ST", "Currency" => 930),
    array ( "Code" => 682, "Name" => "Saudi Arabia", "Alpha2" => "SA", "Alpha3" => "SAU", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "SA", "Currency" => 682),
    array ( "Code" => 686, "Name" => "Senegal", "Alpha2" => "SN", "Alpha3" => "SEN", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "SN", "Currency" => 952),
    array ( "Code" => 688, "Name" => "Serbia", "Alpha2" => "RS", "Alpha3" => "SRB", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "RS", "Currency" => 941),
    array ( "Code" => 690, "Name" => "Seychelles", "Alpha2" => "SC", "Alpha3" => "SYC", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "SC", "Currency" => 690),
    array ( "Code" => 694, "Name" => "Sierra Leone", "Alpha2" => "SL", "Alpha3" => "SLE", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "SL", "Currency" => 694),
    array ( "Code" => 702, "Name" => "Singapore", "Alpha2" => "SG", "Alpha3" => "SGP", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "SG", "Currency" => 702),
    array ( "Code" => 703, "Name" => "Slovakia", "Alpha2" => "SK", "Alpha3" => "SVK", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "SK", "Currency" => 978),
    array ( "Code" => 704, "Name" => "Viet Nam", "Alpha2" => "VN", "Alpha3" => "VNM", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "VN", "Currency" => 704),
    array ( "Code" => 705, "Name" => "Slovenia", "Alpha2" => "SI", "Alpha3" => "SVN", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "SI", "Currency" => 978),
    array ( "Code" => 706, "Name" => "Somalia", "Alpha2" => "SO", "Alpha3" => "SOM", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "SO", "Currency" => 706),
    array ( "Code" => 710, "Name" => "South Africa", "Alpha2" => "ZA", "Alpha3" => "ZAF", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Southern Africa", "SubRegionCode" => 18, "ISO3166-2" => "ZA", "Currency" => 710),
    array ( "Code" => 716, "Name" => "Zimbabwe", "Alpha2" => "ZW", "Alpha3" => "ZWE", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "ZW", "Currency" => 932),
    array ( "Code" => 724, "Name" => "Spain", "Alpha2" => "ES", "Alpha3" => "ESP", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "ES", "Currency" => 978),
    array ( "Code" => 728, "Name" => "South Sudan", "Alpha2" => "SS", "Alpha3" => "SSD", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "SS", "Currency" => 728),
    array ( "Code" => 729, "Name" => "Sudan", "Alpha2" => "SD", "Alpha3" => "SDN", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Northern Africa", "SubRegionCode" => 15, "ISO3166-2" => "SD", "Currency" => 938),
    array ( "Code" => 732, "Name" => "Western Sahara", "Alpha2" => "EH", "Alpha3" => "ESH", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Northern Africa", "SubRegionCode" => 15, "ISO3166-2" => "EH", "Currency" => 504),
    array ( "Code" => 740, "Name" => "Suriname", "Alpha2" => "SR", "Alpha3" => "SUR", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "SR", "Currency" => 968),
    array ( "Code" => 744, "Name" => "Svalbard and Jan Mayen", "Alpha2" => "SJ", "Alpha3" => "SJM", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "SJ", "Currency" => 578),
    array ( "Code" => 748, "Name" => "Swaziland", "Alpha2" => "SZ", "Alpha3" => "SWZ", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Southern Africa", "SubRegionCode" => 18, "ISO3166-2" => "SZ", "Currency" => 748),
    array ( "Code" => 752, "Name" => "Sweden", "Alpha2" => "SE", "Alpha3" => "SWE", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "SE", "Currency" => 752),
    array ( "Code" => 756, "Name" => "Switzerland", "Alpha2" => "CH", "Alpha3" => "CHE", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Western Europe", "SubRegionCode" => 155, "ISO3166-2" => "CH", "Currency" => 756),
    array ( "Code" => 760, "Name" => "Syrian Arab Republic", "Alpha2" => "SY", "Alpha3" => "SYR", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "SY", "Currency" => 760),
    array ( "Code" => 762, "Name" => "Tajikistan", "Alpha2" => "TJ", "Alpha3" => "TJK", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Central Asia", "SubRegionCode" => 143, "ISO3166-2" => "TJ", "Currency" => 972),
    array ( "Code" => 764, "Name" => "Thailand", "Alpha2" => "TH", "Alpha3" => "THA", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "South-Eastern Asia", "SubRegionCode" => 35, "ISO3166-2" => "TH", "Currency" => 764),
    array ( "Code" => 768, "Name" => "Togo", "Alpha2" => "TG", "Alpha3" => "TGO", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "TG", "Currency" => 952),
    array ( "Code" => 772, "Name" => "Tokelau", "Alpha2" => "TK", "Alpha3" => "TKL", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "TK", "Currency" => 554),
    array ( "Code" => 776, "Name" => "Tonga", "Alpha2" => "TO", "Alpha3" => "TON", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "TO", "Currency" => 776),
    array ( "Code" => 780, "Name" => "Trinidad and Tobago", "Alpha2" => "TT", "Alpha3" => "TTO", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "TT", "Currency" => 780),
    array ( "Code" => 784, "Name" => "United Arab Emirates", "Alpha2" => "AE", "Alpha3" => "ARE", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "AE", "Currency" => 784),
    array ( "Code" => 788, "Name" => "Tunisia", "Alpha2" => "TN", "Alpha3" => "TUN", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Northern Africa", "SubRegionCode" => 15, "ISO3166-2" => "TN", "Currency" => 788),
    array ( "Code" => 792, "Name" => "Turkey", "Alpha2" => "TR", "Alpha3" => "TUR", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "TR", "Currency" => 949),
    array ( "Code" => 795, "Name" => "Turkmenistan", "Alpha2" => "TM", "Alpha3" => "TKM", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Central Asia", "SubRegionCode" => 143, "ISO3166-2" => "TM", "Currency" => 934),
    array ( "Code" => 796, "Name" => "Turks and Caicos Islands", "Alpha2" => "TC", "Alpha3" => "TCA", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "TC", "Currency" => 840),
    array ( "Code" => 798, "Name" => "Tuvalu", "Alpha2" => "TV", "Alpha3" => "TUV", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "TV", "Currency" => 36),
    array ( "Code" => 800, "Name" => "Uganda", "Alpha2" => "UG", "Alpha3" => "UGA", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "UG", "Currency" => 800),
    array ( "Code" => 804, "Name" => "Ukraine", "Alpha2" => "UA", "Alpha3" => "UKR", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Eastern Europe", "SubRegionCode" => 151, "ISO3166-2" => "UA", "Currency" => 980),
    array ( "Code" => 807, "Name" => "Macedonia (the former Yugoslav Republic of)", "Alpha2" => "MK", "Alpha3" => "MKD", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Southern Europe", "SubRegionCode" => 39, "ISO3166-2" => "MK", "Currency" => 807),
    array ( "Code" => 818, "Name" => "Egypt", "Alpha2" => "EG", "Alpha3" => "EGY", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Northern Africa", "SubRegionCode" => 15, "ISO3166-2" => "EG", "Currency" => 818),
    array ( "Code" => 826, "Name" => "United Kingdom of Great Britain and Northern Ireland", "Alpha2" => "GB", "Alpha3" => "GBR", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "GB", "Currency" => 826),
    array ( "Code" => 831, "Name" => "Guernsey", "Alpha2" => "GG", "Alpha3" => "GGY", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "GG", "Currency" => 826),
    array ( "Code" => 832, "Name" => "Jersey", "Alpha2" => "JE", "Alpha3" => "JEY", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "JE", "Currency" => 826),
    array ( "Code" => 833, "Name" => "Isle of Man", "Alpha2" => "IM", "Alpha3" => "IMN", "Region" => "Europe", "RegionCode" => 150, "SubRegion" => "Northern Europe", "SubRegionCode" => 154, "ISO3166-2" => "IM", "Currency" => 826),
    array ( "Code" => 834, "Name" => "Tanzania, United Republic of", "Alpha2" => "TZ", "Alpha3" => "TZA", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "TZ", "Currency" => 834),
    array ( "Code" => 840, "Name" => "United States of America", "Alpha2" => "US", "Alpha3" => "USA", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Northern America", "SubRegionCode" => 21, "ISO3166-2" => "US", "Currency" => 840),
    array ( "Code" => 850, "Name" => "Virgin Islands (U.S.)", "Alpha2" => "VI", "Alpha3" => "VIR", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "Caribbean", "SubRegionCode" => 29, "ISO3166-2" => "VI", "Currency" => 840),
    array ( "Code" => 854, "Name" => "Burkina Faso", "Alpha2" => "BF", "Alpha3" => "BFA", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Western Africa", "SubRegionCode" => 11, "ISO3166-2" => "BF", "Currency" => 952),
    array ( "Code" => 858, "Name" => "Uruguay", "Alpha2" => "UY", "Alpha3" => "URY", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "UY", "Currency" => 858),
    array ( "Code" => 860, "Name" => "Uzbekistan", "Alpha2" => "UZ", "Alpha3" => "UZB", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Central Asia", "SubRegionCode" => 143, "ISO3166-2" => "UZ", "Currency" => 860),
    array ( "Code" => 862, "Name" => "Venezuela (Bolivarian Republic of)", "Alpha2" => "VE", "Alpha3" => "VEN", "Region" => "Americas", "RegionCode" => 19, "SubRegion" => "South America", "SubRegionCode" => 5, "ISO3166-2" => "VE", "Currency" => 928),
    array ( "Code" => 876, "Name" => "Wallis and Futuna", "Alpha2" => "WF", "Alpha3" => "WLF", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "WF", "Currency" => 953),
    array ( "Code" => 882, "Name" => "Samoa", "Alpha2" => "WS", "Alpha3" => "WSM", "Region" => "Oceania", "RegionCode" => 9, "SubRegion" => "Polynesia", "SubRegionCode" => 61, "ISO3166-2" => "WS", "Currency" => 882),
    array ( "Code" => 887, "Name" => "Yemen", "Alpha2" => "YE", "Alpha3" => "YEM", "Region" => "Asia", "RegionCode" => 142, "SubRegion" => "Western Asia", "SubRegionCode" => 145, "ISO3166-2" => "YE", "Currency" => 886),
    array ( "Code" => 894, "Name" => "Zambia", "Alpha2" => "ZM", "Alpha3" => "ZMB", "Region" => "Africa", "RegionCode" => 2, "SubRegion" => "Eastern Africa", "SubRegionCode" => 14, "ISO3166-2" => "ZM", "Currency" => 967)
  ));
  install_add_db_data ( "CountryCodes", array (
    array ( "Country" => 4, "Prefix" => "93", "Code" => "93"),
    array ( "Country" => 8, "Prefix" => "355", "Code" => "355"),
    array ( "Country" => 10, "Prefix" => "672", "Code" => "672"),
    array ( "Country" => 12, "Prefix" => "213", "Code" => "213"),
    array ( "Country" => 16, "Prefix" => "1684", "Code" => "1684"),
    array ( "Country" => 20, "Prefix" => "376", "Code" => "376"),
    array ( "Country" => 24, "Prefix" => "244", "Code" => "244"),
    array ( "Country" => 28, "Prefix" => "1268", "Code" => "1268"),
    array ( "Country" => 31, "Prefix" => "994", "Code" => "994"),
    array ( "Country" => 32, "Prefix" => "54", "Code" => "54"),
    array ( "Country" => 36, "Prefix" => "61", "Code" => "61"),
    array ( "Country" => 40, "Prefix" => "43", "Code" => "43"),
    array ( "Country" => 44, "Prefix" => "1242", "Code" => "1242"),
    array ( "Country" => 48, "Prefix" => "973", "Code" => "973"),
    array ( "Country" => 50, "Prefix" => "880", "Code" => "880"),
    array ( "Country" => 51, "Prefix" => "374", "Code" => "374"),
    array ( "Country" => 52, "Prefix" => "1246", "Code" => "1246"),
    array ( "Country" => 56, "Prefix" => "32", "Code" => "32"),
    array ( "Country" => 60, "Prefix" => "1441", "Code" => "1441"),
    array ( "Country" => 64, "Prefix" => "975", "Code" => "975"),
    array ( "Country" => 68, "Prefix" => "591", "Code" => "591"),
    array ( "Country" => 70, "Prefix" => "387", "Code" => "387"),
    array ( "Country" => 72, "Prefix" => "267", "Code" => "267"),
    array ( "Country" => 74, "Prefix" => "47", "Code" => "47"),
    array ( "Country" => 76, "Prefix" => "55", "Code" => "55"),
    array ( "Country" => 84, "Prefix" => "501", "Code" => "501"),
    array ( "Country" => 86, "Prefix" => "246", "Code" => "246"),
    array ( "Country" => 90, "Prefix" => "677", "Code" => "677"),
    array ( "Country" => 92, "Prefix" => "1284", "Code" => "1284"),
    array ( "Country" => 96, "Prefix" => "673", "Code" => "673"),
    array ( "Country" => 100, "Prefix" => "359", "Code" => "359"),
    array ( "Country" => 104, "Prefix" => "95", "Code" => "95"),
    array ( "Country" => 108, "Prefix" => "257", "Code" => "257"),
    array ( "Country" => 112, "Prefix" => "375", "Code" => "375"),
    array ( "Country" => 116, "Prefix" => "855", "Code" => "855"),
    array ( "Country" => 120, "Prefix" => "237", "Code" => "237"),
    array ( "Country" => 124, "Prefix" => "1", "Code" => "1"),
    array ( "Country" => 132, "Prefix" => "238", "Code" => "238"),
    array ( "Country" => 136, "Prefix" => "1345", "Code" => "1345"),
    array ( "Country" => 140, "Prefix" => "236", "Code" => "236"),
    array ( "Country" => 144, "Prefix" => "94", "Code" => "94"),
    array ( "Country" => 148, "Prefix" => "235", "Code" => "235"),
    array ( "Country" => 152, "Prefix" => "56", "Code" => "56"),
    array ( "Country" => 156, "Prefix" => "86", "Code" => "86"),
    array ( "Country" => 158, "Prefix" => "886", "Code" => "886"),
    array ( "Country" => 162, "Prefix" => "61", "Code" => "61"),
    array ( "Country" => 166, "Prefix" => "61", "Code" => "61"),
    array ( "Country" => 170, "Prefix" => "57", "Code" => "57"),
    array ( "Country" => 174, "Prefix" => "269", "Code" => "269"),
    array ( "Country" => 175, "Prefix" => "262", "Code" => "262"),
    array ( "Country" => 178, "Prefix" => "242", "Code" => "242"),
    array ( "Country" => 180, "Prefix" => "243", "Code" => "243"),
    array ( "Country" => 184, "Prefix" => "682", "Code" => "682"),
    array ( "Country" => 188, "Prefix" => "506", "Code" => "506"),
    array ( "Country" => 191, "Prefix" => "385", "Code" => "385"),
    array ( "Country" => 192, "Prefix" => "53", "Code" => "53"),
    array ( "Country" => 196, "Prefix" => "357", "Code" => "357"),
    array ( "Country" => 203, "Prefix" => "420", "Code" => "420"),
    array ( "Country" => 204, "Prefix" => "229", "Code" => "229"),
    array ( "Country" => 208, "Prefix" => "45", "Code" => "45"),
    array ( "Country" => 212, "Prefix" => "1767", "Code" => "1767"),
    array ( "Country" => 214, "Prefix" => "1809", "Code" => "1809"),
    array ( "Country" => 214, "Prefix" => "1829", "Code" => "1829"),
    array ( "Country" => 214, "Prefix" => "1849", "Code" => "1849"),
    array ( "Country" => 218, "Prefix" => "593", "Code" => "593"),
    array ( "Country" => 222, "Prefix" => "503", "Code" => "503"),
    array ( "Country" => 226, "Prefix" => "240", "Code" => "240"),
    array ( "Country" => 231, "Prefix" => "251", "Code" => "251"),
    array ( "Country" => 232, "Prefix" => "291", "Code" => "291"),
    array ( "Country" => 233, "Prefix" => "372", "Code" => "372"),
    array ( "Country" => 234, "Prefix" => "298", "Code" => "298"),
    array ( "Country" => 238, "Prefix" => "500", "Code" => "500"),
    array ( "Country" => 239, "Prefix" => "500", "Code" => "500"),
    array ( "Country" => 242, "Prefix" => "679", "Code" => "679"),
    array ( "Country" => 246, "Prefix" => "358", "Code" => "358"),
    array ( "Country" => 248, "Prefix" => "358", "Code" => "358"),
    array ( "Country" => 250, "Prefix" => "33", "Code" => "33"),
    array ( "Country" => 254, "Prefix" => "594", "Code" => "594"),
    array ( "Country" => 258, "Prefix" => "689", "Code" => "689"),
    array ( "Country" => 260, "Prefix" => "262", "Code" => "262"),
    array ( "Country" => 262, "Prefix" => "253", "Code" => "253"),
    array ( "Country" => 266, "Prefix" => "241", "Code" => "241"),
    array ( "Country" => 268, "Prefix" => "995", "Code" => "995"),
    array ( "Country" => 270, "Prefix" => "220", "Code" => "220"),
    array ( "Country" => 275, "Prefix" => "970", "Code" => "970"),
    array ( "Country" => 276, "Prefix" => "49", "Code" => "49"),
    array ( "Country" => 288, "Prefix" => "233", "Code" => "233"),
    array ( "Country" => 292, "Prefix" => "350", "Code" => "350"),
    array ( "Country" => 296, "Prefix" => "686", "Code" => "686"),
    array ( "Country" => 300, "Prefix" => "30", "Code" => "30"),
    array ( "Country" => 304, "Prefix" => "299", "Code" => "299"),
    array ( "Country" => 308, "Prefix" => "1473", "Code" => "1473"),
    array ( "Country" => 312, "Prefix" => "590", "Code" => "590"),
    array ( "Country" => 316, "Prefix" => "1671", "Code" => "1671"),
    array ( "Country" => 320, "Prefix" => "502", "Code" => "502"),
    array ( "Country" => 324, "Prefix" => "224", "Code" => "224"),
    array ( "Country" => 328, "Prefix" => "592", "Code" => "592"),
    array ( "Country" => 332, "Prefix" => "509", "Code" => "509"),
    array ( "Country" => 334, "Prefix" => "672", "Code" => "672"),
    array ( "Country" => 336, "Prefix" => "379", "Code" => "379"),
    array ( "Country" => 340, "Prefix" => "504", "Code" => "504"),
    array ( "Country" => 344, "Prefix" => "852", "Code" => "852"),
    array ( "Country" => 348, "Prefix" => "36", "Code" => "36"),
    array ( "Country" => 352, "Prefix" => "354", "Code" => "354"),
    array ( "Country" => 356, "Prefix" => "91", "Code" => "91"),
    array ( "Country" => 360, "Prefix" => "62", "Code" => "62"),
    array ( "Country" => 364, "Prefix" => "98", "Code" => "98"),
    array ( "Country" => 368, "Prefix" => "964", "Code" => "964"),
    array ( "Country" => 372, "Prefix" => "353", "Code" => "353"),
    array ( "Country" => 376, "Prefix" => "972", "Code" => "972"),
    array ( "Country" => 380, "Prefix" => "39", "Code" => "39"),
    array ( "Country" => 384, "Prefix" => "225", "Code" => "225"),
    array ( "Country" => 388, "Prefix" => "1876", "Code" => "1876"),
    array ( "Country" => 392, "Prefix" => "81", "Code" => "81"),
    array ( "Country" => 398, "Prefix" => "7", "Code" => "7"),
    array ( "Country" => 400, "Prefix" => "962", "Code" => "962"),
    array ( "Country" => 404, "Prefix" => "254", "Code" => "254"),
    array ( "Country" => 408, "Prefix" => "850", "Code" => "850"),
    array ( "Country" => 410, "Prefix" => "82", "Code" => "82"),
    array ( "Country" => 414, "Prefix" => "965", "Code" => "965"),
    array ( "Country" => 417, "Prefix" => "996", "Code" => "996"),
    array ( "Country" => 418, "Prefix" => "856", "Code" => "856"),
    array ( "Country" => 422, "Prefix" => "961", "Code" => "961"),
    array ( "Country" => 426, "Prefix" => "266", "Code" => "266"),
    array ( "Country" => 428, "Prefix" => "371", "Code" => "371"),
    array ( "Country" => 430, "Prefix" => "231", "Code" => "231"),
    array ( "Country" => 434, "Prefix" => "218", "Code" => "218"),
    array ( "Country" => 438, "Prefix" => "423", "Code" => "423"),
    array ( "Country" => 440, "Prefix" => "370", "Code" => "370"),
    array ( "Country" => 442, "Prefix" => "352", "Code" => "352"),
    array ( "Country" => 446, "Prefix" => "853", "Code" => "853"),
    array ( "Country" => 450, "Prefix" => "261", "Code" => "261"),
    array ( "Country" => 454, "Prefix" => "265", "Code" => "265"),
    array ( "Country" => 458, "Prefix" => "60", "Code" => "60"),
    array ( "Country" => 462, "Prefix" => "960", "Code" => "960"),
    array ( "Country" => 466, "Prefix" => "223", "Code" => "223"),
    array ( "Country" => 470, "Prefix" => "356", "Code" => "356"),
    array ( "Country" => 474, "Prefix" => "596", "Code" => "596"),
    array ( "Country" => 478, "Prefix" => "222", "Code" => "222"),
    array ( "Country" => 480, "Prefix" => "230", "Code" => "230"),
    array ( "Country" => 484, "Prefix" => "52", "Code" => "52"),
    array ( "Country" => 492, "Prefix" => "377", "Code" => "377"),
    array ( "Country" => 496, "Prefix" => "976", "Code" => "976"),
    array ( "Country" => 498, "Prefix" => "373", "Code" => "373"),
    array ( "Country" => 499, "Prefix" => "382", "Code" => "382"),
    array ( "Country" => 500, "Prefix" => "1664", "Code" => "1664"),
    array ( "Country" => 504, "Prefix" => "212", "Code" => "212"),
    array ( "Country" => 508, "Prefix" => "258", "Code" => "258"),
    array ( "Country" => 512, "Prefix" => "968", "Code" => "968"),
    array ( "Country" => 516, "Prefix" => "264", "Code" => "264"),
    array ( "Country" => 520, "Prefix" => "674", "Code" => "674"),
    array ( "Country" => 524, "Prefix" => "977", "Code" => "977"),
    array ( "Country" => 528, "Prefix" => "31", "Code" => "31"),
    array ( "Country" => 531, "Prefix" => "599", "Code" => "599"),
    array ( "Country" => 533, "Prefix" => "297", "Code" => "297"),
    array ( "Country" => 534, "Prefix" => "1721", "Code" => "1721"),
    array ( "Country" => 535, "Prefix" => "599", "Code" => "599"),
    array ( "Country" => 540, "Prefix" => "687", "Code" => "687"),
    array ( "Country" => 548, "Prefix" => "678", "Code" => "678"),
    array ( "Country" => 554, "Prefix" => "64", "Code" => "64"),
    array ( "Country" => 558, "Prefix" => "505", "Code" => "505"),
    array ( "Country" => 562, "Prefix" => "227", "Code" => "227"),
    array ( "Country" => 566, "Prefix" => "234", "Code" => "234"),
    array ( "Country" => 570, "Prefix" => "683", "Code" => "683"),
    array ( "Country" => 574, "Prefix" => "6723", "Code" => "6723"),
    array ( "Country" => 578, "Prefix" => "47", "Code" => "47"),
    array ( "Country" => 580, "Prefix" => "1670", "Code" => "1670"),
    array ( "Country" => 581, "Prefix" => "1", "Code" => "1"),
    array ( "Country" => 583, "Prefix" => "691", "Code" => "691"),
    array ( "Country" => 584, "Prefix" => "692", "Code" => "692"),
    array ( "Country" => 585, "Prefix" => "680", "Code" => "680"),
    array ( "Country" => 586, "Prefix" => "92", "Code" => "92"),
    array ( "Country" => 591, "Prefix" => "507", "Code" => "507"),
    array ( "Country" => 598, "Prefix" => "675", "Code" => "675"),
    array ( "Country" => 600, "Prefix" => "595", "Code" => "595"),
    array ( "Country" => 604, "Prefix" => "51", "Code" => "51"),
    array ( "Country" => 608, "Prefix" => "63", "Code" => "63"),
    array ( "Country" => 612, "Prefix" => "64", "Code" => "64"),
    array ( "Country" => 616, "Prefix" => "48", "Code" => "48"),
    array ( "Country" => 620, "Prefix" => "351", "Code" => "351"),
    array ( "Country" => 624, "Prefix" => "245", "Code" => "245"),
    array ( "Country" => 626, "Prefix" => "670", "Code" => "670"),
    array ( "Country" => 630, "Prefix" => "1787", "Code" => "1787"),
    array ( "Country" => 630, "Prefix" => "1939", "Code" => "1939"),
    array ( "Country" => 634, "Prefix" => "974", "Code" => "974"),
    array ( "Country" => 638, "Prefix" => "262", "Code" => "262"),
    array ( "Country" => 642, "Prefix" => "40", "Code" => "40"),
    array ( "Country" => 643, "Prefix" => "7", "Code" => "7"),
    array ( "Country" => 646, "Prefix" => "250", "Code" => "250"),
    array ( "Country" => 652, "Prefix" => "590", "Code" => "590"),
    array ( "Country" => 654, "Prefix" => "290", "Code" => "290"),
    array ( "Country" => 659, "Prefix" => "1869", "Code" => "1869"),
    array ( "Country" => 660, "Prefix" => "1264", "Code" => "1264"),
    array ( "Country" => 662, "Prefix" => "1758", "Code" => "1758"),
    array ( "Country" => 663, "Prefix" => "590", "Code" => "590"),
    array ( "Country" => 666, "Prefix" => "508", "Code" => "508"),
    array ( "Country" => 670, "Prefix" => "1784", "Code" => "1784"),
    array ( "Country" => 674, "Prefix" => "378", "Code" => "378"),
    array ( "Country" => 678, "Prefix" => "239", "Code" => "239"),
    array ( "Country" => 682, "Prefix" => "966", "Code" => "966"),
    array ( "Country" => 686, "Prefix" => "221", "Code" => "221"),
    array ( "Country" => 688, "Prefix" => "381", "Code" => "381"),
    array ( "Country" => 690, "Prefix" => "248", "Code" => "248"),
    array ( "Country" => 694, "Prefix" => "232", "Code" => "232"),
    array ( "Country" => 702, "Prefix" => "65", "Code" => "65"),
    array ( "Country" => 703, "Prefix" => "421", "Code" => "421"),
    array ( "Country" => 704, "Prefix" => "84", "Code" => "84"),
    array ( "Country" => 705, "Prefix" => "386", "Code" => "386"),
    array ( "Country" => 706, "Prefix" => "252", "Code" => "252"),
    array ( "Country" => 710, "Prefix" => "27", "Code" => "27"),
    array ( "Country" => 716, "Prefix" => "263", "Code" => "263"),
    array ( "Country" => 724, "Prefix" => "34", "Code" => "34"),
    array ( "Country" => 728, "Prefix" => "211", "Code" => "211"),
    array ( "Country" => 729, "Prefix" => "249", "Code" => "249"),
    array ( "Country" => 732, "Prefix" => "212", "Code" => "212"),
    array ( "Country" => 740, "Prefix" => "597", "Code" => "597"),
    array ( "Country" => 744, "Prefix" => "47", "Code" => "47"),
    array ( "Country" => 748, "Prefix" => "268", "Code" => "268"),
    array ( "Country" => 752, "Prefix" => "46", "Code" => "46"),
    array ( "Country" => 756, "Prefix" => "41", "Code" => "41"),
    array ( "Country" => 760, "Prefix" => "963", "Code" => "963"),
    array ( "Country" => 762, "Prefix" => "992", "Code" => "992"),
    array ( "Country" => 764, "Prefix" => "66", "Code" => "66"),
    array ( "Country" => 768, "Prefix" => "228", "Code" => "228"),
    array ( "Country" => 772, "Prefix" => "690", "Code" => "690"),
    array ( "Country" => 776, "Prefix" => "676", "Code" => "676"),
    array ( "Country" => 780, "Prefix" => "1868", "Code" => "1868"),
    array ( "Country" => 784, "Prefix" => "971", "Code" => "971"),
    array ( "Country" => 788, "Prefix" => "216", "Code" => "216"),
    array ( "Country" => 792, "Prefix" => "90", "Code" => "90"),
    array ( "Country" => 795, "Prefix" => "993", "Code" => "993"),
    array ( "Country" => 796, "Prefix" => "1649", "Code" => "1649"),
    array ( "Country" => 798, "Prefix" => "688", "Code" => "688"),
    array ( "Country" => 800, "Prefix" => "256", "Code" => "256"),
    array ( "Country" => 804, "Prefix" => "380", "Code" => "380"),
    array ( "Country" => 807, "Prefix" => "389", "Code" => "389"),
    array ( "Country" => 818, "Prefix" => "20", "Code" => "20"),
    array ( "Country" => 826, "Prefix" => "44", "Code" => "44"),
    array ( "Country" => 831, "Prefix" => "441481", "Code" => "441481"),
    array ( "Country" => 832, "Prefix" => "441534", "Code" => "441534"),
    array ( "Country" => 833, "Prefix" => "441624", "Code" => "441624"),
    array ( "Country" => 834, "Prefix" => "255", "Code" => "255"),
    array ( "Country" => 840, "Prefix" => "1", "Code" => "1"),
    array ( "Country" => 850, "Prefix" => "1340", "Code" => "1340"),
    array ( "Country" => 854, "Prefix" => "226", "Code" => "226"),
    array ( "Country" => 858, "Prefix" => "598", "Code" => "598"),
    array ( "Country" => 860, "Prefix" => "998", "Code" => "998"),
    array ( "Country" => 862, "Prefix" => "58", "Code" => "58"),
    array ( "Country" => 876, "Prefix" => "681", "Code" => "681"),
    array ( "Country" => 882, "Prefix" => "685", "Code" => "685"),
    array ( "Country" => 887, "Prefix" => "967", "Code" => "967"),
    array ( "Country" => 894, "Prefix" => "260", "Code" => "260"),
    array ( "Country" => 654, "Prefix" => "247", "Code" => "247")
  ));
  install_add_db_data ( "Locales", array (
    array ( "Code" => "af", "Name" => "Afrikaans"),
    array ( "Code" => "af_NA", "Name" => "Afrikaans (Namibia)"),
    array ( "Code" => "af_ZA", "Name" => "Afrikaans (South Africa)"),
    array ( "Code" => "ak", "Name" => "Akan"),
    array ( "Code" => "ak_GH", "Name" => "Akan (Ghana)"),
    array ( "Code" => "am", "Name" => "Amharic"),
    array ( "Code" => "am_ET", "Name" => "Amharic (Ethiopia)"),
    array ( "Code" => "ar", "Name" => "Arabic"),
    array ( "Code" => "ar_AE", "Name" => "Arabic (United Arab Emirates)"),
    array ( "Code" => "ar_BH", "Name" => "Arabic (Bahrain)"),
    array ( "Code" => "ar_DJ", "Name" => "Arabic (Djibouti)"),
    array ( "Code" => "ar_DZ", "Name" => "Arabic (Algeria)"),
    array ( "Code" => "ar_EG", "Name" => "Arabic (Egypt)"),
    array ( "Code" => "ar_EH", "Name" => "Arabic (Western Sahara)"),
    array ( "Code" => "ar_ER", "Name" => "Arabic (Eritrea)"),
    array ( "Code" => "ar_IL", "Name" => "Arabic (Israel)"),
    array ( "Code" => "ar_IQ", "Name" => "Arabic (Iraq)"),
    array ( "Code" => "ar_JO", "Name" => "Arabic (Jordan)"),
    array ( "Code" => "ar_KM", "Name" => "Arabic (Comoros)"),
    array ( "Code" => "ar_KW", "Name" => "Arabic (Kuwait)"),
    array ( "Code" => "ar_LB", "Name" => "Arabic (Lebanon)"),
    array ( "Code" => "ar_LY", "Name" => "Arabic (Libya)"),
    array ( "Code" => "ar_MA", "Name" => "Arabic (Morocco)"),
    array ( "Code" => "ar_MR", "Name" => "Arabic (Mauritania)"),
    array ( "Code" => "ar_OM", "Name" => "Arabic (Oman)"),
    array ( "Code" => "ar_PS", "Name" => "Arabic (Palestinian Territories)"),
    array ( "Code" => "ar_QA", "Name" => "Arabic (Qatar)"),
    array ( "Code" => "ar_SA", "Name" => "Arabic (Saudi Arabia)"),
    array ( "Code" => "ar_SD", "Name" => "Arabic (Sudan)"),
    array ( "Code" => "ar_SO", "Name" => "Arabic (Somalia)"),
    array ( "Code" => "ar_SS", "Name" => "Arabic (South Sudan)"),
    array ( "Code" => "ar_SY", "Name" => "Arabic (Syria)"),
    array ( "Code" => "ar_TD", "Name" => "Arabic (Chad)"),
    array ( "Code" => "ar_TN", "Name" => "Arabic (Tunisia)"),
    array ( "Code" => "ar_YE", "Name" => "Arabic (Yemen)"),
    array ( "Code" => "as", "Name" => "Assamese"),
    array ( "Code" => "as_IN", "Name" => "Assamese (India)"),
    array ( "Code" => "az", "Name" => "Azerbaijani"),
    array ( "Code" => "az_AZ", "Name" => "Azerbaijani (Azerbaijan)"),
    array ( "Code" => "az_Cyrl", "Name" => "Azerbaijani (Cyrillic)"),
    array ( "Code" => "az_Cyrl_AZ", "Name" => "Azerbaijani (Cyrillic, Azerbaijan)"),
    array ( "Code" => "az_Latn", "Name" => "Azerbaijani (Latin)"),
    array ( "Code" => "az_Latn_AZ", "Name" => "Azerbaijani (Latin, Azerbaijan)"),
    array ( "Code" => "be", "Name" => "Belarusian"),
    array ( "Code" => "be_BY", "Name" => "Belarusian (Belarus)"),
    array ( "Code" => "bg", "Name" => "Bulgarian"),
    array ( "Code" => "bg_BG", "Name" => "Bulgarian (Bulgaria)"),
    array ( "Code" => "bm", "Name" => "Bambara"),
    array ( "Code" => "bm_Latn", "Name" => "Bambara (Latin)"),
    array ( "Code" => "bm_Latn_ML", "Name" => "Bambara (Latin, Mali)"),
    array ( "Code" => "bn", "Name" => "Bengali"),
    array ( "Code" => "bn_BD", "Name" => "Bengali (Bangladesh)"),
    array ( "Code" => "bn_IN", "Name" => "Bengali (India)"),
    array ( "Code" => "bo", "Name" => "Tibetan"),
    array ( "Code" => "bo_CN", "Name" => "Tibetan (China)"),
    array ( "Code" => "bo_IN", "Name" => "Tibetan (India)"),
    array ( "Code" => "br", "Name" => "Breton"),
    array ( "Code" => "br_FR", "Name" => "Breton (France)"),
    array ( "Code" => "bs", "Name" => "Bosnian"),
    array ( "Code" => "bs_BA", "Name" => "Bosnian (Bosnia & Herzegovina)"),
    array ( "Code" => "bs_Cyrl", "Name" => "Bosnian (Cyrillic)"),
    array ( "Code" => "bs_Cyrl_BA", "Name" => "Bosnian (Cyrillic, Bosnia & Herzegovina)"),
    array ( "Code" => "bs_Latn", "Name" => "Bosnian (Latin)"),
    array ( "Code" => "bs_Latn_BA", "Name" => "Bosnian (Latin, Bosnia & Herzegovina)"),
    array ( "Code" => "ca", "Name" => "Catalan"),
    array ( "Code" => "ca_AD", "Name" => "Catalan (Andorra)"),
    array ( "Code" => "ca_ES", "Name" => "Catalan (Spain)"),
    array ( "Code" => "ca_FR", "Name" => "Catalan (France)"),
    array ( "Code" => "ca_IT", "Name" => "Catalan (Italy)"),
    array ( "Code" => "cs", "Name" => "Czech"),
    array ( "Code" => "cs_CZ", "Name" => "Czech (Czech Republic)"),
    array ( "Code" => "cy", "Name" => "Welsh"),
    array ( "Code" => "cy_GB", "Name" => "Welsh (United Kingdom)"),
    array ( "Code" => "da", "Name" => "Danish"),
    array ( "Code" => "da_DK", "Name" => "Danish (Denmark)"),
    array ( "Code" => "da_GL", "Name" => "Danish (Greenland)"),
    array ( "Code" => "de", "Name" => "German"),
    array ( "Code" => "de_AT", "Name" => "German (Austria)"),
    array ( "Code" => "de_BE", "Name" => "German (Belgium)"),
    array ( "Code" => "de_CH", "Name" => "German (Switzerland)"),
    array ( "Code" => "de_DE", "Name" => "German (Germany)"),
    array ( "Code" => "de_LI", "Name" => "German (Liechtenstein)"),
    array ( "Code" => "de_LU", "Name" => "German (Luxembourg)"),
    array ( "Code" => "dz", "Name" => "Dzongkha"),
    array ( "Code" => "dz_BT", "Name" => "Dzongkha (Bhutan)"),
    array ( "Code" => "ee", "Name" => "Ewe"),
    array ( "Code" => "ee_GH", "Name" => "Ewe (Ghana)"),
    array ( "Code" => "ee_TG", "Name" => "Ewe (Togo)"),
    array ( "Code" => "el", "Name" => "Greek"),
    array ( "Code" => "el_CY", "Name" => "Greek (Cyprus)"),
    array ( "Code" => "el_GR", "Name" => "Greek (Greece)"),
    array ( "Code" => "en", "Name" => "English"),
    array ( "Code" => "en_AG", "Name" => "English (Antigua & Barbuda)"),
    array ( "Code" => "en_AI", "Name" => "English (Anguilla)"),
    array ( "Code" => "en_AS", "Name" => "English (American Samoa)"),
    array ( "Code" => "en_AU", "Name" => "English (Australia)"),
    array ( "Code" => "en_BB", "Name" => "English (Barbados)"),
    array ( "Code" => "en_BE", "Name" => "English (Belgium)"),
    array ( "Code" => "en_BM", "Name" => "English (Bermuda)"),
    array ( "Code" => "en_BS", "Name" => "English (Bahamas)"),
    array ( "Code" => "en_BW", "Name" => "English (Botswana)"),
    array ( "Code" => "en_BZ", "Name" => "English (Belize)"),
    array ( "Code" => "en_CA", "Name" => "English (Canada)"),
    array ( "Code" => "en_CC", "Name" => "English (Cocos (Keeling) Islands)"),
    array ( "Code" => "en_CK", "Name" => "English (Cook Islands)"),
    array ( "Code" => "en_CM", "Name" => "English (Cameroon)"),
    array ( "Code" => "en_CX", "Name" => "English (Christmas Island)"),
    array ( "Code" => "en_DG", "Name" => "English (Diego Garcia)"),
    array ( "Code" => "en_DM", "Name" => "English (Dominica)"),
    array ( "Code" => "en_ER", "Name" => "English (Eritrea)"),
    array ( "Code" => "en_FJ", "Name" => "English (Fiji)"),
    array ( "Code" => "en_FK", "Name" => "English (Falkland Islands)"),
    array ( "Code" => "en_FM", "Name" => "English (Micronesia)"),
    array ( "Code" => "en_GB", "Name" => "English (United Kingdom)"),
    array ( "Code" => "en_GD", "Name" => "English (Grenada)"),
    array ( "Code" => "en_GG", "Name" => "English (Guernsey)"),
    array ( "Code" => "en_GH", "Name" => "English (Ghana)"),
    array ( "Code" => "en_GI", "Name" => "English (Gibraltar)"),
    array ( "Code" => "en_GM", "Name" => "English (Gambia)"),
    array ( "Code" => "en_GU", "Name" => "English (Guam)"),
    array ( "Code" => "en_GY", "Name" => "English (Guyana)"),
    array ( "Code" => "en_HK", "Name" => "English (Hong Kong SAR China)"),
    array ( "Code" => "en_IE", "Name" => "English (Ireland)"),
    array ( "Code" => "en_IM", "Name" => "English (Isle of Man)"),
    array ( "Code" => "en_IN", "Name" => "English (India)"),
    array ( "Code" => "en_IO", "Name" => "English (British Indian Ocean Territory)"),
    array ( "Code" => "en_JE", "Name" => "English (Jersey)"),
    array ( "Code" => "en_JM", "Name" => "English (Jamaica)"),
    array ( "Code" => "en_KE", "Name" => "English (Kenya)"),
    array ( "Code" => "en_KI", "Name" => "English (Kiribati)"),
    array ( "Code" => "en_KN", "Name" => "English (St. Kitts & Nevis)"),
    array ( "Code" => "en_KY", "Name" => "English (Cayman Islands)"),
    array ( "Code" => "en_LC", "Name" => "English (St. Lucia)"),
    array ( "Code" => "en_LR", "Name" => "English (Liberia)"),
    array ( "Code" => "en_LS", "Name" => "English (Lesotho)"),
    array ( "Code" => "en_MG", "Name" => "English (Madagascar)"),
    array ( "Code" => "en_MH", "Name" => "English (Marshall Islands)"),
    array ( "Code" => "en_MO", "Name" => "English (Macau SAR China)"),
    array ( "Code" => "en_MP", "Name" => "English (Northern Mariana Islands)"),
    array ( "Code" => "en_MS", "Name" => "English (Montserrat)"),
    array ( "Code" => "en_MT", "Name" => "English (Malta)"),
    array ( "Code" => "en_MU", "Name" => "English (Mauritius)"),
    array ( "Code" => "en_MW", "Name" => "English (Malawi)"),
    array ( "Code" => "en_MY", "Name" => "English (Malaysia)"),
    array ( "Code" => "en_NA", "Name" => "English (Namibia)"),
    array ( "Code" => "en_NF", "Name" => "English (Norfolk Island)"),
    array ( "Code" => "en_NG", "Name" => "English (Nigeria)"),
    array ( "Code" => "en_NR", "Name" => "English (Nauru)"),
    array ( "Code" => "en_NU", "Name" => "English (Niue)"),
    array ( "Code" => "en_NZ", "Name" => "English (New Zealand)"),
    array ( "Code" => "en_PG", "Name" => "English (Papua New Guinea)"),
    array ( "Code" => "en_PH", "Name" => "English (Philippines)"),
    array ( "Code" => "en_PK", "Name" => "English (Pakistan)"),
    array ( "Code" => "en_PN", "Name" => "English (Pitcairn Islands)"),
    array ( "Code" => "en_PR", "Name" => "English (Puerto Rico)"),
    array ( "Code" => "en_PW", "Name" => "English (Palau)"),
    array ( "Code" => "en_RW", "Name" => "English (Rwanda)"),
    array ( "Code" => "en_SB", "Name" => "English (Solomon Islands)"),
    array ( "Code" => "en_SC", "Name" => "English (Seychelles)"),
    array ( "Code" => "en_SD", "Name" => "English (Sudan)"),
    array ( "Code" => "en_SG", "Name" => "English (Singapore)"),
    array ( "Code" => "en_SH", "Name" => "English (St. Helena)"),
    array ( "Code" => "en_SL", "Name" => "English (Sierra Leone)"),
    array ( "Code" => "en_SS", "Name" => "English (South Sudan)"),
    array ( "Code" => "en_SX", "Name" => "English (Sint Maarten)"),
    array ( "Code" => "en_SZ", "Name" => "English (Swaziland)"),
    array ( "Code" => "en_TC", "Name" => "English (Turks & Caicos Islands)"),
    array ( "Code" => "en_TK", "Name" => "English (Tokelau)"),
    array ( "Code" => "en_TO", "Name" => "English (Tonga)"),
    array ( "Code" => "en_TT", "Name" => "English (Trinidad & Tobago)"),
    array ( "Code" => "en_TV", "Name" => "English (Tuvalu)"),
    array ( "Code" => "en_TZ", "Name" => "English (Tanzania)"),
    array ( "Code" => "en_UG", "Name" => "English (Uganda)"),
    array ( "Code" => "en_UM", "Name" => "English (U.S. Outlying Islands)"),
    array ( "Code" => "en_US", "Name" => "English (United States)"),
    array ( "Code" => "en_VC", "Name" => "English (St. Vincent & Grenadines)"),
    array ( "Code" => "en_VG", "Name" => "English (British Virgin Islands)"),
    array ( "Code" => "en_VI", "Name" => "English (U.S. Virgin Islands)"),
    array ( "Code" => "en_VU", "Name" => "English (Vanuatu)"),
    array ( "Code" => "en_WS", "Name" => "English (Samoa)"),
    array ( "Code" => "en_ZA", "Name" => "English (South Africa)"),
    array ( "Code" => "en_ZM", "Name" => "English (Zambia)"),
    array ( "Code" => "en_ZW", "Name" => "English (Zimbabwe)"),
    array ( "Code" => "eo", "Name" => "Esperanto"),
    array ( "Code" => "es", "Name" => "Spanish"),
    array ( "Code" => "es_AR", "Name" => "Spanish (Argentina)"),
    array ( "Code" => "es_BO", "Name" => "Spanish (Bolivia)"),
    array ( "Code" => "es_CL", "Name" => "Spanish (Chile)"),
    array ( "Code" => "es_CO", "Name" => "Spanish (Colombia)"),
    array ( "Code" => "es_CR", "Name" => "Spanish (Costa Rica)"),
    array ( "Code" => "es_CU", "Name" => "Spanish (Cuba)"),
    array ( "Code" => "es_DO", "Name" => "Spanish (Dominican Republic)"),
    array ( "Code" => "es_EA", "Name" => "Spanish (Ceuta & Melilla)"),
    array ( "Code" => "es_EC", "Name" => "Spanish (Ecuador)"),
    array ( "Code" => "es_ES", "Name" => "Spanish (Spain)"),
    array ( "Code" => "es_GQ", "Name" => "Spanish (Equatorial Guinea)"),
    array ( "Code" => "es_GT", "Name" => "Spanish (Guatemala)"),
    array ( "Code" => "es_HN", "Name" => "Spanish (Honduras)"),
    array ( "Code" => "es_IC", "Name" => "Spanish (Canary Islands)"),
    array ( "Code" => "es_MX", "Name" => "Spanish (Mexico)"),
    array ( "Code" => "es_NI", "Name" => "Spanish (Nicaragua)"),
    array ( "Code" => "es_PA", "Name" => "Spanish (Panama)"),
    array ( "Code" => "es_PE", "Name" => "Spanish (Peru)"),
    array ( "Code" => "es_PH", "Name" => "Spanish (Philippines)"),
    array ( "Code" => "es_PR", "Name" => "Spanish (Puerto Rico)"),
    array ( "Code" => "es_PY", "Name" => "Spanish (Paraguay)"),
    array ( "Code" => "es_SV", "Name" => "Spanish (El Salvador)"),
    array ( "Code" => "es_US", "Name" => "Spanish (United States)"),
    array ( "Code" => "es_UY", "Name" => "Spanish (Uruguay)"),
    array ( "Code" => "es_VE", "Name" => "Spanish (Venezuela)"),
    array ( "Code" => "et", "Name" => "Estonian"),
    array ( "Code" => "et_EE", "Name" => "Estonian (Estonia)"),
    array ( "Code" => "eu", "Name" => "Basque"),
    array ( "Code" => "eu_ES", "Name" => "Basque (Spain)"),
    array ( "Code" => "fa", "Name" => "Persian"),
    array ( "Code" => "fa_AF", "Name" => "Persian (Afghanistan)"),
    array ( "Code" => "fa_IR", "Name" => "Persian (Iran)"),
    array ( "Code" => "ff", "Name" => "Fulah"),
    array ( "Code" => "ff_CM", "Name" => "Fulah (Cameroon)"),
    array ( "Code" => "ff_GN", "Name" => "Fulah (Guinea)"),
    array ( "Code" => "ff_MR", "Name" => "Fulah (Mauritania)"),
    array ( "Code" => "ff_SN", "Name" => "Fulah (Senegal)"),
    array ( "Code" => "fi", "Name" => "Finnish"),
    array ( "Code" => "fi_FI", "Name" => "Finnish (Finland)"),
    array ( "Code" => "fo", "Name" => "Faroese"),
    array ( "Code" => "fo_FO", "Name" => "Faroese (Faroe Islands)"),
    array ( "Code" => "fr", "Name" => "French"),
    array ( "Code" => "fr_BE", "Name" => "French (Belgium)"),
    array ( "Code" => "fr_BF", "Name" => "French (Burkina Faso)"),
    array ( "Code" => "fr_BI", "Name" => "French (Burundi)"),
    array ( "Code" => "fr_BJ", "Name" => "French (Benin)"),
    array ( "Code" => "fr_BL", "Name" => "French (St. Barthélemy)"),
    array ( "Code" => "fr_CA", "Name" => "French (Canada)"),
    array ( "Code" => "fr_CD", "Name" => "French (Congo - Kinshasa)"),
    array ( "Code" => "fr_CF", "Name" => "French (Central African Republic)"),
    array ( "Code" => "fr_CG", "Name" => "French (Congo - Brazzaville)"),
    array ( "Code" => "fr_CH", "Name" => "French (Switzerland)"),
    array ( "Code" => "fr_CI", "Name" => "French (Côte dIvoire)"),
    array ( "Code" => "fr_CM", "Name" => "French (Cameroon)"),
    array ( "Code" => "fr_DJ", "Name" => "French (Djibouti)"),
    array ( "Code" => "fr_DZ", "Name" => "French (Algeria)"),
    array ( "Code" => "fr_FR", "Name" => "French (France)"),
    array ( "Code" => "fr_GA", "Name" => "French (Gabon)"),
    array ( "Code" => "fr_GF", "Name" => "French (French Guiana)"),
    array ( "Code" => "fr_GN", "Name" => "French (Guinea)"),
    array ( "Code" => "fr_GP", "Name" => "French (Guadeloupe)"),
    array ( "Code" => "fr_GQ", "Name" => "French (Equatorial Guinea)"),
    array ( "Code" => "fr_HT", "Name" => "French (Haiti)"),
    array ( "Code" => "fr_KM", "Name" => "French (Comoros)"),
    array ( "Code" => "fr_LU", "Name" => "French (Luxembourg)"),
    array ( "Code" => "fr_MA", "Name" => "French (Morocco)"),
    array ( "Code" => "fr_MC", "Name" => "French (Monaco)"),
    array ( "Code" => "fr_MF", "Name" => "French (St. Martin)"),
    array ( "Code" => "fr_MG", "Name" => "French (Madagascar)"),
    array ( "Code" => "fr_ML", "Name" => "French (Mali)"),
    array ( "Code" => "fr_MQ", "Name" => "French (Martinique)"),
    array ( "Code" => "fr_MR", "Name" => "French (Mauritania)"),
    array ( "Code" => "fr_MU", "Name" => "French (Mauritius)"),
    array ( "Code" => "fr_NC", "Name" => "French (New Caledonia)"),
    array ( "Code" => "fr_NE", "Name" => "French (Niger)"),
    array ( "Code" => "fr_PF", "Name" => "French (French Polynesia)"),
    array ( "Code" => "fr_PM", "Name" => "French (St. Pierre & Miquelon)"),
    array ( "Code" => "fr_RE", "Name" => "French (Réunion)"),
    array ( "Code" => "fr_RW", "Name" => "French (Rwanda)"),
    array ( "Code" => "fr_SC", "Name" => "French (Seychelles)"),
    array ( "Code" => "fr_SN", "Name" => "French (Senegal)"),
    array ( "Code" => "fr_SY", "Name" => "French (Syria)"),
    array ( "Code" => "fr_TD", "Name" => "French (Chad)"),
    array ( "Code" => "fr_TG", "Name" => "French (Togo)"),
    array ( "Code" => "fr_TN", "Name" => "French (Tunisia)"),
    array ( "Code" => "fr_VU", "Name" => "French (Vanuatu)"),
    array ( "Code" => "fr_WF", "Name" => "French (Wallis & Futuna)"),
    array ( "Code" => "fr_YT", "Name" => "French (Mayotte)"),
    array ( "Code" => "fy", "Name" => "Western Frisian"),
    array ( "Code" => "fy_NL", "Name" => "Western Frisian (Netherlands)"),
    array ( "Code" => "ga", "Name" => "Irish"),
    array ( "Code" => "ga_IE", "Name" => "Irish (Ireland)"),
    array ( "Code" => "gd", "Name" => "Scottish Gaelic"),
    array ( "Code" => "gd_GB", "Name" => "Scottish Gaelic (United Kingdom)"),
    array ( "Code" => "gl", "Name" => "Galician"),
    array ( "Code" => "gl_ES", "Name" => "Galician (Spain)"),
    array ( "Code" => "gu", "Name" => "Gujarati"),
    array ( "Code" => "gu_IN", "Name" => "Gujarati (India)"),
    array ( "Code" => "gv", "Name" => "Manx"),
    array ( "Code" => "gv_IM", "Name" => "Manx (Isle of Man)"),
    array ( "Code" => "ha", "Name" => "Hausa"),
    array ( "Code" => "ha_GH", "Name" => "Hausa (Ghana)"),
    array ( "Code" => "ha_Latn", "Name" => "Hausa (Latin)"),
    array ( "Code" => "ha_Latn_GH", "Name" => "Hausa (Latin, Ghana)"),
    array ( "Code" => "ha_Latn_NE", "Name" => "Hausa (Latin, Niger)"),
    array ( "Code" => "ha_Latn_NG", "Name" => "Hausa (Latin, Nigeria)"),
    array ( "Code" => "ha_NE", "Name" => "Hausa (Niger)"),
    array ( "Code" => "ha_NG", "Name" => "Hausa (Nigeria)"),
    array ( "Code" => "he", "Name" => "Hebrew"),
    array ( "Code" => "he_IL", "Name" => "Hebrew (Israel)"),
    array ( "Code" => "hi", "Name" => "Hindi"),
    array ( "Code" => "hi_IN", "Name" => "Hindi (India)"),
    array ( "Code" => "hr", "Name" => "Croatian"),
    array ( "Code" => "hr_BA", "Name" => "Croatian (Bosnia & Herzegovina)"),
    array ( "Code" => "hr_HR", "Name" => "Croatian (Croatia)"),
    array ( "Code" => "hu", "Name" => "Hungarian"),
    array ( "Code" => "hu_HU", "Name" => "Hungarian (Hungary)"),
    array ( "Code" => "hy", "Name" => "Armenian"),
    array ( "Code" => "hy_AM", "Name" => "Armenian (Armenia)"),
    array ( "Code" => "id", "Name" => "Indonesian"),
    array ( "Code" => "id_ID", "Name" => "Indonesian (Indonesia)"),
    array ( "Code" => "ig", "Name" => "Igbo"),
    array ( "Code" => "ig_NG", "Name" => "Igbo (Nigeria)"),
    array ( "Code" => "ii", "Name" => "Sichuan Yi"),
    array ( "Code" => "ii_CN", "Name" => "Sichuan Yi (China)"),
    array ( "Code" => "is", "Name" => "Icelandic"),
    array ( "Code" => "is_IS", "Name" => "Icelandic (Iceland)"),
    array ( "Code" => "it", "Name" => "Italian"),
    array ( "Code" => "it_CH", "Name" => "Italian (Switzerland)"),
    array ( "Code" => "it_IT", "Name" => "Italian (Italy)"),
    array ( "Code" => "it_SM", "Name" => "Italian (San Marino)"),
    array ( "Code" => "ja", "Name" => "Japanese"),
    array ( "Code" => "ja_JP", "Name" => "Japanese (Japan)"),
    array ( "Code" => "ka", "Name" => "Georgian"),
    array ( "Code" => "ka_GE", "Name" => "Georgian (Georgia)"),
    array ( "Code" => "ki", "Name" => "Kikuyu"),
    array ( "Code" => "ki_KE", "Name" => "Kikuyu (Kenya)"),
    array ( "Code" => "kk", "Name" => "Kazakh"),
    array ( "Code" => "kk_Cyrl", "Name" => "Kazakh (Cyrillic)"),
    array ( "Code" => "kk_Cyrl_KZ", "Name" => "Kazakh (Cyrillic, Kazakhstan)"),
    array ( "Code" => "kk_KZ", "Name" => "Kazakh (Kazakhstan)"),
    array ( "Code" => "kl", "Name" => "Kalaallisut"),
    array ( "Code" => "kl_GL", "Name" => "Kalaallisut (Greenland)"),
    array ( "Code" => "km", "Name" => "Khmer"),
    array ( "Code" => "km_KH", "Name" => "Khmer (Cambodia)"),
    array ( "Code" => "kn", "Name" => "Kannada"),
    array ( "Code" => "kn_IN", "Name" => "Kannada (India)"),
    array ( "Code" => "ko", "Name" => "Korean"),
    array ( "Code" => "ko_KP", "Name" => "Korean (North Korea)"),
    array ( "Code" => "ko_KR", "Name" => "Korean (South Korea)"),
    array ( "Code" => "ks", "Name" => "Kashmiri"),
    array ( "Code" => "ks_Arab", "Name" => "Kashmiri (Arabic)"),
    array ( "Code" => "ks_Arab_IN", "Name" => "Kashmiri (Arabic, India)"),
    array ( "Code" => "ks_IN", "Name" => "Kashmiri (India)"),
    array ( "Code" => "kw", "Name" => "Cornish"),
    array ( "Code" => "kw_GB", "Name" => "Cornish (United Kingdom)"),
    array ( "Code" => "ky", "Name" => "Kyrgyz"),
    array ( "Code" => "ky_Cyrl", "Name" => "Kyrgyz (Cyrillic)"),
    array ( "Code" => "ky_Cyrl_KG", "Name" => "Kyrgyz (Cyrillic, Kyrgyzstan)"),
    array ( "Code" => "ky_KG", "Name" => "Kyrgyz (Kyrgyzstan)"),
    array ( "Code" => "lb", "Name" => "Luxembourgish"),
    array ( "Code" => "lb_LU", "Name" => "Luxembourgish (Luxembourg)"),
    array ( "Code" => "lg", "Name" => "Ganda"),
    array ( "Code" => "lg_UG", "Name" => "Ganda (Uganda)"),
    array ( "Code" => "ln", "Name" => "Lingala"),
    array ( "Code" => "ln_AO", "Name" => "Lingala (Angola)"),
    array ( "Code" => "ln_CD", "Name" => "Lingala (Congo - Kinshasa)"),
    array ( "Code" => "ln_CF", "Name" => "Lingala (Central African Republic)"),
    array ( "Code" => "ln_CG", "Name" => "Lingala (Congo - Brazzaville)"),
    array ( "Code" => "lo", "Name" => "Lao"),
    array ( "Code" => "lo_LA", "Name" => "Lao (Laos)"),
    array ( "Code" => "lt", "Name" => "Lithuanian"),
    array ( "Code" => "lt_LT", "Name" => "Lithuanian (Lithuania)"),
    array ( "Code" => "lu", "Name" => "Luba-Katanga"),
    array ( "Code" => "lu_CD", "Name" => "Luba-Katanga (Congo - Kinshasa)"),
    array ( "Code" => "lv", "Name" => "Latvian"),
    array ( "Code" => "lv_LV", "Name" => "Latvian (Latvia)"),
    array ( "Code" => "mg", "Name" => "Malagasy"),
    array ( "Code" => "mg_MG", "Name" => "Malagasy (Madagascar)"),
    array ( "Code" => "mk", "Name" => "Macedonian"),
    array ( "Code" => "mk_MK", "Name" => "Macedonian (Macedonia)"),
    array ( "Code" => "ml", "Name" => "Malayalam"),
    array ( "Code" => "ml_IN", "Name" => "Malayalam (India)"),
    array ( "Code" => "mn", "Name" => "Mongolian"),
    array ( "Code" => "mn_Cyrl", "Name" => "Mongolian (Cyrillic)"),
    array ( "Code" => "mn_Cyrl_MN", "Name" => "Mongolian (Cyrillic, Mongolia)"),
    array ( "Code" => "mn_MN", "Name" => "Mongolian (Mongolia)"),
    array ( "Code" => "mr", "Name" => "Marathi"),
    array ( "Code" => "mr_IN", "Name" => "Marathi (India)"),
    array ( "Code" => "ms", "Name" => "Malay"),
    array ( "Code" => "ms_BN", "Name" => "Malay (Brunei)"),
    array ( "Code" => "ms_Latn", "Name" => "Malay (Latin)"),
    array ( "Code" => "ms_Latn_BN", "Name" => "Malay (Latin, Brunei)"),
    array ( "Code" => "ms_Latn_MY", "Name" => "Malay (Latin, Malaysia)"),
    array ( "Code" => "ms_Latn_SG", "Name" => "Malay (Latin, Singapore)"),
    array ( "Code" => "ms_MY", "Name" => "Malay (Malaysia)"),
    array ( "Code" => "ms_SG", "Name" => "Malay (Singapore)"),
    array ( "Code" => "mt", "Name" => "Maltese"),
    array ( "Code" => "mt_MT", "Name" => "Maltese (Malta)"),
    array ( "Code" => "my", "Name" => "Burmese"),
    array ( "Code" => "my_MM", "Name" => "Burmese (Myanmar (Burma))"),
    array ( "Code" => "nb", "Name" => "Norwegian Bokmål"),
    array ( "Code" => "nb_NO", "Name" => "Norwegian Bokmål (Norway)"),
    array ( "Code" => "nb_SJ", "Name" => "Norwegian Bokmål (Svalbard & Jan Mayen)"),
    array ( "Code" => "nd", "Name" => "North Ndebele"),
    array ( "Code" => "nd_ZW", "Name" => "North Ndebele (Zimbabwe)"),
    array ( "Code" => "ne", "Name" => "Nepali"),
    array ( "Code" => "ne_IN", "Name" => "Nepali (India)"),
    array ( "Code" => "ne_NP", "Name" => "Nepali (Nepal)"),
    array ( "Code" => "nl", "Name" => "Dutch"),
    array ( "Code" => "nl_AW", "Name" => "Dutch (Aruba)"),
    array ( "Code" => "nl_BE", "Name" => "Dutch (Belgium)"),
    array ( "Code" => "nl_BQ", "Name" => "Dutch (Caribbean Netherlands)"),
    array ( "Code" => "nl_CW", "Name" => "Dutch (Curaçao)"),
    array ( "Code" => "nl_NL", "Name" => "Dutch (Netherlands)"),
    array ( "Code" => "nl_SR", "Name" => "Dutch (Suriname)"),
    array ( "Code" => "nl_SX", "Name" => "Dutch (Sint Maarten)"),
    array ( "Code" => "nn", "Name" => "Norwegian Nynorsk"),
    array ( "Code" => "nn_NO", "Name" => "Norwegian Nynorsk (Norway)"),
    array ( "Code" => "no", "Name" => "Norwegian"),
    array ( "Code" => "no_NO", "Name" => "Norwegian (Norway)"),
    array ( "Code" => "om", "Name" => "Oromo"),
    array ( "Code" => "om_ET", "Name" => "Oromo (Ethiopia)"),
    array ( "Code" => "om_KE", "Name" => "Oromo (Kenya)"),
    array ( "Code" => "or", "Name" => "Oriya"),
    array ( "Code" => "or_IN", "Name" => "Oriya (India)"),
    array ( "Code" => "os", "Name" => "Ossetic"),
    array ( "Code" => "os_GE", "Name" => "Ossetic (Georgia)"),
    array ( "Code" => "os_RU", "Name" => "Ossetic (Russia)"),
    array ( "Code" => "pa", "Name" => "Punjabi"),
    array ( "Code" => "pa_Arab", "Name" => "Punjabi (Arabic)"),
    array ( "Code" => "pa_Arab_PK", "Name" => "Punjabi (Arabic, Pakistan)"),
    array ( "Code" => "pa_Guru", "Name" => "Punjabi (Gurmukhi)"),
    array ( "Code" => "pa_Guru_IN", "Name" => "Punjabi (Gurmukhi, India)"),
    array ( "Code" => "pa_IN", "Name" => "Punjabi (India)"),
    array ( "Code" => "pa_PK", "Name" => "Punjabi (Pakistan)"),
    array ( "Code" => "pl", "Name" => "Polish"),
    array ( "Code" => "pl_PL", "Name" => "Polish (Poland)"),
    array ( "Code" => "ps", "Name" => "Pashto"),
    array ( "Code" => "ps_AF", "Name" => "Pashto (Afghanistan)"),
    array ( "Code" => "pt", "Name" => "Portuguese"),
    array ( "Code" => "pt_AO", "Name" => "Portuguese (Angola)"),
    array ( "Code" => "pt_BR", "Name" => "Portuguese (Brazil)"),
    array ( "Code" => "pt_CV", "Name" => "Portuguese (Cape Verde)"),
    array ( "Code" => "pt_GW", "Name" => "Portuguese (Guinea-Bissau)"),
    array ( "Code" => "pt_MO", "Name" => "Portuguese (Macau SAR China)"),
    array ( "Code" => "pt_MZ", "Name" => "Portuguese (Mozambique)"),
    array ( "Code" => "pt_PT", "Name" => "Portuguese (Portugal)"),
    array ( "Code" => "pt_ST", "Name" => "Portuguese (São Tomé & Príncipe)"),
    array ( "Code" => "pt_TL", "Name" => "Portuguese (Timor-Leste)"),
    array ( "Code" => "qu", "Name" => "Quechua"),
    array ( "Code" => "qu_BO", "Name" => "Quechua (Bolivia)"),
    array ( "Code" => "qu_EC", "Name" => "Quechua (Ecuador)"),
    array ( "Code" => "qu_PE", "Name" => "Quechua (Peru)"),
    array ( "Code" => "rm", "Name" => "Romansh"),
    array ( "Code" => "rm_CH", "Name" => "Romansh (Switzerland)"),
    array ( "Code" => "rn", "Name" => "Rundi"),
    array ( "Code" => "rn_BI", "Name" => "Rundi (Burundi)"),
    array ( "Code" => "ro", "Name" => "Romanian"),
    array ( "Code" => "ro_MD", "Name" => "Romanian (Moldova)"),
    array ( "Code" => "ro_RO", "Name" => "Romanian (Romania)"),
    array ( "Code" => "ru", "Name" => "Russian"),
    array ( "Code" => "ru_BY", "Name" => "Russian (Belarus)"),
    array ( "Code" => "ru_KG", "Name" => "Russian (Kyrgyzstan)"),
    array ( "Code" => "ru_KZ", "Name" => "Russian (Kazakhstan)"),
    array ( "Code" => "ru_MD", "Name" => "Russian (Moldova)"),
    array ( "Code" => "ru_RU", "Name" => "Russian (Russia)"),
    array ( "Code" => "ru_UA", "Name" => "Russian (Ukraine)"),
    array ( "Code" => "rw", "Name" => "Kinyarwanda"),
    array ( "Code" => "rw_RW", "Name" => "Kinyarwanda (Rwanda)"),
    array ( "Code" => "se", "Name" => "Northern Sami"),
    array ( "Code" => "se_FI", "Name" => "Northern Sami (Finland)"),
    array ( "Code" => "se_NO", "Name" => "Northern Sami (Norway)"),
    array ( "Code" => "se_SE", "Name" => "Northern Sami (Sweden)"),
    array ( "Code" => "sg", "Name" => "Sango"),
    array ( "Code" => "sg_CF", "Name" => "Sango (Central African Republic)"),
    array ( "Code" => "sh", "Name" => "Serbo-Croatian"),
    array ( "Code" => "sh_BA", "Name" => "Serbo-Croatian (Bosnia & Herzegovina)"),
    array ( "Code" => "si", "Name" => "Sinhala"),
    array ( "Code" => "si_LK", "Name" => "Sinhala (Sri Lanka)"),
    array ( "Code" => "sk", "Name" => "Slovak"),
    array ( "Code" => "sk_SK", "Name" => "Slovak (Slovakia)"),
    array ( "Code" => "sl", "Name" => "Slovenian"),
    array ( "Code" => "sl_SI", "Name" => "Slovenian (Slovenia)"),
    array ( "Code" => "sn", "Name" => "Shona"),
    array ( "Code" => "sn_ZW", "Name" => "Shona (Zimbabwe)"),
    array ( "Code" => "so", "Name" => "Somali"),
    array ( "Code" => "so_DJ", "Name" => "Somali (Djibouti)"),
    array ( "Code" => "so_ET", "Name" => "Somali (Ethiopia)"),
    array ( "Code" => "so_KE", "Name" => "Somali (Kenya)"),
    array ( "Code" => "so_SO", "Name" => "Somali (Somalia)"),
    array ( "Code" => "sq", "Name" => "Albanian"),
    array ( "Code" => "sq_AL", "Name" => "Albanian (Albania)"),
    array ( "Code" => "sq_MK", "Name" => "Albanian (Macedonia)"),
    array ( "Code" => "sq_XK", "Name" => "Albanian (Kosovo)"),
    array ( "Code" => "sr", "Name" => "Serbian"),
    array ( "Code" => "sr_BA", "Name" => "Serbian (Bosnia & Herzegovina)"),
    array ( "Code" => "sr_Cyrl", "Name" => "Serbian (Cyrillic)"),
    array ( "Code" => "sr_Cyrl_BA", "Name" => "Serbian (Cyrillic, Bosnia & Herzegovina)"),
    array ( "Code" => "sr_Cyrl_ME", "Name" => "Serbian (Cyrillic, Montenegro)"),
    array ( "Code" => "sr_Cyrl_RS", "Name" => "Serbian (Cyrillic, Serbia)"),
    array ( "Code" => "sr_Cyrl_XK", "Name" => "Serbian (Cyrillic, Kosovo)"),
    array ( "Code" => "sr_Latn", "Name" => "Serbian (Latin)"),
    array ( "Code" => "sr_Latn_BA", "Name" => "Serbian (Latin, Bosnia & Herzegovina)"),
    array ( "Code" => "sr_Latn_ME", "Name" => "Serbian (Latin, Montenegro)"),
    array ( "Code" => "sr_Latn_RS", "Name" => "Serbian (Latin, Serbia)"),
    array ( "Code" => "sr_Latn_XK", "Name" => "Serbian (Latin, Kosovo)"),
    array ( "Code" => "sr_ME", "Name" => "Serbian (Montenegro)"),
    array ( "Code" => "sr_RS", "Name" => "Serbian (Serbia)"),
    array ( "Code" => "sr_XK", "Name" => "Serbian (Kosovo)"),
    array ( "Code" => "sv", "Name" => "Swedish"),
    array ( "Code" => "sv_AX", "Name" => "Swedish (Åland Islands)"),
    array ( "Code" => "sv_FI", "Name" => "Swedish (Finland)"),
    array ( "Code" => "sv_SE", "Name" => "Swedish (Sweden)"),
    array ( "Code" => "sw", "Name" => "Swahili"),
    array ( "Code" => "sw_KE", "Name" => "Swahili (Kenya)"),
    array ( "Code" => "sw_TZ", "Name" => "Swahili (Tanzania)"),
    array ( "Code" => "sw_UG", "Name" => "Swahili (Uganda)"),
    array ( "Code" => "ta", "Name" => "Tamil"),
    array ( "Code" => "ta_IN", "Name" => "Tamil (India)"),
    array ( "Code" => "ta_LK", "Name" => "Tamil (Sri Lanka)"),
    array ( "Code" => "ta_MY", "Name" => "Tamil (Malaysia)"),
    array ( "Code" => "ta_SG", "Name" => "Tamil (Singapore)"),
    array ( "Code" => "te", "Name" => "Telugu"),
    array ( "Code" => "te_IN", "Name" => "Telugu (India)"),
    array ( "Code" => "th", "Name" => "Thai"),
    array ( "Code" => "th_TH", "Name" => "Thai (Thailand)"),
    array ( "Code" => "ti", "Name" => "Tigrinya"),
    array ( "Code" => "ti_ER", "Name" => "Tigrinya (Eritrea)"),
    array ( "Code" => "ti_ET", "Name" => "Tigrinya (Ethiopia)"),
    array ( "Code" => "tl", "Name" => "Tagalog"),
    array ( "Code" => "tl_PH", "Name" => "Tagalog (Philippines)"),
    array ( "Code" => "to", "Name" => "Tongan"),
    array ( "Code" => "to_TO", "Name" => "Tongan (Tonga)"),
    array ( "Code" => "tr", "Name" => "Turkish"),
    array ( "Code" => "tr_CY", "Name" => "Turkish (Cyprus)"),
    array ( "Code" => "tr_TR", "Name" => "Turkish (Turkey)"),
    array ( "Code" => "ug", "Name" => "Uyghur"),
    array ( "Code" => "ug_Arab", "Name" => "Uyghur (Arabic)"),
    array ( "Code" => "ug_Arab_CN", "Name" => "Uyghur (Arabic, China)"),
    array ( "Code" => "ug_CN", "Name" => "Uyghur (China)"),
    array ( "Code" => "uk", "Name" => "Ukrainian"),
    array ( "Code" => "uk_UA", "Name" => "Ukrainian (Ukraine)"),
    array ( "Code" => "ur", "Name" => "Urdu"),
    array ( "Code" => "ur_IN", "Name" => "Urdu (India)"),
    array ( "Code" => "ur_PK", "Name" => "Urdu (Pakistan)"),
    array ( "Code" => "uz", "Name" => "Uzbek"),
    array ( "Code" => "uz_AF", "Name" => "Uzbek (Afghanistan)"),
    array ( "Code" => "uz_Arab", "Name" => "Uzbek (Arabic)"),
    array ( "Code" => "uz_Arab_AF", "Name" => "Uzbek (Arabic, Afghanistan)"),
    array ( "Code" => "uz_Cyrl", "Name" => "Uzbek (Cyrillic)"),
    array ( "Code" => "uz_Cyrl_UZ", "Name" => "Uzbek (Cyrillic, Uzbekistan)"),
    array ( "Code" => "uz_Latn", "Name" => "Uzbek (Latin)"),
    array ( "Code" => "uz_Latn_UZ", "Name" => "Uzbek (Latin, Uzbekistan)"),
    array ( "Code" => "uz_UZ", "Name" => "Uzbek (Uzbekistan)"),
    array ( "Code" => "vi", "Name" => "Vietnamese"),
    array ( "Code" => "vi_VN", "Name" => "Vietnamese (Vietnam)"),
    array ( "Code" => "yi", "Name" => "Yiddish"),
    array ( "Code" => "yo", "Name" => "Yoruba"),
    array ( "Code" => "yo_BJ", "Name" => "Yoruba (Benin)"),
    array ( "Code" => "yo_NG", "Name" => "Yoruba (Nigeria)"),
    array ( "Code" => "zh", "Name" => "Chinese"),
    array ( "Code" => "zh_CN", "Name" => "Chinese (China)"),
    array ( "Code" => "zh_Hans", "Name" => "Chinese (Simplified)"),
    array ( "Code" => "zh_Hans_CN", "Name" => "Chinese (Simplified, China)"),
    array ( "Code" => "zh_Hans_HK", "Name" => "Chinese (Simplified, Hong Kong SAR China)"),
    array ( "Code" => "zh_Hans_MO", "Name" => "Chinese (Simplified, Macau SAR China)"),
    array ( "Code" => "zh_Hans_SG", "Name" => "Chinese (Simplified, Singapore)"),
    array ( "Code" => "zh_Hant", "Name" => "Chinese (Traditional)"),
    array ( "Code" => "zh_Hant_HK", "Name" => "Chinese (Traditional, Hong Kong SAR China)"),
    array ( "Code" => "zh_Hant_MO", "Name" => "Chinese (Traditional, Macau SAR China)"),
    array ( "Code" => "zh_Hant_TW", "Name" => "Chinese (Traditional, Taiwan)"),
    array ( "Code" => "zh_HK", "Name" => "Chinese (Hong Kong SAR China)"),
    array ( "Code" => "zh_MO", "Name" => "Chinese (Macau SAR China)"),
    array ( "Code" => "zh_SG", "Name" => "Chinese (Singapore)"),
    array ( "Code" => "zh_TW", "Name" => "Chinese (Taiwan)"),
    array ( "Code" => "zu", "Name" => "Zulu"),
    array ( "Code" => "zu_ZA", "Name" => "Zulu (South Africa)")
  ));

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
              $return["Procedures"][] = sprintf ( __ ( "Error installing database procedure \"%s\"!"), $name);
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
              $return["Tables"][] = sprintf ( __ ( "Error installing database table \"%s\"!"), $name);
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
              $return["Triggers"][] = sprintf ( __ ( "Error installing database trigger \"%s\"!"), $name);
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
                $query .= "'" . $_in["mysql"]["id"]->real_escape_string ( $entry[$key]) . "', ";
              }
              $query = substr ( $query, 0, -2) . ")";
            }
            if ( ! @$_in["mysql"]["id"]->query ( $query) && $failed == false)
            {
              if ( ! array_key_exists ( "Data", $return))
              {
                $return["Data"] = array ();
              }
              $return["Data"][] = sprintf ( __ ( "Error inserting database table \"%s\" data!"), $name);
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
    if ( ! @$_in["mysql"]["id"]->query ( "GRANT SELECT, INSERT, UPDATE, DELETE ON `vd`.* TO 'vd'@'" . ( $_in["mysql"]["hostname"] == "127.0.0.1" || strtolower ( $_in["mysql"]["hostname"]) == "localhost" ? "localhost" : "%") . "' IDENTIFIED BY '" . $_in["mysql"]["id"]->real_escape_string ( $_in["mysql"]["vdpassword"]) . "'"))
    {
      $return["Message"] = __ ( "Unable to grant privileges to 'vd' database user!");
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
  if ( sizeof ( $result) == 0)
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
    $config .= "baseurl = " . $_SERVER["SERVER_NAME"] . "\n";
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
  } else {
    $return["Result"] = true;
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $return);
}
?>
