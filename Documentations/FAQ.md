  VoIP Domain Developer's FAQ
--===========================--

Q- My number doesn't appears at hunt group select box!
A- The extension type is filtered only to types existent at $_in["hunts"] configuration global. See module/extensions-phones/config.php for a type include example. This filter exists to avoid recursive and no endpoint extension dials.

Q- There's any way to execute code when install/upgrade/remove a plugin?
A- Yes. You must register the "plugin_YOURPLUGINNAME_{install,upgrade,remove}" framework hook. When triggered, will receive an array containing information about the system.

Q) Why does the VoIP Domain MySQL database run only with the InnoDB engine?
A) Because it requires transaction support, among other minor requirements.

Q) Why does VoIP Domain use the `START TRANSACTION` query instead of `mysqli::begin_transaction`?
A) Because `mysqli::begin_transaction` requires PHP ≥ 5.5.0, and our main focus is on RHEL-based servers. We want to support RHEL 7, which ships PHP 5.4.16 by default.

Q) Does VoIP Domain have plans to support PostgreSQL?
A) Not at the moment. We are focused on MySQL/MariaDB, and PostgreSQL will be considered only if the community does the necessary research and development to support multiple databases.

Q) Do Gearman event names follow a standard?
