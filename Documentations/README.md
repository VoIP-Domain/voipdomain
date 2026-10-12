# VoIP Domain — Documentation (English)

Central index for all project documentation. Brazilian Portuguese: [pt_BR/README.md](pt_BR/README.md).

---

## Start here

| Audience | Document |
|----------|----------|
| **First contact / lab** | [en/QUICK-START.md](en/QUICK-START.md) ← **Start here!** |
| **Developers (full reference)** | [en/DEVELOPER-MANUAL.md](en/DEVELOPER-MANUAL.md) |
| **Operators / installers** | [en/INSTALL.md](en/INSTALL.md) · [en/DEPLOYMENT-PLANNING.md](en/DEPLOYMENT-PLANNING.md) |
| **Quick architecture overview** | [en/ARCHITECTURE.md](en/ARCHITECTURE.md) |
| **End users / admins (FAQ)** | [en/FAQ.md](en/FAQ.md) · [pt_BR/FAQ.md](pt_BR/FAQ.md) |

---

## Developer documentation

| Topic | English |
|-------|---------|
| Complete developer manual | [en/DEVELOPER-MANUAL.md](en/DEVELOPER-MANUAL.md) |
| Architecture & data flows | [en/ARCHITECTURE.md](en/ARCHITECTURE.md) |
| Testing methodology | [en/TESTING-METHODOLOGY.md](en/TESTING-METHODOLOGY.md) |
| Recently implemented features | [en/IMPLEMENTED-FEATURES.md](en/IMPLEMENTED-FEATURES.md) |
| Sample plugin | [../Plugins/sampleitems/README.md](../Plugins/sampleitems/README.md) |

Legacy stub (redirects to manual): [en/DEVELOPER-GUIDE.md](en/DEVELOPER-GUIDE.md), [en/DEVELOPER-FAQ.md](en/DEVELOPER-FAQ.md).

---

## Installation & deployment

| Topic | Document |
|-------|----------|
| **Quick start (30 min)** | [en/QUICK-START.md](en/QUICK-START.md) |
| Full installation guide | [en/INSTALL.md](en/INSTALL.md) |
| Deployment planning (topology, DB, daemons) | [en/DEPLOYMENT-PLANNING.md](en/DEPLOYMENT-PLANNING.md) |
| Asterisk dialplan layout | [en/DIALPLAN.md](en/DIALPLAN.md) |
| RPM packaging (RHEL) | [rpm/README.md](rpm/README.md) |
| NGiNX example | [etc/nginx/README.md](etc/nginx/README.md) |
| PHP-FPM pool | [etc/php-fpm.d/README.md](etc/php-fpm.d/README.md) |
| systemd units | [systemd/](systemd/) |
| Sample configs | [etc/voipdomain/](etc/voipdomain/) |

---

## Security & operations

| Topic | Document |
|-------|----------|
| Security guide | [en/SECURITY.md](en/SECURITY.md) |
| Testing scripts | [testing/README.md](testing/README.md) |

---

## Repository map

```
Documentations/
├── README.md              ← this index (English hub)
├── en/                    ← English documentation
├── pt_BR/                 ← Portuguese (Brazil) documentation
├── etc/                   ← sample server configs
├── rpm/                   ← RPM spec & build
├── systemd/               ← unit files
├── scripts/               ← maintainer scripts
├── testing/               ← smoke-test scripts
├── API/                   ← API reference assets
├── ITU-T/                 ← numbering / LCR data tools
└── swagger/               ← OpenAPI UI assets
```
