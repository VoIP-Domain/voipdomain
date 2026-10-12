# Changelog

All notable changes to this project are documented in this file.

## [1.0] — 2026-07-21

First stable release. VoIP Domain is now production-ready for multi-tenant enterprise
VoIP deployments with centralized Asterisk management.

### Highlights

- Multi-tenant architecture with per-tenant cryptographic isolation
- Centralized management of multiple Asterisk servers via web interface
- Automatic phone provisioning (Yealink, Grandstream, Fanvil, Polycom, Snom, Cisco, AudioCodes, Htek, Khomp, Softphone)
- Least Cost Routing (LCR) with automatic dialplan generation
- Call billing engine
- IVR builder with workflow-configurable menus
- RESTful API with Swagger OAS 3.0 documentation
- Plugin architecture with remote sync
- VDFT (VoIP Domain File Transfer) streaming protocol
- Inter-server PJSIP trunks
- SELinux policy module
- RPM packaging for RHEL/Fedora

### Fixed (since 0.3 RC1)

- Server rebuild logic: frontend now correctly passes selected servers/backups to API
- Server rebuild: missing RSA key generation for new backup servers
- Server rebuild: parentheses bug in backup address validation (gethostbyname)
- Server rebuild: hook ordering for extensions-phones (dependency on profiles)
- Commands table: entries now properly deleted after successful client execution
- Router crypto: was using wrong keypair to decrypt client task replies (master key instead of tenant key)
- Router startup: fatal crash on first task_reply due to missing config (now reads keys from database)
- Router: pre-loads all cryptographic keys before dropping privileges to nobody
- Client daemon: system_event now verifies Gearman submission success and retries on failure
- Router: GroupedCommands counter properly decremented; completed groups cleaned up
- tmpfiles.d: removed duplicate entries for /var/lib/tftpboot and /var/log/voipdomain
- RPM: separated asterisk-specific tmpfiles to avoid systemd errors on admin-only servers
- IVR: fixed audio path resolution, FILTER syntax, wipe function
- Comprehensive bug fix sweep (~30 bugs across 16 files)

## [0.3-rc1] — 2026-06-05

- Dynamic timezone picker driven by host IANA/tzdata (replaces hardcoded map and offsets)
- Documentation reorganized by language (en/, pt_BR/)
- Rewritten installation and security guides
- Platform features: VDFT file transfer, plugin lifecycle and remote sync, monitor delta sync, inter-server PJSIP trunks, extension number migration

## [0.2] — 2025-07-21

- Major system refactoring to add Multi-Tenant support
- Aligned systemd units, nginx/php-fpm examples, and daemon paths

## [0.1] — 2025-04-11

- First pre-release after years of development and several rewrites

## [0.0.2] — 2018-10-17

- Hundreds of new features and bug fixes (alpha version)

## [0.0.1] — 2018-05-11

- Initial public alpha release
