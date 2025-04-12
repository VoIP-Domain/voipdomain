  VoIP Domain Developer's FAQ
--===========================--

Q- My number doesn't appears at hunt group select box!
A- The extension type is filtered only to types existent at $_in["hunts"] configuration global. See module/extensions-phones/config.php for a type include example. This filter exists to avoid recursive and no endpoint extension dials.

Q- There's any way to execute code when install/upgrade/remove a plugin?
A- Yes. You must register the "plugin_YOURPLUGINNAME_{install,upgrade,remove}" framework hook. When triggered, will receive an array containing information about the system.
