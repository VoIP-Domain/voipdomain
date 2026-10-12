# Install/Upgrade System

VoIP Domain uses a version-aware install and upgrade mechanism for both core modules
and plugins. This document describes how schema management works and how to properly
implement versioned database migrations.

## Overview

- **Fresh install**: The `install_db` hook is called with `type = "install"` and a
  `version` parameter indicating the target version being installed.
- **Upgrade**: The `upgrade_db` hook is called with `type = "upgrade"`, `version_from`
  (currently installed), and `version_to` (target version).
- **Schema log**: Every install/upgrade operation is recorded in the `SchemaLog` table.
- **Module versions**: The `ModuleVersions` table tracks the current version of every
  core module and plugin.

## Database Tables

### SchemaLog

Records every schema migration applied to the database:

```sql
CREATE TABLE `SchemaLog` (
  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Module` varchar(128) NOT NULL,
  `VersionFrom` varchar(32) DEFAULT NULL,
  `VersionTo` varchar(32) NOT NULL,
  `Type` enum('install','upgrade') NOT NULL,
  `AppliedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('success','failed','partial') NOT NULL,
  `Details` text,
  PRIMARY KEY (`ID`),
  KEY `idx_module_version` (`Module`, `VersionTo`)
) ENGINE=InnoDB;
```

### ModuleVersions

Tracks the current version of each module:

```sql
CREATE TABLE `ModuleVersions` (
  `Module` varchar(128) NOT NULL,
  `Version` varchar(32) NOT NULL,
  `InstalledAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`Module`)
) ENGINE=InnoDB;
```

## Core Module Versioning

All core module versions are declared in `includes/module-versions.inc.php`:

```php
define ( "MODULE_USERS_VERSION", "1.0.0");
define ( "MODULE_SERVERS_VERSION", "1.0.0");
// ... etc.
```

When bumping a module version, update the constant and add the corresponding
`upgrade_db` hook logic.

## Install Hook (Fresh Install)

Called during first-time system installation. The `$parameters` array contains:

| Key | Value |
|-----|-------|
| `type` | `"install"` |
| `version` | Target version (e.g., `"1.0.0"`) |

Example:

```php
framework_add_hook ( "install_db", "mymodule_install_db");

function mymodule_install_db ( $buffer, $parameters)
{
  // $parameters["type"] == "install"
  // $parameters["version"] == "1.0.0"
  install_add_db_table ( "MyTable", "CREATE TABLE `MyTable` ( ... )", array("Dep"));
  return $buffer;
}
```

## Upgrade Hook (Version Bump)

Called when the system or plugin is upgraded. The `$parameters` array contains:

| Key | Value |
|-----|-------|
| `type` | `"upgrade"` |
| `version_from` | Currently installed version |
| `version_to` | Target version |
| `version` | Same as `version_to` |

Example:

```php
framework_add_hook ( "upgrade_db", "mymodule_upgrade_db");

function mymodule_upgrade_db ( $buffer, $parameters)
{
  if ( version_compare ( $parameters["version_from"], "1.1.0", "<"))
  {
    install_add_db_alter ( "MyTable_add_col",
      "ALTER TABLE `MyTable` ADD COLUMN `NewCol` VARCHAR(64) DEFAULT NULL");
  }
  if ( version_compare ( $parameters["version_from"], "1.2.0", "<"))
  {
    install_add_db_alter ( "MyTable_idx_newcol",
      "ALTER TABLE `MyTable` ADD INDEX `idx_newcol` (`NewCol`)",
      array("MyTable_add_col"));
  }
  return $buffer;
}
```

Version checks are cumulative — upgrading from 1.0.0 to 1.2.0 applies both blocks.

## Available DB Functions

### install_add_db_table($name, $sql, $deps)

Register a CREATE TABLE statement. Used during fresh installs.

### install_add_db_alter($name, $sql, $deps)

Register an ALTER TABLE statement. Used during upgrades. The `$name` must be unique
(e.g., `"MyTable_add_priority"`). Dependencies reference other alter names.

### install_add_db_raw($name, $sql, $deps)

Escape hatch for arbitrary SQL (data migrations, complex operations). Runs after
alters, before triggers.

### install_add_db_drop($name, $type, $deps)

Register a DROP statement. `$type` is one of: `"table"`, `"trigger"`, `"procedure"`.

### install_add_db_procedure($name, $sql, $deps)

Register a stored procedure.

### install_add_db_trigger($name, $sql, $deps)

Register a trigger.

### install_add_db_data($name, $data, $deps)

Register INSERT data for a table.

## Execution Order

`plugin_process_db()` executes registered statements in this order:

1. Procedures
2. Tables (CREATE)
3. Alters (ALTER TABLE)
4. Raw SQL
5. Triggers
6. Data (INSERT)

Within each category, dependency ordering is respected.

## Plugin Developer Guide

### plugin.json

Your plugin's `plugin.json` must declare a semver version:

```json
{
  "name": "My Plugin",
  "version": "1.0.0",
  "author": "Author Name",
  "description": "Plugin description",
  "license": "GPLv3"
}
```

### install.php

Creates the initial schema. Called on first install and on upgrade if no `update.php`
exists:

```php
framework_add_hook ( "install_db", "myplugin_install_db");

function myplugin_install_db ( $buffer, $parameters)
{
  install_add_db_table ( "MyPluginTable", "CREATE TABLE ...");
  return $buffer;
}
```

### update.php

Handles schema migrations for upgrades. If this file exists and the plugin is being
upgraded, it is used instead of `install.php`:

```php
framework_add_hook ( "upgrade_db", "myplugin_upgrade_db");

function myplugin_upgrade_db ( $buffer, $parameters)
{
  if ( version_compare ( $parameters["version_from"], "1.1.0", "<"))
  {
    install_add_db_alter ( "MyPluginTable_add_col",
      "ALTER TABLE `MyPluginTable` ADD COLUMN `NewCol` INT DEFAULT 0");
  }
  return $buffer;
}
```

### Version Comparison

Always use `version_compare()` for version checks:

```php
if ( version_compare ( $parameters["version_from"], "1.1.0", "<"))
```

Never use numeric operators (`<`, `>`, `==`) on version strings.

## System Upgrade API

The system upgrade is triggered via:

```
POST /api/system/upgrade
```

This endpoint (admin-only):
1. Ensures `SchemaLog` and `ModuleVersions` tables exist (bootstraps pre-1.1 installs)
2. Fixes `Plugins.Version` column if still `float` (legacy)
3. Reads current versions from `ModuleVersions`
4. Compares against declared `MODULE_*_VERSION` constants
5. Runs `upgrade_db` hooks for modules needing upgrade
6. Logs results to `SchemaLog`
7. Updates `ModuleVersions`

## Remote Server Sync

When a plugin is installed or upgraded, the database manifest (with version info)
is synced to remote servers via VDFT. The remote `plugin_db_apply` handler receives
both the manifest and version context, enabling remote servers to apply the same
schema changes.

## Backward Compatibility

- Existing `install_db` hooks that ignore `$parameters` continue to work unchanged
- Plugins without `update.php` fall through to `install.php` on upgrade (existing behavior)
- Pre-1.1 installs without `SchemaLog`/`ModuleVersions` tables are bootstrapped
  automatically on first upgrade
