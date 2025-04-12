# VoIP Domain

[![GitHub release](https://img.shields.io/github/release/VoIP-Domain/voipdomain.svg?maxAge=2592000)](https://github.com/VoIP-Domain/voipdomain)
[![GitHub license](https://img.shields.io/github/license/VoIP-Domain/voipdomain.svg)](https://github.com/VoIP-Domain/voipdomain)

**VoIP Domain** is a modern interface designed to manage a network of Asterisk VoIP servers, offering a wide range of native features. The entire system is built around a RESTful API—used not only by third-party integrations such as CRMs but also by the administrative interface itself. This architecture allows seamless interaction and control over your telephony infrastructure.

Communication between the administrative interface and the Asterisk servers is handled by a dedicated daemon. This daemon manages all necessary local configurations, ensuring that key features such as Least Cost Routing, Call Billing, and Call Routing continue to function even if the connection to the central interface is lost.

Deployment is streamlined: once your servers are installed, all configurations can be managed through the web interface—no need to log in to individual servers again.

A live demo will be available soon.

## Features

- Automatic phone provisioning
- Least Cost Routing (LCR)
- Call billing
- Centralized management for multiple Asterisk servers
- Intelligent call routing

## Project Status

The project is currently in pre-release. Stay tuned for updates as we approach the first stable release.

## Install instructions

Please refer to the Documentation/INSTALL.TXT file for install instructions.

### Release History

**v0.0.1 – May 11, 2018**
- Initial public (alpha) release

**v0.0.2 – October 17, 2018**
- Hundreds of new features and bug fixes (alpha version)

**v0.1 – April 11, 2025**
- After years of development and several rewrites, this version marks the first pre-release

## License

This project is licensed under the **GPLv3**.
