# NGiNX examples for VoIP Domain (RPM / RHEL)

Use these files on the **administration server** (`voipdomain-webserver` package).

The administrative interface runs under the **`nginx`** system user via PHP-FPM (`user = nginx` in the pool). After install, the RPM sets ownership on `/var/lib/voipdomain/webroot` and `/var/lib/voipdomain/storage` to `nginx:nginx`.

## Files in this directory

| Repository file | Installed on server (RPM) |
|-----------------|---------------------------|
| `voipdomain.conf` | `/etc/nginx/conf.d/voipdomain.conf` |
| `voipdomain-fastcgi.conf` | `/etc/nginx/voipdomain-fastcgi.conf` |
| `nginx.conf` | **Reference only** — full main config example (not installed as `%config`) |
| `ssl/gencert.sh` | Optional self-signed certificate helper |

On RHEL 8/9 the shipped vhost snippet under `conf.d/` is the supported layout. Use `nginx.conf` when you need a standalone reference for TLS, logging, or upstream blocks.

## PHP-FPM socket

The vhost uses:

```nginx
fastcgi_pass unix:/run/php-fpm/voipdomain.sock;
```

This must match `listen =` in `/etc/php-fpm.d/voipdomain.conf`. Cross-reference: [../php-fpm.d/README.md](../php-fpm.d/README.md).

## Front controller (`GET /`)

The vhost must **not** use `index index.php` with `try_files $uri $uri/`. That combination makes NGiNX internally open `/index.php` for `/`, which hits `location ~ \.php$ { return 404; }` and returns the default NGiNX 404 page. Use `try_files $uri @vdmodules;` only (as in `voipdomain.conf`).

Static paths (`/js/`, `/css/`, `/img/`, `/vendors/`) are served directly from disk and must not fall through to PHP (otherwise a missing file returns HTML from `index.php` and breaks MIME types with `X-Content-Type-Options: nosniff`).

## Security headers

The shipped vhost uses **pragmatic** headers for an admin UI (HSTS, `X-Frame-Options: SAMEORIGIN`, `nosniff`, referrer policy). There is **no Content-Security-Policy** — the PHP application and error pages rely on inline script/style in several places. Do not add a strict `default-src 'self'` CSP unless you audit the entire webroot.

## After copying configs (manual install)

```bash
cp Documentations/etc/nginx/voipdomain.conf /etc/nginx/conf.d/
cp Documentations/etc/nginx/voipdomain-fastcgi.conf /etc/nginx/
nginx -t
systemctl enable --now nginx php-fpm
systemctl reload nginx
```

## RPM note

The `voipdomain-webserver` package installs the vhost and FastCGI params as `%config(noreplace)` and ships this entire directory under `/usr/share/doc/voipdomain-webserver*/nginx/`. See [../../rpm/README.md](../../rpm/README.md).
