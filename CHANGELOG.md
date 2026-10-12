# Changelog

All notable changes to this project are documented in this file.

## [1.1.0] - 2026-07-30

### Added

- Administrators module (`admins`): full CRUD for master portal administrators using the `Admins` table - search, view, add, edit, clone, remove pages with SFA (second factor authentication) support; accessible only to Super-Administrators; 7 REST API endpoints (`/admins`, `/admins/:ID`, `/admins/sfa`); complete i18n (EN + pt_BR)
- Module registry system: `$_in["modules"]` tracks all loaded core modules and plugins with metadata (version, author, type, loaded status, file path, files list); populated during bootstrap, cached in `cache.json`, exposed via `GET /config/modules` API endpoint
- Required field indicators: all mandatory form fields across all modules now display a red asterisk via CSS `required` class on `form-group` divs (previously only applied in IVR workflow operator dialogs)
- Gateway CallerID source identification: configurable outbound caller ID mode (none/fixed/extension) per gateway, with AstDB-backed dialplan manipulation for PSTN providers requiring valid source numbers
- IVR workflow editor: undo/redo system with toolbar buttons and keyboard shortcuts (Ctrl+Z, Ctrl+Y/Ctrl+Shift+Z); state-snapshot-based via flowchart `onAfterChange` callback, with disabled button styling when stacks are empty
- Master portal dashboard: separate `/dashboard/master` API endpoint and `dashboard_master_page` WebUI with system-wide KPIs (no tenant filter), tenant count cards, top tenants by call volume ranking, global server status (with tenant labels), and system-wide recent calls (with tenant column); fully separated from tenant dashboard via distinct hooks and permissions
- CDR pipeline: local SQLite3 storage (`cdr_sqlite3_custom`) on Asterisk servers for crash recovery, AMI-based real-time CDR forwarding to central router with Tenant resolution from Server ID, startup sync of unprocessed records, and duplicate detection
- CEL pipeline: Channel Event Logging capture via AMI (`cel_manager`) with Gearman forwarding and central `cel` table storage with Tenant resolution
- CDR expanded fields: `hangupcause` (Asterisk hangup cause code), `dialstatus` (Dial application result), `encryption` (TLS/SRTP signaling and media security status)
- Asterisk bootstrap sections: `cdr`, `cdr_manager`, `cdr_sqlite3_custom`, and `cel` added to bootstrap section registry with hook-based configuration generation
- Conference rooms module (ConfBridge): full CRUD API, WebUI, client daemon config generation
- Extension type "conference": maps DID/number to conference room with admin flag
- Asterisk bootstrap: confbridge.conf include and placeholder config
- IVR: 8 new operators - queue, voicemail, say, gotoivr, calleridcheck, http, conference, callback
- IVR: play operator interruptible mode (Background vs PlayBack)
- IVR: menu operator distinct "maxretries" output
- IVR: router operator regex condition type and AND/OR logic toggle
- IVR: dial operator CallerID name/number override and MOH class
- IVR: script operator ok/error outputs based on SHELL_EXIT_CODE
- Asterisk hook-based configuration generation: all bootstrap files (dialplan, PJSIP, queues, confbridge, voicemail, MOH, manager, indications, pjsip_notify) are now dynamically generated via named hooks
- Plugin extensibility: plugins can now append, modify, or replace any Asterisk configuration section through the hook pipeline
- Selective Asterisk module reload: only modules with changed configuration are reloaded (e.g., `dialplan reload`, `pjsip reload`)
- `asterisk_bootstrap_generate_section()`: allows modules to regenerate a single config section
- Bootstrap session: daemon restores pending VDFT sessions and regenerates bootstrap on startup
- Install/upgrade system restructure: version-aware `install_db` and `upgrade_db` hooks
- `install_add_db_alter()`: register ALTER TABLE statements for schema migrations
- `install_add_db_raw()`: escape hatch for arbitrary SQL during migrations
- `install_add_db_drop()`: register DROP statements for cleanup
- `SchemaLog` table: records every schema migration applied
- `ModuleVersions` table: tracks current version of every core module
- Core module version constants (`MODULE_*_VERSION`) in `module-versions.inc.php`
- System upgrade orchestrator (`POST /api/system/upgrade`): auto-detects needed upgrades and applies them
- Plugin `update.php` pattern: version-aware schema migrations with cumulative `version_compare()` blocks
- Remote DB manifest sync now includes version context for proper upgrade tracking on remote servers
- **Plugin system complete overhaul:**
  - `plugin.json` manifest format replacing legacy `.cfg` INI files
  - Plugin manifest validator (`plugin_validate_manifest()`) and config bridge (`plugin_manifest_to_config()`)
  - Extended Plugins table: AuthorEmail, SupportURL, PublishedAt, Daemons, SignedBy, Conflicts columns
  - Plugin state machine: A (Active), I (Inactive), S (Suspended), F (Failed)
  - Dependency enforcement: cannot disable/remove plugins that others depend on
  - Ed25519 cryptographic signing for plugin integrity verification
  - System keyring at `/etc/voipdomain/keyring/` with configurable policy (block/warn/allow_unsigned)
  - Default signing policy: `block` - unsigned plugins are rejected
  - Manifest-based signing: `_signature/` directory with MANIFEST, MANIFEST.sig, developer.pub
  - Plugin code deployed to `*-plugins/` directories (separated from core `*-modules/`)
  - Non-blocking Gearman worker added to FastAGI daemon (`vd_fastagi_{ID}_task`)
  - Gearman worker added to Monitor daemon (`vd_monitor_{ID}_task`)
  - Multi-daemon plugin deployment routing via Gearman (client, fastagi, monitor, router)
  - Plugin configuration sync to `/var/lib/voipdomain/data/plugin-{name}.json` on all daemons
  - inotify-based config change detection on daemon side
  - All 4 daemons (client, fastagi, monitor, router) scan `*-plugins/` on startup
  - Router SIGHUP handler detects new plugins and exits for clean restart
  - `plugin_gearman_dispatch()`: send tasks to specific daemon types on specific servers
  - `plugin_check_dependents()`: reverse dependency lookup for disable/remove enforcement
  - `plugin_sync_config_to_daemons()` / `plugin_remove_config_from_daemons()`: config lifecycle
- **Debug system overhaul:**
  - Output buffering (`ob_start()`) prevents stray PHP output from corrupting JSON API responses
  - Custom `set_error_handler()` captures all PHP warnings, notices, and deprecations into `$_in["php_errors"]` array
  - All API error paths now return structured JSON: `{result: false, error: "...", _debug: {...}}`
  - `database_query_error()` enhanced: includes backtrace, captured PHP errors, optional SQL query
  - New `api_error_response()` helper for non-database error exits with consistent structure
  - Debug information (`_debug` key with backtrace + PHP errors) automatically included in error responses when debug mode active
  - Debug information injected into successful responses (`_debug.php_errors`) when debug mode active and errors were captured
  - Broken `declare(ticks=1)` + `register_tick_function()` debug mechanism completely removed (non-functional in PHP 8+)
  - Debug modal redesigned to parse structured JSON error responses instead of fragile `__X_VD_DEBUG__` text markers
  - Swagger/OpenAPI documentation updated with `ErrorResponse` and `DebugInfo` component schemas
- OAS export parser enhancements: `deprecated` passthrough for parameters and schema properties; `readOnly`/`writeOnly` passthrough for schema properties - enables marking deprecated fields and distinguishing request-only vs response-only properties in generated Swagger documentation
- **Daemon error handling infrastructure:**
  - New `error-handler.inc.php` with centralized `register_shutdown_function()`, `set_error_handler()`, and `set_exception_handler()`
  - Fatal crashes (E_ERROR, E_PARSE, E_CORE_ERROR) now logged with "DAEMON CRASH:" prefix, file, and line before process terminates
  - Uncaught exceptions logged with class name, message, file:line, and abbreviated stack trace (up to 10 frames)
  - PHP warnings/notices/deprecations captured and logged with severity and source location instead of being silently discarded
  - New `VoIP_LOG_DEBUG` severity level for verbose messages only written in debug mode
  - All daemon event loops (client task_manager, router gearman_event, monitor AMI loop, FastAGI dispatch) wrapped in try/catch - a single bad hook no longer crashes the entire daemon process
  - `error_reporting(E_ALL)` replaces `error_reporting(E_ERROR)` in all daemons - custom handler manages filtering
- **Security & Compliance plugin (v1.1):** security dashboard with real-time encrypted call monitoring via daemon AMI, hierarchical TLS/SRTP policies (Tenant/Group/Extension) with inheritance, compliance reporting with CSV export, exceptions management (bypass whitelist), full CA lifecycle (generate/import/replace/remove with safety confirmation), certificate requirement enforcement (blocks TLS/encryption config without CA), per-server certificate management (generate/deploy/revoke), equipment provisioning hooks for 9 vendors, 12 new API endpoints, daemon realtime status hook, ~120 i18n entries (EN + pt_BR)

### Fixed

- Framework lazy-loading (`framework_call` in `plugins.inc.php`, `filters_call` in `config.inc.php`, `is_loaded` in `plugins.inc.php`): all three functions only searched `modules/` directory - plugin hooks, filters, and included-file detection in `plugins/` silently failed in cached/production mode; extended all three to search both `modules/` and `plugins/` directories
- Plugin DB install (`plugin_process_db` in `plugin-install.inc.php`): dependency resolution for tables, procedures, triggers, and data only checked within the current install batch - plugins declaring dependencies on pre-existing core tables (e.g., `Tenants`) silently failed to create any tables; now checks database for pre-existing tables and skips external dependencies for other object types
- Configuration edit (`configs_edit` in `config/api.php`): 6 bugs - form field `name="Length"` mismatched API parameter `NumbersLength` (value never received); typo `NumberLength` (missing 's') broke immutability check; `ON DUPLICATE KEY UPDATE` wrote `$parameters["Language"]` instead of `NumbersLength` (data corruption); typo `$parmeters` (missing 'a') prevented ORIGINAL defaults for Operator/MOH/NTP from being set; Country ORIGINAL stored numeric code (76) while form sends ISO code (BR); MOH/Operator type inconsistency between ORIGINAL (string) and sanitized parameter (int) caused phantom changes
- Plugin import: replaced deprecated `zip_open()`/`zip_read()`/`zip_entry_*()` procedural API (removed in PHP 8.2) with `ZipArchive` class; also auto-creates the `/var/spool/voipdomain/plugins/` directory on first upload if it doesn't exist
- OAS export parser (`debug_api_export`): `const` keyword silently dropped from exported schemas - discriminated `oneOf` schemas (e.g. `/config/modules`) produced no type constraint; now auto-converts `const` to single-value `enum` for OAS 3.0 compatibility
- OAS export parser: `"type" => "time"` not handled by type switch - server uptime/boottime fields silently dropped from export; added `time` to string format shorthand list
- OAS export parser: explicit `"format"` key silently dropped when paired with standard types (e.g. `"type" => "string", "format" => "binary"`) - plugin upload, audio data, and password fields lost format metadata; added format passthrough after type normalization
- OAS export parser: PHP undefined-index warnings on composition-only schemas (bare `oneOf`/`anyOf` without `type`) - guarded `$parameters["type"]` and `$result["type"]` accesses with `array_key_exists()` checks
- OAS export parser: HTTP 422 response only documented for POST methods - PATCH/PUT methods also return 422 on validation errors; extended condition to include all write methods
- OAS export parser: HTTP 400 description was "Object not found." (should be 404 semantics) - corrected to "Bad request."
- OAS export parser: `nullable` only supported for integer/number types - moved nullable handling outside type-specific switch to support all types per OAS 3.0 spec
- OAS source schemas: `"type" => "integer", "format" => "float"` in gateway Cost fields and plugin version - semantically wrong (float is a number format, not integer); changed to `"type" => "float"` shorthand
- LocaleDB: crash (500) on `/config` endpoint when locale names for current language not loaded - `array_key_exists()` received null instead of array; added lazy-load and defensive null check in `get_locale` filter
- IVR: date operator missing "other" fallback output
- IVR: email operator now applies variable substitution to To, Subject, and Body
- Server rebuild modal: success/failure notification now works even if modal is closed
- Server rebuild modal: reopening modal for server with rebuild in progress correctly disables submit button
- Server rebuild modal: closing modal during rebuild shows info notification instead of silently losing feedback
- Audios/MOH: `file convert` command and MOH `directory` used relative paths resolved from `astdatadir` instead of absolute paths, causing "No such file or directory" on systems where `astdatadir` differs from `astsoundsdir`
- Dialplan: `ExecIf` in `set_cdr` handler missing closing parenthesis, causing parse error in `[VoIPDomain-Tools]` context
- Dialplan: `configdb` `ExecIf` missing `$[...]` expression wrapper around condition
- SELinux: `voipdomain_t` lacked write permission to `asterisk_etc_t` (bootstrap/config generation blocked), `asterisk_var_lib_t` (audio file management blocked), and `asterisk_log_t` (CDR SQLite sync blocked)
- SELinux: `voipdomain_t` lacked manage permission to `tftpdir_rw_t` - equipment provisioning (firmware upload, config write) blocked in `/var/lib/tftpboot/`
- RPM `%post` scriptlets: daemon services were enabled at boot but never started on install/upgrade - `systemctl start` now runs for each daemon service and for nginx/php-fpm after all directory/permission setup completes
- Audio wipe: `audio_wipe()` relied solely on datafiles to identify audio directories for deletion - orphaned audio directories (from interrupted rebuilds or missing datafiles) were never cleaned, causing "Audio already exists" errors on subsequent rebuilds; now uses direct glob on `soundsdir` to recursively delete all `audio-*` directories
- Daemon logs: `framework_error()` emitted HTML `<strong>` tags in error messages - replaced with plain text for CLI/syslog readability
- IVR: audio files referenced in IVR workflows were not reported as in-use by the `audio_inuse` filter - added `ivr_audio_inuse()` to check `IVRWorkflows` for `File` and `InvalidFile` references
- Sidebar menu highlight on sub-pages: new `sys_set_menu_highlight()` function allows pages to declare alternative menu paths; the JS menu activation now falls back to these alternatives when the current URL has no exact sidebar match - applied to all add/view/edit/report/configure sub-pages across all modules
- DataTables: `sInfoFiltered` string was not wrapped in `VoIP.i18n.__()` - displayed hardcoded English "(filtered out of _MAX_ total records)" regardless of locale
- Dashboard: call disposition labels (`ANSWERED`, `NO ANSWER`, `BUSY`, `FAILED`) displayed as raw API values instead of translated text; time unit abbreviations (`m`, `s`) were also hardcoded
- Page-level JS i18n delivery: `sys_addtext()` mechanism was dormant - `$_in["page"]["i18n"]` was initialized and populated but never included in AJAX page responses and never processed by the frontend; wired up delivery in `index.php` and `voipdomain.js`
- OAS export i18n: all translatable strings in API documentation (endpoint summaries, descriptions, response descriptions, schema field descriptions and examples) displayed in English regardless of user language - modules are loaded before authentication sets the user's language, so `__()` calls in `api.php` hook definitions evaluated with default "en_US"; fixed by passing correct module context via `$context` parameter to `__()` in `debug_api_export()`, `format_schema()`, and `format_parameters()`; added `$_plugins["componentmap"]` to track which module registered each component schema
- OAS export: HTTP 503 response description used a string not registered in the language file - `"Internal server communication error (e.g. database failure)..."` did not match the registered key `"Internal server communication error."`; added correct registration and pt_BR translation
- OAS export: `ErrorResponse` and `DebugInfo` component schema descriptions were not registered in `debug/language.php` - added English keys and pt_BR translations for 11 missing strings
- Interface module i18n: 104 missing translation entries in `interface/language.php` - strings used via `__()` in `api.php`, `install.php`, `update.php`, `webui.php`, and `filter.php` were never registered with `i18n_add()`; added both English and pt_BR entries covering login validation, call report fields, installation/upgrade API descriptions, call type labels (phone/mobile/marine/toll free/special/satellite/service variants), codec labels, and dashboard UI strings
- i18n duplicate entries: removed duplicate `i18n_add()` registrations (both en_US and pt_BR) from 17 module `language.php` files - duplicates were introduced by code refactors that re-added strings without removing the originals; 102 duplicate lines removed total (agents, audios, blocks, costcenters, equipments, exceptions, extensions, gateways, groups, notifications, profiles, queues, ranges, reports, tenants, tokens, users)
- IVR module i18n: comprehensive cleanup of `ivr/language.php` - removed 7 duplicate entries (4 EN + 3 pt_BR: "Search IVRs", "Conditions", "View IVR", "IVR name"), removed 172 unused entries (86 EN + 86 pt_BR) for operator API strings never wired to `__()` calls (queue/voicemail/say/gotoivr/calleridcheck/http/conference/callback operators, plus replaced UI labels), added 48 missing entries (24 EN + 24 pt_BR) for new UI strings in config.php, fixed 4 wrong pt_BR translations ("Add IVR", "View IVR", "Edit IVR" were translated as "users" instead of "URA", "Search IVRs" had typographic apostrophe)

### Security

- Plugin signing system migrated from Ed25519/sodium to X.509 certificate-based RSA-SHA256 signatures - VoIP Domain master key (`voipdomain.pem`) distributed via repo/RPM as trusted project key; developer certificates are self-signed and manually installed in keyring; plugins optionally carry a `MANIFEST.official.sig` signed by the master key for an "official" badge; `php-sodium` dependency removed in favor of standard PHP `openssl` extension; new `voipdomain-filesystem` RPM sub-package owns common directories, master key, and keyring policy

### Changed

- Plugin enable/disable: both actions now trigger `server_rebuild` on all daemons - enabling a plugin regenerates Asterisk configuration with the plugin's hooks active; disabling removes the plugin's contributions
- Daemon plugin architecture: `plugin_db_apply` replaced with `plugin_db_apply_reject` - client daemons no longer attempt MySQL operations; DB manifests from admin interface are gracefully ignored with a log warning; plugins must use file-based storage (JSON in datadir) or SQLite3 for local persistence
- Administrators module: database tables (`Admins`, `AdminSFA`, `AdminSessions`), triggers, and default data moved from `tenants/install.php` to `admins/install.php` for proper module ownership
- Gateways: removed CallerID mode selector from UI; replaced "Caller ID number" field with "Source number" - CallerID mode is now inferred from the field value (empty = pass through, contains `{extension}` = extension-based, otherwise = fixed)
- Dashboard: complete overhaul - summary cards (total calls, answered, missed, extensions, servers, gateways), daily call volume line chart (tui.chart), server status panel with pending commands, recent calls table, fixed knob icons (LCR→clock, Allocation→sitemap), improved value labels
- About page: complete redesign with structured layout (header, feature cards, technical info table, project links, copyright footer) and full pt_BR translation
- VDFT client: temp files now use UUID-based names instead of TransferID
- VDFT client: temp file pre-allocated to final size on transfer begin (sparse file)
- VDFT client: .data JSON sidecar replaces .meta, stores per-chunk SHA1 hashes for integrity verification
- VDFT client: daemon startup scans staging directory and reloads pending sessions into memory for resume
- `Plugins.Version` column type changed from `float` to `varchar(32)` to support semver
- Plugin version comparisons now use `version_compare()` instead of numeric operators
- `plugin_process_db()` execution order expanded: procedures → tables → alters → raw → triggers → data
- Documentation: comprehensive rewrite of hook system (Section 6) and filter system (Section 7) in developer manuals (EN/PT) - covers `$_plugins` global structure, all registration/invocation/removal functions, handler signature requirements, lazy loading, deferred hook resolution, module context switching, and buffer lifecycle
- PHPDoc: validated and fixed ~67 PHPDoc issues across 27 files - wrong tag names (`$param`, `@array`, `@result`), non-standard param format (`@param $var type` → `@param type $var`), wrong/missing `@param` types, wrong `@return` types, missing `@global` declarations, and missing PHPDoc block

### Fixed

- `framework_remove_hook()` in `plugins.inc.php`: referenced undefined `$function` variable instead of checking if hook exists - replaced with `array_key_exists()` check
- `framework_remove_hook_function()` in `plugins.inc.php`: bare `return;` instead of `return false;` in error path

## [1.0.0] - 2026-07-21

First stable release. VoIP Domain is now production-ready for multi-tenant enterprise
VoIP deployments with centralized Asterisk management.

### Highlights

- Multi-tenant architecture with per-tenant cryptographic isolation
- Centralized management of multiple Asterisk servers via web interface
- Automatic phone provisioning (Yealink, Grandstream, Fanvil, Polycom, Snom, Cisco, AudioCodes, Htek, Khomp, Softphone)
- Least Cost Routing (LCR) with automatic dialplan generation
- Call billing engine
- IVR builder with workflow-configurable menus
- RESTful API with Swagger OAS 3.0 documentation
- Plugin architecture with remote sync
- VDFT (VoIP Domain File Transfer) streaming protocol
- Inter-server PJSIP trunks
- SELinux policy module
- RPM packaging for RHEL/Fedora

### Fixed (since 0.3 RC1)

- Server rebuild logic: frontend now correctly passes selected servers/backups to API
- Server rebuild: missing RSA key generation for new backup servers
- Server rebuild: parentheses bug in backup address validation (gethostbyname)
- Server rebuild: hook ordering for extensions-phones (dependency on profiles)
- Commands table: entries now properly deleted after successful client execution
- Router crypto: was using wrong keypair to decrypt client task replies (master key instead of tenant key)
- Router startup: fatal crash on first task_reply due to missing config (now reads keys from database)
- Router: pre-loads all cryptographic keys before dropping privileges to nobody
- Client daemon: system_event now verifies Gearman submission success and retries on failure
- Router: GroupedCommands counter properly decremented; completed groups cleaned up
- tmpfiles.d: removed duplicate entries for /var/lib/tftpboot and /var/log/voipdomain
- RPM: separated asterisk-specific tmpfiles to avoid systemd errors on admin-only servers
- IVR: fixed audio path resolution, FILTER syntax, wipe function
- Comprehensive bug fix sweep (~30 bugs across 16 files)

## [0.3-rc1] - 2026-06-05

- Dynamic timezone picker driven by host IANA/tzdata (replaces hardcoded map and offsets)
- Documentation reorganized by language (en/, pt_BR/)
- Rewritten installation and security guides
- Platform features: VDFT file transfer, plugin lifecycle and remote sync, monitor delta sync, inter-server PJSIP trunks, extension number migration

## [0.2] - 2025-07-21

- Major system refactoring to add Multi-Tenant support
- Aligned systemd units, nginx/php-fpm examples, and daemon paths

## [0.1] - 2025-04-11

- First pre-release after years of development and several rewrites

## [0.0.2] - 2018-10-17

- Hundreds of new features and bug fixes (alpha version)

## [0.0.1] - 2018-05-11

- Initial public alpha release
