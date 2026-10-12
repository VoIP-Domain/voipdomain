# VoIP Domain — Deployment Planning Overview

This document helps architects and operators **design an installation** before running [INSTALL.md](INSTALL.md). VoIP Domain is deliberately flexible: components can be split across sites, colocated, or omitted when their features are not required.

**Related:** [ARCHITECTURE.md](ARCHITECTURE.md) (technical flows) · [DIALPLAN.md](DIALPLAN.md) (Asterisk dialplan layout) · [SECURITY.md](SECURITY.md) · [../rpm/README.md](../rpm/README.md)

Portuguese: [../pt_BR/PLANEJAMENTO-DE-IMPLANTACAO.md](../pt_BR/PLANEJAMENTO-DE-IMPLANTACAO.md)

---

## 1. Planning inputs

Before choosing topology, collect:

| Input | Why it matters |
|-------|----------------|
| Number of **sites** (offices, DCs, cloud regions) | Drives Gearman reachability, TFTP placement, MariaDB strategy |
| **WAN reliability** between sites and admin | Queued `Commands` vs real-time UI |
| **Phone count** and vendors | Provisioning load, firmware storage, TFTP per LAN |
| **LCR / billing / agent AGI** usage | FastAGI placement and `datadir` sharing |
| **Live dashboard** requirement | Monitor + router path mandatory |
| **Regulatory / HA** needs | Standby servers, backup nodes, DB replication |

---

## 2. Software components (what each piece does)

| Component | Runs on | Hard dependencies | Purpose |
|-----------|---------|-------------------|---------|
| **Web UI + REST API** | Admin server | MariaDB **`vd`**, NGiNX, PHP-FPM | Configuration, reports, provisioning commands |
| **voipdomain-router** | Admin (typical) | **`vd`**, Gearman, PHP CLI | Live state, notifications, `Commands` replay, WebSocket |
| **gearmand** | Admin and/or each site | Network between producers/consumers | Encrypted job bus (port **4730**) |
| **voipdomain-client** | Each Asterisk node | Gearman (reachable), **`vdclient`**, Asterisk | Writes dialplan/PJSIP/TFTP files; applies admin changes |
| **Asterisk + PJSIP** | Each telephony node | Local configs under `/etc/asterisk/voipdomain/` | Call processing |
| **voipdomain-monitor** | Each Asterisk node (typical) | AMI (**5038**), Gearman → router | Live channels, queues, peers in UI |
| **voipdomain-fastagi** | Flexible (see §6) | TCP **4573**, `datadir` JSON cache, optional MariaDB | LCR, agents, call cost AGI scripts |
| **MariaDB `vd`** | Admin server | — | **Central** tenant, user, server, CDR config, `Commands` queue |
| **MariaDB `vdclient`** | Each node (typical) | — | **Local** plugin tables, offline billing/plugin cache |
| **TFTP / HTTP provisioning** | **Per phone LAN** (external) | Files from client `tftpdir` | Auto-provision handsets (not bundled in VoIP Domain RPM) |

VoIP Domain **generates** provisioning files; it does **not** ship a TFTP or DHCP server. DHCP option **66** (TFTP server) or **150** (VoIP) must point phones to the **provisioning server on their own subnet**.

---

## 3. Two databases — central vs node-local

| Database | Authoritative for | Used by | Independence |
|----------|-------------------|---------|--------------|
| **`vd`** | Tenants, users, servers, extensions (logical model), gateways, CDR policy, equipment catalog, **`Commands`** | Admin UI/API, **router** | Single central instance (replicate for HA if needed) |
| **`vdclient`** | Plugin schemas, node-local billing/cache tables | **Client**, **monitor** (config), **FastAGI** on nodes | **One per Asterisk node** (or shared only if you accept coupling) |

**Principles:**

- Daemons on Asterisk nodes must **not** require live access to **`vd`** for day-to-day calls. Cached dialplan, PJSIP, and JSON under `datadir` allow operation when the admin link is down.
- When admin connectivity returns, the client **replays** pending rows from **`vd`.`Commands`**.
- **`vdclient`** is populated by `plugin_db_apply` and plugin install hooks — not by the web installer on the node.

**FastAGI data:** FastAGI reads gateway data from local JSON files (`gateway-*.json`) in `datadir`, synchronized by the client daemon. No database connection is required. When FastAGI runs on a different host than the client, ensure `datadir` is shared or replicated.

---

## 4. Topology patterns

### 4.1 Centralized admin, distributed Asterisk (most common)

- One admin server: web + router + **`vd`** + Gearman.
- N Asterisk nodes: client + Asterisk + **`vdclient`** each.
- Optional monitor + FastAGI per node.

**Best for:** MSP, enterprise HQ with branch offices on VPN.

### 4.2 Remote office (offline-capable)

- Branch Asterisk + client + **`vdclient`** + local Gearman **or** VPN to central Gearman.
- Phones provision from **branch TFTP** (`client` `tftpdir` + `address` = LAN IP).
- If WAN to admin fails: **calls continue** on cached config; **new changes queue** in `Commands`; **live UI** for that site stops unless monitor reaches central router.

### 4.3 All-in-one lab

- Single host runs admin, router, client, monitor, FastAGI, Asterisk, MariaDB, Gearman.
- See [INSTALL.md](INSTALL.md) §4 — manual reload when path units are not used.

### 4.4 Centralized FastAGI pool

- All Asterisk nodes: `AGIADDR` / dialplan globals point to FastAGI cluster IP.
- Firewalls: allow Asterisk nodes → FastAGI **TCP 4573**.
- Replicate or NFS-mount **`datadir`** so FastAGI can read gateway JSON files written by clients.

### 4.5 Inter-site trunking

- Multiple **`Servers`** rows in admin with automatic PJSIP peer configuration (IP-based trust).
- Each server has its own extension ranges; overflow uses `dialplan-range-*.conf` when configured.

---

## 5. Daemon minimization matrix

What you lose when omitting a daemon on a node:

| Omit | Calls still work? | Impact |
|------|-------------------|--------|
| **client** | Only on **existing** cached config | No admin updates, no provisioning file refresh |
| **monitor** | Yes | No live channels/queues/peers in UI for that server |
| **fastagi** | Partial | LCR, agent login AGI, call-cost AGI fail unless dialplan changed |
| **local gearmand** | Yes if remote Gearman reachable | Client/monitor cannot receive jobs |
| **router** (admin) | Yes on nodes | No live UI state, no command replay orchestration, API notify backlog |

**Minimum viable branch (calls only):** Asterisk + **client** + **`vdclient`** + reachability to Gearman (direct or queued replay after reconnect).

**Minimum for operator dashboard:** above + **monitor** + central **router**.

---

## 6. Auto-provisioning per network

Provisioning is **per server / per LAN**, not global:

1. In admin, each **`Server`** has an **address** (reachable IP/hostname on the phone VLAN).
2. Client daemon writes templates to **`tftpdir`** (set in `client.conf`; not in the minimal sample — add explicitly).
3. Vendor modules replace `{{{tftpserver}}}` / `{{{server1addr}}}` with **`general.address`** from client config.
4. Deploy **tftpd** (or vendor HTTP provisioning) on the **same network as phones**.
5. Configure **DHCP** for that VLAN:
   - Option **66** → TFTP server IP, **or**
   - Option **150** → VoIP TFTP (vendor-dependent).
6. Ensure firewall allows phones → TFTP/HTTP; **no need** for phones to reach the admin UI.

**Multi-site example:** Office A phones → DHCP scope A → TFTP at Server A LAN IP. Office B → separate scope and TFTP on Server B. Both servers register to the same central admin.

---

## 7. Gearman deployment

| Model | When to use | Configuration |
|-------|-------------|---------------|
| **Single central `gearmand`** | Stable VPN/hub network | All `client.conf` / `monitor.conf`: `[gearman] servers = admin-host:4730`. Router: `[gearman] hostname` + `port`. |
| **Per-site `gearmand`** | High latency or isolated WAN | Admin notifies only servers that share that bus; document firewall rules. |

**Note:** Router/monitor samples use `hostname`/`port`; client uses `servers = host:port`. Both are valid — set explicitly on every host.

---

## 8. Network and ports (planning checklist)

| Service | Port | Between |
|---------|------|---------|
| Admin HTTPS | 443 | Admins → admin server |
| Gearman | 4730 | Admin ↔ all daemons |
| MariaDB `vd` | 3306 | Admin components only (not open to Internet) |
| MariaDB `vdclient` | 3306 | Localhost on node (default) |
| Asterisk SIP/PJSIP | 5060/5061, RTP range | Carriers/phones |
| AMI | 5038 | localhost → monitor |
| FastAGI | 4573/tcp | Asterisk → FastAGI host(s) |
| Router HTTP/WS | 8080 (default) | Internal — see `router.conf` |

Full hardening: [SECURITY.md](SECURITY.md).

---

## 9. Offline and degradation behaviour

| Scenario | Behaviour |
|----------|-----------|
| WAN down, client was connected | Asterisk keeps last config; **`Commands`** queue on admin; replay on reconnect |
| Client daemon stopped | No new config; calls on current dialplan |
| Monitor stopped | Calls OK; UI live state stale |
| FastAGI down | Outbound LCR/agent AGI paths fail |
| Central **`vd`** unavailable | Admin UI down; nodes unaffected if already provisioned |

There is **no automatic conflict merge** for long partitions — plan operational procedures for extended outages.

---

## 10. Standby and backup nodes

- **`ServerBackup`** in admin + `backup = true` in `client.conf` registers `vd_backup_server_{id}_task`.
- Use for warm standby / DR drills; validate failover dialplan and trunk behaviour in lab.

---

## 11. Sample `client.conf` keys (production)

Minimal samples omit keys required in production. Plan to set on **each Asterisk node**:

```ini
[general]
confdir = /etc/asterisk/voipdomain/configs/
address = 10.0.1.50          ; IP phones use for SIP/TFTP (this LAN)
tftpdir = /var/lib/tftpboot/voipdomain
datadir = /var/lib/voipdomain/data
soundsdir = /var/lib/voipdomain/storage/sounds
transferdir = /var/spool/voipdomain/file-transfer

[gearman]
servers = admin.example.com:4730

[mysql]
database = vdclient
```

Cryptographic keys (`privateKey`, `publicKey`, `masterPublicKey`) are created by the node installer — see [INSTALL.md](INSTALL.md).

---

## 12. Installation execution map

| Planning decision | Install reference |
|-------------------|-------------------|
| RPM layout | [../rpm/README.md](../rpm/README.md) |
| Step-by-step install | [INSTALL.md](INSTALL.md) |
| systemd / tmpfiles | [INSTALL.md](INSTALL.md) §3, §7 |
| Lab smoke tests | [../testing/README.md](../testing/README.md) |
| Dialplan / plugin hooks | [DIALPLAN.md](DIALPLAN.md) |

---

## 13. Pre-flight checklist (by role)

### Admin server

- [ ] MariaDB **`vd`** created (web installer)
- [ ] NGiNX + PHP-FPM + `voipdomain-webserver`
- [ ] `gearmand` running
- [ ] `voipdomain-router` + `router.conf` → **`vd`**
- [ ] Firewall: 443, 4730 to known nodes
- [ ] TLS certificates configured

### Each Asterisk node

- [ ] Asterisk 21/22 + `voipdomain-client` (+ monitor/fastagi as planned)
- [ ] MariaDB **`vdclient`**
- [ ] `client.conf`: `serverid`, `address`, `tftpdir`, `datadir`, `[gearman]`
- [ ] `#include voipdomain/bootstrap/dialplan.conf` in `extensions.conf`
- [ ] TFTP/DHCP on **local LAN** for phones
- [ ] Gearman reachable (or accept queued replay delay)

### Centralized FastAGI (optional)

- [ ] `fastagi.conf`: port, `datadir` path
- [ ] `AGIADDR` in dialplan globals matches service IP
- [ ] `datadir` shared/replicated with client nodes

---

*Document version: 2026-06 — VoIP Domain 0.3 RC1.*
