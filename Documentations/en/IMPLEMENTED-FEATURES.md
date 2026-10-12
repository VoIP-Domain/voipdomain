# Implemented Features (v1.0)

This document describes infrastructure and platform features implemented in the
recent development cycle. It is aimed at operators and developers who need to
understand **what was added**, **how it behaves**, and **where to look in the
codebase**.

For installation and deployment prerequisites, see [INSTALL.md](INSTALL.md),
[RPM packaging](../rpm/README.md), and the [documentation index](../README.md).

For developers: [DEVELOPER-MANUAL.md](DEVELOPER-MANUAL.md) · [../pt_BR/MANUAL-DO-DESENVOLVEDOR.md](../pt_BR/MANUAL-DO-DESENVOLVEDOR.md)
For hook programming conventions, see [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md).

---

## 1. VoIP Domain File Transfer (VDFT)

Large binary payloads (firmware, audio, plugin archives) are no longer sent as a
single base64 blob in Gearman events when they exceed the inline limit. The
administrative interface stages the file locally and streams it to Asterisk nodes
in chunks.

### Protocol events

| Event | Role |
|-------|------|
| `file_transfer_begin` | Opens a transfer session on the target node |
| `file_transfer_chunk` | Delivers one chunk (base64 payload, 256 KiB max) |
| `file_transfer_commit` | Validates the session and dispatches `TargetEvent` |
| `file_transfer_abort` | Cancels a session and removes staged parts |

Sender-side helpers live in `Administrative Interface/includes/file-transfer.inc.php`.
Receiver-side session handling lives in `Daemons/client-modules/file-transfer/client.php`.

### Writing payloads on daemons

Daemon handlers accept either inline `Data` (legacy) or a staged `FilePath`. Use
`write_payload_to_file()` in `Daemons/includes/functions.inc.php` to write the
final file; it streams from `FilePath` in 1 MiB blocks and deletes the staging
file when done.

```php
write_payload_to_file ( $destpath, $parameters);
```

### Partial failure handling

If `file_transfer_send()` fails while dispatching events to one or more servers,
it now emits `file_transfer_abort` for the active `TransferID` so orphaned chunk
files under `/var/spool/voipdomain/file-transfer/` are cleaned up.

### Runtime directory

Install `Documentations/systemd/voipdomain-tmpfiles.conf` and run
`systemd-tmpfiles --create`. It creates `/var/spool/voipdomain/file-transfer/`
with mode `0777`.

---

## 2. Audio synchronization

Audio add/edit/remove operations on the administrative interface use VDFT instead
of inline base64 in Gearman payloads.

| API hook | Target event | Notes |
|----------|--------------|-------|
| `audios_add` | `audio_add` | Sends the uploaded sound file to all servers |
| `audios_edit` | `audio_change` | Fires when file content is replaced, **including when the filename stays the same** |
| `audios_remove` | `audio_remove` | Removes converted audio from nodes |

Daemon handlers: `Daemons/client-modules/audios/client.php` — they read from
`FilePath` when present and fall back to `Data`.

---

## 3. Firmware deployment (all vendors)

Firmware upload handlers for phone vendors were migrated to
`write_payload_to_file()` so VDFT-staged files are streamed to TFTP directories
without loading the entire archive into memory:

- Yealink — `Daemons/client-modules/equipments-yealink/client.php`
- Audiocodes — `equipments-audiocodes/client.php`
- Khomp — `equipments-khomp/client.php`
- Snom — `equipments-snom/client.php`
- Grandstream — `equipments-grandstream/client.php`
- Fanvil — `equipments-fanvil/client.php`
- Htek — `equipments-htek/client.php`
- Cisco — `equipments-cisco/client.php` (see section 4)

---

## 4. Cisco firmware extraction

Cisco firmware archives are written to TFTP and extracted in place. The archive
file is kept (phones may still request it) while extracted contents are flattened
into the model directory.

### Helpers (`equipments-cisco/client.php`)

| Function | Purpose |
|----------|---------|
| `equipment_model_cisco_deploy_firmware_archive()` | Write archive + extract (zip or tar) |
| `equipment_model_cisco_extract_zip()` | Extract zip (ZipArchive or `unzip`) |
| `equipment_model_cisco_extract_tar()` | Extract tar (PharData or `tar -xf`) |
| `equipment_model_cisco_flatten_extract()` | Move nested files to model root |
| `equipment_model_cisco_clear_firmware_directory()` | Recursive wipe of a model directory |

### Supported destinations

| Subdirectory | Archive type | Models |
|--------------|--------------|--------|
| `Cisco/3905` | zip | CP3905 |
| `Cisco/7811` | zip | SPA78xx |
| `Cisco/8961` | zip | CP8961 |
| `Cisco/Locale` | tar | Locale packs (e.g. `po-locale-*`) |

### Cleanup

- `remove_firmware` — clears extracted files and archives per model, including Locale
- `firmware_cisco_wipe` — global Cisco firmware wipe also clears `Cisco/Locale`
  before removing the vendor directory

Hook names were corrected from the legacy `firmare_*` typo to `firmware_*`.

---

## 5. Plugin lifecycle and remote sync

### Enable / disable API

`POST /api/plugins/:Plugin/toggle|enable|disable` (`plugins_toggle` in
`Administrative Interface/modules/config/api.php`) now:

1. Updates the `Plugins` table and renames the plugin directory (`disabled_` prefix)
2. Calls plugin lifecycle hooks (`enable` / `disable`)
3. Deploys or undeploys daemon modules locally
4. Syncs `*-modules/` directories to remote Asterisk nodes via VDFT
5. Requests systemd reload for affected daemon units (local + remote)

Permission: `plugins_toggle` (Super-Administrator).

### Remote daemon module sync

`plugin_sync_daemon_modules_remote()` in
`Administrative Interface/includes/plugin-install.inc.php`:

- **deploy** — tars the module directory, sends it with
  `TargetEvent = plugin_daemon_module_deploy`
- **remove** — sends `plugin_daemon_module_remove`

Remote extraction: `Daemons/client-modules/plugins/client.php`.

### Remote database manifest sync

Plugin packages with `install.php`, `update.php`, or `uninstall.php` database
hooks produce a JSON manifest that is sent to each node's `vdclient` database
through `plugin_db_apply` (`plugin_sync_db_manifest_remote()`).

### Systemd reload after plugin changes

`systemd_request_daemon_reload()` triggers path units
`voipdomain-daemon-reload@<type>.path` (client, router, monitor, fastagi).
After remote plugin deploy, the same reload is requested on all nodes.

On **all-in-one / dev** setups without path units, reload daemons manually:

```bash
systemctl reload voipdomain-client.service
# repeat for router, monitor, fastagi as needed
```

---

## 6. Inter-server PJSIP trunks

Multi-server tenants receive automatic PJSIP peer definitions when servers are
added, changed, or rebuilt. Inter-server communication uses **IP-based trust**
(via PJSIP `identify` section) rather than password authentication, since
servers within a tenant are assumed to be in a trusted internal network.

### Configuration generated

For each peer server, the client daemon creates:

- **Endpoint** with `context=VoIPDomain-Internal` and `allow=all`
- **AOR** with `contact=sip:ADDRESS:PORT`
- **Identify** section with `match=ADDRESS` (IP-based trust)

No authentication credentials are generated or exchanged between servers.

---

## 7. Monitor delta synchronization

After an AMI reconnect or reload, the monitor daemon no longer discards channel
and queue state silently. On **reload** (not bootup), it compares the previous
snapshot with the new one and emits only the differences.

### Channels (`Daemons/monitor-modules/channels/monitor.php`)

`channels_sync_reload()` emits:

- `channels_destroy` — channel gone after reload
- `channels_create` — new channel or metadata change
- `channels_state` — state change
- `channels_callerid` — caller ID / connected line change

Bootup still sends a full `channels_bootup` snapshot.

### Queues (`Daemons/monitor-modules/queues/monitor.php`)

`queues_sync_reload()` emits member and caller delta events:

- `queues_member_add`, `queues_member_remove`, `queues_member_update`
- `queues_caller_add`, `queues_caller_remove`, `queues_caller_update`

### Router fix

`queues_member_remove` in `Daemons/router-modules/queues/router.php` accepts
`Member` (as sent by the monitor) with fallback to legacy `Name`.

---

## 8. Router in-memory cache reload

The router daemon caches live monitoring data (`Channels`, `Queues`, `Peers`) and
notification routing keys. Stale cache after configuration changes caused missed
or incorrect event delivery.

### Hook: `router_reload_cache`

Registered in `Daemons/router.php`. Also aliased to:

- `notifications_reload`
- `notification_create`
- `notification_change`
- `notification_destroy`

### What it does

1. Calls `reload_notifications()`
2. Clears `$_in["keys"]`
3. Clears `Channels`, `Queues`, and `Peers` (all servers, or one server when
   `ServerID` is passed)

### Triggering a reload

From the administrative interface:

```php
router_request_cache_reload ( array ());           // all servers
router_request_cache_reload ( array ( "ServerID" => 3));  // one server
```

Gearman background task name: `router_reload_cache`.

The notifications API (`Administrative Interface/modules/notifications/api.php`)
calls `router_request_cache_reload()` after add/edit/remove instead of firing
orphaned Gearman events.

### SIGHUP / systemd reload

`router_systemd_reload_handler()` reloads caches **in-process** on SIGHUP — the
router does not exit and restart.

---

## 9. Extension number migration (Asterisk DB)

When an extension number changes, astDB families tied to the old number are
migrated to the new number.

### Hook

`extension_number_migrate` — implemented by
`extension_phone_migrate_asterisk_database()` in
`Daemons/client-modules/extensions-phones/client.php`.

Called from the extension change flow when `NewNumber` differs from `Number`.

### Migrated families

| Family | Migration |
|--------|-----------|
| `lastcallerid` | Key moved old → new |
| `CFBS`, `CIDA`, `VoIPDomain-counter` | Key moved old → new |
| `denylist-{ext}` | All keys copied to `denylist-{new}` |
| `lastcalled` | Keys/values referencing old number updated |
| `lastcalledid` | Legacy typo family — deleted if present |

On extension remove, `extension_phone_clear_asterisk_database()` cleans all
associated families.

---

## 10. JavaScript number formatting

`Administrative Interface/js/voipdomain.js`:

### `voipdomain_toFixed(number, precision)`

Shared helper using `Math.round(n * k) / k` before `.toFixed()`. Fixes legacy
browser rounding bugs (e.g. `(0.55).toFixed(0)` returning `"0"` instead of
`"1"`).

Used by:

- `sprintf()` — `%e`, `%f`, `%g` specifiers when formatting with `toFixed`
- `number_format()` — decimal rounding

Locale-aware decimal separators for `sprintf %f` are **not** implemented; use
`number_format(number, decimals, decPoint, thousandsSep)` with explicit
separators for localized output.

---

## 11. Gearman task naming

Client daemon workers register as `vd_client_{serverid}_task`. Standby nodes
with `backup = true` in `/etc/voipdomain/client.conf` also register
`vd_backup_server_{serverid}_task` for mirrored command delivery.

Helper: `gearman_client_task_name()` in both
`Administrative Interface/includes/functions.inc.php` and
`Daemons/includes/functions.inc.php`.

---

## 12. Installation and operations (summary)

Documented in [INSTALL.md](INSTALL.md):

| Topic | Detail |
|-------|--------|
| Path units | `voipdomain-daemon-reload@.path` — one instance per daemon type |
| tmpfiles | `voipdomain-tmpfiles.conf` — `/run/voipdomain/reload`, file-transfer spool |
| Dev / all-in-one | Manual `systemctl reload` when path units are not installed |
| Plugin DB sync | `plugin_db_apply` on remote `vdclient` database |
| Client worker name | Must match `vd_client_{serverid}_task` on each node |

---

## 13. Quick reference — new or changed hooks/events

| Name | Type | Location |
|------|------|----------|
| `router_reload_cache` | Gearman / hook | `Daemons/router.php` |
| `router_request_cache_reload()` | PHP helper | `Administrative Interface/includes/functions.inc.php` |
| `write_payload_to_file()` | PHP helper | `Daemons/includes/functions.inc.php` |
| `file_transfer_abort` | Gearman event | `Daemons/client-modules/file-transfer/client.php` |
| `extension_number_migrate` | Hook | `Daemons/client-modules/extensions-phones/client.php` |
| `plugin_daemon_module_deploy` | Gearman event | `Daemons/client-modules/plugins/client.php` |
| `plugin_daemon_module_remove` | Gearman event | same |
| `plugin_db_apply` | Gearman event | same |
| `plugins_toggle` | API hook | `Administrative Interface/modules/config/api.php` |
| `channels_sync_reload()` | Internal | `Daemons/monitor-modules/channels/monitor.php` |
| `queues_sync_reload()` | Internal | `Daemons/monitor-modules/queues/monitor.php` |
| `equipment_model_cisco_deploy_firmware_archive()` | Hook helper | `Daemons/client-modules/equipments-cisco/client.php` |

---

## Related files (by area)

| Area | Primary paths |
|------|---------------|
| VDFT sender | `Administrative Interface/includes/file-transfer.inc.php` |
| VDFT receiver | `Daemons/client-modules/file-transfer/client.php` |
| Payload writer | `Daemons/includes/functions.inc.php` |
| Audio sync | `Administrative Interface/modules/audios/api.php`, `Daemons/client-modules/audios/client.php` |
| Firmware vendors | `Daemons/client-modules/equipments-*/client.php` |
| Plugins | `Administrative Interface/includes/plugin-install.inc.php`, `modules/config/api.php` |
| Monitor sync | `Daemons/monitor-modules/{channels,queues}/monitor.php` |
| Router cache | `Daemons/router.php`, `Daemons/router-modules/queues/router.php` |
| Extensions | `Daemons/client-modules/extensions-phones/client.php` |
| Inter-server PJSIP | `Daemons/client-modules/servers/client.php` |
| JS formatting | `Administrative Interface/js/voipdomain.js` |
| Systemd / tmpfiles | `Documentations/systemd/` |
