  VoIP Domain Developer's FAQ
--===========================--

> **Extended reference:** [DEVELOPER-MANUAL.md](DEVELOPER-MANUAL.md) · [Documentation index](../README.md)

Q- My number doesn't appear in the hunt group select box!
A- The extension type list is filtered to include only types present in the global `$_in["hunts"]` configuration. See `modules/extensions-phones/config.php` for an example of which types to include. This filter exists to avoid recursive and non-endpoint extension types.

Q- Is there any way to execute code when installing, upgrading, or removing a plugin?
A- Yes. You must register the "plugin_YOURPLUGINNAME_{install,upgrade,remove}" framework hook. When triggered, it will receive an array containing information about the system.

Q) Why does the VoIP Domain MySQL database run only with the InnoDB engine?
A) Because it requires transaction support, among other minor requirements.

Q) Why does VoIP Domain use the `START TRANSACTION` query instead of `mysqli::begin_transaction`?
A) Because `mysqli::begin_transaction` requires PHP ≥ 5.5.0, and our main focus is on RHEL-based servers. We want to support RHEL 7, which ships PHP 5.4.16 by default.

Q) Does VoIP Domain have plans to support PostgreSQL?
A) Not at the moment. We are focused on MySQL/MariaDB, and PostgreSQL will be considered only if the community does the necessary research and development to support multiple databases.

Q) Do Gearman event names follow a standard?
A) Yes. They must use `MODULENAME_ACTION`. If any other subaction or specification exists, it must be appended afterward, separated by underscores.
