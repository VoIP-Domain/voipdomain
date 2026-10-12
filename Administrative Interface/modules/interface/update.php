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
 * VoIP Domain core system upgrade orchestrator. Handles version-aware schema
 * migrations for both core modules and plugins.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Interface
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to upgrade the system database schema
 */
framework_add_hook (
  "upgrade_system",
  "interface_upgrade_system",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object with upgrade results."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Result" => array (
              "type" => "boolean",
              "description" => __ ( "The result of the upgrade operation."),
              "example" => true
            ),
            "Upgraded" => array (
              "type" => "array",
              "description" => __ ( "List of modules that were upgraded."),
              "items" => array (
                "type" => "object",
                "properties" => array (
                  "Module" => array ( "type" => "string"),
                  "From" => array ( "type" => "string"),
                  "To" => array ( "type" => "string")
                )
              )
            ),
            "Errors" => array (
              "type" => "array",
              "description" => __ ( "List of errors encountered during upgrade."),
              "items" => array ( "type" => "string")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/system/upgrade",
  "Create",
  "upgrade_system",
  array (
    "permissions" => array ( "administrator"),
    "title" => __ ( "System upgrade."),
    "description" => __ ( "Upgrade the system database schema to the latest version.")
  )
);

/**
 * Function to upgrade the system database schema.
 *
 * Reads current module versions from ModuleVersions table, compares against
 * declared MODULE_*_VERSION constants, and runs upgrade_db hooks for modules
 * that need it. Results are logged to SchemaLog.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function interface_upgrade_system ( $buffer, $parameters)
{
  global $_in;

  set_time_limit ( 600);

  $return = array (
    "Result" => true,
    "Upgraded" => array (),
    "Errors" => array ()
  );

  /**
   * Bootstrap: ensure SchemaLog and ModuleVersions tables exist (for upgrades
   * from pre-1.1 installs that don't have them yet)
   */
  $tables_exist = @$_in["mysql"]["id"]->query ( "SELECT 1 FROM `ModuleVersions` LIMIT 1");
  if ( ! $tables_exist)
  {
    @$_in["mysql"]["id"]->query ( "CREATE TABLE IF NOT EXISTS `SchemaLog` (\n" .
      "  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,\n" .
      "  `Module` varchar(128) NOT NULL,\n" .
      "  `VersionFrom` varchar(32) DEFAULT NULL,\n" .
      "  `VersionTo` varchar(32) NOT NULL,\n" .
      "  `Type` enum('install','upgrade') NOT NULL,\n" .
      "  `AppliedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,\n" .
      "  `Status` enum('success','failed','partial') NOT NULL,\n" .
      "  `Details` text,\n" .
      "  PRIMARY KEY (`ID`),\n" .
      "  KEY `idx_module_version` (`Module`, `VersionTo`)\n" .
      ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Database schema migration log';\n");
    @$_in["mysql"]["id"]->query ( "CREATE TABLE IF NOT EXISTS `ModuleVersions` (\n" .
      "  `Module` varchar(128) NOT NULL,\n" .
      "  `Version` varchar(32) NOT NULL,\n" .
      "  `InstalledAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,\n" .
      "  `UpdatedAt` datetime DEFAULT NULL,\n" .
      "  PRIMARY KEY (`Module`)\n" .
      ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Core module version registry';\n");
    @$_in["mysql"]["id"]->query ( "INSERT IGNORE INTO `ModuleVersions` (`Module`, `Version`) VALUES ('core', '1.0.0')");
  }

  /**
   * Also fix Plugins.Version column type if still float (for pre-1.1 installs)
   */
  $col_check = @$_in["mysql"]["id"]->query ( "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . $_in["mysql"]["id"]->real_escape_string ( $_in["mysql"]["database"]) . "' AND TABLE_NAME = 'Plugins' AND COLUMN_NAME = 'Version'");
  if ( $col_check && $row = $col_check->fetch_assoc ())
  {
    if ( stripos ( $row["COLUMN_TYPE"], "float") !== false)
    {
      @$_in["mysql"]["id"]->query ( "ALTER TABLE `Plugins` MODIFY COLUMN `Version` varchar(32) NOT NULL");
    }
  }

  /**
   * Add new Plugins table columns for v1.1 (AuthorEmail, SupportURL, PublishedAt, Daemons, SignedBy, Conflicts, expanded Status)
   */
  $col_check = @$_in["mysql"]["id"]->query ( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . $_in["mysql"]["id"]->real_escape_string ( $_in["mysql"]["database"]) . "' AND TABLE_NAME = 'Plugins' AND COLUMN_NAME = 'AuthorEmail'");
  if ( $col_check && $col_check->num_rows == 0)
  {
    @$_in["mysql"]["id"]->query ( "ALTER TABLE `Plugins` ADD COLUMN `AuthorEmail` varchar(255) DEFAULT NULL AFTER `Author`");
    @$_in["mysql"]["id"]->query ( "ALTER TABLE `Plugins` ADD COLUMN `SupportURL` varchar(512) DEFAULT NULL AFTER `License`");
    @$_in["mysql"]["id"]->query ( "ALTER TABLE `Plugins` ADD COLUMN `PublishedAt` date DEFAULT NULL AFTER `SupportURL`");
    @$_in["mysql"]["id"]->query ( "ALTER TABLE `Plugins` ADD COLUMN `Daemons` varchar(255) DEFAULT NULL AFTER `PublishedAt`");
    @$_in["mysql"]["id"]->query ( "ALTER TABLE `Plugins` ADD COLUMN `SignedBy` varchar(255) DEFAULT NULL AFTER `Daemons`");
    @$_in["mysql"]["id"]->query ( "ALTER TABLE `Plugins` ADD COLUMN `Conflicts` text DEFAULT NULL AFTER `SignedBy`");
    @$_in["mysql"]["id"]->query ( "ALTER TABLE `Plugins` MODIFY COLUMN `Status` enum('A','I','S','F') NOT NULL DEFAULT 'A'");
  }

  /**
   * Read current versions from ModuleVersions table
   */
  $current_versions = array ();
  $result = @$_in["mysql"]["id"]->query ( "SELECT `Module`, `Version` FROM `ModuleVersions`");
  if ( $result)
  {
    while ( $row = $result->fetch_assoc ())
    {
      $current_versions[$row["Module"]] = $row["Version"];
    }
  }

  /**
   * Collect declared module versions from constants
   */
  $declared_versions = array ();
  $constants = get_defined_constants ( true);
  if ( array_key_exists ( "user", $constants))
  {
    foreach ( $constants["user"] as $name => $value)
    {
      if ( preg_match ( '/^MODULE_(.+)_VERSION$/', $name, $matches))
      {
        $module = strtolower ( $matches[1]);
        $declared_versions[$module] = $value;
      }
    }
  }

  /**
   * Determine which modules need upgrading
   */
  $upgrades_needed = array ();
  foreach ( $declared_versions as $module => $target_version)
  {
    $current = array_key_exists ( $module, $current_versions) ? $current_versions[$module] : "0.0.0";
    if ( version_compare ( $current, $target_version, "<"))
    {
      $upgrades_needed[$module] = array (
        "from" => $current,
        "to" => $target_version
      );
    }
  }

  /**
   * Run upgrade_db hooks for each module that needs upgrading
   */
  plugin_install_db_init ();

  foreach ( $upgrades_needed as $module => $versions)
  {
    $hook_params = array (
      "type" => "upgrade",
      "module" => $module,
      "version_from" => $versions["from"],
      "version_to" => $versions["to"],
      "version" => $versions["to"]
    );

    if ( framework_has_hook ( "upgrade_db"))
    {
      framework_call ( "upgrade_db", $hook_params);
    }

    $module_hook = $module . "_upgrade_db";
    if ( framework_has_hook ( $module_hook))
    {
      framework_call ( $module_hook, $hook_params);
    }
  }

  /**
   * Execute collected schema changes
   */
  $db_errors = plugin_process_db ( array ());
  if ( sizeof ( $db_errors) > 0)
  {
    $return["Errors"] = array_merge ( $return["Errors"], array_values ( array_map ( function ( $v) { return is_array ( $v) ? implode ( "; ", $v) : $v; }, $db_errors)));
  }

  /**
   * Log results and update versions
   */
  foreach ( $upgrades_needed as $module => $versions)
  {
    $status = sizeof ( $db_errors) > 0 ? "partial" : "success";
    $details = sizeof ( $db_errors) > 0 ? json_encode ( $db_errors) : null;

    @$_in["mysql"]["id"]->query ( "INSERT INTO `SchemaLog` (`Module`, `VersionFrom`, `VersionTo`, `Type`, `Status`, `Details`) VALUES ('" .
      $_in["mysql"]["id"]->real_escape_string ( $module) . "', '" .
      $_in["mysql"]["id"]->real_escape_string ( $versions["from"]) . "', '" .
      $_in["mysql"]["id"]->real_escape_string ( $versions["to"]) . "', 'upgrade', '" .
      $status . "', " . ( $details !== null ? "'" . $_in["mysql"]["id"]->real_escape_string ( $details) . "'" : "NULL") . ")");

    if ( $status === "success")
    {
      if ( array_key_exists ( $module, $current_versions))
      {
        @$_in["mysql"]["id"]->query ( "UPDATE `ModuleVersions` SET `Version` = '" . $_in["mysql"]["id"]->real_escape_string ( $versions["to"]) . "', `UpdatedAt` = NOW() WHERE `Module` = '" . $_in["mysql"]["id"]->real_escape_string ( $module) . "'");
      } else {
        @$_in["mysql"]["id"]->query ( "INSERT INTO `ModuleVersions` (`Module`, `Version`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $module) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $versions["to"]) . "')");
      }
    }

    $return["Upgraded"][] = array (
      "Module" => $module,
      "From" => $versions["from"],
      "To" => $versions["to"],
      "Status" => $status
    );
  }

  if ( sizeof ( $return["Errors"]) > 0)
  {
    $return["Result"] = false;
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $return);
}
?>
