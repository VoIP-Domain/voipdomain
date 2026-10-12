# Releases

Detailed release notes for VoIP Domain. Each minor and major release is documented
with new features, modifications, bug fixes, and security fixes.

---

## v1.1.0 - July 30, 2026

### New Features

- **Module registry system** - Centralized `$_in["modules"]` array populated during bootstrap tracks all loaded core modules and plugins. Each entry stores: `type` (core/plugin), `version`, `author`, `loaded` (bool), `path` (filesystem path), `files` (map of standard files found). Core modules get version from `MODULE_*_VERSION` constants and author "VoIP Domain". Plugins get metadata from `plugin.json` manifests (version, author, display_name, description, license, status). Disabled plugins (`disabled_*` directories) are registered with `loaded => false` and `status => "I"`. The registry is persisted in `cache.json` for cached loading paths and exposed via a new `GET /config/modules` API endpoint (Administrator permission). The `sys_register_module()` helper function handles registration for both types. This also fixes the pre-existing dependency check in `config/api.php` which read `$_in["modules"]` but the array was never populated.

- **Conference rooms module** - Full ConfBridge management via admin interface:
  - Database table `Conferences` with PIN, Admin PIN, max members, MOH, record,
    announce join/leave, wait for admin options
  - REST API: `GET|POST /api/conferences`, `GET|PUT|DELETE /api/conferences/:id`
  - WebUI with DataTables search, add/view/edit/clone pages
  - Client daemon generates Asterisk ConfBridge config files with bridge, user,
    and admin profiles per conference room
  - Full i18n (EN + pt_BR)

- **Extension type "conference"** - Maps extensions to conference rooms:
  - Database table `ExtensionConference` linking extensions to conferences with admin flag
  - Client daemon generates dialplan routing to `ConfBridge()` with appropriate profile
  - BLF hint: `Custom:conference-{name}`

- **Asterisk bootstrap: confbridge.conf** - `#include voipdomain/configs/confbridge-*.conf`

- **CDR pipeline** - End-to-end CDR collection with crash recovery:
  - Local SQLite3 storage on Asterisk server via `cdr_sqlite3_custom` with `processed` column for forwarding status tracking
  - AMI `Cdr` event capture by monitor daemon for real-time forwarding to central router via Gearman (`cdr_new` job)
  - Startup synchronization: on `FullyBooted`, monitor queries local SQLite for unprocessed records and forwards them
  - Router daemon resolves Tenant ID from Server ID before inserting into central `cdr` table
  - Duplicate detection by `uniqueid` + `server` prevents re-insertion
  - New CDR fields: `hangupcause` (Asterisk hangup cause code), `dialstatus` (Dial application result), `encryption` (TLS/SRTP status)
  - Bootstrap hooks generate `cdr.conf`, `cdr_manager.conf`, `cdr_sqlite3_custom.conf` automatically

- **CEL pipeline** - Channel Event Logging for granular call flow reconstruction:
  - AMI `CEL` event capture via `cel_manager` backend
  - Monitor daemon forwards events to central router via Gearman (`cel_new` job)
  - Router inserts into new `cel` table with Tenant resolution from Server ID
  - Tracks all channel lifecycle events: CHAN_START, ANSWER, BRIDGE_START, HOLD, DTMF, etc.
  - Bootstrap hooks generate `cel.conf` automatically
  - Applications tracked: Dial, Park, Queue, ConfBridge, VoiceMail

- **IVR: 8 new operators**:
  | Operator | Asterisk app | Description |
  |----------|--------------|-------------|
  | `queue` | `Queue()` | Route to queue with position announcement and max callers |
  | `voicemail` | `VoiceMail()` | Send to voicemail (busy/unavailable greeting) |
  | `say` | `SayDigits()`/`SayNumber()`/`SayAlpha()`/`SayUnixTime()` | Speak digits, numbers, letters, or date/time |
  | `gotoivr` | `GoTo()` | Jump to another IVR workflow |
  | `calleridcheck` | `GotoIf()` | Branch by CallerID number pattern |
  | `http` | `Set(${CURL()})` | HTTP GET/POST; response stored in variable |
  | `conference` | `ConfBridge()` | Send caller to a conference room |
  | `callback` | `Dial(Local/...)` | Schedule callback via local channel |

- **Security & Compliance plugin (v1.1)** - Complete security management suite:
  - Security dashboard with 6 stat cards (total extensions, encrypted %, unencrypted, exceptions, active encrypted calls, expiring certificates), compliance overview progress bars, real-time encrypted calls table via daemon AMI (`pjsip show channels`), and recent policy changes log
  - Hierarchical TLS/SRTP policies at Tenant, Group, and Extension levels with inheritance (child overrides parent)
  - Configuration page with certificate requirement enforcement - all form fields disabled with warning banner when no CA is installed; API rejects saves (HTTP 422) without active CA
  - Exceptions management - bypass whitelist for exempting extensions/groups from compliance, plus custom policy overrides
  - Compliance reporting - filterable DataTable with resolved effective policies per extension, status badges (Compliant/Non-compliant/Exempt), policy source, summary cards, and CSV export
  - Full CA lifecycle - generate internal self-signed CA (RSA 4096, 10-year), import external CA/intermediate (PEM), replace certificate (revokes old CA + all server certs), remove certificate (typed "REMOVE" confirmation, resets policy, triggers rebuild)
  - Per-server certificate management - generate (RSA 2048, 1-year), deploy to Asterisk/TFTP, revoke
  - Equipment provisioning hooks for SRTP/TLS injection across Yealink, Grandstream, Fanvil, Snom, Polycom, Cisco, AudioCodes, Htek, and Khomp
  - Daemon realtime status hook parsing AMI channel data for per-call encryption indicators
  - 12 API endpoints, ~120 i18n entries (EN + pt_BR)

### Modifications

- **Required field indicators on all forms** - Added CSS class `required` to all mandatory `form-group` divs across all 19 modules with add/edit/configure forms (blocks, gateways, extensions, groups, servers, agents, audios, conferences, costcenters, exceptions, queues, ranges, profiles, notifications, users, tokens, tenants, equipments, IVR). The existing `.required label.control-label::after` CSS rule in `system.css` renders a red asterisk after the field label. Previously this was only applied in IVR workflow operator dialogs (`config.php`).
- **Gateways CallerID UI simplification** - Removed the CallerID mode selector (none/fixed/extension); replaced "Caller ID number" field with "Source number" that is always visible. CallerID mode is now inferred from the field value: empty = pass through, contains `{extension}` = extension-based, otherwise = fixed number. Database schema and daemon logic unchanged.
- **IVR play operator** - New `Interruptible` toggle: uses `Background()` instead of `PlayBack()` when enabled
- **IVR menu operator** - New `maxretries` output link, distinct from `timedout`
- **IVR router operator** - New `regex` condition type; new `Logic` property for AND/OR evaluation
- **IVR dial operator** - New `CallerIDName`, `CallerIDNum` override properties; `MOHClass` for music on hold
- **IVR script operator** - Now returns two outputs (`ok`/`error`) based on `SHELL_EXIT_CODE`
- **VDFT client protocol** - Rewritten for resilience:
  - Temp files use UUID-based names (no real filenames on disk)
  - Temp file pre-allocated to final size on `file_transfer_begin` (sparse file via fseek+write)
  - `.data` JSON sidecar (replaces `.meta`) stores per-chunk SHA1 hashes, file metadata, total hash, and progress
  - Each received chunk validated (SHA1) and written at correct offset
  - On final chunk (`file_transfer_commit`), full file hash (SHA256) verified before dispatching target event
  - On daemon startup, staging directory scanned for `.data` files; pending sessions restored to memory for seamless resume
  - Multiple files per logical event handled as independent TransferIDs

- **Hook-based Asterisk configuration generation** - Complete redesign of bootstrap system:
  - All bootstrap files (dialplan, PJSIP, queues, confbridge, voicemail, MOH, manager, indications, pjsip_notify, cdr, cdr_manager, cdr_sqlite3_custom, cel) are now dynamically generated by the client daemon via named hooks
  - Pipeline pattern: each registered function receives the accumulated buffer and returns modified content, enabling plugins to append, modify, or replace any section
  - `write_bootstrap_file()`: compares content to detect real changes (ignoring auto-generated header)
  - Selective Asterisk module reload: only modules whose config file actually changed are reloaded (e.g., `dialplan reload`, `pjsip reload`, `module reload app_queue.so`)
  - `asterisk_bootstrap_generate_section()`: allows modules to regenerate a single config section independently
  - Bootstrap regeneration triggered by: server rebuild (`server_rebuild_post`), plugin state changes (`plugin_state_change`), daemon startup
  - Modules register their Asterisk config contributions: agents module adds `asterisk_dialplan_internal` for agent login; conferences adds `asterisk_confbridge_confbridge`; queues adds `asterisk_queues_queues`
  - Static bootstrap files in `/etc/asterisk/voipdomain/bootstrap/` eliminated - directory now holds only daemon-generated output
  - Full documentation in DIALPLAN.md (EN + PT_BR) updated for new architecture

- **Install/upgrade system restructure** - Version-aware schema management:
  - `install_db` hook now receives `$parameters["type"]` (`"install"`) and `$parameters["version"]` (target version)
  - New `upgrade_db` hook with `version_from`, `version_to`, and `type` parameters
  - `install_add_db_alter($name, $sql, $deps)`: register ALTER TABLE statements with dependency ordering
  - `install_add_db_raw($name, $sql, $deps)`: escape hatch for arbitrary SQL migrations
  - `install_add_db_drop($name, $type, $deps)`: register DROP statements for cleanup
  - `plugin_process_db()` execution order: procedures → tables → alters → raw → triggers → data
  - `SchemaLog` table: immutable log of every schema migration (module, from/to version, status, timestamp)
  - `ModuleVersions` table: tracks current version of every core module and plugin
  - Core module version constants in `includes/module-versions.inc.php` (38 modules)
  - System upgrade orchestrator (`POST /api/system/upgrade`): auto-detects version gaps, runs upgrade hooks, logs results
  - Plugin `update.php` pattern: cumulative `version_compare()` blocks for incremental schema evolution
  - Remote DB manifest sync includes version context for proper upgrade tracking on remote servers
  - `Plugins.Version` column changed from `float` to `varchar(32)` to support proper semver
  - All plugin version comparisons migrated from numeric `<`/`>` to `version_compare()`
  - Pre-1.1 installs auto-bootstrapped on first upgrade (tables created, versions seeded)
  - Full documentation: INSTALL-UPGRADE.md (EN + PT_BR)

- **Plugin system complete overhaul** - Modern plugin architecture:
  - `plugin.json` manifest replaces legacy `.cfg` INI format with full metadata schema
  - Manifest validator (`plugin_validate_manifest()`) ensures all required fields are present
  - Config bridge (`plugin_manifest_to_config()`) maps JSON manifest to internal pipeline format
  - Extended `Plugins` table: `AuthorEmail`, `SupportURL`, `PublishedAt`, `Daemons`, `SignedBy`, `Conflicts` columns
  - Plugin state machine with 4 states: A (Active), I (Inactive), S (Suspended), F (Failed)
  - Dependency enforcement: `plugin_check_dependents()` prevents disabling/removing plugins that others depend on
  - Ed25519 cryptographic signing (`plugin-signing.inc.php`):
    - Per-file SHA-256 manifest (`_signature/MANIFEST`)
    - Detached Ed25519 signature (`_signature/MANIFEST.sig`)
    - Developer public key reference (`_signature/developer.pub`)
    - System keyring at `/etc/voipdomain/keyring/` with `policy.conf`
    - Default policy: `block` - unsigned plugins are rejected at import
    - Configurable: `block`, `warn`, `allow_unsigned`
    - `trust_embedded_keys` option for development environments
  - Plugin code separated from core: deployed to `*-plugins/` directories (not `*-modules/`)
  - Non-blocking Gearman worker added to FastAGI daemon (`vd_fastagi_{serverid}_task`)
  - Gearman worker added to Monitor daemon alongside existing client (`vd_monitor_{serverid}_task`)
  - Multi-daemon deployment routing: `plugin_gearman_dispatch()` sends tasks to correct daemon type
  - Plugin configuration sync: admin pushes `plugin-{name}.json` to all target daemons' datadir
  - Daemons detect config changes via existing inotify integration
  - All 4 daemons (client, fastagi, monitor, router) scan `*-plugins/` directories on startup
  - Router SIGHUP handler detects new plugin directories and exits cleanly for systemd restart
  - RPM spec updated: `*-plugins/` directories created for all daemon packages, keyring packaged
  - 3 existing plugins migrated to `plugin.json` format (sampleitems, users-avatar, tellows-score)
  - Full developer documentation: PLUGINS.md (EN + PT_BR)

- **Debug system overhaul** - Structured error reporting for developers:
  - Output buffering (`ob_start()`) at API entry point prevents any stray PHP output (warnings, deprecations, notices) from corrupting JSON API responses
  - Custom `set_error_handler()` captures all non-fatal PHP errors into structured `$_in["php_errors"]` array with type, message, file, and line
  - All API error paths now return consistent structured JSON: `{result: false, error: "...", message: "..."}`
  - In debug mode, error responses include `_debug` key with full backtrace and captured PHP errors
  - `database_query_error()` enhanced: accepts optional `$query` parameter, includes `debug_backtrace()` at error point, discards any buffered output before emitting clean JSON
  - New `api_error_response($code, $error, $message)` helper for non-database error exits with the same structured format
  - Successful responses in debug mode include `_debug.php_errors` if any PHP warnings/deprecations were captured during request execution
  - Broken `declare(ticks=1)` + `register_tick_function("write_dbg_stack")` mechanism completely removed - this was non-functional in PHP 8+ for included files
  - Debug modal (`#vd_debug`) in web interface completely rewritten to parse new structured JSON format:
    - Displays error type, context, MySQL error details, and optional SQL query
    - Shows backtrace as numbered list with file:line → function() format
    - Shows captured PHP errors with type classification and source location
    - Falls back to raw content display for non-JSON responses
    - Modal body scrollable with max-height constraint
  - Swagger/OpenAPI documentation updated:
    - New `ErrorResponse` component schema documenting all possible error response fields
    - New `DebugInfo` component schema documenting the debug payload structure
    - 500 and 503 response definitions now reference `ErrorResponse` schema
  - All bare `header("503"); exit()` patterns across the codebase replaced with `database_query_error("context")` calls, ensuring every failure point produces structured diagnostic output

### Bug Fixes

- **OAS export parser** - 8 bugs fixed in `debug_api_export()`, `format_schema()`, and `format_parameters()`:
  - `const` keyword silently dropped from exported schemas - discriminated `oneOf` schemas (e.g. `/config/modules` core vs plugin) had no type constraint; now auto-converts `const` to single-value `enum` for OAS 3.0 compatibility
  - `"type" => "time"` not handled - server uptime/boottime fields dropped from export; added `time` shorthand (normalizes to `type: string, format: time`)
  - Explicit `"format"` key dropped when paired with standard types (e.g. `"type" => "string", "format" => "binary"`) - plugin upload, audio data, password fields lost format metadata; added format passthrough after type normalization in both `format_schema()` and `format_parameters()`
  - PHP undefined-index warnings on composition-only schemas (bare `oneOf`/`anyOf` without `type` key) - guarded all `$parameters["type"]` and `$result["type"]` accesses with `array_key_exists()`, wrapped type switch in `if` guard
  - HTTP 422 response only documented for POST - PATCH/PUT also return 422 on validation errors; extended condition to `post || put || patch`
  - HTTP 400 description was "Object not found." (404 semantics) - corrected to "Bad request."
  - `nullable` only supported for `integer`/`number` - moved nullable handling outside type-specific switch to support all types per OAS 3.0 spec
  - Source schema fix: `"type" => "integer", "format" => "float"` in gateway Cost fields (`gateways/api.php`) and plugin version (`config/api.php`) - semantically wrong (float format belongs to `type: number`); changed to `"type" => "float"` shorthand which the parser normalizes to `type: number, format: float`

- **OAS documentation i18n** - All API documentation strings (endpoint summaries, descriptions, response descriptions, schema field descriptions, examples) displayed in English regardless of user language. Root cause: `api.php` module files are loaded before authentication sets the user's language, so `__()` calls in `framework_add_hook()` definitions evaluated with default "en_US". Fix: `debug_api_export()`, `format_schema()`, and `format_parameters()` now receive the originating module name and pass it as the `$context` parameter to `__()` for correct i18n lookup. Added `$_plugins["componentmap"]` in `framework_add_component_documentation()` to track which module registered each OAS component schema. Also fixed HTTP 503 description string mismatch and added 11 missing `i18n_add()` registrations (EN + pt_BR) for `ErrorResponse` and `DebugInfo` component schema descriptions.

- **OAS export parser enhancements**:
  - `deprecated` passthrough for both parameters and schema properties - enables marking endpoints or fields as deprecated in generated Swagger docs
  - `readOnly` / `writeOnly` passthrough for schema properties - enables documenting response-only fields (e.g. auto-generated IDs) and request-only fields (e.g. passwords) in generated Swagger docs

- **JavaScript i18n fixes**:
  - DataTables `sInfoFiltered` string was not wrapped in `VoIP.i18n.__()` - displayed hardcoded English "(filtered out of _MAX_ total records)" regardless of locale; pt_BR translation already existed in language file but was unused
  - Dashboard call disposition labels (`ANSWERED`, `NO ANSWER`, `BUSY`, `FAILED`) displayed as raw API values instead of translated text - added disposition translation map with `__()` in both tenant and master dashboards
  - Dashboard time unit abbreviations (`m `, `s`) were hardcoded English - now use `__()` for translation support
  - Page-level JS i18n delivery (`sys_addtext()`) was dormant - `$_in["page"]["i18n"]` was initialized in `config.inc.php` and populated by `sys_addtext()` but never included in AJAX page responses (`index.php`) and never processed by the frontend (`voipdomain.js`); wired up both the server-side delivery (new `i18n` key in page JSON response) and client-side consumption (registers strings via `VoIP.i18n.add()` before executing inline JS)

- Sidebar menu highlight on sub-pages: new `sys_set_menu_highlight()` function allows pages to declare alternative menu paths for sidebar highlighting; JS menu activation falls back to these alternatives when the current URL has no exact sidebar match - applied to all add/view/edit/report/configure sub-pages across all modules
- IVR: audio files referenced in IVR workflows (`File` and `InvalidFile` operator properties) were not reported as in-use by the `audio_inuse` filter - added `ivr_audio_inuse()` to search `IVRWorkflows` blob for audio ID references
- IVR date operator: added missing "other" fallback output when no condition matches
- IVR email operator: `operator_apply_variables()` now applied to To, Subject, and Body fields
- Server rebuild modal: success/failure PNotify now fires regardless of whether modal is open
- Server rebuild modal: reopening modal for a server with rebuild in progress correctly shows disabled button with hourglass message
- Server rebuild modal: closing modal during active rebuild shows informational notification that user will be notified on completion
- **i18n duplicate entries** - Removed 102 duplicate `i18n_add()` lines (both en_US and pt_BR) from 17 module `language.php` files: agents (8), audios (4), blocks (6), costcenters (8), equipments (2), exceptions (4), extensions (6), gateways (6), groups (8), notifications (8), profiles (8), queues (6), ranges (8), reports (8), tenants (8), tokens (6), users (2). Duplicates were introduced by code refactors that re-added strings without removing the original registrations.

### Security Fixes

- **Plugin signing: Ed25519 → X.509** - Migrated the plugin signing and verification system from Ed25519 raw keys (`php-sodium`) to standard X.509 certificate-based RSA-SHA256 signatures (`php-openssl`). The VoIP Domain master key (`voipdomain.pem`, RSA 4096-bit, 20-year validity, self-signed) is distributed via the git repository and `voipdomain-filesystem` RPM package as the project's trusted signing key. Developers create their own self-signed X.509 certificates and administrators install them manually in the system keyring (`/etc/voipdomain/keyring/developers/`). The `_signature/` directory now contains `MANIFEST`, `MANIFEST.sig` (RSA-SHA256 developer signature, base64), `developer.pem` (developer's self-signed certificate), and optionally `MANIFEST.official.sig` (RSA-SHA256 signature by the VoIP Domain master key). Plugins carrying a valid official signature get a green "official/validated" badge. Verification matches developer certificates by SHA-256 fingerprint against the keyring. Policy configuration (`policy.conf`) is unchanged. A new `voipdomain-filesystem` RPM sub-package owns common directories (`/etc/voipdomain/`, `/var/lib/voipdomain/`, `/var/log/voipdomain/`), the master key, and keyring policy. All other sub-packages depend on it. The `php-sodium` dependency has been removed.

---

## v1.0.0 - July 21, 2026

First stable release. Production-ready for multi-tenant enterprise VoIP deployments
with centralized Asterisk management.

### New Features

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

### Bug Fixes

- Server rebuild logic: frontend now correctly passes selected servers/backups to API
- Server rebuild: missing RSA key generation for new backup servers
- Server rebuild: parentheses bug in backup address validation (gethostbyname)
- Server rebuild: hook ordering for extensions-phones (dependency on profiles)
- Commands table: entries now properly deleted after successful client execution
- Router startup: fatal crash on first task_reply due to missing config (now reads keys from database)
- Client daemon: system_event now verifies Gearman submission success and retries on failure
- Router: GroupedCommands counter properly decremented; completed groups cleaned up
- tmpfiles.d: removed duplicate entries for /var/lib/tftpboot and /var/log/voipdomain
- RPM: separated asterisk-specific tmpfiles to avoid systemd errors on admin-only servers
- IVR: fixed audio path resolution, FILTER syntax, wipe function
- Comprehensive bug fix sweep (~30 bugs across 16 files)

### Security Fixes

- Router crypto: was using wrong keypair to decrypt client task replies (master key instead of tenant key) - now uses per-tenant private key fetched from database
- Router: pre-loads all cryptographic keys before dropping privileges to `nobody`, eliminating filesystem key access after privilege drop
