# VoIP Domain

[![GitHub release](https://img.shields.io/github/v/release/VoIP-Domain/voipdomain)](https://github.com/VoIP-Domain/voipdomain)
[![GitHub license](https://img.shields.io/github/license/VoIP-Domain/voipdomain.svg)](https://github.com/VoIP-Domain/voipdomain)

**VoIP Domain – The new era of VoIP management**

Migrating your telephony to VoIP has never been easier!

**VoIP Domain** was created to address a real business need: modernizing communications **without complications or disruption for end users**.

Our platform was built with a focus on simplicity and cutting-edge technology, offering:
- **Multi-Tenant**: The system supports multiple tenants, allowing service providers to offer the system to customers.
- **Smart Auto-Provisioning**: Automatically configure any device from any manufacturer with a unified standard.
- **Multiple Servers**: Manage a network of Asterisk VoIP servers easily using a modern interface.
- **Intuitive and Powerful Interface**: No need to be a VoIP or telephony expert to manage the system.
- **Full Compatibility**: Native support for multiple Asterisk servers, even offline.
- **Automatic Dial Plans** and **Smart Least-Cost Routing**.
- **Call Billing**: Native billing of each call.
- **Workflow-Configurable IVR**: Create dynamic and efficient call menus.
- **Local Standards**: Second dial tone, tones, and language automatically configured to ensure user familiarity.
- **Streamline Deployment**: once your servers are installed, all configurations can be managed through the web interface—no need to log in to individual servers again.
- **Complete RESTful API**: Generated directly from the code, documented in Swagger OAS 3.0.
- **100% Modular Architecture**: Build plugins, translations, custom integrations, and more!

**VoIP Domain** is the system that respects your legacy while delivering all the innovation your business needs for the future, allowing seamless interaction and control over your telephony infrastructure.

**Modernize your company’s communication today with VoIP Domain!**

A live demo will be available soon.

## Features

- Multi-tenant
- Automatic phone provisioning
- Least Cost Routing (LCR)
- Call billing
- Centralized management for multiple Asterisk servers
- Intelligent call routing
- RESTful API
- Code documentation
- Modular code with plugin support
- Multilingual
  - English (native language)
  - Brazilian Portuguese
- Easy to use

## Links

- Project homepage: https://voipdomain.io/
- RPM repository (RHEL): https://rhel.voipdomain.io/

## Project Status

**v1.0** — first stable release; production-ready for multi-tenant enterprise VoIP deployments.

## Documentation

Full documentation index: [Documentations/README.md](Documentations/README.md) (EN) · [Documentations/pt_BR/README.md](Documentations/pt_BR/README.md) (PT-BR)

| Document | English | Português (BR) |
|----------|---------|----------------|
| **Quick start (30 min)** | [en/QUICK-START.md](Documentations/en/QUICK-START.md) | [pt_BR/QUICK-START.md](Documentations/pt_BR/QUICK-START.md) |
| **Developer manual (complete)** | [en/DEVELOPER-MANUAL.md](Documentations/en/DEVELOPER-MANUAL.md) | [pt_BR/MANUAL-DO-DESENVOLVEDOR.md](Documentations/pt_BR/MANUAL-DO-DESENVOLVEDOR.md) |
| Architecture | [en/ARCHITECTURE.md](Documentations/en/ARCHITECTURE.md) | [pt_BR/ARQUITETURA.md](Documentations/pt_BR/ARQUITETURA.md) |
| Install guide (full) | [en/INSTALL.md](Documentations/en/INSTALL.md) | [pt_BR/INSTALL.md](Documentations/pt_BR/INSTALL.md) |
| Deployment planning | [en/DEPLOYMENT-PLANNING.md](Documentations/en/DEPLOYMENT-PLANNING.md) | [pt_BR/PLANEJAMENTO-DE-IMPLANTACAO.md](Documentations/pt_BR/PLANEJAMENTO-DE-IMPLANTACAO.md) |
| Dialplan (Asterisk) | [en/DIALPLAN.md](Documentations/en/DIALPLAN.md) | [pt_BR/DIALPLAN.md](Documentations/pt_BR/DIALPLAN.md) |
| RPM packaging | [rpm/README.md](Documentations/rpm/README.md) | — |
| Testing | [en/TESTING-METHODOLOGY.md](Documentations/en/TESTING-METHODOLOGY.md) | [pt_BR/METODOLOGIA-DE-TESTES.md](Documentations/pt_BR/METODOLOGIA-DE-TESTES.md) |
| FAQ | [en/FAQ.md](Documentations/en/FAQ.md) | [pt_BR/FAQ.md](Documentations/pt_BR/FAQ.md) |
| Security | [en/SECURITY.md](Documentations/en/SECURITY.md) | [pt_BR/SEGURANCA.md](Documentations/pt_BR/SEGURANCA.md) |
| Recent features | [en/IMPLEMENTED-FEATURES.md](Documentations/en/IMPLEMENTED-FEATURES.md) | [pt_BR/RECURSOS-IMPLEMENTADOS.md](Documentations/pt_BR/RECURSOS-IMPLEMENTADOS.md) |
| Sample plugin | [Plugins/sampleitems/README.md](Plugins/sampleitems/README.md) | — |

## Release History

**v0.0.1 – May 11, 2018**
- Initial public (alpha) release

**v0.0.2 – October 17, 2018**
- Hundreds of new features and bug fixes (alpha version)

**v0.1 – April 11, 2025**
- After years of development and several rewrites, this version marks the first pre-release

**v0.2 – July 21, 2025**
- A major system refactoring to add Multi-Tenant support and some minor optimizations

**v0.3 RC1 – June 5, 2026**
- Dynamic timezone picker driven by host IANA/tzdata (replaces hardcoded map and offsets)
- Documentation reorganized by language (`Documentations/en/`, `Documentations/pt_BR/`)
- Rewritten installation and security guides; RPM `%doc` aligned with new layout
- Platform features: VDFT file transfer, plugin lifecycle and remote sync, monitor delta sync, inter-server PJSIP trunks, extension number migration, and related daemon hooks

**v1.0 – July 21, 2026**
- First stable release — production-ready for multi-tenant enterprise VoIP deployments
- Server rebuild logic overhauled (selective main/backup rebuild, per-server crypto keys)
- Router cryptographic isolation: per-tenant key management, pre-privilege-drop key caching
- Command lifecycle cleanup: executed commands properly removed from database
- Comprehensive bug fix sweep (~30 issues across 16 files)
- Full documentation refresh for 1.0

## License

This project is licensed under the **GPLv3**.
