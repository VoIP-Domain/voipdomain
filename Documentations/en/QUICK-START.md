# VoIP Domain — Quick Start

Minimal installation in **30 minutes** to explore the system. Português: [../pt_BR/QUICK-START.md](../pt_BR/QUICK-START.md).

> **Goal:** Spin up an all-in-one environment (admin + Asterisk) on a single machine to explore the interface and test basic calls. Not recommended for production without security and scalability adjustments.

---

## Prerequisites

| Item | Requirement |
|------|-------------|
| **OS** | RHEL 9 or compatible (Rocky Linux, AlmaLinux, etc) |
| **RAM** | 2 GB minimum (4 GB recommended) |
| **Disk** | 20 GB free |
| **Network** | Internet access to download packages |
| **User** | Root or sudo access |

---

## Step 1 — Configure VoIP Domain repository

```bash
sudo tee /etc/yum.repos.d/VoIPDomain.repo > /dev/null << 'EOF'
[voipdomain]
name=VoIP Domain Repository
baseurl=https://rhel.voipdomain.io/9/
enabled=1
gpgcheck=1
gpgkey=https://rhel.voipdomain.io/azevedo@voipdomain.io.key
EOF

sudo rpm --import https://rhel.voipdomain.io/azevedo@voipdomain.io.key
```

---

## Step 2 — Install packages

```bash
sudo dnf install -y \
  mariadb-server \
  gearmand \
  nginx \
  php-fpm \
  asterisk \
  asterisk-pjsip \
  asterisk-voicemail-plain \
  asterisk-sounds-core-en-ulaw \
  asterisk-sounds-core-en-alaw \
  tftp-server \
  voipdomain-webserver \
  voipdomain-router \
  voipdomain-client \
  voipdomain-fastagi \
  voipdomain-monitor \
  voipdomain-daemons-common
```

---

## Step 3 — Start base services

```bash
sudo systemctl enable --now mariadb.service
sudo systemctl enable --now gearmand.service
sudo systemctl enable --now nginx.service
sudo systemctl enable --now php-fpm.service
sudo systemctl enable --now asterisk.service
sudo systemctl enable --now tftp.service
```

---

## Step 4 — Create directories and permissions

```bash
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain.conf
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-webserver.conf
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-client.conf
```

---

## Step 5 — Open firewall (optional)

If using firewalld:

```bash
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --permanent --add-port=5060/udp   # SIP
sudo firewall-cmd --permanent --add-port=5060/tcp   # SIP
sudo firewall-cmd --permanent --add-port=10000-20000/udp  # RTP
sudo firewall-cmd --reload
```

---

## Step 6 — Access the web installer

1. Open your browser and go to: `http://<SERVER_IP>/`

2. The web installer will guide you through:
   - **Permission check** — should pass automatically
   - **Database** — enter:
     - Host: `localhost`
     - MariaDB root user: `root` (leave password blank if not configured)
     - The installer will create the `vd` database and `vd` user automatically
   - **Administrator** — create the admin user (default: `admin` / `admin`)

3. After completion, log in with the credentials you created.

---

## Step 7 — Start VoIP Domain daemons

```bash
sudo systemctl enable --now voipdomain-router.service
sudo systemctl enable --now voipdomain-client.service
sudo systemctl enable --now voipdomain-fastagi.service
sudo systemctl enable --now voipdomain-monitor.service

# Path units for automatic reload
sudo systemctl enable --now voipdomain-daemon-reload@router.path
sudo systemctl enable --now voipdomain-daemon-reload@client.path
sudo systemctl enable --now voipdomain-daemon-reload@fastagi.path
sudo systemctl enable --now voipdomain-daemon-reload@monitor.path
```

---

## Step 8 — Register the Asterisk server

1. In the web interface, go to **Servers** → **Add**
2. Fill in:
   - **Name:** `Local Server`
   - **Address:** `127.0.0.1`
   - **Port:** `5060`
3. Save and note the generated **Server ID** (e.g., `1`)

---

## Step 9 — Configure Asterisk

Run the configuration script with the server ID:

```bash
sudo /etc/asterisk/voipdomain/configure-asterisk.sh <SERVER_ID>
# Example: sudo /etc/asterisk/voipdomain/configure-asterisk.sh 1
```

Confirm with `y` when prompted.

Reload Asterisk:

```bash
sudo asterisk -rx "core reload"
```

---

## Step 10 — Create minimal structure for testing

In the web interface, create objects in this order:

| Order | Menu | What to create |
|-------|------|----------------|
| 1 | **Profiles** | A profile (e.g., "Default") |
| 2 | **Cost Centers** | A cost center (e.g., "General") |
| 3 | **Groups** | A group associated with the profile and cost center |
| 4 | **Ranges** | An extension range (e.g., 1000-1099) associated with the group |
| 5 | **Extensions** | Two extensions for testing (e.g., 1001 and 1002) |

---

## Step 11 — Test a call

1. Configure a softphone (e.g., Zoiper, Linphone, MicroSIP) with:
   - **Server:** Your machine's IP
   - **User:** extension number (e.g., `1001`)
   - **Password:** password set on the extension
   - **Protocol:** SIP (UDP)

2. Repeat for the second extension on another device/softphone

3. Dial from one extension to the other (e.g., `1002`)

4. If the call connects, the system is working!

---

## Quick verification

| Check | Command |
|-------|---------|
| Web interface loads | Access `http://<IP>/` |
| MariaDB running | `sudo systemctl status mariadb` |
| Gearman running | `gearadmin --status` |
| Router active | `sudo systemctl status voipdomain-router` |
| Client registered | `gearadmin --status | grep vd_client` |
| Asterisk ok | `sudo asterisk -rx "pjsip show endpoints"` |

---

## Common troubleshooting

| Problem | Solution |
|---------|----------|
| Blank page/error 500 | Check logs: `sudo journalctl -u php-fpm -f` |
| Installer says no permission | Run: `sudo chmod 0770 /etc/voipdomain && sudo chown root:nginx /etc/voipdomain` |
| Node offline in UI | Check: `sudo systemctl status voipdomain-client` |
| Extension won't register | Check firewall and port 5060 |
| No audio in call | Check RTP ports (10000-20000 UDP) |

---

## Next steps

After validating the basic installation, see:

| Topic | Document |
|-------|----------|
| Full installation | [INSTALL.md](INSTALL.md) |
| Production planning | [DEPLOYMENT-PLANNING.md](DEPLOYMENT-PLANNING.md) |
| Security | [SECURITY.md](SECURITY.md) |
| Architecture | [ARCHITECTURE.md](ARCHITECTURE.md) |
| Multi-tenant | Configure in **System** → **Tenants** |
| External gateways | Add in **Gateways** for external calls |
| TFTP/DHCP | For automatic phone provisioning |

---

## Command summary

```bash
# 1. Repository
sudo tee /etc/yum.repos.d/VoIPDomain.repo > /dev/null << 'EOF'
[voipdomain]
name=VoIP Domain Repository
baseurl=https://rhel.voipdomain.io/9/
enabled=1
gpgcheck=1
gpgkey=https://rhel.voipdomain.io/azevedo@voipdomain.io.key
EOF
sudo rpm --import https://rhel.voipdomain.io/azevedo@voipdomain.io.key

# 2. Packages
sudo dnf install -y mariadb-server gearmand nginx php-fpm asterisk \
  asterisk-pjsip asterisk-voicemail-plain asterisk-sounds-core-en-ulaw \
  asterisk-sounds-core-en-alaw tftp-server voipdomain-webserver \
  voipdomain-router voipdomain-client voipdomain-fastagi voipdomain-monitor \
  voipdomain-daemons-common

# 3. Base services
sudo systemctl enable --now mariadb.service gearmand.service nginx.service \
  php-fpm.service asterisk.service tftp.service

# 4. Directories
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain.conf
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-webserver.conf
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-client.conf

# 5. Firewall (if applicable)
sudo firewall-cmd --permanent --add-service=http --add-service=https
sudo firewall-cmd --permanent --add-port=5060/udp --add-port=5060/tcp
sudo firewall-cmd --permanent --add-port=10000-20000/udp
sudo firewall-cmd --reload

# 6. Access http://<IP>/ and complete web installer

# 7. VoIP Domain daemons (after web installer)
sudo systemctl enable --now voipdomain-router.service voipdomain-client.service \
  voipdomain-fastagi.service voipdomain-monitor.service
sudo systemctl enable --now voipdomain-daemon-reload@{router,client,fastagi,monitor}.path

# 8. Register server in UI and note the ID

# 9. Configure Asterisk
sudo /etc/asterisk/voipdomain/configure-asterisk.sh <ID>
sudo asterisk -rx "core reload"

# 10. Create profile, cost center, group, range, extensions in UI

# 11. Test a call between two extensions
```
