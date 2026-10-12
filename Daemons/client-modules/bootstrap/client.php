<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * VoIP Domain Asterisk bootstrap generation orchestrator.
 *
 * This module is the central point for Asterisk configuration generation via
 * hooks. It registers:
 *   - Trigger hooks (server_rebuild_post, plugin_state_change) that fire
 *     full bootstrap regeneration
 *   - Core PJSIP hooks (transports, defaults, includes, notify)
 *   - Core hooks for queues, confbridge, voicemail, MOH, manager, indications
 *
 * Dialplan hooks are registered separately in the dialplan module, and
 * individual entity modules (agents, conferences, etc.) register their own
 * hooks for their specific config areas.
 *
 * Plugins extend the system by registering additional functions on any of
 * these hooks. Since each function receives the full buffer built by prior
 * functions, plugins can append, prepend, modify, or replace content.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Bootstrap
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Register trigger hooks — these fire full bootstrap regeneration
 */
framework_add_hook ( "server_rebuild_post", "bootstrap_regenerate_on_rebuild");
framework_add_hook ( "plugin_state_change", "bootstrap_regenerate_on_plugin");
framework_add_hook ( "daemon_startup", "bootstrap_regenerate_on_startup");

/**
 * Register core PJSIP hooks
 */
framework_add_hook ( "asterisk_pjsip_transports", "bootstrap_pjsip_transports");
framework_add_hook ( "asterisk_pjsip_defaults", "bootstrap_pjsip_defaults");
framework_add_hook ( "asterisk_pjsip_includes", "bootstrap_pjsip_includes");
framework_add_hook ( "asterisk_pjsip_notify_notify", "bootstrap_pjsip_notify");

/**
 * Register core hooks for simple config files (glob includes)
 */
framework_add_hook ( "asterisk_queues_queues", "bootstrap_queues_includes");
framework_add_hook ( "asterisk_confbridge_confbridge", "bootstrap_confbridge_includes");
framework_add_hook ( "asterisk_voicemail_voicemail", "bootstrap_voicemail_includes");
framework_add_hook ( "asterisk_moh_moh", "bootstrap_moh_includes");
framework_add_hook ( "asterisk_manager_manager", "bootstrap_manager_includes");
framework_add_hook ( "asterisk_indications_indications", "bootstrap_indications_includes");

/**
 * Trigger: regenerate bootstrap after server rebuild completes.
 * Also ensures all required Asterisk modules are loaded and verifies
 * directory structure.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_regenerate_on_rebuild ( $buffer, $parameters)
{
  bootstrap_ensure_structure ();
  bootstrap_ensure_modules ();
  asterisk_bootstrap_generate ( $parameters);
  return $buffer;
}

/**
 * Ensure all required directories exist and Asterisk config files include
 * the VoIP Domain bootstrap files.
 */
function bootstrap_ensure_structure ()
{
  global $_in;

  $dirs = array (
    asterisk_bootstrap_dir (),
    $_in["general"]["confdir"],
  );
  if ( array_key_exists ( "soundsdir", $_in["general"]))
  {
    $dirs[] = $_in["general"]["soundsdir"];
  }

  foreach ( $dirs as $dir)
  {
    if ( ! is_dir ( $dir))
    {
      if ( @mkdir ( $dir, 0755, true))
      {
        writeLog ( "Created directory: " . $dir);
      } else {
        writeLog ( "Failed to create directory: " . $dir, VoIP_LOG_WARNING);
      }
    }
  }

  $asteriskdir = array_key_exists ( "asteriskdir", $_in["general"]) ? rtrim ( $_in["general"]["asteriskdir"], "/") . "/" : "/etc/asterisk/";

  $includes = array (
    "extensions.conf" => "#include voipdomain/bootstrap/dialplan.conf",
    "pjsip.conf" => "#include voipdomain/bootstrap/pjsip.conf",
    "pjsip_notify.conf" => "#include voipdomain/bootstrap/pjsip_notify.conf",
    "queues.conf" => "#include voipdomain/bootstrap/queues.conf",
    "confbridge.conf" => "#include voipdomain/bootstrap/confbridge.conf",
    "voicemail.conf" => "#include voipdomain/bootstrap/voicemail.conf",
    "musiconhold.conf" => "#include voipdomain/bootstrap/musiconhold.conf",
    "manager.conf" => "#include voipdomain/bootstrap/manager.conf",
    "indications.conf" => "#include voipdomain/bootstrap/indications.conf",
    "cdr.conf" => "#include voipdomain/bootstrap/cdr.conf",
    "cdr_manager.conf" => "#include voipdomain/bootstrap/cdr_manager.conf",
    "cdr_sqlite3_custom.conf" => "#include voipdomain/bootstrap/cdr_sqlite3_custom.conf",
    "cel.conf" => "#include voipdomain/bootstrap/cel.conf"
  );

  foreach ( $includes as $file => $line)
  {
    $path = $asteriskdir . $file;
    if ( ! is_file ( $path))
    {
      if ( @file_put_contents ( $path, $line . "\n") !== false)
      {
        writeLog ( "Created " . $path . " with bootstrap include.");
      } else {
        writeLog ( "Cannot create " . $path . " (permission denied). Run configure-asterisk.sh as root.", VoIP_LOG_WARNING);
      }
      continue;
    }
    $content = @file_get_contents ( $path);
    if ( $content !== false && strpos ( $content, $line) === false)
    {
      if ( is_writable ( $path))
      {
        if ( @file_put_contents ( $path, $content . "\n" . $line . "\n") !== false)
        {
          writeLog ( "Added bootstrap include to " . $path);
        }
      } else {
        writeLog ( "Missing include in " . $path . " but file is not writable. Run configure-asterisk.sh as root.", VoIP_LOG_WARNING);
      }
    }
  }
}

/**
 * Ensure all required Asterisk modules are loaded. Attempts to load any
 * missing modules via CLI. This guarantees that CDR, CEL, and other
 * subsystems are active after a rebuild.
 */
function bootstrap_ensure_modules ()
{
  $required = array (
    "cdr_manager.so",
    "cdr_sqlite3_custom.so",
    "cel_manager.so",
    "res_pjsip.so",
    "app_queue.so",
    "app_confbridge.so",
    "app_voicemail.so"
  );

  foreach ( $required as $module)
  {
    $output = shell_exec ( "/sbin/asterisk -r -x \"module show like " . addslashes ( $module) . "\" 2>/dev/null");
    if ( $output === null || strpos ( $output, $module) === false)
    {
      shell_exec ( "/sbin/asterisk -r -x \"module load " . addslashes ( $module) . "\" 2>/dev/null");
      writeLog ( "Loaded Asterisk module: " . $module);
    }
  }
}

/**
 * Trigger: regenerate bootstrap on daemon startup to ensure configs are current.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_regenerate_on_startup ( $buffer, $parameters)
{
  bootstrap_ensure_structure ();
  bootstrap_ensure_modules ();
  asterisk_bootstrap_generate ( $parameters);
  return $buffer;
}

/**
 * Trigger: regenerate bootstrap when a plugin is installed or removed.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_regenerate_on_plugin ( $buffer, $parameters)
{
  asterisk_bootstrap_generate ( $parameters);
  return $buffer;
}

/**
 * PJSIP: transport definitions (UDP, TCP, WS, WSS).
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_pjsip_transports ( $buffer, $parameters)
{
  $buffer .= "[transport-udp]\n";
  $buffer .= "type=transport\n";
  $buffer .= "protocol=udp\n";
  $buffer .= "bind=0.0.0.0\n";
  $buffer .= "\n";
  $buffer .= "[transport-tcp]\n";
  $buffer .= "type=transport\n";
  $buffer .= "protocol=tcp\n";
  $buffer .= "bind=0.0.0.0\n";
  $buffer .= "\n";
  $buffer .= "[transport-ws]\n";
  $buffer .= "type=transport\n";
  $buffer .= "protocol=ws\n";
  $buffer .= "bind=0.0.0.0\n";
  $buffer .= "\n";
  $buffer .= "[transport-wss]\n";
  $buffer .= "type=transport\n";
  $buffer .= "protocol=wss\n";
  $buffer .= "bind=0.0.0.0\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * PJSIP: default endpoint template.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_pjsip_defaults ( $buffer, $parameters)
{
  $buffer .= "[vd_group_default](!)\n";
  $buffer .= "type=endpoint\n";
  $buffer .= "rtp_symmetric=no\n";
  $buffer .= "force_rport=no\n";
  $buffer .= "rewrite_contact=no\n";
  $buffer .= "dtmf_mode=rfc4733\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * PJSIP: glob includes for generated SIP fragments.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_pjsip_includes ( $buffer, $parameters)
{
  $buffer .= "#include voipdomain/configs/sip-model-*.conf\n";
  $buffer .= "#include voipdomain/configs/sip-server-*.conf\n";
  $buffer .= "#include voipdomain/configs/sip-profile-*.conf\n";
  $buffer .= "#include voipdomain/configs/sip-group-*.conf\n";
  $buffer .= "#include voipdomain/configs/sip-extension-*.conf\n";
  $buffer .= "#include voipdomain/configs/sip-account-*.conf\n";
  $buffer .= "#include voipdomain/configs/sip-gateway-*.conf\n";
  return $buffer;
}

/**
 * PJSIP NOTIFY: vendor-specific SIP NOTIFY definitions.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_pjsip_notify ( $buffer, $parameters)
{
  $buffer .= ";;\n;; Cisco\n;;\n";
  $buffer .= "[cisco-restart]\n";
  $buffer .= "Event => service-control\n";
  $buffer .= "Subscription-State => active\n";
  $buffer .= "Content-Type => text/plain\n";
  $buffer .= "Content => action=restart\n";
  $buffer .= "Content => RegisterCallId={\${SIPPEER(\${PEERNAME},regcallid)}}\n";
  $buffer .= "Content => ConfigVersionStamp={00000000-0000-0000-0000-000000000000}\n";
  $buffer .= "Content => DialplanVersionStamp={00000000-0000-0000-0000-000000000000}\n";
  $buffer .= "Content => SoftkeyVersionStamp={00000000-0000-0000-0000-000000000000}\n";
  $buffer .= "Content => FeatureControlVersionStamp={00000000-0000-0000-0000-000000000000}\n";
  $buffer .= "Content => HeadsetVersionStamp={0-0000000000}\n";
  $buffer .= "\n";
  $buffer .= "[cisco-reset]\n";
  $buffer .= "Event => service-control\n";
  $buffer .= "Subscription-State => active\n";
  $buffer .= "Content-Type => text/plain\n";
  $buffer .= "Content => action=reset\n";
  $buffer .= "Content => RegisterCallId={\${SIPPEER(\${PEERNAME},regcallid)}}\n";
  $buffer .= "Content => ConfigVersionStamp={00000000-0000-0000-0000-000000000000}\n";
  $buffer .= "Content => DialplanVersionStamp={00000000-0000-0000-0000-000000000000}\n";
  $buffer .= "Content => SoftkeyVersionStamp={00000000-0000-0000-0000-000000000000}\n";
  $buffer .= "Content => FeatureControlVersionStamp={00000000-0000-0000-0000-000000000000}\n";
  $buffer .= "Content => HeadsetVersionStamp={0-0000000000}\n";
  $buffer .= "\n";
  $buffer .= "[cisco-prt-report]\n";
  $buffer .= "Event => service-control\n";
  $buffer .= "Subscription-State => active\n";
  $buffer .= "Content-Type => text/plain\n";
  $buffer .= "Content => action=prt-report\n";
  $buffer .= "Content => RegisterCallId={\${SIPPEER(\${PEERNAME},regcallid)}}\n";
  $buffer .= "\n";
  $buffer .= ";;\n;; Yealink\n;;\n";
  $buffer .= "[yealink-check-cfg]\n";
  $buffer .= "Event=>check-sync\\;reboot=false\n";
  $buffer .= "\n";
  $buffer .= "[yealink-reboot]\n";
  $buffer .= "Event=>check-sync\\;reboot=true\n";
  $buffer .= "\n";
  $buffer .= "[yealink-dndon]\n";
  $buffer .= "Event=>ACTION-URI\n";
  $buffer .= "Content-Type=>message/sipfrag\n";
  $buffer .= "\n";
  $buffer .= "[yealink-dndoff]\n";
  $buffer .= "Event=>ACTION-URI\n";
  $buffer .= "Content-Type=>message/sipfrag\n";
  $buffer .= "Content=>key=DNDOff\n";
  $buffer .= "\n";
  $buffer .= ";;\n;; KHOMP\n;;\n";
  $buffer .= "[khomp-check-cfg]\n";
  $buffer .= "Event=>check-sync\\;reboot=false\n";
  $buffer .= "\n";
  $buffer .= "[khomp-reboot]\n";
  $buffer .= "Event=>check-sync\\;reboot=true\n";
  return $buffer;
}

/**
 * Queues: glob include for generated queue configs.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_queues_includes ( $buffer, $parameters)
{
  $buffer .= "#include voipdomain/configs/queue-*.conf\n";
  return $buffer;
}

/**
 * ConfBridge: glob include for generated confbridge configs.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_confbridge_includes ( $buffer, $parameters)
{
  $buffer .= "#include voipdomain/configs/confbridge-*.conf\n";
  return $buffer;
}

/**
 * Voicemail: glob include for generated voicemail configs.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_voicemail_includes ( $buffer, $parameters)
{
  $buffer .= "#include voipdomain/configs/voicemail-*.conf\n";
  return $buffer;
}

/**
 * Music on Hold: glob include for generated MOH configs.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_moh_includes ( $buffer, $parameters)
{
  $buffer .= "#include voipdomain/configs/moh-*.conf\n";
  return $buffer;
}

/**
 * Manager (AMI): glob include for generated manager user configs.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_manager_includes ( $buffer, $parameters)
{
  $buffer .= "#include voipdomain/configs/manager-*.conf\n";
  return $buffer;
}

/**
 * Indications: include for country-specific tone indications.
 *
 * @param string $buffer Buffer from plugin system
 * @param array $parameters Optional parameters
 * @return string
 */
function bootstrap_indications_includes ( $buffer, $parameters)
{
  $buffer .= "#include voipdomain/configs/indications-voipdomain.conf\n";
  return $buffer;
}
?>
