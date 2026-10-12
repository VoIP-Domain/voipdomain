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
 * VoIP Domain dialplan bootstrap hook registration (client daemon).
 *
 * Registers hook functions for each dialplan section so the bootstrap generator
 * produces a complete dialplan.conf file. Each function appends its content to
 * the pipeline buffer. Plugins can register additional hooks (or use
 * framework_add_hook_at to insert before/after) to modify any section.
 *
 * Hook naming convention: asterisk_dialplan_{section}
 * Sections defined in bootstrap.inc.php: globals, database, extensions, hints,
 * exceptions, blocks, ranges, ivr, services, routing, internal, tools,
 * handlers, extra
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Dialplan
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Register core dialplan hooks (ordered by section)
 */
framework_add_hook ( "asterisk_dialplan_globals", "dialplan_hook_globals");
framework_add_hook ( "asterisk_dialplan_database", "dialplan_hook_database");
framework_add_hook ( "asterisk_dialplan_extensions", "dialplan_hook_extensions");
framework_add_hook ( "asterisk_dialplan_hints", "dialplan_hook_hints");
framework_add_hook ( "asterisk_dialplan_exceptions", "dialplan_hook_exceptions");
framework_add_hook ( "asterisk_dialplan_blocks", "dialplan_hook_blocks");
framework_add_hook ( "asterisk_dialplan_ranges", "dialplan_hook_ranges");
framework_add_hook ( "asterisk_dialplan_ivr", "dialplan_hook_ivr");
framework_add_hook ( "asterisk_dialplan_services", "dialplan_hook_services");
framework_add_hook ( "asterisk_dialplan_routing", "dialplan_hook_routing");
framework_add_hook ( "asterisk_dialplan_tools", "dialplan_hook_tools");
framework_add_hook ( "asterisk_dialplan_handlers", "dialplan_hook_handlers");
framework_add_hook ( "asterisk_dialplan_extra", "dialplan_hook_extra");

/**
 * Internal context hook — defines [VoIPDomain-Internal] and includes Extensions
 */
framework_add_hook ( "asterisk_dialplan_internal", "dialplan_hook_internal");

/**
 * Dialplan section: globals and generated config includes.
 */
function dialplan_hook_globals ( $buffer, $parameters)
{
  $buffer .= ";;\n;; VoIP Domain global configurations and generated dialplan fragments\n;;\n";
  $buffer .= "#include voipdomain/configs/dialplan-globals.conf\n";
  $buffer .= "#include voipdomain/configs/dialplan-configs-*.conf\n";
  $buffer .= "\n";
  $buffer .= ";;\n;; System profiles\n;;\n";
  $buffer .= "#include voipdomain/configs/dialplan-profile-*.conf\n";
  $buffer .= "\n";
  $buffer .= ";;\n;; Gateways inbound contexts\n;;\n";
  $buffer .= "#include voipdomain/configs/dialplan-gateway-*.conf\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: local server Asterisk databases.
 */
function dialplan_hook_database ( $buffer, $parameters)
{
  $buffer .= ";;\n;; Local server Asterisk databases\n;;\n";
  $buffer .= "[VoIPDomain-Database]\n";
  $buffer .= "#include voipdomain/configs/dialplan-db-*.conf\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: local server extensions.
 */
function dialplan_hook_extensions ( $buffer, $parameters)
{
  $buffer .= ";;\n;; Local server extensions\n;;\n";
  $buffer .= "[VoIPDomain-Extensions]\n";
  $buffer .= "#include voipdomain/configs/dialplan-extension-*.conf\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: local server extension hints.
 */
function dialplan_hook_hints ( $buffer, $parameters)
{
  $buffer .= ";;\n;; Local server extension hints\n;;\n";
  $buffer .= "[VoIPDomain-Hints]\n";
  $buffer .= "#include voipdomain/configs/dialplan-hint-*.conf\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: global exceptions.
 */
function dialplan_hook_exceptions ( $buffer, $parameters)
{
  $buffer .= ";;\n;; Global exceptions\n;;\n";
  $buffer .= "[VoIPDomain-Exceptions]\n";
  $buffer .= "#include voipdomain/configs/dialplan-exception-*.conf\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: global blocks.
 */
function dialplan_hook_blocks ( $buffer, $parameters)
{
  $buffer .= ";;\n;; Global blocks\n;;\n";
  $buffer .= "[VoIPDomain-blocks]\n";
  $buffer .= "#include voipdomain/configs/dialplan-block-*.conf\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: server ranges.
 */
function dialplan_hook_ranges ( $buffer, $parameters)
{
  $buffer .= ";;\n;; Server ranges\n;;\n";
  $buffer .= "[VoIPDomain-ranges]\n";
  $buffer .= "exten => _X.,1,NoOp(VoIP Domain range lookup for \${ARG1})\n";
  $buffer .= "#include voipdomain/configs/dialplan-range-*.conf\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: global IVRs.
 */
function dialplan_hook_ivr ( $buffer, $parameters)
{
  $buffer .= ";;\n;; Global IVRs\n;;\n";
  $buffer .= "#include voipdomain/configs/dialplan-ivr-*.conf\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: services (pickup, CDR on hangup).
 */
function dialplan_hook_services ( $buffer, $parameters)
{
  $buffer .= ";;\n;; VoIP Domain services (pickup, CDR on hangup)\n;;\n";
  $buffer .= "[VoIPDomain-Services]\n";
  $buffer .= "; Direct extension pickup (used on BLF phones)\n";
  $buffer .= "exten => _*8.,1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: VoIPDomain functions - Direct extension pickup for \${EXTEN:2} from \${CALLERID(num)})\n";
  $buffer .= " same => n,ExecIf(\$[\"\${DIALPLAN_EXISTS(VoIPDomain-Database,extension_\${EXTEN:2})}\" != \"1\"]?HangUp())\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Database,extension_\${EXTEN:2},1(_,dialstring))\n";
  $buffer .= " same => n,PickupChan(\${vd_dialstring})\n";
  $buffer .= " same => n,HangUp()\n";
  $buffer .= "\n";
  $buffer .= "exten => h,1,Set(CDR(server)=\${SERVER})\n";
  $buffer .= " same => n,Set(CDR(sourcetype)=\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_EXTENSION}])\n";
  $buffer .= " same => n,Set(CDR(calltype)=\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SERVICES}])\n";
  $buffer .= " same => n,Set(CDR(nativecodec)=\${CHANNEL(audionativeformat)})\n";
  $buffer .= " same => n,Set(CDR(readcodec)=\${CHANNEL(audioreadformat)})\n";
  $buffer .= " same => n,Set(CDR(writecodec)=\${CHANNEL(audiowriteformat)})\n";
  $buffer .= " same => n,ExecIf(\$[\"\${CHANNEL(channeltype)}\" = \"PJSIP\"]?Set(CDR(QOS)=\${CHANNEL(rtcp,all_jitter)}\${CHANNEL(rtcp,all_loss)}\${CHANNEL(rtcp,all_rtt)}\${CHANNEL(rtcp,all)}))\n";
  $buffer .= " same => n,ExecIf(\$[\"\${CHANNEL(hangupsource)}\" = \"\${CHANNEL(name)}\"]?Set(CDR(WhoHungUp)=Caller):Set(CDR(WhoHungUp)=Called))\n";
  $buffer .= " same => n,Set(CDR(SIPID)=\${CHANNEL(pjsip,call-id)})\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: call routing contexts.
 */
function dialplan_hook_routing ( $buffer, $parameters)
{
  $buffer .= ";;\n;; VoIP Domain call routing contexts\n;;\n";
  $buffer .= "[VoIPDomain-Transhipment]\n";
  $buffer .= "include => VoIPDomain-Hints\n";
  $buffer .= "\n";
  $buffer .= "exten => _Z.,1,NoCDR()\n";
  $buffer .= " same => n,ExecIf(\${DB_EXISTS(denylist-\${EXTEN}/\${CALLERID(num)})}?HangUp())\n";
  $buffer .= " same => n,ExecIf(\$[\"\${DIALPLAN_EXISTS(VoIPDomain-Database,extension_\${EXTEN})}\" != \"1\"]?HangUp())\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Database,extension_\${EXTEN},1)\n";
  $buffer .= " same => n,Wait(\${TRANSTIME})\n";
  $buffer .= " same => n,Dial(\${dialstring},\${MATH(\${DIALTIME}-\${TRANSTIME},int)},tTrcU(handler-VoIPDomain-pre-bridge))\n";
  $buffer .= " same => n,HangUp()\n";
  $buffer .= "\n";
  $buffer .= "[VoIPDomain-Direct]\n";
  $buffer .= "include => VoIPDomain-Hints\n";
  $buffer .= "\n";
  $buffer .= "exten => _Z.,1,NoCDR()\n";
  $buffer .= " same => n,ExecIf(\${DB_EXISTS(denylist-\${EXTEN}/\${CALLERID(num)})}?HangUp())\n";
  $buffer .= " same => n,ExecIf(\$[\"\${DIALPLAN_EXISTS(VoIPDomain-Database,extension_\${EXTEN})}\" != \"1\"]?HangUp())\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Database,extension_\${EXTEN},1)\n";
  $buffer .= " same => n,Dial(\${vd_dialstring},\${MATH(\${DIALTIME}-\${TRANSTIME},int)},tTrcU(handler-VoIPDomain-pre-bridge))\n";
  $buffer .= " same => n,HangUp()\n";
  $buffer .= "\n";
  $buffer .= ";;\n;; Inbound from gateways\n;;\n";
  $buffer .= "[VoIPDomain-public]\n";
  $buffer .= "exten => _X.,1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: VoIPDomain - Inbound public call to \${EXTEN} from \${CALLERID(num)})\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_extension,1(\${EXTEN}))\n";
  $buffer .= " same => n,HangUp()\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: tools (utilities, call handling, CDR, billing).
 */
function dialplan_hook_tools ( $buffer, $parameters)
{
  $buffer .= ";;\n;; VoIP Domain dialplan tools\n;;\n";
  $buffer .= "[VoIPDomain-Tools]\n";
  $buffer .= "\n";

  // Core utilities
  $buffer .= "exten => counter,1,ExecIf(\$[\"\${ARG1}\" = \"\"]?Return())\n";
  $buffer .= " same => n,Set(vd_entries=\${DB(VoIPDomain-counter/\${ARG1})})\n";
  $buffer .= " same => n,ExecIf(\$[\"\${ARG2}\" = \"\"]?Set(PUSH(DB(VoIPDomain-counter/\${ARG1}))=\${EPOCH}))\n";
  $buffer .= " same => n,ExecIf(\$[\"\${ARG2}\" = \"\"]?Return())\n";
  $buffer .= " same => n,Set(vd_newentries=)\n";
  $buffer .= " same => n,While(\$[\"\${SET(vd_var=\${SHIFT(vd_entries)})}\" != \"\"])\n";
  $buffer .= " same => n,ExecIf(\$[\${vd_var} > \$[\${EPOCH} - \${ARG2}]]?Set(PUSH(vd_newentries)=\${vd_var}))\n";
  $buffer .= " same => n,EndWhile\n";
  $buffer .= " same => n,GoToIf(\$[\"\${ARG3}\" != \"\"]?dontclear:clear)\n";
  $buffer .= " same => n(dontclear),Set(PUSH(DB(VoIPDomain-counter/\${ARG1}))=\${EPOCH})\n";
  $buffer .= " same => n,Return(\${FIELDQTY(vd_newentries,\\,)})\n";
  $buffer .= " same => n(clear),Set(PUSH(vd_newentries)=\${EPOCH})\n";
  $buffer .= " same => n,Set(DB(VoIPDomain-counter/\${ARG1})=\${vd_newentries})\n";
  $buffer .= " same => n,Return(\$[\${FIELDQTY(vd_newentries,\\,)} - 1])\n";
  $buffer .= "\n";

  $buffer .= "exten => announce,1,ExecIf(\$[\"\${DB_EXISTS(CIDA/\${CALLERID(num)})}\" = \"0\"]?Return())\n";
  $buffer .= " same => n,ExecIf(\$[\$[\"\${ARG2}\" != \"\"] -a \$[\${LEN(\${ARG1})} >= \${ARG2}]]?Return())\n";
  $buffer .= " same => n,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: Announcing \${ARG1} to \${CALLERID(num)})\n";
  $buffer .= " same => n,Wait(.5)\n";
  $buffer .= " same => n,SayDigits(\${ARG1})\n";
  $buffer .= " same => n,PlayTones(1000/250)\n";
  $buffer .= " same => n,Wait(.25)\n";
  $buffer .= " same => n,StopPlaytones()\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";

  $buffer .= "exten => get_digits,1,Set(\${ARG1}=)\n";
  $buffer .= " same => n,Set(lastdigit=\${ARG2})\n";
  $buffer .= " same => n,Set(size=\${ARG3})\n";
  $buffer .= " same => n,Set(timeout=\${ARG4})\n";
  $buffer .= " same => n,Set(ignorehash=\$[\"\${ARG5}\" = \"yes\"])\n";
  $buffer .= " same => n,Set(saydigit=\$[\$[\"\${ARG6}\" = \"\"] | \$[\"\${ARG6}\" = \"yes\"]])\n";
  $buffer .= " same => n,ExecIf(\$[\$[\${size} = 0] & \$[\${ignorehash} = 1]]?Set(ignorehash=0))\n";
  $buffer .= " same => n,NoOp(Reading \${size} digits to \$\${ARG1})\n";
  $buffer .= " same => n,Set(digits=)\n";
  $buffer .= " same => n(digit),Read(digit,\${lastdigit},1,s,1,\${timeout})\n";
  $buffer .= " same => n,ExecIf(\$[\"\${ARG7}\" != \"\"]?Set(\${ARG7}=\${READSTATUS}))\n";
  $buffer .= " same => n,GoToIf(\$[\"\${READSTATUS}\" != \"OK\"]?return)\n";
  $buffer .= " same => n,ExecIf(\$[\$[\"\${digit}\" != \"\"] & \$[\"\${digit}\" != \"*\"]]?Set(digits=\${digits}\${digit}))\n";
  $buffer .= " same => n,GoToIf(\$[\$[\"\${digit}\" = \"\"] & \$[\${ignorehash} = 0]]?return)\n";
  $buffer .= " same => n,GoToIf(\$[\$[\"\${size}\" != \"0\"] & \$[\${LEN(\${digits})} = \${size}]]?sayandreturn)\n";
  $buffer .= " same => n,ExecIf(\$[\$[\${saydigit}] & \$[\"\${digit}\" != \"\"] & \$[\"\${digit}\" != \"*\"]]?Set(lastdigit=digits/\${digit}):Set(lastdigit=))\n";
  $buffer .= " same => n,GoTo(digit)\n";
  $buffer .= " same => n(sayandreturn),ExecIf(\$[\$[\${saydigit}] & \$[\"\${digit}\" != \"\"] & \$[\"\${digit}\" != \"*\"]]?PlayBack(digits/\${digit}))\n";
  $buffer .= " same => n(return),Set(\${ARG1}=\${digits})\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";

  $buffer .= "exten => blocked,1,GoSub(VoIPDomain-Tools,addflag,1(blocked))\n";
  $buffer .= " same => n,Ringing()\n";
  $buffer .= " same => n,Wait(\${DIALTIME})\n";
  $buffer .= " same => n,ExecIf(\$[\${VM_INFO(\${CALLERID(num)},exists)}]?Voicemail(\${CALLERID(num)},u))\n";
  $buffer .= " same => n,HangUp()\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";

  $buffer .= "exten => set_gw_cid,1,NoOp(Setting gateway CallerID for GW \${ARG1})\n";
  $buffer .= " same => n,ExecIf(\$[\"\${DB_EXISTS(gw_cid/\${ARG1}/mode)}\" != \"1\"]?Return())\n";
  $buffer .= " same => n,Set(gw_cid_mode=\${DB(gw_cid/\${ARG1}/mode)})\n";
  $buffer .= " same => n,ExecIf(\$[\"\${gw_cid_mode}\" = \"fixed\"]?Set(CALLERID(num)=\${DB(gw_cid/\${ARG1}/number)}))\n";
  $buffer .= " same => n,ExecIf(\$[\"\${gw_cid_mode}\" = \"extension\"]?Set(CALLERID(num)=\${DB(gw_cid/\${ARG1}/prefix)}\${CALLERID(num)}\${DB(gw_cid/\${ARG1}/suffix)}))\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";

  $buffer .= "exten => addflag,1,ExecIf(\$[\"\${MASTER_CHANNEL(CDR(flags))}\" != \"\"]?Set(MASTER_CHANNEL(CDR(flags))=\${MASTER_CHANNEL(CDR(flags))}|\${ARG1}):Set(MASTER_CHANNEL(CDR(flags))=\${ARG1}))\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";

  // Call handling
  $buffer .= "exten => call_extension,1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: VoIPDomain functions - Call to extension \${ARG1} from \${CALLERID(num)})\n";
  $buffer .= " same => n,GoToIf(\$[\"\${DIALPLAN_EXISTS(VoIPDomain-Database,extension_\${ARG1})}\" != \"1\"]?novars)\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Database,extension_\${ARG1},1)\n";
  $buffer .= " same => n,Set(CDR(calltype)=\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_EXTENSION}])\n";
  $buffer .= " same => n,Set(CDR(gateway)=)\n";
  $buffer .= " same => n,Set(CDR(value)=0)\n";
  $buffer .= " same => n,Set(CDR(processed)=1)\n";
  $buffer .= " same => n,Set(CHANNEL(hangup_handler_push)=VoIPDomain-Tools,set_cdr,1)\n";
  $buffer .= " same => n,ExecIf(\$[\"\${PJSIP_HEADERS(read,X-No-CDR)}\" != \"\"]?NoCDR())\n";
  $buffer .= " same => n,ExecIf(\$[\"\${vd_dialstring}\" = \"\"]?Congestion())\n";
  $buffer .= " same => n,GoToIf(\${DB_EXISTS(denylist-\${ARG1}/\${CALLERID(num)})}?denylist)\n";
  $buffer .= " same => n,GoSubIf(\$[\"\${vd_transfer}\" != \"\"]?transfer)\n";
  $buffer .= " same => n,Set(DB(lastcallerid/\${ARG1})=\${CALLERID(num)})\n";
  $buffer .= " same => n,ExecIf(\$[\${LEN(\${CALLERID(num)})} >= 6]?Set(infotype=external):Set(infotype=internal))\n";
  $buffer .= " same => n,Dial(\${vd_dialstring},\${DIALTIME},etrU(handler-VoIPDomain-pre-bridge)b(VoIPDomain-Tools^call_extension^addheader_\${infotype}))\n";
  $buffer .= " same => n,GoToIf(\$[\${DB_EXISTS(CFBS/\${ARG1})} & \$[\"\${DIALSTATUS}\" != \"CANCEL\"]]?followme)\n";
  $buffer .= " same => n,GoTo(end)\n";
  $buffer .= "\n";
  $buffer .= " same => n(denylist),Set(CDR(userfield)=denylist)\n";
  $buffer .= " same => n,Ringing()\n";
  $buffer .= " same => n,Wait(\${DIALTIME})\n";
  $buffer .= " same => n,ExecIf(\$[\${VM_INFO(\${ARG1},exists)}]?Voicemail(\${ARG1},u))\n";
  $buffer .= " same => n,HangUp()\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";
  $buffer .= " same => n(transfer),ChanIsAvail(\${vd_dialstring},as)\n";
  $buffer .= " same => n,ExecIf(\$[\"\${REGEX(\"(^|&)1(\$|&)\" \${AVAILSTATUS})}\" = \"1\"]?Set(context=Transhipment):Set(context=Direct))\n";
  $buffer .= " same => n(cut),ExecIf(\$[\"\${FIELDQTY(vd_transfer,\\,)}\" = \"0\"]?Return())\n";
  $buffer .= " same => n,Set(vd_dialstring=\${vd_dialstring}&LOCAL/\${CUT(vd_transfer,\\,,1)}@VoIPDomain-Transhipment)\n";
  $buffer .= " same => n,Set(vd_transfer=\${CUT(vd_transfer,\\,,2-)})\n";
  $buffer .= " same => n,GoTo(cut)\n";
  $buffer .= "\n";
  $buffer .= " same => n(novars),Verbose(1,                          `--> Failed, no extension variables found!)\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";
  $buffer .= " same => n(end),ExecIf(\$[\$[\"\${DIALSTATUS}\" = \"CANCEL\"] & \$[\${VM_INFO(\${ARG1},exists)}]]?HangUp())\n";
  $buffer .= " same => n,ExecIf(\$[\$[\"\${DIALSTATUS}\" = \"BUSY\"] & \$[\${VM_INFO(\${ARG1},exists)}]]?Voicemail(\${ARG1},b))\n";
  $buffer .= " same => n,ExecIf(\$[\$[\"\${DIALSTATUS}\" = \"NOANSWER\"] & \$[\${VM_INFO(\${ARG1},exists)}]]?Voicemail(\${ARG1},u))\n";
  $buffer .= " same => n,ExecIf(\$[\$[\"\${DIALSTATUS}\" = \"CHANUNAVAIL\"] & \$[\${VM_INFO(\${ARG1},exists)}]]?Voicemail(\${ARG1},u))\n";
  $buffer .= " same => n,HangUp()\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";
  $buffer .= " same => n(addheader_external),Set(PJSIP_HEADER(add,Alert-Info)=<http://localhost/>\\;info=external)\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";
  $buffer .= " same => n(addheader_internal),Set(PJSIP_HEADER(add,Alert-Info)=<http://localhost/>\\;info=internal)\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";

  // Blind / attended transfer helper
  $buffer .= "exten => call_transfer,1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: VoIPDomain functions - Transfer to \${ARG1} from \${ARG2})\n";
  $buffer .= " same => n,ExecIf(\$[\"\${ARG3}\" != \"\"]?Dial(\${ARG3},\${DIALTIME},etrU(handler-VoIPDomain-pre-bridge)):GoSub(VoIPDomain-Tools,call_extension,1(\${ARG1})))\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";

  // Emergency outbound
  $buffer .= "exten => call_emergency,1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: VoIPDomain functions - Emergency call to \${ARG1})\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${ARG1},\${ARG2}))\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";

  // External call routing with LCR
  $buffer .= "exten => call_external,1,Set(CDR(server)=\${SERVER})\n";
  $buffer .= " same => n,Set(CDR(calltype)=\${ARG2})\n";
  $buffer .= " same => n,GoToIf(\$[\"\${DIALPLAN_EXISTS(VoIPDomain-Database,extension_\${CALLERID(num)})}\" != \"1\"]?noroute)\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Database,extension_\${CALLERID(num)},1)\n";
  $buffer .= " same => n,GoToIf(\$[\"\${DIALPLAN_EXISTS(VoIPDomain-Database,group_\${vd_group})}\" != \"1\"]?noroute)\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Database,group_\${vd_group},1(_,profile))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Database,group_\${vd_group},1(_default,costcenter))\n";
  $buffer .= " same => n,ExecIf(\$[\"\${vd_costcenter}\" = \"\"]?Set(vd_costcenter=\${default_vd_costcenter}))\n";
  $buffer .= " same => n,GoToIf(\$[\"\${DIALPLAN_EXISTS(VoIPDomain-Database,profile_\${vd_profile})}\" != \"1\"]?noroute)\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Database,profile_\${vd_profile},1)\n";
  $buffer .= " same => n,GoToIf(\$[\"\${ARG1:0:1}\" = \"+\"]?geographic:nongeographic)\n";
  $buffer .= " same => n(nongeographic),Set(CDR(gateway)=\${vd_nggw})\n";
  $buffer .= " same => n,GoToIf(\$[\"\${vd_nggw}\" = \"\"]?noroute)\n";
  $buffer .= " same => n,Set(permission=)\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SERVICES}]]?Set(permission=\${vd_p_landline_local}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SPECIAL}]]?Set(permission=\${vd_p_prn_local}))\n";
  $buffer .= " same => n,GoToIf(\$[\"\${permission}\" = \"\"]?noroute)\n";
  $buffer .= " same => n,GoToIf(\$[\"\${permission}\" = \"y\"]?dialnggw)\n";
  $buffer .= " same => n,GoSub(getpass)\n";
  $buffer .= " same => n,GoTo(dialnggw)\n";
  $buffer .= "\n";
  $buffer .= " same => n(dialnggw),Set(CHANNEL(hangup_handler_push)=VoIPDomain-Tools,set_cdr,1)\n";
  $buffer .= " same => n,Set(DB(lastcalled/\${CALLERID(num)})=\${ARG1})\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,set_gw_cid,1(\${vd_nggw}))\n";
  $buffer .= " same => n,Dial(PJSIP/vd_gateway_\${vd_nggw}/\${ARG1},\${DIALTIME},TrCU(handler-VoIPDomain-pre-bridge))\n";
  $buffer .= " same => n,GoTo(end)\n";
  $buffer .= "\n";
  $buffer .= " same => n(geographic),GoToIf(\$[\"\${vd_defaultgw}\" = \"\"]?noroute)\n";
  $buffer .= " same => n,GoToIf(\$[\"\${DIALPLAN_EXISTS(VoIPDomain-Exceptions,\${ARG1})}\" = \"1\"]?dialgeographic)\n";
  $buffer .= " same => n,Set(permission=)\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_LANDLINE}]]?Set(permission=\${vd_p_landline_local}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_MOBILE}]]?Set(permission=\${vd_p_mobile_local}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_MARINE}]]?Set(permission=\${vd_p_marine_local}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_TOLLFREE}]]?Set(permission=\${vd_p_tollfree_local}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SPECIAL}]]?Set(permission=\${vd_p_prn_local}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SATELLITE}]]?Set(permission=\${vd_p_satellite_local}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERSTATE} + \${VD_CALLENDPOINT_LANDLINE}]]?Set(permission=\${vd_p_landline_interstate}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERSTATE} + \${VD_CALLENDPOINT_MOBILE}]]?Set(permission=\${vd_p_mobile_interstate}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERSTATE} + \${VD_CALLENDPOINT_MARINE}]]?Set(permission=\${vd_p_marine_interstate}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERNATIONAL} + \${VD_CALLENDPOINT_LANDLINE}]]?Set(permission=\${vd_p_landline_international}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERNATIONAL} + \${VD_CALLENDPOINT_MOBILE}]]?Set(permission=\${vd_p_mobile_international}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERNATIONAL} + \${VD_CALLENDPOINT_MARINE}]]?Set(permission=\${vd_p_marine_international}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERNATIONAL} + \${VD_CALLENDPOINT_TOLLFREE}]]?Set(permission=\${vd_p_tollfree_international}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERNATIONAL} + \${VD_CALLENDPOINT_SPECIAL}]]?Set(permission=\${vd_p_prn_international}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERNATIONAL} + \${VD_CALLENDPOINT_SATELLITE}]]?Set(permission=\${vd_p_satellite_international}))\n";
  $buffer .= " same => n,ExecIf(\$[\${ARG2} = \$[\${VD_CALLTYPE_INTERNATIONAL} + \${VD_CALLENDPOINT_UNKNOWN}]]?Set(permission=\${vd_p_mobile_international}))\n";
  $buffer .= " same => n,GoToIf(\$[\"\${permission}\" = \"\"]?noroute)\n";
  $buffer .= " same => n,GoToIf(\$[\"\${permission}\" = \"y\"]?dialgeographic)\n";
  $buffer .= " same => n,GoSub(getpass)\n";
  $buffer .= " same => n,GoTo(dialgeographic)\n";
  $buffer .= "\n";
  $buffer .= " same => n(dialgeographic),Set(CHANNEL(hangup_handler_push)=VoIPDomain-Tools,set_cdr,1)\n";
  $buffer .= " same => n,Set(DB(lastcalled/\${CALLERID(num)})=\${ARG1})\n";
  $buffer .= " same => n,AGI(agi://\${AGIADDR}/lcr,\${ARG1},\"\${vd_defaultgw}\",\"\${vd_blockedgw}\")\n";
  $buffer .= " same => n,Set(gw=0)\n";
  $buffer .= " same => n,Set(PJSIP_HEADER(add,X-Orig-URI)=\${CALLERID(num)}@\${SERVERADDR})\n";
  $buffer .= " same => n(nextgw),Set(gw=\$[\${gw} + 1])\n";
  $buffer .= " same => n,GoToIf(\$[\"\${r_\${gw}_gw}\" = \"\"]?fail)\n";
  $buffer .= " same => n,Set(c_gateway=\${r_\${gw}_gw})\n";
  $buffer .= " same => n,Set(c_discard=\${r_\${gw}_td})\n";
  $buffer .= " same => n,Set(c_minimum=\${r_\${gw}_tm})\n";
  $buffer .= " same => n,Set(c_fraction=\${r_\${gw}_tf})\n";
  $buffer .= " same => n,Set(c_cost=\${r_\${gw}_cm})\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,set_gw_cid,1(\${c_gateway}))\n";
  $buffer .= " same => n,Dial(PJSIP/\${r_\${gw}_dial}@vd_gateway_\${c_gateway},\${DIALTIME},etrU(handler-VoIPDomain-pre-bridge))\n";
  $buffer .= " same => n,GoToIf(\$[\"\${DIALSTATUS}\" = \"ANSWER\"]?end)\n";
  $buffer .= " same => n,GoToIf(\$[\"\${DIALSTATUS}\" = \"CHANUNAVAIL\"]?nextgw)\n";
  $buffer .= " same => n,GoToIf(\$[\"\${DIALSTATUS}\" = \"CONGESTION\"]?nextgw)\n";
  $buffer .= " same => n,GoTo(fail)\n";
  $buffer .= "\n";
  $buffer .= " same => n(fail),Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: Call failed from \${CALLERID(num)} to \${ARG1}: \${DIALSTATUS})\n";
  $buffer .= " same => n,GoTo(end)\n";
  $buffer .= "\n";
  $buffer .= " same => n(getpass),Ringing()\n";
  $buffer .= " same => n,Wait(2)\n";
  $buffer .= " same => n,Answer()\n";
  $buffer .= " same => n,Set(tries=0)\n";
  $buffer .= " same => n(nexttry),Set(tries=\$[\${tries} + 1])\n";
  $buffer .= " same => n,ExecIf(\$[\${tries} = 1]?Set(audio=agent-pass):Set(audio=auth-incorrect))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,get_digits,1(password,\${audio},6,10,1,0))\n";
  $buffer .= " same => n,ExecIf(\$[\"\${password}\" = \"\${vd_password}\"]?Return())\n";
  $buffer .= " same => n,GoToIf(\$[\${tries} < 3]?nexttry)\n";
  $buffer .= " same => n,PlayBack(auth-thankyou)\n";
  $buffer .= " same => n,GoTo(end)\n";
  $buffer .= "\n";
  $buffer .= " same => n(noroute),Set(CDR(processed)=1)\n";
  $buffer .= " same => n,Set(CDR(value)=0)\n";
  $buffer .= " same => n,Congestion()\n";
  $buffer .= " same => n,GoTo(end)\n";
  $buffer .= "\n";
  $buffer .= " same => n(end),HangUp()\n";
  $buffer .= "\n";

  // CDR and billing
  $buffer .= "exten => call_cost,1,ExecIf(\$[\"\${e164}\" = \"\"]?Set(e164=\${MASTER_CHANNEL(CALLERID(dnid))}))\n";
  $buffer .= " same => n,NoOp(Checking call cost from \${CALLERID(num)} to \${e164})\n";
  $buffer .= " same => n,Set(CDR(processed)=1)\n";
  $buffer .= " same => n,GoToIf(\$[\"\${MATH(\${CDR(calltype)}AND\${VD_CALLENDPOINT_EXTENSION},int)}\" = \"\${VD_CALLENDPOINT_EXTENSION}\"]?cost-extension)\n";
  $buffer .= " same => n,Set(CDR(gateway)=\${c_gateway})\n";
  $buffer .= " same => n,GoToIf(\$[\"\${MATH(\${CDR(calltype)}AND\${VD_CALLENDPOINT_TOLLFREE},int)}\" = \"\${VD_CALLENDPOINT_TOLLFREE}\"]?cost-free)\n";
  $buffer .= " same => n,GoToIf(\$[\$[\"\${ANSWEREDTIME}\" = \"\"] | \$[\${ANSWEREDTIME} <= \${c_discard}]]?cost-free)\n";
  $buffer .= " same => n,ExecIf(\$[\${ANSWEREDTIME} <= \${c_minimum}]?Set(c_total=\${c_minimum}):Set(c_total=\$[CEIL(\${MATH(\${ANSWEREDTIME}/\${c_fraction})})*\${c_fraction}]))\n";
  $buffer .= " same => n,Set(CDR(value)=\${MATH(\${c_total}*\${MATH(\${c_cost}/60)})})\n";
  $buffer .= " same => n,GoTo(cost-end)\n";
  $buffer .= " same => n(cost-extension),Set(CDR(gateway)=0)\n";
  $buffer .= " same => n(cost-free),Set(CDR(value)=0.000000)\n";
  $buffer .= " same => n,GoTo(cost-end)\n";
  $buffer .= " same => n(cost-end),Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: Call cost from \${CALLERID(num)} to \${e164} (via gateway \${CDR(gateway)}): \${CDR(value)})\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";

  // Agent password check tool
  $buffer .= "exten => agents_password,1,AGI(agi://127.0.0.1:4573/agents_password,\${ARG1},\${ARG2})\n";
  $buffer .= " same => n,Return(\${AGENTCHECK})\n";

  // CDR hangup handler
  $buffer .= "exten => set_cdr,1,Set(CDR(server)=\${SERVER})\n";
  $buffer .= " same => n,Set(CDR(nativecodec)=\${CHANNEL(audionativeformat)})\n";
  $buffer .= " same => n,Set(CDR(readcodec)=\${CHANNEL(audioreadformat)})\n";
  $buffer .= " same => n,Set(CDR(writecodec)=\${CHANNEL(audiowriteformat)})\n";
  $buffer .= " same => n,ExecIf(\$[\$[\"\${CHANNEL(channeltype)}\" = \"PJSIP\"] & \$[\"\${DIALSTATUS}\" != \"\"]]?Set(CDR(QOS)=\${CHANNEL(rtcp,all_jitter)}\${CHANNEL(rtcp,all_loss)}\${CHANNEL(rtcp,all_rtt)}\${CHANNEL(rtcp,all)}))\n";
  $buffer .= " same => n,ExecIf(\$[\"\${CHANNEL(hangupsource)}\" = \"\${CHANNEL(name)}\"]?Set(CDR(WhoHungUp)=Caller):Set(CDR(WhoHungUp)=Called))\n";
  $buffer .= " same => n,Set(CDR(SIPID)=\${CHANNEL(pjsip,call-id)})\n";
  $buffer .= " same => n,Set(CDR(hangupcause)=\${HANGUPCAUSE})\n";
  $buffer .= " same => n,ExecIf(\$[\"\${DIALSTATUS}\" != \"\"]?Set(CDR(dialstatus)=\${DIALSTATUS}))\n";
  $buffer .= " same => n,Set(CDR(encryption)=\${IF(\$[\"\${CHANNEL(secure_bridge_signaling)}\" = \"1\"]?TLS:none)}/\${IF(\$[\"\${CHANNEL(secure_bridge_media)}\" = \"1\"]?SRTP:none)})\n";
  $buffer .= " same => n,ExecIf(\$[\"\${c_cost}\" != \"\"]?GoSub(VoIPDomain-Tools,call_cost,1))\n";
  $buffer .= " same => n,Return()\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: handlers (pre-bridge, hangup).
 */
function dialplan_hook_handlers ( $buffer, $parameters)
{
  $buffer .= ";;\n;; Dial() pre-bridge handler\n;;\n";
  $buffer .= "[handler-VoIPDomain-pre-bridge]\n";
  $buffer .= "exten => s,1,Return()\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: internal extensions context header.
 */
function dialplan_hook_internal ( $buffer, $parameters)
{
  $buffer .= ";;\n;; VoIP Domain internal extensions\n;;\n";
  $buffer .= "[VoIPDomain-Internal]\n";
  $buffer .= "include => VoIPDomain-Extensions\n";
  $buffer .= "\n";
  return $buffer;
}

/**
 * Dialplan section: extra (plugin bootstrap fragments).
 */
function dialplan_hook_extra ( $buffer, $parameters)
{
  $buffer .= "#include voipdomain/configs/dialplan-bootstrap-*.conf\n";
  return $buffer;
}
?>
