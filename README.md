VoIP Domain
===========
[![GitHub release](https://img.shields.io/github/release/ernaniaz/voipdomain.svg?maxAge=2592000)](https://github.com/ernaniaz/voipdomain)
[![GitHub license](https://img.shields.io/github/license/ernaniaz/voipdomain.svg)](https://github.com/ernaniaz/voipdomain)

VoIP Domain is a modern interface to manage a network of VoIP Asterisk servers, with many native features. All the system are based on a RESTful API, and even the administrative interface uses this API to manage the system, enabling your third party application (like a CRM) to interact with your telephony system. The communication between the administrative interface and the Asterisk servers are done by a daemon, that manage all the configurations needed at your local server, enabling all the features local, in other words, if you lost the connectivity from your Asterisk server to the administrative interface, all the features like Least Cost Route, Call billing, Call routing, and others are keep working.

You can easy deploy one or many servers and after installed, you wouldn't need to access the server anymore to change configurations, all done by the administrative interface.

The demo will be available soon.

Features
--------
* Phone auto provisioning;
* Least Cost Route (LCR);
* Call billing;
* Intelligent call route;

History
-------
This project are in alpha version now. Please keep in touch while we prepare the first production version.

v0.0.1 - Released May/11/2018:
* First public release (alpha version).

License
-------
GPLv3 License.
