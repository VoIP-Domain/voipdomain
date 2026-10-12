# PHP-FPM pool `[voipdomain]` for VoIP Domain admin UI

Install to `/etc/php-fpm.d/voipdomain.conf`.

The pool runs as user and group **`nginx`**, matching the NGiNX worker user on RHEL-family systems. The web installer and uploads require write access to `/etc/voipdomain/` (group `nginx`) and `/var/lib/voipdomain/storage/`.

## Socket (must match NGiNX)

Align with the FastCGI socket in [../nginx/voipdomain.conf](../nginx/voipdomain.conf) (installed as `/etc/nginx/conf.d/voipdomain.conf`):

```ini
listen = /run/php-fpm/voipdomain.sock
listen.owner = nginx
listen.group = nginx
user = nginx
group = nginx
```

After editing:

```bash
systemctl restart php-fpm
```

## Restricted paths

`open_basedir` uses `/var/lib/voipdomain/` (covers webroot and storage). Trailing slashes on each path are recommended. See the pool file for the full list.

## Working directory (`chdir`)

`chdir = /var/lib/voipdomain/webroot` in the pool is **optional**. Entry scripts load `includes/config.inc.php` with an absolute path; that file defines `VD_WEBROOT` from its own location and calls `chdir()` before any relative `modules/…` includes. The UI works even if `chdir` is missing from the pool file.

## Slow requests

`request_slowlog_timeout = 60s` — only requests running longer than 60 seconds are logged to `slowlog`. The web DB installer calls `set_time_limit(600)`; do not lower the slowlog threshold below 60s on admin servers.

On some hosts (SELinux, `kernel.yama.ptrace_scope`), PHP-FPM may log `failed to ptrace(ATTACH)` when writing slowlog stack traces. That does not stop the request; fix policy or ignore if you only need the duration warning.

## RPM note

Shipped as `%config(noreplace)` by `voipdomain-webserver`. NGiNX examples (including full `nginx.conf` reference) are in [../nginx/README.md](../nginx/README.md).
