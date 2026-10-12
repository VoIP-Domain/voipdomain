# VoIP Domain — RPM packaging (RHEL 7 / 8 / 9)

This directory contains the source-of-truth packaging metadata for RPM-based deployments.

## Package split

| RPM subpackage | Role | Key paths |
|----------------|------|-----------|
| `voipdomain` | Meta-package (description only) | — |
| `voipdomain-webserver` | Admin UI (NGiNX + PHP-FPM) | `/var/lib/voipdomain/webroot`, `/etc/voipdomain/webinterface.conf` |
| `voipdomain-daemons-common` | Shared daemon code, reload path units, tmpfiles | `/var/lib/voipdomain/daemons/includes/`, `/usr/libexec/voipdomain/daemons` → symlink |
| `voipdomain-router` | Gearman router | `/var/lib/voipdomain/daemons/router.php`, `voipdomain-router.service` |
| `voipdomain-client` | Asterisk client daemon | `/var/lib/voipdomain/daemons/client.php`, Asterisk bootstrap under `/etc/asterisk/voipdomain/` — **Requires: asterisk** |
| `voipdomain-fastagi` | FastAGI daemon | `voipdomain-fastagi.service` |
| `voipdomain-monitor` | AMI monitor | `voipdomain-monitor.service` |

## Files installed by the webserver package

| Source (repository) | Target (server) |
|---------------------|-----------------|
| `Documentations/etc/nginx/voipdomain.conf` | `/etc/nginx/conf.d/voipdomain.conf` |
| `Documentations/etc/nginx/voipdomain-fastcgi.conf` | `/etc/nginx/voipdomain-fastcgi.conf` |
| `Documentations/etc/php-fpm.d/voipdomain.conf` | `/etc/php-fpm.d/voipdomain.conf` |

Documentation shipped in the package: `Documentations/etc/nginx/` (includes full `nginx.conf` reference) and `Documentations/etc/php-fpm.d/`.

Edit `server_name` and SSL certificate paths in the vhost before production.

## Runtime ownership (admin server)

| Path | Owner | Purpose |
|------|-------|---------|
| `/var/lib/voipdomain/webroot` | `nginx:nginx` | PHP application (PHP-FPM pool user) |
| `/var/lib/voipdomain/storage/*` | `nginx:nginx` | Uploads: avatars, firmwares, sounds, recordings, captures |
| `/etc/voipdomain/` | `root:nginx` (mode `0770`) | PHP-FPM writes installer output; owned by `voipdomain-webserver` |
| `/etc/voipdomain/webinterface.conf` | `root:nginx` (mode `0660`) | PHP-FPM (user `nginx`, group `nginx`) must read and write during install |
| `/etc/voipdomain/router.conf` | `root:root` (mode `0600`) | Router daemon (runs as root by default) |

On SELinux **Enforcing** hosts, `%post`/`%posttrans` for `voipdomain-webserver` (≥ 0.3-12) label `/etc/voipdomain` and installer files as `httpd_sys_rw_content_t` so PHP-FPM can write during the web installer. `router.conf` keeps the default `etc_t` label. MariaDB: use `localhost` (socket) when co-located; TCP/`httpd_can_network_connect_db` is documented in INSTALL.md Section 5 and is **not** changed by the RPM.

Subdirectories under `storage/` are created by `voipdomain-webserver-tmpfiles.conf` when the **webserver** package is installed (requires `nginx` user).

`/var/lib/voipdomain/data` is created by `voipdomain-client-tmpfiles.conf` when the **client** package is installed (requires `asterisk` user).

`voipdomain.conf` (daemons-common) only creates runtime dirs (`/run/voipdomain`, spool) — safe on admin and Asterisk nodes.

## Runtime ownership (Asterisk node)

| Path | Owner | Purpose |
|------|-------|---------|
| `/var/lib/voipdomain/data` | `asterisk:asterisk` (mode `0750`) | Client/FastAGI JSON cache (`gateway-*.json`, etc.) |

## PHP-FPM

Daemons are installed under `/var/lib/voipdomain/daemons/`. Systemd units and a compatibility symlink at `/usr/libexec/voipdomain/daemons` point to the same location.

Socket (must match NGiNX vhost):

```ini
listen = /run/php-fpm/voipdomain.sock
```

## systemd services

| Service | Package |
|---------|---------|
| `voipdomain-client.service` | `voipdomain-client` |
| `voipdomain-router.service` | `voipdomain-router` |
| `voipdomain-fastagi.service` | `voipdomain-fastagi` |
| `voipdomain-monitor.service` | `voipdomain-monitor` |
| `voipdomain-daemon-reload@{client,router,monitor,fastagi}.path` | respective daemon + `daemons-common` |

After `%post`, run manually if needed:

```bash
systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain.conf
systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-webserver.conf   # admin only
systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-client.conf      # Asterisk node only
systemctl enable --now voipdomain-daemon-reload@client.path
```

## YUM/DNF repository

Example repo file: `VoIPDomain.repo`. Adjust `baseurl` to your mirror.

```bash
cp VoIPDomain.repo /etc/yum.repos.d/
rpm --import https://rhel.voipdomain.io/azevedo@voipdomain.io.key
dnf install voipdomain-webserver voipdomain-router voipdomain-client voipdomain-fastagi voipdomain-monitor
```

## Building from source tarball

```bash
Documentations/rpm/build-rpm.sh
```

Requires: `rpmbuild`, `tar`, `xz`. Output lands in `~/rpmbuild/RPMS/noarch/`.

## Recommended stack (not all hard Requires)

| Component | Admin server | Asterisk node |
|-----------|--------------|---------------|
| MariaDB | Required | Required (`vdclient`) |
| Gearman (`gearmand`) | Required | Required |
| NGiNX + PHP-FPM | Required | — |
| Asterisk 21/22 | — | Required (`voipdomain-client` RPM **Requires: asterisk**) |

PHP minimum: **5.5** (application); RHEL 7 ships 5.4 — use Remi or a newer OS for production.

## Related documentation

- [../en/INSTALL.md](../en/INSTALL.md) — full installation guide
- [../etc/nginx/README.md](../etc/nginx/README.md) — NGiNX examples
- [../en/TESTING-METHODOLOGY.md](../en/TESTING-METHODOLOGY.md) — lab validation
