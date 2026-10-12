# VoIP Domain — Developer Manual

Complete reference for maintaining VoIP Domain, writing modules, and creating plugins.

**Portuguese:** [../pt_BR/MANUAL-DO-DESENVOLVEDOR.md](../pt_BR/MANUAL-DO-DESENVOLVEDOR.md)  
**Architecture overview:** [ARCHITECTURE.md](ARCHITECTURE.md)  
**Installation:** [INSTALL.md](INSTALL.md)

---

## Table of contents

1. [Introduction](#1-introduction)
2. [Repository layout](#2-repository-layout)
3. [Runtime environments](#3-runtime-environments)
4. [Bootstrap and module loading](#4-bootstrap-and-module-loading)
5. [Global state: `$_in`](#5-global-state-_in)
6. [Hooks](#6-hooks)
7. [Filters](#7-filters)
8. [Hook and filter naming conventions](#8-hook-and-filter-naming-conventions)
9. [REST API](#9-rest-api)
10. [Web interface](#10-web-interface)
11. [Authentication and permissions](#11-authentication-and-permissions)
12. [Multi-tenant model](#12-multi-tenant-model)
13. [Gearman and `notify_server`](#13-gearman-and-notify_server)
14. [Daemons](#14-daemons)
15. [Monitor and router event flow](#15-monitor-and-router-event-flow)
16. [Client daemon and Asterisk](#16-client-daemon-and-asterisk)
17. [VDFT file transfer](#17-vdft-file-transfer)
18. [Plugin system](#18-plugin-system)
19. [Extensions](#19-extensions)
20. [Equipment provisioning](#20-equipment-provisioning)
21. [IVR workflow engine](#21-ivr-workflow-engine)
22. [Database: `vd` vs `vdclient`](#22-database-vd-vs-vdclient)
23. [Configuration files](#23-configuration-files)
24. [Internationalization](#24-internationalization)
25. [Debugging and tooling](#25-debugging-and-tooling)
26. [Creating a plugin step by step](#26-creating-a-plugin-step-by-step)
27. [Coding conventions](#27-coding-conventions)
28. [Appendix: hook lifecycle phases](#28-appendix-hook-lifecycle-phases)

---

## 1. Introduction

VoIP Domain is a **multi-tenant VoIP management platform** built around Asterisk. Its core design principle is **extensibility without forking core code**: almost every feature is a PHP function registered on a string hook, similar to WordPress pluggable functions.

You will work with:

- **Hooks** — ordered function chains for actions and API handlers.
- **Filters** — one function per module per filter name; transform shared data (menus, types, session).
- **Gearman** — async job queue between admin, router, and client daemons.
- **INI config files** under `/etc/voipdomain/`.

Minimum PHP version for application logic: **5.5** (mysqli transactions, modern syntax). RHEL 7 ships PHP 5.4 — use Remi or a newer OS for production.

---

## 2. Repository layout

```
VoIP Domain/
├── Administrative Interface/
│   ├── index.php              # SPA shell, page routing
│   ├── api.php                # REST API front controller
│   ├── calls.php              # Call recording playback entry
│   ├── includes/
│   │   ├── config.inc.php     # Bootstrap, $_in init, DB connect
│   │   ├── plugins.inc.php    # framework_add_hook, framework_call
│   │   ├── plugin-install.inc.php
│   │   ├── file-transfer.inc.php
│   │   ├── systemd.inc.php    # systemd unit reload helpers
│   │   ├── timezonepicker_ui.inc.php  # Dynamic timezone map/select markup
│   │   └── functions.inc.php  # notify_server, helpers
│   ├── modules/<name>/        # Core modules (see below)
│   └── plugins/<name>/        # Deployed plugins (runtime)
├── Daemons/
│   ├── client.php             # Asterisk node worker
│   ├── router.php             # Central event router
│   ├── monitor.php            # AMI listener
│   ├── fastagi.php            # FastAGI server
│   ├── includes/              # Shared daemon libraries
│   ├── client-modules/        # Per-feature + vendor client hooks
│   ├── router-modules/
│   ├── monitor-modules/
│   └── fastagi-modules/
├── Plugins/                   # Source templates for custom plugins
└── Documentations/
```

### Standard module files (admin)

| File | Purpose |
|------|---------|
| `api.php` | REST hooks + `framework_add_api_call` |
| `webui.php` | Page hooks, HTML fragments |
| `filter.php` | `framework_add_filter` registrations |
| `language.php` | `i18n_add()` translation strings |
| `install.php` | DB schema on `install_db` hook |
| `update.php` | Schema migrations on `upgrade_db` |
| `functions.php` | Shared helpers |
| `functions-api.php` / `functions-webui.php` | Split helpers |
| `config.php` | Module config pushed into `$_in` |

Core modules include: `interface`, `authentication`, `tenants`, `users`, `tokens`, `servers`, `extensions`, `extensions-phones`, `extensions-queues`, `extensions-ivr`, `extensions-hunts`, `ivr`, `queues`, `agents`, `gateways`, `ranges`, `profiles`, `groups`, `blocks`, `exceptions`, `equipments`, `equipments-*` (vendors), `audios`, `notifications`, `config`, `debug`, `countrydb`, `currencies`, `costcenters`.

---

## 3. Runtime environments

| Environment | Entry point | Config file | Database |
|-------------|-------------|-------------|----------|
| Web UI | `index.php` | `webinterface.conf` | `vd` |
| REST API | `api.php` | `webinterface.conf` | `vd` |
| Client daemon | `client.php -d` | `client.conf` | `vdclient` |
| Router daemon | `router.php -d` | `router.conf` | `vd` |
| Monitor daemon | `monitor.php -d` | `monitor.conf` | `vdclient` |
| FastAGI | `fastagi.php -d` | `fastagi.conf` | Local files |

Each environment loads only the PHP files relevant to its role (API modules are not loaded in `index.php` page cache path except via cached hook map).

---

## 4. Bootstrap and module loading

### 4.1 Web UI (`index.php`)

1. `config.inc.php` — parse `/etc/voipdomain/webinterface.conf` or set install mode.
2. Load `modules/interface/{language,filter,webui}.php`.
3. If install mode → `install_page_generate` and exit.
4. **Module discovery:**
   - **Production:** read `modules/cache.json` (hooks, filters, paths, i18n); load `functions.php`, `functions-webui.php`, `config.php` per module.
   - **Debug mode (`general.debug = true`):** rescan all `modules/*` and `plugins/*`; rebuild cache.
5. Authenticate session; route to `framework_page_generate` or login hook.

Plugins in `plugins/disabled_<name>/` are **skipped**.

### 4.2 REST API (`api.php`)

1. `config.inc.php` + shutdown handler (flushes `notifycapture` via Gearman).
2. Load `modules/interface/api.php` + every `modules/*/api.php` (or `install.php` in install mode).
3. Load `plugins/*/api.php` (skipped for `127.0.0.1` internal calls).
4. Authenticate (see §11).
5. Match `$_paths` by HTTP method + URI → `framework_call(main_hook, $parameters)`.
6. JSON-encode return value.

### 4.3 Client daemon (`Daemons/client.php`)

1. Load `includes/{functions,plugins,file-transfer,systemd,plugin-install}.inc.php`.
2. Parse `client.conf`; connect MySQL `vdclient`; load crypto keys.
3. Glob `client-modules/*/config.php` then `client-modules/*/client.php`.
4. Drop privileges to `[daemon] uid/gid` (typically `asterisk`).
5. Register Gearman worker `vd_client_{serverid}_task` (+ backup task if `backup = true`).
6. `task_replay` on startup; enter `work()` loop.

### 4.4 Router daemon (`Daemons/router.php`)

1. Parse `router.conf`; connect `vd`.
2. Load all `router-modules/*/router.php`.
3. Register **every hook name** as a Gearman function → `gearman_event` → `framework_call`.
4. HTTP server on `[daemon] port` (default 8080) for internal callbacks.
5. Load notification rules from DB.

### 4.5 Monitor daemon (`Daemons/monitor.php`)

1. Parse `monitor.conf`; connect `vdclient`.
2. Load `monitor-modules/*/monitor.php` (registers AMI event hooks).
3. Connect AMI; on each event → `framework_call($event["Event"], $event)`.
4. Monitor modules call `$gm->doBackground("<router_hook>", $json)`.

---

## 5. Global state: `$_in`

`$_in` is the framework configuration and runtime bag, initialized in each environment's bootstrap.

### Admin (`config.inc.php`)

| Key | Description |
|-----|-------------|
| `mode` | `install` or `normal` |
| `general` | INI `[general]` + runtime overrides |
| `mysql` | Connection params + `id` (mysqli resource) |
| `api` | `baseurl`, `baseuri` |
| `gearman` | `servers`, lazy `socket` (GearmanClient) |
| `session` | Auth state (see §11) |
| `page` | Current page title, assets, i18n strings for JS |
| `notifycapture` | Batched `notify_server` calls (API shutdown flush) |
| `module` | Current module name during hook execution |

### Client daemon

| Key | Description |
|-----|-------------|
| `daemon` | `serverid`, `backup`, `uid`, `gid`, URLs |
| `keys` | Loaded PEM contents: `privateKey`, `publicKey`, `masterPublicKey` |
| `processed` | Command ID deduplication map |
| `cleanup` | Registered wipe handlers (dependency order) |
| `equipments` | Vendor/model registry from config modules |

### Router daemon

| Key | Description |
|-----|-------------|
| `Channels`, `Queues`, `Peers` | Live state indexed by server ID |
| `Notifications` | Active notification rules |

---

## 6. Hooks

### 6.1 Registration

```php
framework_add_hook ( "ranges_add", "ranges_add", IN_HOOK_NULL, $documentation);
```

| Constant | Effect |
|----------|--------|
| `IN_HOOK_NULL` (0) | Append handler to end of chain |
| `IN_HOOK_INSERT_FIRST` (1) | Prepend handler |
| `IN_HOOK_REMOVE_OTHERS` (2) | Clear existing handlers first |
| `IN_HOOK_REPLACE` (4) | Used with `framework_add_hook_at` |
| `IN_HOOK_INSERT_BEFORE` / `IN_HOOK_INSERT_AFTER` | Positional insert |

Optional 4th argument: OpenAPI documentation array (merged into Swagger export).

### 6.2 Invocation

```php
if ( framework_has_hook ( "ranges_add_pre"))
{
  $parameters = framework_call ( "ranges_add_pre", $parameters);
}
$result = framework_call ( "ranges_add", array (), false, $parameters);
```

Signature for every hook handler:

```php
function my_handler ( $buffer, $parameters)
{
  // $buffer: accumulated return from prior handlers in chain
  // $parameters: caller context (often the API parameter array)
  return $buffer; // or new value replaces buffer for next handler
}
```

`framework_call` returns the final buffer after all handlers run. Loop detection prevents re-entrant calls on the same hook.

### 6.3 Dynamic hooks

Some hooks are built at runtime:

```php
framework_call ( "ivr_operator_" . $operator["Operator"], $params);
framework_call ( "extensions_add_" . $parameters["Type"] . "_post", ...);
framework_call ( "equipment_model_" . $model . "_configure", ...);
```

Always guard with `framework_has_hook()` when the target may not exist.

### 6.4 Daemon hook return format

Client event handlers invoked from `task_manager` should return:

```php
return array (
  "code"    => 200,           // HTTP-like status
  "message" => "OK",
  "content" => $optionalData,
);
```

Non-2xx codes are logged; `task_finished` still updates `Commands` based on code.

---

## 7. Filters

Filters differ from hooks: **at most one handler per module** per filter name.

```php
framework_add_filter ( "page_menu_registers", "myplugin_menu");
```

```php
$menu = filters_call ( "page_menu_registers", $parameters, $existingMenu);
```

Filter handlers signature:

```php
function myplugin_menu ( $buffer, $parameters)
{
  $buffer[] = array ( "title" => __ ( "My plugin"), "url" => "/myplugin");
  return $buffer;
}
```

Common filters:

| Filter | Purpose |
|--------|---------|
| `objects_types` | Register extension/object types |
| `page_menu_registers` / `page_menu_configurations` / `page_menu_resources` | Admin menu sections |
| `session_extend` | Add data to user session after login |
| `authentication_plugins` | OAuth provider list |
| `extensions_add_subpages` | Extra tabs on extension forms |
| `e164_identify` / `e164_identify_country_XXX` | Number normalization |

Optional filter slots (may have zero registrants): `page_menu_others`, `page_menu_reports_*`, `authentication_failure`.

---

## 8. Hook and filter naming conventions

### 8.1 CRUD API hooks

Pattern: `{entity}_{action}` as **main hook**, with optional phases:

```
{entity}_{action}_start
{entity}_{action}_validate
{entity}_{action}_sanitize
{entity}_{action}_pre
{entity}_{action}_post
{entity}_{action}_notify
{entity}_{action}_finish
```

| Action | Meaning |
|--------|---------|
| `search` | List/filter |
| `view` | Read single |
| `add` | Create |
| `edit` | Update |
| `remove` | Delete |
| `clone` | Duplicate |

Examples: `agents_add`, `queues_edit_notify`, `servers_rebuild_config`.

**Entity prefix** matches module name: `extensions`, `ranges`, `gateways`, `extension_phone`, `extension_queue`.

### 8.2 Page hooks (Web UI)

Pattern: `{entity}_{action}_page` or `{feature}_page`.

Examples: `extensions_search_page`, `login_page_generate`, `install_page_generate`, `about_page`.

### 8.3 Daemon / Gearman event hooks (client)

Pattern: `{domain}_{action}` — **must match** `notify_server` second argument exactly.

Examples:

```
server_rebuild_config
extension_phone_add
extension_phone_change
extension_phone_remove
queue_add
queue_join_member
equipment_configure
firmware_remove
plugin_daemon_module_deploy
plugin_db_apply
daemon_reload
account_add
```

Full list: grep `notify_server(` in `Administrative Interface/` or inspect client hooks via `GET /debug/api/map` (requires `general.debug = true`).

### 8.4 Router hooks (from monitor)

AMI-derived events forwarded by monitor modules:

```
channels_bootup, channels_create, channels_state, channels_destroy, channels_unload
queues_bootup, queues_member_add, queues_caller_add, queues_caller_abandon, ...
peers_bootup, peer_status, peers_unload
```

Registered in `router-modules/`; called via Gearman from `monitor-modules/`.

### 8.5 IVR operators (client)

Pattern: `ivr_operator_{operator}` where `{operator}` is lowercase from workflow JSON (`start`, `menu`, `dial`, …).

Admin validation filters: `ivr_operator_filter_{operator}`.

### 8.6 Equipment (client)

Per model (lowercase, no spaces):

```
equipment_model_{model}_configure
equipment_model_{model}_firmware_add
equipment_model_{model}_firmware_remove
ap_type_{model}
account_type_{model}
```

Vendor config in `client-modules/equipments-{vendor}/config.php` registers models in `$_in["equipments"]`.

### 8.7 Plugin lifecycle

```
plugin_{name}_install
plugin_{name}_upgrade
plugin_{name}_remove
install_db / upgrade_db / uninstall_db   (global DB hooks)
```

### 8.8 Infrastructure hooks

| Hook | Role |
|------|------|
| `router_reload_cache` | Invalidate router in-memory cache |
| `dialplan_generate` | Regenerate dialplan snippet |
| `file_transfer_begin/chunk/commit/abort` | VDFT protocol |
| `ping` | Health check |
| `debug_api_map` | Export hook/route map |

---

## 9. REST API

### 9.1 Declaring an endpoint

```php
framework_add_hook ( "widgets_view", "widgets_view", IN_HOOK_NULL, $doc);
framework_add_api_call (
  "/widgets/:ID",
  "Read",
  "widgets_view",
  array (
    "permissions" => array ( "User", "widgets_view"),
    "title"       => __ ( "View widget"),
    "parameters"  => array ( ...),
    "response"    => array ( 200 => array ( "schema" => ...)),
  )
);
framework_add_permission ( "widgets_view", __ ( "View widgets"));
```

HTTP verbs for `framework_add_api_call`: `Create`, `Read`, `Update`, `Delete`, `Custom`.

Actual URL: `{baseuri}/widgets/:ID` (default `/api/widgets/:ID`).

### 9.2 Versioning

- Header: `X-API-VERSION: 2`
- Path prefix: `/api/v2/widgets`

### 9.3 Parameters

Route params (`:ID`) and body/query merge into `$parameters` passed to hooks. Validation in `{hook}_validate` returns field errors → **422**.

### 9.4 Permissions

`framework_add_permission ( "perm_name", "Human label")` — referenced in endpoint `permissions` array as `array ( "User", "perm_name")`.

Reserved: `Internal`, `Install`, `Token`, `AuthToken`, `User`, `Administrator`, `Super-Administrator`.

### 9.5 OpenAPI

Documentation arrays on hooks feed `/api/docs` (Swagger UI). Use `GET /debug/api/map` for machine-readable map.

### 9.6 Internal API calls

From localhost (`127.0.0.1`), authentication becomes `Internal` — used for server-to-server callbacks.

---

## 10. Web interface

Pages are HTML fragments assembled by hook chains ending in `framework_page_generate`.

1. Router resolves path → page hook (e.g. `extensions_search_page`).
2. Modules register with `IN_HOOK_INSERT_FIRST` or default append order.
3. AdminLTE 3 layout from `modules/interface/webui.php`.
4. Assets via `addjs` / `addcss` in module `config.php` or page hooks.

Adding a page:

```php
// modules/myfeature/webui.php
framework_add_hook ( "myfeature_list_page", "myfeature_list_page");
function myfeature_list_page ( $buffer, $parameters)
{
  return $buffer . "<section class=\"content\">...</section>";
}
```

Register route in `filter.php` or `webui.php` via path hooks (`framework_add_path`).

---

## 11. Authentication and permissions

### 11.1 Methods (`$_in["session"]["Method"]`)

| Method | Trigger |
|--------|---------|
| `Install` | No `webinterface.conf` yet |
| `Internal` | Request from `127.0.0.1` |
| `Token` | Header `X-{cookie}-TOKEN` or `?token=` |
| `Server` | Headers `X-{cookie}-SID` + `X-{cookie}-SPWD` |
| `AuthToken` | Cookie `{cookie}_authtoken` (OAuth plugins) |
| `Session` | Cookie `{cookie}` (tenant user) or `{cookie}_adm` (super-admin) |

### 11.2 Session fields

```php
$_in["session"]["Authenticated"]  // bool
$_in["session"]["Tenant"]         // int, 0 = super-admin
$_in["session"]["Permissions"]    // flat list of permission strings
$_in["session"]["Data"]           // method-specific (User ID, Token ID, …)
```

### 11.3 Password security

`[security] iterations` in webinterface.conf — PBKDF2 rounds for password hashes. TOTP via `[security] totprange`.

---

## 12. Multi-tenant model

- **`Tenants` table** — domain suffix, branding, limits.
- **`masterhostname`** — host serving super-admin UI (`multitenant_login_page_generate`, path `/master`).
- **`authhostname`** — optional dedicated auth UI (`authentication_login_page_generate`, path `/auth`).
- All other hosts → tenant login (`login_page_generate`) resolved by `get_tenant()` from HTTP Host.

API queries append `Tenant = $_in["session"]["Tenant"]` except super-admin contexts.

---

## 13. Gearman and `notify_server`

### 13.1 Task names

| Gearman function | Direction |
|------------------|-----------|
| `vd_client_{serverId}_task` | Admin/router → client |
| `vd_backup_server_{serverId}_task` | To standby node (`ServerBackup`) |
| `server_task_reply` | Client → router |
| `router_reload_cache` | Admin → router |
| Router hook names | Monitor → router |

### 13.2 `notify_server` algorithm

Location: `Administrative Interface/includes/functions.inc.php`.

1. Resolve target servers (`0` = all, negative = all except).
2. `INSERT INTO Commands (Server, Event, Data)`.
3. Skip if server has empty `PublicKey`.
4. Build JSON `{event, id, data, group}`.
5. Encrypt payload (AES-256-CBC + HMAC; RSA-wrap key with server public key; sign with master private key).
6. `GearmanClient::doBackground(gearman_client_task_name($id), ...)`.
7. Repeat for backup servers.

Master private key: `/etc/voipdomain/master-certificate.key` or generated at install.

### 13.3 Client `task_manager`

1. Decrypt + verify signature.
2. Dedupe by command `id` in `$_in["processed"]`.
3. If `group` event → expand sub-events.
4. Else `framework_call($event, $data)`.
5. `reply_event` → Gearman `server_task_reply`.

### 13.4 Command replay

On client startup, router hook `task_replay` re-sends pending `Commands` rows for that server.

---

## 14. Daemons

### 14.1 systemd units

| Unit | Binary |
|------|--------|
| `voipdomain-client.service` | `/var/lib/voipdomain/daemons/client.php -d` |
| `voipdomain-router.service` | `router.php -d` |
| `voipdomain-monitor.service` | `monitor.php -d` |
| `voipdomain-fastagi.service` | `fastagi.php -d` |

Reload without full restart: write to `/run/voipdomain/reload/{client,router,monitor,fastagi}` (path units) or `systemctl reload voipdomain-client`.

### 14.2 Plugin daemon modules

Plugins may ship:

```
Plugins/myplugin/client-modules/myfeature/client.php
Plugins/myplugin/router-modules/...
```

Deployed to `Daemons/*-modules/` locally and synced to nodes via VDFT (see §18).

---

## 15. Monitor and router event flow

```
Asterisk AMI event
  → monitor.php: framework_call("Newchannel", $event)
  → monitor-modules/channels/monitor.php: update $_in, gm->doBackground("channels_create", ...)
  → router.php: gearman_event → framework_call("channels_create", ...)
  → router-modules/channels/router.php: update $_in["Channels"][$serverId]
  → optional: notification match → HTTP push to pushpub
```

Queue abandon example: AMI `QueueCallerAbandon` → monitor `queues_caller_abandon` → Gearman → router `queues_caller_abandon` updates cluster queue state.

This path is **independent** from client config hooks — live state only.

---

## 16. Client daemon and Asterisk

### 16.1 Config output

Client hooks write files under `[general] confdir` (default `/etc/asterisk/voipdomain/configs/`). Asterisk loads them via bootstrap includes:

```
#include voipdomain/bootstrap/pjsip.conf
#include voipdomain/bootstrap/dialplan.conf
...
```

Use `/etc/asterisk/voipdomain/configure-asterisk.sh <SERVER_ID>` to append includes.

### 16.2 Server rebuild

`notify_server($id, "server_rebuild_config", ...)` triggers full config regeneration on node.

### 16.3 Contexts and macros

From `client.conf` `[contexts]` and `[macros]` — dialplan references `VoIPDomain-extensions`, `VoIPDomain-LCR`, etc.

---

## 17. VDFT file transfer

**VoIP Domain File Transfer** — chunked binary sync over Gearman notify chain.

Events: `file_transfer_begin`, `file_transfer_chunk`, `file_transfer_commit`, `file_transfer_abort`.

| Size | Behavior |
|------|----------|
| ≤ 32 KiB | Inline base64 in single notify |
| Larger | Chunks (256 KiB) staged in `/var/spool/voipdomain/file-transfer/` |

Sender: `file_transfer_send($serverId, $filepath, array ( "TargetEvent" => "equipment_configure", ...))`.

Receiver: `Daemons/client-modules/file-transfer/client.php` reassembles and dispatches `TargetEvent` with local file path.

Used for: audio upload to nodes, firmware, plugin tarball deploy, plugin DB JSON manifest.

---

## 18. Plugin system

### 18.1 Plugin locations

| Location | Role |
|----------|------|
| `Plugins/<name>/` | Source in git (reference) |
| `Administrative Interface/plugins/<name>/` | Runtime (webroot) |
| `plugins/disabled_<name>/` | Disabled (ignored by loader) |

### 18.2 Required files

Minimum: `api.php` and/or `webui.php`, `language.php`.  
Recommended: `install.php`, `filter.php`.

Optional daemon trees: `client-modules/`, `router-modules/`, `monitor-modules/`, `fastagi-modules/`.

### 18.3 Database hooks

```php
// install.php
framework_add_hook ( "install_db", "myplugin_install_db");
function myplugin_install_db ( $buffer, $parameters)
{
  return plugin_process_db ( $buffer, $parameters); // uses $_in["install"] manifest
}
```

Install manifest helpers: `install_add_db_table`, `install_add_db_trigger`, `install_add_db_data`.

### 18.4 Enable / disable

API: `PATCH /config/plugin/:Plugin/toggle` (`plugins_toggle` hook).

- **Enable:** rename `disabled_*` → active; `plugin_apply_installation()` (DB + local daemon deploy + remote sync + systemd reload).
- **Disable:** `plugin_apply_removal()`; rename to `disabled_*`.

Implementation: `Administrative Interface/includes/plugin-install.inc.php`.

### 18.5 Remote sync to Asterisk nodes

1. Tar daemon module directory.
2. `file_transfer_send(..., TargetEvent => plugin_daemon_module_deploy)`.
3. Client extracts under `Daemons/*-modules/`.
4. DB manifest JSON → `plugin_db_apply` on each node's `vdclient`.
5. `notify_server(0, "daemon_reload", ...)` or `systemd_request_daemon_reload()`.

### 18.6 Lifecycle hooks

```php
framework_add_hook ( "plugin_myplugin_install", "myplugin_on_install");
```

Called from `plugin_call_lifecycle()` during toggle/install.

See `Plugins/sampleitems/` for working example.

---

## 19. Extensions

Core module `extensions` provides shared CRUD; type-specific behavior in submodules:

| Submodule | Filter type | Client events |
|-----------|-------------|---------------|
| `extensions-phones` | `extension_phone` | `extension_phone_*` |
| `extensions-queues` | `extension_queue` | `extension_queue_*` |
| `extensions-ivr` | `extension_ivr` | `extension_ivr_*` |
| `extensions-hunts` | `extension_hunt` | `extension_hunt_*` |

Cross-module hooks example: `extensions_number_changed`, `extensions_remove_pre` (hunt cleanup).

Register types:

```php
framework_add_filter ( "objects_types", "phones_objects_types");
function phones_objects_types ( $buffer, $parameters)
{
  $buffer["extension_phone"] = array ( "type" => "human", ...);
  return $buffer;
}
```

---

## 20. Equipment provisioning

### 20.1 Admin side

- `equipments` — base CRUD, firmware storage.
- `equipments-{vendor}` — vendor-specific UI, model filters, firmware upload.

### 20.2 Client side

`Daemons/client-modules/equipments-{vendor}/`:

- `config.php` — register vendor/models in `$_in["equipments"]`.
- `client.php` — hooks per model (configure, firmware, AP templates).
- Template files with placeholders `{{{MAC}}}`, `{{{SERVER}}}`, etc.

Event `equipment_configure` pushes generated config to phone via HTTP/TFTP/FTP depending on vendor hook.

---

## 21. IVR workflow engine

### 21.1 Admin

- Visual workflow editor (`modules/ivr/config.php` defines operators: time, menu, dial, …).
- Saved as JSON on IVR + extension records.
- Validation filters: `ivr_operator_filter_{operator}`.

### 21.2 Client dialplan

`Daemons/client-modules/ivr/client.php`:

```php
create_ivr_workflow ( ...);
// foreach operator:
framework_call ( "ivr_operator_" . $operator["Operator"], array (
  "Properties" => ...,
  "Links"      => ...,
  "ID"         => $nodeId,
));
```

Built-in operators in `operator-builtin.php` return Asterisk dialplan lines (`Answer()`, `Read()`, `GoTo()`, …).

Variables in expressions: `{date}`, `{time}`, `{callerid}`, `{var_NAME}` via `operator_apply_variables()`.

---

## 22. Database: `vd` vs `vdclient`

| Database | Hosts | Contents |
|----------|-------|----------|
| **`vd`** | Admin, router, fastagi | Tenants, users, servers, commands, CDR config, equipment catalog, notifications |
| **`vdclient`** | Client, monitor | Node-local provisioning cache, plugin-local tables, runtime mirrors |

**Rule:** admin writes authoritative data to `vd`, pushes execution to nodes via Gearman. Plugin `install.php` runs on `vd`; `plugin_db_apply` replicates manifest to each `vdclient`.

Pending work queue: table `Commands` in **`vd`**.

---

## 23. Configuration files

All under `/etc/voipdomain/`. Samples in `Documentations/etc/voipdomain/`.

### webinterface.conf

| Section | Keys |
|---------|------|
| `[general]` | `baseurl`, `masterhostname`, `authhostname`, `storagedir`, `spooldir`, `tempdir`, `debug`, … |
| `[mysql]` | `hostname`, `username`, `password`, `database` (= `vd`) |
| `[gearman]` | `servers` (comma-separated host:port) |
| `[security]` | `iterations`, `totprange` |

### client.conf

| Section | Keys |
|---------|------|
| `[daemon]` | `serverid`, `backup`, `uid`, `gid`, `lpurl`, `apiurl` |
| `[mysql]` | database = `vdclient` |
| `[general]` | `confdir`, `privateKey`, `publicKey`, `masterPublicKey`, `transferdir` |

### router.conf / monitor.conf

See samples; router uses `vd`, monitor uses `vdclient` + `[ami]` credentials.

---

## 24. Internationalization

```php
// language.php
i18n_add ( "My string");
echo __ ( "My string"); // resolved at runtime from $_in["i18n"]
```

Locale from `[general] language` (e.g. `en_US`, `pt_BR`). JS strings exported via `$_in["page"]["i18n"]`.

---

## 25. Debugging and tooling

| Tool | Purpose |
|------|---------|
| `GET /debug/ping` | Health check |
| `GET /debug/api/map` | All routes + hooks + source files |
| `GET /debug/callhook/{name}` | Execute hook with debug output |
| `general.debug = true` | Disable module cache, verbose errors |
| `Documentations/scripts/hook-inventory.sh` | CSV hook inventory |

Client debug: run `php client.php` without `-d`; set `[daemon] debug = true` and optional `eventsfile`.

---

## 26. Creating a plugin step by step

1. Copy `Plugins/sampleitems/` to `Plugins/mycompany-billing/`.
2. Rename hooks (`sampleitems_*` → `mycompany_billing_*`).
3. Write `install.php` with tables/triggers.
4. Add `api.php` endpoints + permissions.
5. Add `webui.php` page + `filter.php` menu entry.
6. (Optional) Add `client-modules/` for node-side logic.
7. Deploy: symlink or copy to `Administrative Interface/plugins/mycompany-billing/`.
8. In super-admin UI: Config → Plugins → enable (runs install + remote sync).
9. Verify: `/debug/api/map`, `hook-inventory.sh`, test API calls.

For daemon-only extensions without admin UI, add files under `Daemons/client-modules/mymodule/` directly (core deployment).

---

## 27. Coding conventions

- **Identifiers in English**; user-visible strings via `__()`.
- **Never** manually `require` module files — use discovery.
- **SQL:** use mysqli with escaped strings or prepared patterns; tenant-scope every query.
- **Security:** validate in `_validate`, sanitize in `_sanitize`; never trust extension input in dialplan generation.
- **Hooks:** prefer optional phases over monolithic handlers.
- **Plugins:** keep under `Plugins/` in git; avoid editing core `modules/` for site-specific logic.

---

## 28. Appendix: hook lifecycle phases

| Phase | Typical responsibility |
|-------|------------------------|
| `_start` | Normalize input structure, set defaults |
| `_validate` | Return error array or empty |
| `_sanitize` | Strip tags, cast types |
| `_pre` | Last checks before SQL |
| main hook | DB read/write, core logic |
| `_post` | Shape response, side effects |
| `_notify` | `notify_server()` calls |
| `_finish` | Logging, metrics, cleanup |

Not all phases are implemented for every entity — register only what you need; callers always check `framework_has_hook()` first.

---

*Document version: 2026-06 — aligned with VoIP Domain 0.3 RC1.*
