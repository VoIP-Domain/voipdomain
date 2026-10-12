# VoIP Domain

[![GitHub release](https://img.shields.io/github/v/release/VoIP-Domain/voipdomain)](https://github.com/VoIP-Domain/voipdomain)
[![GitHub license](https://img.shields.io/github/license/VoIP-Domain/voipdomain.svg)](https://github.com/VoIP-Domain/voipdomain)

**VoIP Domain – The new era of VoIP management**

Migrating your telephony to VoIP has never been easier!

**VoIP Domain** was created to address a real business need: modernizing communications **without complications or disruption for end users**.

Our platform was built with a focus on simplicity and cutting-edge technology, offering:
- **Multi-Tenant**: The system supports multiple tenants, allowing service providers to offer the system to customers.
- **Smart Auto-Provisioning**: Automatically configure any device from any manufacturer with a unified standard.
- **Multiple Servers**: Manage a network of Asterisk VoIP servers easily using a modern interface.
- **Intuitive and Powerful Interface**: No need to be a VoIP or telephony expert to manage the system.
- **Full Compatibility**: Native support for multiple Asterisk servers, even offline.
- **Automatic Dial Plans** and **Smart Least-Cost Routing**.
- **Call Billing**: Native billing of each call.
- **Workflow-Configurable IVR**: Create dynamic and efficient call menus.
- **Local Standards**: Second dial tone, tones, and language automatically configured to ensure user familiarity.
- **Streamline Deployment**: once your servers are installed, all configurations can be managed through the web interface-no need to log in to individual servers again.
- **Complete RESTful API**: Generated directly from the code, documented in Swagger OAS 3.0.
- **100% Modular Architecture**: Build plugins, translations, custom integrations, and more!

**VoIP Domain** is the system that respects your legacy while delivering all the innovation your business needs for the future, allowing seamless interaction and control over your telephony infrastructure.

**Modernize your company’s communication today with VoIP Domain!**

A live demo will be available soon.

## Features

- Multi-tenant
- Automatic phone provisioning
- Least Cost Routing (LCR)
- Call billing
- Centralized management for multiple Asterisk servers
- Intelligent call routing
- RESTful API
- Code documentation
- Modular code with plugin support
- Multilingual
  - English (native language)
  - Brazilian Portuguese
- Easy to use

## Links

- Project homepage: https://voipdomain.io/
- RPM repository (RHEL): https://rhel.voipdomain.io/

## Project Status

**v1.1.0** - latest stable release; production-ready for multi-tenant enterprise VoIP deployments.

## Documentation

Full documentation index: [Documentations/README.md](Documentations/README.md) (EN) · [Documentations/pt_BR/README.md](Documentations/pt_BR/README.md) (PT-BR)

| Document | English | Português (BR) |
|----------|---------|----------------|
| **Quick start (30 min)** | [en/QUICK-START.md](Documentations/en/QUICK-START.md) | [pt_BR/QUICK-START.md](Documentations/pt_BR/QUICK-START.md) |
| **Developer manual (complete)** | [en/DEVELOPER-MANUAL.md](Documentations/en/DEVELOPER-MANUAL.md) | [pt_BR/MANUAL-DO-DESENVOLVEDOR.md](Documentations/pt_BR/MANUAL-DO-DESENVOLVEDOR.md) |
| Architecture | [en/ARCHITECTURE.md](Documentations/en/ARCHITECTURE.md) | [pt_BR/ARQUITETURA.md](Documentations/pt_BR/ARQUITETURA.md) |
| Install guide (full) | [en/INSTALL.md](Documentations/en/INSTALL.md) | [pt_BR/INSTALL.md](Documentations/pt_BR/INSTALL.md) |
| Deployment planning | [en/DEPLOYMENT-PLANNING.md](Documentations/en/DEPLOYMENT-PLANNING.md) | [pt_BR/PLANEJAMENTO-DE-IMPLANTACAO.md](Documentations/pt_BR/PLANEJAMENTO-DE-IMPLANTACAO.md) |
| Dialplan (Asterisk) | [en/DIALPLAN.md](Documentations/en/DIALPLAN.md) | [pt_BR/DIALPLAN.md](Documentations/pt_BR/DIALPLAN.md) |
| RPM packaging | [rpm/README.md](Documentations/rpm/README.md) | - |
| Testing | [en/TESTING-METHODOLOGY.md](Documentations/en/TESTING-METHODOLOGY.md) | [pt_BR/METODOLOGIA-DE-TESTES.md](Documentations/pt_BR/METODOLOGIA-DE-TESTES.md) |
| FAQ | [en/FAQ.md](Documentations/en/FAQ.md) | [pt_BR/FAQ.md](Documentations/pt_BR/FAQ.md) |
| Security | [en/SECURITY.md](Documentations/en/SECURITY.md) | [pt_BR/SEGURANCA.md](Documentations/pt_BR/SEGURANCA.md) |
| Recent features | [en/IMPLEMENTED-FEATURES.md](Documentations/en/IMPLEMENTED-FEATURES.md) | [pt_BR/RECURSOS-IMPLEMENTADOS.md](Documentations/pt_BR/RECURSOS-IMPLEMENTADOS.md) |
| Sample plugin | [Plugins/sampleitems/README.md](Plugins/sampleitems/README.md) | - |

## Release History

**v0.0.1 – May 11, 2018**
- Initial public (alpha) release

**v0.0.2 – October 17, 2018**
- Hundreds of new features and bug fixes (alpha version)

**v0.1 – April 11, 2025**
- After years of development and several rewrites, this version marks the first pre-release

**v0.2 – July 21, 2025**
- A major system refactoring to add Multi-Tenant support and some minor optimizations

**v0.3 RC1 – June 5, 2026**
- Dynamic timezone picker driven by host IANA/tzdata (replaces hardcoded map and offsets)
- Documentation reorganized by language (`Documentations/en/`, `Documentations/pt_BR/`)
- Rewritten installation and security guides; RPM `%doc` aligned with new layout
- Platform features: VDFT file transfer, plugin lifecycle and remote sync, monitor delta sync, inter-server PJSIP trunks, extension number migration, and related daemon hooks

**v1.0.0 – July 21, 2026**
- First stable release - production-ready for multi-tenant enterprise VoIP deployments
- Server rebuild logic overhauled (selective main/backup rebuild, per-server crypto keys)
- Router cryptographic isolation: per-tenant key management, pre-privilege-drop key caching
- Command lifecycle cleanup: executed commands properly removed from database
- Comprehensive bug fix sweep (~30 issues across 16 files)
- Full documentation refresh for 1.0

## Changes in release v1.1.0

**New features:**
- Administrators module: master portal administrator management (CRUD + SFA) via `Admins` table, with "Administrators" menu under Configurations for Super-Administrators
- Module registry: centralized `$_in["modules"]` tracks all core modules and plugins with version, author, type, path, loaded status; new `GET /config/modules` API endpoint for diagnostics
- Gateway CallerID source identification: configurable outbound caller ID mode (none/fixed/extension) per gateway, with AstDB-backed dialplan manipulation for PSTN providers requiring valid source numbers
- Master portal dashboard: dedicated system-wide dashboard with separate API (`/dashboard/master`), global KPIs, tenant ranking, cross-tenant server status, and system-wide recent calls
- Conference rooms module (ConfBridge): full CRUD API, WebUI, client daemon, extension type
- IVR: 8 new operators (queue, voicemail, say, gotoivr, calleridcheck, http, conference, callback)
- IVR: play interruptible mode, menu maxretries output, router regex/AND-OR, dial CallerID/MOH, script ok/error outputs
- IVR: undo/redo in workflow editor (toolbar buttons + Ctrl+Z/Ctrl+Y keyboard shortcuts)
- VDFT client: resumable file transfers with UUID temp files, pre-allocation, per-chunk SHA1 tracking, and daemon startup session restore
- CDR/CEL pipeline: local SQLite3 crash recovery on Asterisk, real-time AMI forwarding to central router with Tenant resolution, startup sync of missed records, new `cel` table for channel event logging, expanded CDR fields (`hangupcause`, `dialstatus`, `encryption`)
- Hook-based Asterisk configuration: all bootstrap files dynamically generated via pipeline hooks; plugins can modify any config section; selective per-module Asterisk reload; new sections for `cdr`, `cdr_manager`, `cdr_sqlite3_custom`, and `cel`
- Install/upgrade system: version-aware hooks (`install_db`/`upgrade_db`), `SchemaLog` and `ModuleVersions` tables, `install_add_db_alter()`/`install_add_db_raw()`, system upgrade API endpoint, plugin `update.php` pattern with cumulative `version_compare()` blocks
- Plugin system overhaul: `plugin.json` manifest, X.509 certificate-based signing with RSA-SHA256 and optional official badge (default policy: block), new `voipdomain-filesystem` RPM sub-package for common directories and master signing key, multi-daemon Gearman workers (FastAGI/Monitor), `*-plugins/` directories, config sync to datadir, dependency enforcement, state machine (A/I/S/F), system keyring
- Debug system overhaul: structured JSON error responses on all API error paths, output buffering to prevent stray PHP output corruption, custom error handler captures all warnings/deprecations, enhanced debug modal with backtrace and PHP error display, `ErrorResponse`/`DebugInfo` schemas in Swagger docs
- Daemon error handling: centralized crash/exception/error handlers for all 4 daemons - fatal crashes logged with `DAEMON CRASH:` prefix before termination, uncaught exceptions logged with full trace, PHP warnings captured instead of silently discarded, event loops wrapped in try/catch for resilience, new `VoIP_LOG_DEBUG` severity level
- Security & Compliance plugin (v1.1): security dashboard with real-time monitoring, hierarchical TLS/SRTP policies (Tenant/Group/Extension), compliance reporting with CSV export, exceptions management, full CA lifecycle (generate/import/replace/remove), certificate requirement enforcement, server certificate management, and equipment provisioning hooks for 9 vendors

**Improvements:**
- Forms: all mandatory fields now show a red asterisk indicator across all 19 modules (blocks, gateways, extensions, groups, servers, agents, audios, conferences, costcenters, exceptions, queues, ranges, profiles, notifications, users, tokens, tenants, equipments, IVR)
- Gateways: simplified CallerID configuration - removed mode selector, replaced with a single "Source number" field (empty = pass through, value with `{extension}` wildcard supported)
- Dashboard: complete overhaul with summary cards, daily call volume chart, server status panel, recent calls table, and fixed KPI icons
- About page: complete redesign with feature cards, technical information, project links, and full pt_BR translation
- Plugin enable/disable now triggers `server_rebuild_config` on all daemons, ensuring plugin hooks are applied/removed from Asterisk configuration immediately
- Daemon plugin architecture: client daemons no longer attempt MySQL operations; plugins must use file-based storage (JSON in datadir) or SQLite3 for local persistence
- Administrators module: database tables (`Admins`, `AdminSFA`, `AdminSessions`), triggers, and default data moved from `tenants/install.php` to `admins/install.php`
- Sidebar menu: sub-pages (add/view/edit/report/configure) now correctly keep the parent menu item active and the submenu expanded via new `sys_set_menu_highlight()` mechanism
- IVR: audio files used in IVR workflows now correctly reported as in-use (prevents accidental deletion)
- OAS export parser: new `deprecated`, `readOnly`, and `writeOnly` schema property passthrough for richer Swagger documentation

**Bug fixes:**
- Framework lazy-loading: `framework_call()`, `filters_call()`, and `is_loaded()` only searched `modules/` - plugin hooks, filters, and include detection in `plugins/` silently failed in cached/production mode; extended all three to search both `modules/` and `plugins/`
- Plugin DB install: `plugin_process_db()` dependency resolution only checked within the current install batch - plugins with dependencies on pre-existing core tables (e.g., `Tenants`) silently failed to create any tables during web-based plugin installation
- Configuration edit: 6 bugs in `configs_edit` - form field name mismatch (`Length` vs `NumbersLength`), typo in immutability check, `ON DUPLICATE KEY UPDATE` writing wrong variable (data corruption), typo `$parmeters` preventing ORIGINAL defaults, Country format mismatch (numeric vs ISO), and MOH/Operator type inconsistency
- JavaScript i18n: DataTables `sInfoFiltered` string not translated; dashboard call dispositions and time units displayed as hardcoded English; `sys_addtext()` page-level JS i18n delivery mechanism wired up (was dormant)
- OAS documentation i18n: all API documentation strings (summaries, descriptions, response descriptions, schema fields) displayed in English regardless of user language - modules load before authentication sets the language; fixed by passing correct module context (`$context` parameter) to `__()` at export time via `$_plugins["componentmap"]` for component schemas; also fixed 503 description string mismatch and added 11 missing translations for ErrorResponse/DebugInfo schemas
- OAS export parser: 8 bugs fixed - `const` keyword dropped (broke discriminated schemas), `time` type dropped, explicit `format` dropped, PHP warnings on composition schemas, 422 only on POST, wrong 400 description, nullable limited to integer/number, wrong type+format in source schemas
- LocaleDB: fixed crash (500) on `/config` endpoint when locale names for current language were not loaded at boot time
- IVR: date operator missing "other" fallback output
- IVR: email operator variable substitution on To/Subject/Body
- Server rebuild modal: notifications now work even if modal is closed; button state preserved across reopen; closing during rebuild shows info notification
- RPM packaging: daemon services now started automatically on install/upgrade (previously only enabled for boot)
- Audio wipe: orphaned audio directories (from interrupted rebuilds) no longer cause "Audio already exists" errors - wipe now globs `soundsdir` directly for cleanup
- SELinux: added `tftpdir_rw_t` policy for equipment provisioning in `/var/lib/tftpboot/`
- Daemon logs: removed HTML tags from `framework_error()` messages
- Interface module i18n: 104 missing translation entries (EN + pt_BR) for strings used across login, installation, upgrade, call reports, call type labels, codecs, and dashboard
- i18n duplicate entries: removed 102 duplicate `i18n_add()` lines from 17 module `language.php` files (caused by code refactors re-adding strings without removing originals)
- IVR module i18n: removed 7 duplicates and 172 unused entries, added 48 missing entries (24 EN + 24 pt_BR) for new operator UI strings, fixed 4 wrong pt_BR translations

**Security:**
- Plugin signing migrated from Ed25519/sodium to X.509 RSA-SHA256 - VoIP Domain master key as trusted project key, self-signed developer certificates installed in keyring, optional official badge via `MANIFEST.official.sig`, `php-sodium` dependency removed in favor of standard `openssl` extension, new `voipdomain-filesystem` RPM sub-package owns common directories and master key

**Documentation:**
- Hook and filter system: comprehensive rewrite of Sections 6 and 7 in developer manuals (EN/PT) - covers `$_plugins` global structure, all framework functions, handler signatures, lazy loading, deferred resolution, module context, and buffer lifecycle
- PHPDoc: validated and fixed ~67 issues across 27 files - wrong tags, non-standard format, wrong types, missing params/return/global declarations
- Code fixes: `framework_remove_hook()` undefined variable bug; `framework_remove_hook_function()` bare return in error path

See [RELEASES.md](RELEASES.md) for full details.

## License

This project is licensed under the **GPLv3**.
