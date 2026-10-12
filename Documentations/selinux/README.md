# VoIP Domain SELinux Policy Module

This directory contains the SELinux policy module for VoIP Domain.

## Files

| File | Description |
|------|-------------|
| `voipdomain.te` | Type Enforcement rules - defines domains, types, and access rules |
| `voipdomain.fc` | File Contexts - maps filesystem paths to SELinux types |
| `voipdomain.if` | Interface definitions - macros for other modules to interact with VoIP Domain |
| `Makefile` | Build and installation targets |

## Types Defined

| Type | Purpose |
|------|---------|
| `voipdomain_t` | Domain for VoIP Domain daemon processes |
| `voipdomain_exec_t` | Executable files (daemon PHP scripts) |
| `voipdomain_etc_t` | Configuration files in `/etc/voipdomain/` |
| `voipdomain_var_lib_t` | Data files in `/var/lib/voipdomain/` |
| `voipdomain_log_t` | Log files in `/var/log/voipdomain/` |
| `voipdomain_runtime_t` | Runtime files in `/run/voipdomain/` |
| `voipdomain_spool_t` | Spool files in `/var/spool/voipdomain/` |
| `voipdomain_unit_t` | Systemd unit files |

## Manual Installation

If not using the RPM package:

```bash
# Install build dependencies
dnf install selinux-policy-devel

# Build the module
make

# Install the module
sudo semodule -i voipdomain.pp

# Apply file contexts
sudo restorecon -RF /etc/voipdomain
sudo restorecon -RF /var/lib/voipdomain
sudo restorecon -RF /var/log/voipdomain
sudo restorecon -RF /var/spool/voipdomain
sudo restorecon -RF /run/voipdomain
```

## Uninstallation

```bash
sudo semodule -r voipdomain
```

## Troubleshooting

### View denials

```bash
ausearch -m avc -ts recent | grep voipdomain
```

### Generate allow rules from denials

```bash
ausearch -m avc -ts recent | audit2allow -M voipdomain_local
```

### Check current contexts

```bash
ls -Z /var/lib/voipdomain/daemons/
ls -Z /etc/voipdomain/
```

### Verify module is loaded

```bash
semodule -l | grep voipdomain
```

## Integration with httpd_t (PHP-FPM/Nginx)

The policy allows `httpd_t` (PHP-FPM running as nginx) to:
- Read/write VoIP Domain configuration files
- Manage webroot and storage directories
- Access spool for file transfers

## Integration with Asterisk

The policy includes optional rules for Asterisk integration:
- Stream connect to Asterisk socket
- Manage Asterisk configuration
- Access Asterisk spool and var_lib directories

These rules are only applied if the `asterisk` SELinux module is present.
