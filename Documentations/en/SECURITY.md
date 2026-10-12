# VoIP Domain — Security

Operational security guidance for administrators and operators. Brazilian Portuguese: [../pt_BR/SEGURANCA.md](../pt_BR/SEGURANCA.md).

---

## 1. Scope

VoIP Domain spans an **administration server** (web UI, central database, router), **Asterisk nodes** (telephony, client/monitor/fastagi daemons, local `vdclient` database for plugins), and **Gearman** as the job bus between them. FastAGI reads gateway data from local JSON files. Apply the sections below to every host in your deployment.

Related: [INSTALL.md](INSTALL.md) · [ARCHITECTURE.md](ARCHITECTURE.md)

---

## 2. Administration server

| Area | Recommendation |
|------|----------------|
| **HTTPS** | Terminate TLS on NGiNX. Do not expose the admin UI over plain HTTP in production. |
| **Default credentials** | The web installer creates super-admin `admin` / `admin`. Change the password immediately after first login. |
| **Configuration files** | `/etc/voipdomain/` holds database credentials, Gearman settings, and the master certificate key. Restrict to root and the daemon users that need read access (`nginx`, router user). |
| **Master certificate** | `/etc/voipdomain/master-certificate.key` encrypts Gearman payloads. Back it up securely; loss prevents decrypting in-flight jobs. Mode `0600 root:root`; the router reads tenant keys from the database instead. |
| **Debug endpoints** | Disable `general.debug = true` in production. `/debug/*` routes expose internal hook and API maps. |
| **PHP-FPM** | Run the VoIP Domain pool as a dedicated user (`nginx` in RPM layouts). Do not share the pool with unrelated sites. |
| **Updates** | Keep the OS, NGiNX, PHP, and MariaDB patched. Rebuild RPMs from tagged releases rather than copying live trees. |

---

## 3. Network and firewall

Expose only what each role requires.

| Service | Typical port | Expose to |
|---------|--------------|-----------|
| Admin UI (HTTPS) | 443 | Administrators / VPN |
| Gearman (`gearmand`) | 4730 | Admin server ↔ Asterisk nodes only |
| MariaDB | 3306 | Localhost, or private network between known hosts |
| Asterisk SIP (PJSIP) | UDP/TCP 5060, TLS 5061 | Trusted carriers and internal phones — not the open Internet |
| Asterisk RTP | UDP 10000–20000 (your range) | Same as SIP peers |
| AMI (monitor daemon) | 5038 | Localhost on the Asterisk node |

- Place Gearman and MariaDB on private networks; never publish them on a public interface.
- Prefer site-to-site VPN or SBC for remote phones instead of forwarding SIP to Asterisk directly.

---

## 4. MariaDB

- Use strong passwords for `vd` (admin) and `vdclient` (node) accounts created by the installer.
- Bind MariaDB to internal addresses when remote access is required; restrict with firewall rules and `GRANT` host lists.
- Encrypt backups at rest; they contain CDR, credentials metadata, and tenant configuration.

---

## 5. Asterisk and telephony

These recommendations apply to every Asterisk node managed by VoIP Domain.

### 5.1 Exposure

- **Avoid** exposing Asterisk directly to the Internet.
- **Never** register IP phones with a public IP unless they are hardened, patched, and use unique strong credentials. Default vendor passwords are a common breach vector.
- If Internet-facing SIP is unavoidable:
  - Use strong passwords (minimum 8 characters; mixed case, digits, symbols).
  - Rotate credentials every 2–3 months.
  - Prefer **TLS + SRTP** only; disable plain UDP/TCP SIP where possible.
  - Block or rate-limit calls to high-cost or unusual destinations (LCR and dialplan rules).

### 5.2 SIP hardening

In PJSIP or legacy `sip.conf` (`[general]`):

```ini
alwaysauthreject = yes
```

Asterisk then responds with an authentication challenge instead of “404 Not Found” for unknown peers, reducing username enumeration.

### 5.3 Brute-force mitigation

Run **Fail2Ban** (or equivalent) against Asterisk/PJSIP logs to ban sources that repeatedly fail authentication.

### 5.4 Local node database

The `vdclient` database on each node stores billing and plugin data while the admin link is down. Protect it with the same care as the central `vd` database.

---

## 6. Daemons and Gearman jobs

- Client daemons register workers named `vd_client_{serverid}_task`. Standby nodes with `backup = true` also register `vd_backup_server_{serverid}_task`. Compromise of a node allows execution of jobs targeted at that server ID — isolate node networks and restrict SSH.
- Payloads are encrypted with the master certificate; still treat Gearman as a trusted segment (private VLAN).
- After plugin or module changes, reload daemons (`voipdomain-daemon-reload@*.path` on RPM installs) so workers load current code.

---

## 7. Checklist (production)

- [ ] HTTPS on admin UI; HSTS where appropriate
- [ ] Default `admin` password changed
- [ ] `/etc/voipdomain/` permissions reviewed
- [ ] Gearman and MariaDB not reachable from the Internet
- [ ] Asterisk SIP/RTP restricted to known peers
- [ ] TLS/SRTP enforced for remote endpoints
- [ ] `alwaysauthreject = yes` on SIP stack
- [ ] Fail2Ban or equivalent active
- [ ] Debug mode off; `/debug/*` not used in production
- [ ] OS and package updates scheduled

---

## See also

- [INSTALL.md](INSTALL.md) — deployment and hardening steps during install
- [../testing/README.md](../testing/README.md) — lab validation (not a substitute for production hardening)
