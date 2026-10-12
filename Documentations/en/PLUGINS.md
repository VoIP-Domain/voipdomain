# VoIP Domain Plugin Developer Guide

## Overview

The VoIP Domain plugin system provides a modular architecture that allows developers to extend the platform without modifying core code. The design is conceptually based on WordPress's pluggable system, adapted for a distributed telephony environment.

The central administrative interface manages the plugin lifecycle: importing, enabling, disabling, upgrading, and removing plugins. Plugin code that targets remote daemons (Client, FastAGI, Monitor, Router) is deployed automatically via Gearman job dispatch and file transfer.

**Key distinctions:**

- `*-plugins/` directories hold third-party plugin code deployed to each daemon.
- `*-modules/` directories are reserved for core RPM-packaged code. Never place plugin files there.

Plugin-specific configuration is stored at `/var/lib/voipdomain/data/plugin-{name}.json` and distributed to target daemons automatically.

---

## Plugin Structure

A plugin is packaged as a zip file containing a single directory whose name matches the plugin's `name` field in `plugin.json`. The directory layout is:

```
myplugin/
  plugin.json          (required - manifest)
  api.php              (required - admin interface hooks and API endpoints)
  install.php          (optional - database setup on fresh install)
  update.php           (optional - version-aware database upgrades)
  uninstall.php        (optional - database cleanup on removal)
  filter.php           (optional - data filter hooks, e.g. menu entries)
  webui.php            (optional - admin interface pages and forms)
  language.php         (optional - i18n translation strings)
  language/            (optional - i18n translation files)
  client-plugins/      (optional - code deployed to Client daemon)
    myplugin/
      client.php
  fastagi-plugins/     (optional - code deployed to FastAGI daemon)
    myplugin/
      fastagi.php
  monitor-plugins/     (optional - code deployed to Monitor daemon)
    myplugin/
      monitor.php
  router-plugins/      (optional - code deployed to Router daemon)
    myplugin/
      router.php
  _signature/          (optional - cryptographic signing)
    MANIFEST
    MANIFEST.sig
    developer.pem
    MANIFEST.official.sig  (optional - official badge)
```

### File Roles

| File | Purpose |
|------|---------|
| `plugin.json` | Manifest with metadata, dependencies, daemon targets, and configuration. |
| `api.php` | Registers hooks, permissions, and API endpoints via `framework_add_hook()`, `framework_add_permission()`, and `framework_add_api_call()`. Loaded on every request. |
| `install.php` | Registers the `install_db` hook to create tables, triggers, procedures, and seed data on first install. |
| `update.php` | Registers the `upgrade_db` hook with `version_compare()` guards for incremental schema migrations. |
| `uninstall.php` | Registers the `uninstall_db` hook to drop tables and clean up resources on removal. |
| `filter.php` | Registers filter hooks (e.g. `framework_add_filter()`) for menu entries and data transformations. Lazy-loaded when a hook fires. |
| `webui.php` | Registers admin UI page paths via `framework_add_path()` and generates HTML/JavaScript for CRUD interfaces. Lazy-loaded on page access. |
| `language.php` | Translation string definitions for i18n support. Loaded at module startup; registers English keys and locale-specific translations in `$_in["i18n"]`. See [Internationalization](#internationalization) below. |

---

## plugin.json Manifest

The `plugin.json` file is the plugin's manifest. It is validated by `plugin_validate_manifest()` on import.

### Schema Definition

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `$schema` | string | No | Schema identifier. Always `"voipdomain-plugin-1.0"`. |
| `name` | string | Yes | Plugin directory name. Must match the zip folder name. Lowercase, alphanumeric with hyphens. |
| `display_name` | string | Yes | Human-friendly name shown in the admin interface. |
| `version` | string | Yes | Semantic version (e.g., `"1.2.3"`). Must match `/^\d+\.\d+\.\d+/`. |
| `author` | object | Yes | Author information (see below). |
| `published` | string | No | Publication date in `YYYY-MM-DD` format. |
| `description` | string | Yes | Short description of the plugin's purpose. |
| `license` | string | Yes | SPDX license identifier (e.g., `"GPLv3"`, `"MIT"`). |
| `support` | object | No | Support contact information (see below). |
| `requires` | object | No | Dependency constraints (see below). |
| `conflicts` | array | No | List of plugin names that conflict with this plugin. |
| `daemons` | array | No | Target daemon types: `"client"`, `"fastagi"`, `"monitor"`, `"router"`. |
| `hooks` | object | No | Lifecycle hook function names (see below). |
| `config` | object | No | Plugin-specific configuration distributed to target daemons. |
| `signature` | object | No | Populated by the signing tool. Do not edit manually. |

### Author Object

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `name` | string | Yes | Author's full name. |
| `email` | string | No | Contact email. |
| `url` | string | No | Author's website. |

### Support Object

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `url` | string | No | Support page URL. |
| `email` | string | No | Support email address. |

### Requires Object

Keys are module or dependency names, values are version constraint strings.

| Key | Example | Description |
|-----|---------|-------------|
| `core` | `">=1.0.0"` | Minimum VoIP Domain core version. |
| `php` | `">=8.1"` | Minimum PHP version. |
| `{plugin-name}` | `">=2.0.0"` | Dependency on another plugin. |

Supported operators: `>=`, `<=`, `>`, `<`, `=`, `!=`. If no operator is provided, `>=` is assumed.

### Hooks Object

| Field | Type | Description |
|-------|------|-------------|
| `install` | string | Function name registered on the `install_db` hook. |
| `upgrade` | string | Function name registered on the `upgrade_db` hook. |

### Full Example

```json
{
  "$schema": "voipdomain-plugin-1.0",
  "name": "sampleitems",
  "display_name": "Sample Items",
  "version": "1.0.0",
  "author": {
    "name": "Ernani José Camargo Azevedo",
    "email": "azevedo@voipdomain.io",
    "url": "https://voipdomain.io"
  },
  "published": "2026-07-22",
  "description": "Sample plugin demonstrating the plugin system with a minimal CRUD module",
  "license": "GPLv3",
  "support": {
    "url": "https://voipdomain.io/support",
    "email": "support@voipdomain.io"
  },
  "requires": {
    "core": ">=1.0.0",
    "php": ">=8.1"
  },
  "conflicts": [],
  "daemons": ["client"],
  "hooks": {
    "install": "sampleitems_install_db",
    "upgrade": "sampleitems_upgrade_db"
  },
  "config": {},
  "signature": {}
}
```

---

## Plugin Lifecycle

Plugins have four states:

| State | Code | Description |
|-------|------|-------------|
| Active | `A` | Plugin is installed and running. |
| Inactive | `I` | Plugin is disabled. Its directory is renamed with a `disabled_` prefix. |
| Suspended | `S` | Plugin was suspended by the system (e.g., failed dependency). |
| Failed | `F` | Plugin encountered an error during lifecycle operation. |

### Import (Install or Upgrade)

1. Upload the plugin zip via `POST /config/plugins/import` (multipart upload).
2. Extract the zip and locate the `plugin.json` manifest.
3. Validate the manifest with `plugin_validate_manifest()`.
4. Verify the cryptographic signature against system keyring policy.
5. Check dependency requirements (`requires`) and conflicts (`conflicts`).
6. If a plugin with the same `name` already exists and the new version is greater, treat as an upgrade.
7. Copy plugin files to the admin interface `plugins/` directory.
8. Run `install.php` (fresh install) or `update.php` (upgrade) to execute database migrations.
9. Deploy daemon module directories (`client-plugins/`, `fastagi-plugins/`, etc.) to the local daemons path and sync to remote servers via Gearman/file transfer.
10. Sync plugin configuration to target daemons as `/var/lib/voipdomain/data/plugin-{name}.json`.
11. Insert or update the plugin record in the `Plugins` database table with status `A`.
12. Request daemon reload for affected systemd units.

### Disable

1. Check if other active plugins depend on this plugin via `plugin_check_dependents()`.
2. If dependents exist, reject the operation with an error listing the dependent plugins.
3. Rename the plugin directory with the `disabled_` prefix.
4. Update the database record status to `I`.

### Enable

1. Rename the plugin directory back (remove `disabled_` prefix).
2. Update the database record status to `A`.
3. Reload affected daemons.

### Remove

1. Check if other active plugins depend on this plugin.
2. If dependents exist, reject the operation.
3. Run `uninstall.php` to execute database cleanup (drop tables, triggers, etc.).
4. Sync uninstall database manifest to remote servers.
5. Undeploy daemon module files from local and remote servers.
6. Remove plugin configuration files from target daemons.
7. Delete the plugin directory from disk.
8. Delete the plugin record from the database.
9. Request daemon reload for affected systemd units.

### Upgrade

1. Verify the new version is greater than the currently installed version.
2. Back up existing plugin files.
3. Replace plugin files with the new version.
4. Run `update.php` with version context (`version_from`, `version_to`).
5. Sync database manifest to remote servers.
6. Redeploy daemon module files.
7. Sync updated configuration to target daemons.
8. Update the database record with the new version.

---

## Database Management

### install.php (Fresh Install)

Registers the `install_db` hook to create database objects on first install.

```php
framework_add_hook ( "install_db", "myplugin_install_db");

function myplugin_install_db ( $buffer, $parameters)
{
  install_add_db_table ( "MyTable", "CREATE TABLE `MyTable` (
    `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Name` VARCHAR(64) NOT NULL,
    `Description` TEXT NULL,
    PRIMARY KEY (`ID`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

  install_add_db_trigger ( "MyTableInsert",
    "CREATE TRIGGER `MyTableInsert` AFTER INSERT ON `MyTable`
     FOR EACH ROW CALL UpdateCache('MyTable')");
  install_add_db_trigger ( "MyTableUpdate",
    "CREATE TRIGGER `MyTableUpdate` AFTER UPDATE ON `MyTable`
     FOR EACH ROW CALL UpdateCache('MyTable')");
  install_add_db_trigger ( "MyTableDelete",
    "CREATE TRIGGER `MyTableDelete` AFTER DELETE ON `MyTable`
     FOR EACH ROW CALL UpdateCache('MyTable')");

  install_add_db_data ( "MyTable", array (
    array ( "Name" => "Default Item", "Description" => "Seed data" )
  ));

  return $buffer;
}
```

### Available Database Functions

| Function | Purpose |
|----------|---------|
| `install_add_db_table($name, $sql, $deps)` | Create a table. `$deps` is an array of table names that must be created first. |
| `install_add_db_procedure($name, $sql, $deps)` | Create a stored procedure. |
| `install_add_db_trigger($name, $sql, $deps)` | Create a trigger. Triggers run after alters and raw SQL. |
| `install_add_db_data($name, $rows, $deps)` | Insert seed data. `$rows` is an array of associative arrays. Can be called multiple times for the same table (data is merged). |
| `install_add_db_alter($name, $sql, $deps)` | Execute an ALTER TABLE statement. |
| `install_add_db_raw($name, $sql, $deps)` | Execute arbitrary SQL. Runs after alters and before triggers. |
| `install_add_db_drop($name, $type, $deps)` | Register a DROP statement. `$type` is one of: `"table"`, `"trigger"`, `"procedure"`. |

All functions accept an optional `$deps` array specifying other entries (by their `$name`) that must execute first. The framework resolves dependencies topologically and aborts with an error if circular dependencies are detected.

### Execution Order

The database processing pipeline runs objects in this fixed order:

1. Procedures
2. Tables
3. Alters
4. Raw SQL
5. Triggers
6. Data inserts

Within each category, dependency ordering is respected.

### update.php (Version-Aware Upgrades)

Registers the `upgrade_db` hook. The function receives version context in `$parameters`:

- `$parameters["version_from"]` — currently installed version.
- `$parameters["version_to"]` — target version being installed.
- `$parameters["type"]` — always `"upgrade"`.

Use `version_compare()` with the `<` operator to apply cumulative migrations. Upgrading from 1.0.0 to 1.2.0 applies both the 1.1.0 and 1.2.0 migration blocks in order:

```php
framework_add_hook ( "upgrade_db", "myplugin_upgrade_db");

function myplugin_upgrade_db ( $buffer, $parameters)
{
  if ( version_compare ( $parameters["version_from"], "1.1.0", "<"))
  {
    install_add_db_alter ( "MyTable_add_priority",
      "ALTER TABLE `MyTable` ADD COLUMN `Priority` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `Description`");
  }

  if ( version_compare ( $parameters["version_from"], "1.2.0", "<"))
  {
    install_add_db_alter ( "MyTable_idx_priority",
      "ALTER TABLE `MyTable` ADD INDEX `idx_priority` (`Priority`)",
      array ( "MyTable_add_priority"));
  }

  return $buffer;
}
```

### uninstall.php (Cleanup on Removal)

Registers the `uninstall_db` hook to drop tables, triggers, and other objects.

```php
framework_add_hook ( "uninstall_db", "myplugin_uninstall_db");

function myplugin_uninstall_db ( $buffer, $parameters)
{
  install_add_db_drop ( "MyTableInsert", "trigger");
  install_add_db_drop ( "MyTableUpdate", "trigger");
  install_add_db_drop ( "MyTableDelete", "trigger");
  install_add_db_drop ( "MyTable", "table");

  return $buffer;
}
```

### Schema Tracking

The framework tracks installed schema versions in the `Plugins` database table. Each plugin record stores its current version, which is compared during upgrades to determine which migration blocks to apply. The `SchemaLog` and `ModuleVersions` tables track core module versions for dependency resolution.

---

## Cryptographic Signing (X.509 / OpenSSL)

Plugins can be cryptographically signed using X.509 certificates and RSA-SHA256 to guarantee integrity and authenticity. The signing model uses self-signed certificates and a flat keyring: the VoIP Domain master key is the project's trust anchor, and developer certificates are self-signed and trusted by manual installation into the system keyring. The standard PHP `openssl` extension is used for all cryptographic operations.

### Trust Model

```
VoIP Domain master key (voipdomain.pem)
  → Signs MANIFEST.official.sig (optional "official" badge)

Developer self-signed certificate (developer.pem)
  → Signs MANIFEST.sig (developer signature)
  → Trusted by presence in /etc/voipdomain/keyring/developers/
```

There is no Certificate Authority. Developer certificates are NOT signed by the VoIP Domain key. Trust is established by the administrator manually placing developer certificates in the keyring directory.

### Signing Model

Each signed plugin zip contains a `_signature/` directory with:

- `MANIFEST` — SHA-256 hashes of all plugin files (sorted alphabetically, excluding `_signature/`).
- `MANIFEST.sig` — RSA-SHA256 detached signature of the MANIFEST content by the developer (base64-encoded).
- `developer.pem` — Developer's self-signed X.509 certificate (PEM format).
- `MANIFEST.official.sig` — (optional) RSA-SHA256 detached signature by the VoIP Domain master key (base64-encoded). Grants the "official/validated" badge.

### Generating Developer Keys and Certificate

```bash
# Generate a 2048-bit RSA private key
openssl genrsa -out developer.key 2048

# Create a self-signed X.509 certificate (valid 3 years)
openssl req -new -x509 -key developer.key -sha256 -days 1095 \
  -out developer.pem -subj "/CN=Developer Name/O=Company/OU=VoIP Domain Plugins"
```

Guard `developer.key` with strict file permissions (`chmod 600`). Never include it in plugin packages or version control. The `developer.pem` certificate is public and included in the signed plugin.

To make a developer trusted, the system administrator installs the certificate in the keyring:

```bash
cp developer.pem /etc/voipdomain/keyring/developers/developername.pem
```

### Signing a Plugin

```bash
# 1. Build your plugin zip WITHOUT the _signature/ directory
# 2. Extract to a temporary directory
# 3. Generate the manifest and sign it

php -r "
require '/path/to/plugin-signing.inc.php';

\$extractdir = '/tmp/extract';
\$pluginname = 'myplugin';
\$developerid = 'your-developer-id';

// Generate MANIFEST
\$manifest = plugin_generate_manifest(\$extractdir, \$pluginname, \$developerid);

// Create _signature/ directory
\$sigdir = \$extractdir . '/' . \$pluginname . '/_signature';
if ( ! is_dir(\$sigdir)) mkdir(\$sigdir, 0755, true);

// Write MANIFEST
file_put_contents(\$sigdir . '/MANIFEST', \$manifest);
"

# 4. Sign MANIFEST with developer private key (RSA-SHA256)
openssl dgst -sha256 -sign developer.key -out _signature/MANIFEST.sig MANIFEST

# 5. Base64-encode the signature for portability
base64 _signature/MANIFEST.sig > _signature/MANIFEST.sig.tmp
mv _signature/MANIFEST.sig.tmp _signature/MANIFEST.sig

# 6. Copy developer certificate into the signature directory
cp developer.pem _signature/developer.pem

# 7. Re-create the zip
cd /tmp/extract && zip -r myplugin.zip myplugin/
```

Alternatively, sign entirely in PHP:

```php
$manifest = file_get_contents($sigdir . '/MANIFEST');
$privkey = openssl_pkey_get_private(file_get_contents('developer.key'));
openssl_sign($manifest, $sig, $privkey, OPENSSL_ALGO_SHA256);
file_put_contents($sigdir . '/MANIFEST.sig', base64_encode($sig));
copy('developer.pem', $sigdir . '/developer.pem');
```

### Official Signing (VoIP Domain Team Only)

If the VoIP Domain team validates a plugin, they sign the MANIFEST with the project master key to grant the "official/validated" badge:

```bash
openssl dgst -sha256 -sign voipdomain.key -out MANIFEST.official.sig MANIFEST
base64 MANIFEST.official.sig > MANIFEST.official.sig.b64 && mv MANIFEST.official.sig.b64 MANIFEST.official.sig
```

The resulting `MANIFEST.official.sig` is placed in the `_signature/` directory alongside the developer signature. This is optional and independent of the developer signature — a plugin can be developer-signed without being officially signed.

### MANIFEST Format

The manifest follows a fixed format with a header and one hash entry per file:

```
#!voipdomain-manifest-v1
# Developer: your-developer-id
# Signed: 2026-07-22T10:30:00-03:00
# Files: 5
SHA256:a1b2c3d4...  api.php
SHA256:e5f6a7b8...  filter.php
SHA256:c9d0e1f2...  install.php
SHA256:a3b4c5d6...  plugin.json
SHA256:e7f8a9b0...  webui.php
```

### Verification Process

On import, `plugin_verify_signature()` performs:

1. Check for `_signature/` directory. If absent, the plugin is unsigned.
2. Read `MANIFEST`, `MANIFEST.sig`, and `developer.pem` from `_signature/`.
3. Compute the SHA-256 fingerprint of `developer.pem`.
4. Search the keyring (`/etc/voipdomain/keyring/developers/*.pem`) for a certificate with a matching fingerprint. If found, the developer is marked as "trusted".
5. If no matching certificate is found in the keyring and `trust_embedded_keys=true` in `policy.conf`, use the embedded certificate but flag the plugin with an "untrusted developer" warning.
6. If no matching certificate is found and `trust_embedded_keys=false`, reject the plugin.
7. Extract the public key from `developer.pem` using `openssl_pkey_get_public()`.
8. Verify the MANIFEST signature using `openssl_verify()` with `OPENSSL_ALGO_SHA256`.
9. Verify SHA-256 hashes of every file listed in the manifest.
10. Detect files present on disk but not listed in the manifest (possible injection).
11. If `MANIFEST.official.sig` exists in `_signature/`, load the VoIP Domain master key from `/etc/voipdomain/keyring/voipdomain.pem` and verify the official signature. If valid, mark the plugin as "official/validated".

### Keyring

The system keyring stores the master key and trusted developer certificates.

| Path | Purpose |
|------|---------|
| `/etc/voipdomain/keyring/` | System keyring root directory. |
| `/etc/voipdomain/keyring/voipdomain.pem` | VoIP Domain master key (self-signed X.509 certificate, trust anchor). |
| `/etc/voipdomain/keyring/developers/` | Directory containing trusted developer certificates. |
| `/etc/voipdomain/keyring/developers/{name}.pem` | A developer's self-signed X.509 certificate, trusted by manual installation. |
| `/etc/voipdomain/keyring/policy.conf` | Signing policy configuration. |

The VoIP Domain master key (`voipdomain.pem`) is distributed via the git repository and the `voipdomain-filesystem` RPM package.

### Signing Policy

The policy file `/etc/voipdomain/keyring/policy.conf` controls how the system handles plugin signatures:

```ini
[signing]
policy = block
trust_embedded_keys = false
```

| Setting | Values | Description |
|---------|--------|-------------|
| `policy` | `block` (default), `warn`, `allow_unsigned` | Controls unsigned plugin handling. |
| `trust_embedded_keys` | `true`, `false` (default) | Whether to accept embedded developer certificates not in the keyring. |

| Policy | Behavior |
|--------|----------|
| `block` | (Default) Reject unsigned plugins and plugins with invalid signatures. |
| `warn` | Allow unsigned plugins with a warning. Reject invalid signatures. |
| `allow_unsigned` | No signature verification required. Useful during development. |

---

## Multi-Daemon Architecture

VoIP Domain runs four daemon types, each serving a distinct role. Plugins can ship code for any combination of these daemons.

| Daemon | Directory | Role |
|--------|-----------|------|
| Client | `client-plugins/` | Manages Asterisk configuration, file transfers, and server rebuild operations. |
| FastAGI | `fastagi-plugins/` | Handles AGI scripts and call routing logic at runtime. |
| Monitor | `monitor-plugins/` | Tracks AMI (Asterisk Manager Interface) events and real-time status. |
| Router | `router-plugins/` | Gearman message routing and command lifecycle management. |

Each daemon loads plugins from its `*-plugins/` directory on startup. Plugins register hooks using `framework_add_hook()`, following the same pattern as the admin interface.

### Gearman Integration

Daemons register as Gearman workers with server-specific function names:

| Daemon | Gearman Function Name |
|--------|-----------------------|
| FastAGI | `vd_fastagi_{serverid}_task` |
| Monitor | `vd_monitor_{serverid}_task` |
| Router | `vd_router_task` |
| Client | Receives commands via the existing notification system (not Gearman). |

The admin interface dispatches plugin operations to daemons using `plugin_gearman_dispatch()`, which sends a JSON-encoded workload containing the hook name and payload data:

```json
{
  "hook": "plugin_daemon_module_deploy",
  "data": {
    "Plugin": "myplugin",
    "ModuleType": "fastagi-plugins",
    "Module": "myplugin",
    "DaemonType": "fastagi"
  }
}
```

When `serverid` is `0`, the dispatch iterates all servers in the database and sends the task to each one.

### Daemon Module Deployment

When a plugin is imported, daemon modules are deployed in two steps:

1. **Local deployment**: Files are copied from the plugin directory (e.g., `myplugin/fastagi-plugins/myplugin/`) to the daemons directory (e.g., `/usr/libexec/voipdomain/daemons/fastagi-plugins/myplugin/`).
2. **Remote deployment**: Files are archived as tar.gz, transferred to remote servers via `file_transfer_send()`, and extracted on the target. Client daemons use the notification system; other daemons use Gearman.

### Configuration Distribution

Plugin configuration is stored in the `config` key of `plugin.json` and distributed to target daemons as `/var/lib/voipdomain/data/plugin-{name}.json`.

The `daemons` array in `plugin.json` specifies which daemon types receive the configuration. If empty, it defaults to `["client"]`.

The distribution process:

1. The admin interface encodes the `config` object as JSON with pretty-print formatting.
2. The payload is base64-encoded and sent to each target server/daemon pair.
3. Daemons write the file to their local data directory.
4. Daemons with inotify detect changes to the data directory automatically and reload configuration.

Use the `config.targets` key within the config object to further specify which daemons need your configuration when daemon-level targeting is insufficient.

---

## Hook System

The hook system is the core extensibility mechanism. Every function in VoIP Domain is registered as a hook, and plugins extend behavior by adding functions to existing hooks.

### Core Functions

| Function | Signature | Description |
|----------|-----------|-------------|
| `framework_add_hook()` | `($hook, $function, $options, $documentation)` | Register a function on a hook. |
| `framework_add_hook_at()` | `($hook, $function, $reference, $options)` | Register a function relative to another function on the same hook. |
| `framework_call()` | `($hook, $parameters, $output, $buffer)` | Execute all functions registered on a hook, chaining the buffer. |
| `framework_has_hook()` | `($hook, $function)` | Check if a hook (or a specific function on a hook) exists. |
| `framework_remove_hook_function()` | `($hook, $function)` | Remove a specific function from a hook. |
| `framework_get_buffer()` | `($hook)` | Retrieve the current buffer for an active hook call. |

### Hook Options

| Constant | Value | Description |
|----------|-------|-------------|
| `IN_HOOK_NULL` | `0` | Default behavior: append to end of hook chain. |
| `IN_HOOK_INSERT_FIRST` | `1` | Insert the function at the beginning of the hook chain. |
| `IN_HOOK_REMOVE_OTHERS` | `2` | Remove all existing functions from the hook before adding. |
| `IN_HOOK_REPLACE` | `4` | Replace the referenced function (used with `framework_add_hook_at()`). |
| `IN_HOOK_INSERT_BEFORE` | `8` | Insert before the referenced function (used with `framework_add_hook_at()`). |
| `IN_HOOK_INSERT_AFTER` | `16` | Insert after the referenced function (used with `framework_add_hook_at()`). |

### Hook Function Signature

Every hook function follows the same signature:

```php
function my_hook_function ( $buffer, $parameters )
{
  // $buffer: output from the previous function in the chain
  // $parameters: associative array passed to framework_call()

  // ... your logic ...

  return $buffer; // always return the buffer to continue the chain
}
```

### Sub-Hook Pattern

API functions in VoIP Domain follow a standardized pattern with sub-hooks for each lifecycle stage:

| Sub-Hook | Purpose |
|----------|---------|
| `{action}_start` | Initial parameter modification before processing. |
| `{action}_validate` | Parameter validation. Return an array of errors. |
| `{action}_sanitize` | Input sanitization after validation passes. |
| `{action}_pre` | Pre-processing before the main SQL query. |
| `{action}_post` | Post-processing of the result before return. |
| `{action}_finish` | Final cleanup tasks. |

Example: for a hook named `sampleitems_search`, the sub-hooks are `sampleitems_search_start`, `sampleitems_search_validate`, etc. Your plugin can register on any of these to modify behavior at the appropriate stage.

---

## Dependency Management

### Requirements

The `requires` field in `plugin.json` enforces version constraints:

```json
{
  "requires": {
    "core": ">=1.0.0",
    "php": ">=8.1",
    "other-plugin": ">=2.0.0"
  }
}
```

During import, each constraint is parsed and checked:

- `core` is validated against the running VoIP Domain version.
- `php` is validated against the runtime PHP version.
- Plugin dependencies are validated against versions in the `Plugins` database table.
- Core module versions are defined in `module-versions.inc.php`.

### Conflicts

The `conflicts` array prevents installation of incompatible plugins:

```json
{
  "conflicts": ["incompatible-plugin"]
}
```

If any plugin in the `conflicts` list is currently active, the import is rejected.

### Dependent Checking

Before disabling or removing a plugin, `plugin_check_dependents()` queries the database for all active plugins whose `requires` field references the target plugin. If dependents are found, the operation is rejected and the dependent plugin names are returned in the error response.

---

## API Endpoints

The admin interface exposes REST API endpoints for plugin management:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/config/plugins/import` | Import or upgrade a plugin (multipart file upload). |
| `GET` | `/config/plugins` | List all installed plugins with status and metadata. |
| `PUT` | `/config/plugin/:Plugin/toggle` | Toggle plugin between enabled and disabled state. |
| `PUT` | `/config/plugin/:Plugin/enable` | Enable a disabled plugin. |
| `PUT` | `/config/plugin/:Plugin/disable` | Disable an active plugin. |
| `DELETE` | `/config/plugin/:Plugin` | Remove a plugin entirely. |
| `GET` | `/config/plugin/:Plugin` | Get detailed information about a specific plugin. |

All endpoints require administrative authentication. The `:Plugin` parameter is the plugin's directory name (the `name` field from `plugin.json`).

---

## Registering API Endpoints in Your Plugin

Plugins register their own API endpoints in `api.php`:

```php
// 1. Register the hook with documentation for OpenAPI spec generation
framework_add_hook (
  "myplugin_search",
  "myplugin_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search string.")
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "Array of results."),
        "schema" => array ( /* ... */ )
      )
    )
  )
);

// 2. Add a permission for token-based access
framework_add_permission ( "myplugin_search", __ ( "Search my plugin items"));

// 3. Register the API route
framework_add_api_call (
  "/myplugin",               // URI path (prefixed by /api automatically)
  "Read",                    // HTTP verb: Read (GET), Create (POST),
                             //   Modify/Edit (PUT/PATCH), Delete (DELETE)
  "myplugin_search",         // Hook name to call
  array (
    "permissions" => array ( "User", "myplugin_search"),
    "title"       => __ ( "Search my plugin items"),
    "description" => __ ( "Return the list of items")
  )
);
```

---

## Registering WebUI Pages

Plugins register admin interface pages in `webui.php`:

```php
// Register a page path with permissions
framework_add_path ( "/myplugin", "myplugin_search_page",
  array ( "permissions" => array ( "User")));
framework_add_hook ( "myplugin_search_page", "myplugin_search_page",
  IN_HOOK_INSERT_FIRST);

function myplugin_search_page ( $buffer, $parameters )
{
  sys_set_title ( __ ( "My Plugin"));
  sys_set_subtitle ( __ ( "item search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "My Plugin"))
  ));

  // Add CSS/JS requirements with sys_addcss() and sys_addjs()
  // Generate HTML output
  $output = "<table id=\"datatables\" ...></table>\n";

  // Add JavaScript for DataTables initialization
  sys_addjs ( "/* ... DataTables init code ... */");

  return $output;
}
```

---

## Registering Menu Entries

Plugins add themselves to the admin navigation in `filter.php`:

```php
framework_add_filter ( "page_menu_registers", "myplugin_menu");

function myplugin_menu ( $buffer, $parameters )
{
  return array_merge ( (array) $buffer, array (
    array (
      "type" => "entry",
      "icon" => "flask",
      "href" => "/myplugin",
      "text" => __ ( "My Plugin"),
      "permissions" => array ( "User", "Administrator")
    )
  ));
}
```

---

## Internationalization

All user-visible strings in a plugin must be wrapped with `__()` for translation support. Translations are defined in `language.php`.

### Writing `language.php`

The file has two sections: English key registration and translations.

```php
<?php
/**
 * Add basic language structure (english)
 */
i18n_add ( "Search my plugin items");
i18n_add ( "Add new item");
i18n_add ( "The item name is required.");
i18n_add ( "An object containing the plugin item details.");

/**
 * Add Brazilian Portuguese support
 */
i18n_add ( "Search my plugin items", "Pesquisar itens do meu plugin", "pt_BR");
i18n_add ( "Add new item", "Adicionar novo item", "pt_BR");
i18n_add ( "The item name is required.", "O nome do item é obrigatório.", "pt_BR");
i18n_add ( "An object containing the plugin item details.", "Um objeto contendo os detalhes do item do plugin.", "pt_BR");
```

**Rules:**

- The English string is the lookup key — it must match exactly between `i18n_add()` registration and `__()` usage (case-sensitive).
- English-only calls (`i18n_add("text")`) register the key. Translation calls (`i18n_add("text", "translation", "lang")`) add the translation for a specific locale.
- `i18n_add()` automatically uses the current module name as context. Do not pass the `$context` parameter unless you have a specific reason.
- Do not re-register strings already defined in `modules/interface/language.php` — those are globally available to all modules.

### Using translations in code

```php
// In api.php — endpoint metadata:
"title" => __ ( "View item"),
"description" => __ ( "Returns item details."),

// In webui.php — page setup:
sys_set_title ( __ ( "My Plugin"));
sys_set_subtitle ( __ ( "item management"));

// In filter.php — menu entry:
"text" => __ ( "My Plugin"),

// In webui.php — JavaScript strings for page-specific use:
sys_addtext ( "Confirm delete?", __ ( "Confirm delete?"));
```

### JavaScript translations

For strings used in inline JavaScript, use `sys_addtext()` in the page hook to deliver translations to the frontend:

```php
function myplugin_page ( $buffer, $parameters)
{
  sys_addtext ( "Item saved", __ ( "Item saved"));
  sys_addtext ( "Delete failed", __ ( "Delete failed"));
  // ... page HTML ...
}
```

In JavaScript, use `VoIP.i18n.__()` to look up the translated string:

```javascript
VoIP.REST.call ( url, "DELETE", null, function ( data)
{
  new PNotify ( { title: VoIP.i18n.__ ( "Item saved"), type: "success" });
});
```

### Validation

Use the `checklanguage` CLI tool to verify your plugin's `language.php`:

```bash
cd modules
./checklanguage myplugin
```

This reports missing translations, unused entries, and duplicates. Run it before every release.

For full i18n system documentation, see [DEVELOPER-MANUAL.md §24](DEVELOPER-MANUAL.md#24-internationalization).

---

## Best Practices

1. **Always use semantic versioning.** The system relies on `version_compare()` for upgrade paths. Non-semver versions will cause unpredictable behavior.

2. **Keep `update.php` idempotent with `version_compare()` guards.** Each migration block should be gated by `version_compare($parameters["version_from"], "x.y.z", "<")` so it only runs when upgrading from an older version. Never use `==` — upgrades are cumulative.

3. **Test with `policy = allow_unsigned` during development.** Set the signing policy to `allow_unsigned` in `/etc/voipdomain/keyring/policy.conf` while developing. Switch to `block` or `warn` for production and staging.

4. **Sign releases before distribution.** Always sign plugin releases with your developer certificate before distributing to users. Ensure your certificate has been installed in the target system's keyring (`/etc/voipdomain/keyring/developers/`) by the administrator so the signature can be verified as trusted.

5. **Specify `daemons` to control configuration distribution.** Only list the daemons that actually need your plugin's configuration. An empty `daemons` array defaults to `["client"]`.

6. **Prefer hooks over direct function calls.** Register hooks at every meaningful extension point in your plugin so other plugins can extend your functionality without modifying your code. Follow the sub-hook pattern (`_start`, `_validate`, `_sanitize`, `_pre`, `_post`, `_finish`).

7. **Use `framework_add_hook_at()` for hook ordering.** When your plugin must run before or after a specific function in a hook chain, use `framework_add_hook_at()` with `IN_HOOK_INSERT_BEFORE` or `IN_HOOK_INSERT_AFTER` instead of relying on load order.

8. **Always return `$buffer` from hook functions.** The hook chain passes the return value of each function as the `$buffer` parameter to the next. Failing to return `$buffer` (or merging it with your data) breaks the chain for downstream hooks.

9. **Use `install_add_db_*` dependency arrays.** When your tables have foreign keys or your alters depend on prior schema changes, declare the dependency by name in the `$deps` parameter to guarantee correct execution order.

10. **Clean up in `uninstall.php`.** Drop every database object your plugin created. Leave no orphan tables, triggers, or procedures behind.
