# VoIP Domain — System Testing Methodology

This document defines a repeatable methodology to test the VoIP Domain platform
end-to-end, with **explicit coverage of the hook-based architecture**. It is
written for lab/staging environments — not production.

**Related docs:** [INSTALL.md](INSTALL.md), [../rpm/README.md](../rpm/README.md),
[DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md), [IMPLEMENTED-FEATURES.md](IMPLEMENTED-FEATURES.md)

---

## 1. Scope and inventory (codebase scan)

A static scan of the repository (Administrative Interface modules, Daemons,
Plugins) shows approximately:

| Asset | Approx. count | Where |
|-------|---------------|--------|
| Registered hooks | ~770 | `framework_add_hook()` |
| Registered filters | ~685 | `framework_add_filter()` |
| REST API modules | 44 | `Administrative Interface/modules/*/api.php` |
| Client daemon modules | 31 | `Daemons/client-modules/*/client.php` |
| Router modules | several | `Daemons/router-modules/` |
| Monitor modules | 2 | `Daemons/monitor-modules/{channels,queues}/` |
| FastAGI modules | 2+ | `Daemons/fastagi-modules/` |

**Reality check:** you cannot manually execute every hook before each release.
This methodology prioritizes **risk-based coverage**: infrastructure hooks,
CRUD chains, telephony paths, and recently implemented features first; exhaustive
hook sweeps are automated where possible.

---

## 2. Testing principles

1. **Lab first** — dedicated tenant, dummy gateway, test extensions, snapshot/restore DB.
2. **Layered** — static → hook isolation → API → daemon → telephony → UI.
3. **Observable** — every test declares expected logs, files, DB rows, and Gearman events.
4. **Non-destructive by default** — classify hooks before calling them (section 5).
5. **Reproducible** — record hook name, payload JSON, server ID, and timestamp in test notes.

---

## 3. Test environments

### 3.1 Minimum lab (all-in-one)

| Component | Service / path |
|-----------|----------------|
| Web + API | NGiNX + PHP-FPM → `/var/lib/voipdomain/webroot` (RPM) or `Administrative Interface/` (dev) |
| MariaDB | `vd` (admin) + `vdclient` (Asterisk node) |
| Gearman | `gearmand` on localhost:4730 |
| Daemons | `voipdomain-{client,router,monitor,fastagi}.service` |
| Asterisk | PJSIP + dialplan includes from VoIP Domain |
| Spool | `/var/spool/voipdomain/file-transfer/` |

Enable daemon debug during hook investigations:

```ini
; /etc/voipdomain/client.conf
debug = true
eventsfile = /var/log/voipdomain/client-events.log
```

```ini
; /etc/voipdomain/router.conf (if supported)
general[debug] = true
```

Run monitor in foreground while debugging AMI resync:

```bash
php /var/lib/voipdomain/daemons/monitor.php --debug
```

### 3.2 Distributed lab

Same as above, split across admin host + one or more Asterisk nodes. Required
for testing: VDFT, plugin remote sync, inter-server PJSIP, monitor delta sync,
`router_reload_cache` with `ServerID`.

### 3.3 Pre-flight checklist

Run before any test cycle:

```bash
# Services
systemctl is-active voipdomain-client voipdomain-router voipdomain-monitor gearmand mariadb asterisk

# Health
curl -sS -b cookies.txt https://lab.example/api/debug/ping   # expect "Pong"

# PHP syntax (all daemon client modules)
find Daemons -name '*.php' -print0 | xargs -0 -n1 php -l

# tmpfiles / spool
test -d /var/spool/voipdomain/file-transfer && echo OK

# Gearman workers visible
gearadmin --status 2>/dev/null | head
```

---

## 4. Test layers (pyramid)

```
                    ┌─────────────────────┐
                    │  E2E telephony + UI │
                    └──────────┬──────────┘
               ┌───────────────┴───────────────┐
               │  API integration + notify chain │
               └───────────────┬───────────────┘
          ┌────────────────────┴────────────────────┐
          │  Hook isolation (debug_call_hook, etc.) │
          └────────────────────┬────────────────────┘
     ┌─────────────────────────┴─────────────────────────┐
     │  Static analysis (php -l, API map, hook inventory) │
     └───────────────────────────────────────────────────┘
```

| Layer | Goal | Primary tools |
|-------|------|-----------------|
| L0 Static | No syntax errors; hooks registered | `php -l`, `rg`, API map |
| L1 Hook unit | Single hook behaviour | `POST /debug/callhook/:Hook`, `/debug/exec_hook/` |
| L2 API | REST contract + sub-hook chain | curl, Swagger UI, tokens |
| L3 Daemon | Gearman + file side-effects | logs, `gearadmin`, filesystem |
| L4 Telephony | Calls, queues, provisioning | Asterisk CLI, SIP phone |
| L5 UI | Web pages render | Browser, AdminLTE |

---

## 5. Hook taxonomy and risk classes

Understanding hook **type** determines **how** to test it.

### 5.1 Hook categories

| Category | Naming pattern | Runs on | Example |
|----------|----------------|---------|---------|
| API main | `<module>_<action>` | Admin PHP | `extensions_add` |
| API sub-hook | `<main>_{start,validate,sanitize,pre,post,notify,finish}` | Admin PHP | `extensions_add_pre` |
| Page hook | `<module>_<page>_page` | Admin WebUI | `extensions_view_page` |
| Filter | arbitrary (often `e164_*`) | Admin PHP | `e164_identify` |
| Client event | `<entity>_{add,change,remove}` | Client daemon | `extension_phone_add` |
| Wipe / cleanup | `<entity>_wipe`, `*_wipe` | Client daemon | `firmware_cisco_wipe` |
| Router event | `channels_*`, `queues_*`, `router_*` | Router daemon | `router_reload_cache` |
| Monitor internal | `channels_*`, `queues_*` (AMI) | Monitor daemon | `channels_sync_reload` |
| Infrastructure | global | varies | `servers_rebuild_config` |
| Plugin lifecycle | `plugin_*_{install,upgrade,remove,enable,disable}` | Admin / client | `plugin_db_apply` |
| File transfer | `file_transfer_*` | Client daemon | `file_transfer_commit` |

### 5.2 Risk classes

| Class | Description | Test approach |
|-------|-------------|---------------|
| **R0 — Read-only** | Search, view, ping, map dumps | Safe to call anytime |
| **R1 — Transform** | Filters, sanitize, validate-only | Call with fixture payloads; no DB writes |
| **R2 — Mutating** | add/change/remove API + client events | Lab tenant only; verify DB + files + logs |
| **R3 — Destructive** | wipe, rebuild, remove server | Dedicated disposable server or snapshot restore |
| **R4 — Side-effect external** | notify, Gearman broadcast, systemd reload | Use capture mode or single test node |

**Rule:** never call R3 hooks against shared or production systems.

---

## 6. Built-in hook testing tools

VoIP Domain ships debug facilities — use them before writing custom scripts.

### 6.1 Authenticated API (recommended)

Requires Administrator + permission. Use session cookie or Bearer token.

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/debug/ping` | GET | API + auth smoke test |
| `/api/debug/api/map` | GET | Full API tree + hook → file/line map |
| `/api/debug/callhook/:Hook` | POST | Execute any registered hook with JSON body |
| `/api/debug/explain/:Number` | GET | Exercise `e164_identify` filter chain |

Example — call a read-only hook:

```bash
curl -sS -X POST 'https://lab.example/api/debug/callhook/debug_ping' \
  -H 'Authorization: Bearer YOUR_TOKEN' \
  -H 'Content-Type: application/json' \
  -d '{}'
```

Example — test a validate hook (expect 422 field errors):

```bash
curl -sS -X POST 'https://lab.example/api/debug/callhook/ranges_add_validate' \
  -H 'Authorization: Bearer YOUR_TOKEN' \
  -H 'Content-Type: application/json' \
  -d '{"Number":""}'
```

Example — test infrastructure hook (lab router must be running):

```bash
curl -sS -X POST 'https://lab.example/api/debug/callhook/router_reload_cache' \
  -H 'Authorization: Bearer YOUR_TOKEN' \
  -H 'Content-Type: application/json' \
  -d '{"ServerID":"1"}'
```

**Note:** `debug_call_hook` passes the JSON body as `$parameters` to the target
hook. Include all keys the hook expects. Path parameter `:Hook` is available as
`$parameters["Hook"]`.

### 6.2 Debug WebUI paths (development only)

Module `debug/webui.php` exposes **unauthenticated** paths. The module comments
state it must **never** be enabled in production.

| Path | Purpose |
|------|---------|
| `/debug/dump_hooks` | Dump full `$_plugins` hook registry |
| `/debug/dump_filters` | Dump filter registry |
| `/debug/dump_paths` | Dump URL path → hook map |
| `/debug/exec_hook/:hook` | Execute hook, print return value |
| `/debug/exec_filter/:filter` | Execute filter with query params |

Use only on isolated lab networks.

### 6.3 Hook inventory script (local)

Generate a CSV checklist from the repository:

```bash
cd /path/to/voipdomain
rg 'framework_add_hook\s*\(\s*"([^"]+)"' -o -r '$1' --glob '*.php' \
  Administrative\ Interface/modules Daemons Plugins \
  | sort -u > /tmp/voipdomain-hooks.txt

rg 'framework_add_filter\s*\(\s*"([^"]+)"' -o -r '$1' --glob '*.php' \
  Administrative\ Interface/modules Daemons \
  | sort -u > /tmp/voipdomain-filters.txt

wc -l /tmp/voipdomain-hooks.txt /tmp/voipdomain-filters.txt
```

Track coverage by marking hooks tested in the CSV (class R0–R4, date, result).

### 6.4 Swagger / OpenAPI

Open `/api/docs` and walk each module:

- HTTP status codes match [API/api.txt](API/api.txt)
- Required fields reject with **422**
- Permissions reject with **403** when using a low-privilege token

---

## 7. Hook testing procedure (step-by-step)

Use this procedure for **each hook or hook group** under test.

### Step 1 — Discover

1. Find hook in code: `rg 'framework_add_hook.*"HOOK_NAME"'`
2. Confirm handler function and file/line via `GET /api/debug/api/map`
3. Read function signature and expected `$parameters` keys
4. Classify risk (section 5.2)
5. List side effects: SQL tables, `write_config()`, `notify_server()`, Gearman

### Step 2 — Prepare fixtures

- Tenant context: use Super-Administrator or target tenant Administrator session
- Create minimal dependency tree if needed (server → gateway → profile → …)
- For client hooks: ensure client daemon debug logging is on

### Step 3 — Execute

**Admin-only hooks:**

```bash
curl -X POST ".../api/debug/callhook/HOOK_NAME" -H "Authorization: Bearer …" -d '{ ... }'
```

**Sub-hooks in CRUD chain:** prefer calling the **main API endpoint** and verify
sub-hooks ran via logs or DB state, e.g. `POST /api/extensions` exercises
`extensions_add_start` through `extensions_add_finish`.

**Filters:**

```bash
curl ".../api/debug/explain/+5511999999999"
# or WebUI: /debug/exec_filter/e164_identify?Number=...
```

### Step 4 — Assert

| Signal | Check |
|--------|-------|
| Return value | JSON shape, HTTP code, `code`/`message` for daemon-style hooks |
| Database | Row created/updated/deleted in `vd` / `vdclient` |
| Filesystem | Config under Asterisk confdir, TFTP, sounds, spool |
| Gearman | Event in client log / `eventsfile` |
| Asterisk | `asterisk -rx "pjsip show endpoints"`, dialplan reload |
| Router cache | Channel/queue state after monitor reload |

### Step 5 — Teardown

- R2: delete test objects via API (`*_remove`) or restore DB snapshot
- R3: rebuild disposable server or restore VM snapshot
- Clear capture buffers: `notify_capture_discard()`

---

## 8. Daemon and Gearman hook testing

Hooks registered in Daemons are invoked differently per daemon type.

### 8.1 Router daemon (plain JSON Gearman functions)

Every hook in `Daemons/router.php` and `router-modules/` is registered as a
Gearman function. Payload is JSON decoded and passed to `framework_call()`.

```bash
# Example: reload router cache (lab)
echo '{}' | gearman -f router_reload_cache -h 127.0.0.1 -p 4730

# Example: simulate channel destroy
echo '{"ServerID":1,"Channel":"PJSIP/100-00000001","Cause":0}' \
  | gearman -f channels_destroy -h 127.0.0.1 -p 4730
```

Verify in `/var/log/voipdomain/router.log`.

**SIGHUP test:**

```bash
systemctl reload voipdomain-router.service
grep 'cache reloaded' /var/log/voipdomain/router.log
```

### 8.2 Client daemon (encrypted task queue)

Client hooks are **not** direct Gearman function names. The admin interface
sends encrypted jobs to `vd_client_{serverid}_task` (or backup task name).

**Preferred test path:**

1. Perform API action (`POST /api/audios`, `POST /api/extensions`, …)
2. Inspect client log / `eventsfile` for event name matching hook
3. Verify filesystem and Asterisk config output

**Event capture (admin side):** use `notify_capture($serverid)` before API
calls and inspect captured events before `notify_capture_send()` — useful to
assert VDFT event sequences without hitting the node.

### 8.3 Monitor daemon (AMI-driven)

Monitor hooks are triggered by Asterisk Manager events, not directly by API.

| Test | Procedure |
|------|-------------|
| Bootup snapshot | Restart monitor; expect `channels_bootup` in router log |
| Reload delta | `asterisk -rx "core reload"` or reconnect AMI; expect delta events not full wipe |
| Queue delta | Add/remove queue member; compare monitor backup vs current |

Run monitor with `--debug` to stay in foreground and watch stdout.

### 8.4 FastAGI

Place test call through dialplan that hits AGI script; verify CDR/billing side
effects in `vdclient` billing tables.

---

## 9. Domain test matrices

### 9.1 Platform bootstrap

| # | Test | Hooks / endpoints | Pass criteria |
|---|------|-------------------|---------------|
| 1 | Installer | install hooks | `/etc/voipdomain/` writable; DB populated |
| 2 | Login | authentication hooks | Session cookie; permissions loaded |
| 3 | API map | `debug_api_map` | All expected modules present |
| 4 | Ping | `debug_ping` | Returns `Pong` |

### 9.2 Tenant object tree (CRUD + notify)

Execute in order on a fresh tenant. Each row is one API test **plus** client hook
verification on the Asterisk node.

| # | Object | API endpoints | Client hooks (examples) |
|---|--------|---------------|-------------------------|
| 1 | Server | `POST /servers` | `server_add`, PJSIP peer notify |
| 2 | Gateway | `POST /gateways` | `gateway_add` |
| 3 | Profile | `POST /profiles` | `profile_add` |
| 4 | Cost center | `POST /costcenters` | (module hooks) |
| 5 | Group | `POST /groups` | `group_add` |
| 6 | Range | `POST /ranges` | `range_add`, dialplan generate |
| 7 | Extension (phone) | `POST /extensions` | `extension_phone_add` |
| 8 | Queue | `POST /queues` | queue client hooks |
| 9 | IVR | `POST /ivr` | `extension_ivr_add` |
| 10 | Audio | `POST /audios` | `audio_add` via VDFT |

For each: **Create → Read → Update → Delete**, checking 201/200/204/422/404
codes per [API/api.txt](API/api.txt).

### 9.3 Hook sub-chain tests (API modules)

For critical modules, explicitly test sub-hook behaviour:

| Scenario | Hook | Expected |
|----------|------|----------|
| Missing required field | `*_validate` | 422 + field map |
| Invalid ID on edit | `*_validate` | 422 |
| Sanitize strips chars | `*_sanitize` | cleaned `$parameters` in post hook |
| Notify fires Gearman | `*_notify` / post | client log entry |

Modules to prioritize: `extensions`, `servers`, `gateways`, `ranges`, `audios`,
`config` (plugins), `notifications`, `equipments-*`.

### 9.4 Telephony E2E

| # | Scenario | Verification |
|---|----------|--------------|
| 1 | Internal extension call | Channel in router cache; CDR/billing row |
| 2 | Outbound via gateway | LCR range selection; gateway dial |
| 3 | Queue join / agent | Monitor queue events; router queue cache |
| 4 | IVR navigation | DTMF; IVR client config |
| 5 | Voicemail | Message file; MWI if configured |
| 6 | Call recording | Recording file path under spool |

Asterisk CLI helpers:

```bash
asterisk -rx "core show channels"
asterisk -rx "queue show"
asterisk -rx "pjsip show endpoints"
```

### 9.5 Provisioning / firmware

| # | Vendor module | Hooks | Pass criteria |
|---|---------------|-------|---------------|
| 1 | Cisco | `equipment_model_cisco_deploy_firmware_archive` | TFTP dir populated; zip/tar extracted |
| 2 | Yealink | firmware add hooks | `write_payload_to_file` destination |
| 3 | Phone HTTP/TFTP | provisioning hooks | Device receives config |

Test VDFT with file > 256 KiB to force multi-chunk transfer.

### 9.6 Plugins

| # | Test | Hooks / flow |
|---|------|--------------|
| 1 | Install plugin | `plugin_install`, remote sync, `plugin_db_apply` |
| 2 | Toggle enable | `plugins_toggle`, lifecycle `enable`, systemd reload |
| 3 | Toggle disable | undeploy, remote remove |
| 4 | Uninstall | `plugin_remove`, DB manifest uninstall |

### 9.7 Infrastructure (recent features)

See [IMPLEMENTED-FEATURES.md](IMPLEMENTED-FEATURES.md) for detail.

| # | Feature | Test |
|---|---------|------|
| 1 | VDFT | Large firmware upload; chunks in spool; abort on forced failure |
| 2 | `file_transfer_abort` | Interrupt transfer; spool cleaned |
| 3 | Monitor delta | AMI reload; delta events in router log, not full resync |
| 4 | `router_reload_cache` | Edit notification; cache cleared; new events populate |
| 5 | Extension migrate | Change extension number; astDB keys migrated |
| 6 | Inter-server PJSIP | Add 2nd server; PJSIP endpoint+aor+identify files on both nodes |
| 7 | Cisco Locale wipe | `firmware_cisco_wipe`; `Cisco/Locale` removed |

### 9.8 Filters (numbering / locale)

Country and NANPA filters number in the thousands. Do **not** test each one
manually.

Sample strategy:

1. Pick one filter per region class (BR, US NANPA, EU) from `modules/countrydb/`
2. Call `/api/debug/explain/:Number` for valid/invalid/sample numbers
3. Regression: if a country plugin changes, test that filter file only

### 9.9 Web UI smoke

| Area | Pages to open |
|------|---------------|
| Core | Dashboard, extensions list/add, servers, reports |
| Debug off | Confirm debug module disabled in production builds |
| JS | Browser console free of errors; `number_format` / tables load |

---

## 10. Wipe and cleanup hook registry

Wipe hooks remove Asterisk-side configuration. Test only on disposable servers.

Discover wipe hooks:

```bash
rg 'cleanup_register|function \w+_wipe' Daemons/client-modules -g '*.php'
```

Common pattern: `cleanup_register("Entity", "entity_wipe")` runs before rebuild.

| Hook | Trigger |
|------|---------|
| `server_wipe` | Server rebuild with `Clean: true` |
| `firmware_cisco_wipe` | Global Cisco firmware cleanup |
| `*_wipe` per module | Tenant/server cleanup pipelines |

**Procedure:** snapshot → call wipe via rebuild API → verify TFTP/conf dirs empty
→ restore snapshot.

---

## 11. Automation recommendations

The project ships lab automation under `Documentations/testing/`:

| Script | Purpose |
|--------|---------|
| `Documentations/scripts/hook-inventory.sh` | Generate `hook-inventory.csv` with R0–R4 classification |
| `Documentations/testing/run-all.sh` | Full smoke run (inventory + static + API + Gearman) |
| `Documentations/testing/smoke-static.sh` | `php -l` on Daemons and Admin Interface |
| `Documentations/testing/smoke-api.sh` | Login, ping, API map, sample hook calls |
| `Documentations/testing/smoke-gearman.sh` | `gearadmin`, optional `router_reload_cache` |
| `Documentations/testing/hook-call.sh` | Call one hook via `/debug/callhook/:Hook` |
| `Documentations/testing/hook-smoke-r0.sh` | Batch sample of R0 admin hooks |
| `Documentations/testing/postman/*.json` | Postman/Insomnia collection |

See [Documentations/testing/README.md](testing/README.md) for setup (`env.local`) and usage.

Recommended CI progression:

| Phase | Automation | Covers |
|-------|------------|--------|
| A | CI: `bash Documentations/testing/smoke-static.sh` | L0 |
| B | `smoke-api.sh` against lab stack | L1–L2 |
| C | `hook-inventory.sh` + manual CSV coverage marks | L1 tracking |
| D | `smoke-gearman.sh` with `VD_RUN_INFRA_HOOKS=1` | L3 subset |
| E | SIPp or physical phones for call scenarios | L4 |

---

## 12. Test cycle checklist (sign-off)

Use this checklist per release candidate:

### Static / smoke

- [ ] All PHP files pass `php -l`
- [ ] `GET /api/debug/ping` → `Pong`
- [ ] `GET /api/debug/api/map` loads without error
- [ ] All daemons active; Gearman workers registered
- [ ] tmpfiles paths exist (file-transfer spool)

### Hooks (sampled)

- [ ] R0: 10 read-only hooks via `debug_call_hook` — OK
- [ ] R1: 5 validate hooks return expected 422 shapes
- [ ] R2: Full tenant tree CRUD — DB + client logs OK
- [ ] R3: Wipe on disposable node — restore verified
- [ ] Router: `router_reload_cache` via API + SIGHUP
- [ ] Client: VDFT audio + firmware on one vendor each

### Telephony

- [ ] Internal call
- [ ] Outbound call
- [ ] Queue call (if used)
- [ ] Provisioning request from one phone model

### Regression (implemented features)

- [ ] Monitor delta after AMI reload
- [ ] Extension number migration (astDB)
- [ ] Plugin toggle + remote sync
- [ ] Inter-server PJSIP on 2-node lab

### Security

- [ ] Debug WebUI **disabled** or module not loaded in production config
- [ ] `debug_call_hook` permission not granted to tenant admins
- [ ] Token ACL restrictions honored

---

## 13. Failure triage

| Symptom | Likely layer | First action |
|---------|--------------|--------------|
| 404 on `callhook` | Hook not registered | `dump_hooks`; plugin disabled? |
| 422 unexpected | validate sub-hook | Call `*_validate` directly with payload |
| Client ignores event | Gearman / crypto | Check sign/HMAC errors in client.log |
| Stale monitor state | Router cache | `router_reload_cache`; check monitor delta |
| Partial file on node | VDFT | Check spool; look for missing `file_transfer_commit` |
| Permission denied | ACL | Token permissions vs `framework_add_permission` |

---

## 14. Document maintenance

When adding hooks:

1. Register via `framework_add_hook()` / `framework_add_api_call()` with docs
2. Add entry to module test matrix (section 9) if user-facing
3. Classify R0–R4 in module README or inline `@risk` comment (optional convention)
4. Update [IMPLEMENTED-FEATURES.md](IMPLEMENTED-FEATURES.md) for infrastructure hooks

---

*Last inventory scan: ~772 hooks, ~685 filters, 44 API modules — re-run section 6.3 after major merges.*
