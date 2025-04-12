<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2025 Ernani José Camargo Azevedo
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
 * VoIP Domain profiles country actions module. This module add the Asterisk
 * dialplan for a specific country.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Profiles
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "profile_country_BR", "profile_country_BR");

/**
 * Function to create Brazilian profile.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profile_country_BR ( $buffer, $parameters)
{
  $buffer .= ";\n";
  $buffer .= "; VoIP Domain - Brazilian (BR) dialplan profile\n";
  $buffer .= ";\n";
  $buffer .= "\n";
  $buffer .= "; Local service, yellow pages (special charging)\n";
  $buffer .= "exten => " . $parameters["Prefix"] . "102,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Yellow pages))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SPECIAL}]))\n";
  $buffer .= "\n";
  $buffer .= "; Local service, current date and time (special charging)\n";
  $buffer .= "exten => " . $parameters["Prefix"] . "130,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Current date and time))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SPECIAL}]))\n";
  $buffer .= "\n";
  $buffer .= "; Local service, wake up alarm (special charging)\n";
  $buffer .= "exten => " . $parameters["Prefix"] . "134,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Wake up alarm))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SPECIAL}]))\n";
  $buffer .= "\n";
  $buffer .= "; Local services\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "1XX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Local services))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SERVICES}]))\n";
  $buffer .= "\n";
  $buffer .= "; Local services (customer services)\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "1XXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Customer services))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SERVICES}]))\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "10[36]XX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Customer services))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SERVICES}]))\n";
  $buffer .= "\n";
  $buffer .= "; Toll free\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "0800XXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Toll free))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_TOLLFREE}]))\n";
  $buffer .= "\n";
  $buffer .= "; Special charging numbers\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "0300XXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Shared charging))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SPECIAL}]))\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "0500XXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Donations))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SPECIAL}]))\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "0900XXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN:1} (Premium services))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SPECIAL}]))\n";
  $buffer .= "\n";
  $buffer .= "; Local landline\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "[2-5]XXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to +55" . $parameters["AreaCode"] . "\${EXTEN:1} (Local landline))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(+55" . $parameters["AreaCode"] . "\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_LANDLINE}]))\n";
  $buffer .= "exten => _+55" . $parameters["AreaCode"] . "[2-5]XXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Local landline))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_LANDLINE}]))\n";
  $buffer .= "\n";
  $buffer .= "; Local mobile\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "[6-9]XXXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to +55" . $parameters["AreaCode"] . "\${EXTEN:1} (Local mobile))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(+55" . $parameters["AreaCode"] . "\${EXTEN:1},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_MOBILE}]))\n";
  $buffer .= "exten => _+55" . $parameters["AreaCode"] . "[6-9]XXXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Local mobile))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_MOBILE}]))\n";
  $buffer .= "\n";
  $buffer .= "; Interstate landline\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "0ZZ[2-5]XXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to +55\${EXTEN:2} (Interstate landline))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(+55\${EXTEN:2},\$[\${VD_CALLTYPE_INTERSTATE} + \${VD_CALLENDPOINT_LANDLINE}]))\n";
  $buffer .= "exten => _+55ZZ[2-5]XXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Interstate landline))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN},\$[\${VD_CALLTYPE_INTERSTATE} + \${VD_CALLENDPOINT_LANDLINE}]))\n";
  $buffer .= "\n";
  $buffer .= "; Interstate mobile\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "0ZZ[6-9]XXXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to +55\${EXTEN:2} (Interstate mobile))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(+55\${EXTEN:2},\$[\${VD_CALLTYPE_INTERSTATE} + \${VD_CALLENDPOINT_MOBILE}]))\n";
  $buffer .= "exten => _+55ZZ[6-9]XXXXXXXX,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Interstate mobile))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN},\$[\${VD_CALLTYPE_INTERSTATE} + \${VD_CALLENDPOINT_MOBILE}]))\n";
  $buffer .= "\n";
  $buffer .= "; International\n";
  $buffer .= "exten => _" . $parameters["Prefix"] . "00XXXXX.,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to +\${EXTEN:3} (International))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(+\${EXTEN:3},\$[\${VD_CALLTYPE_INTERNATIONAL} + \${VD_CALLENDPOINT_UNKNOWN}]))\n";
  $buffer .= "exten => _+X.,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (International))\n";
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_external,1(\${EXTEN},\$[\${VD_CALLTYPE_INTERNATIONAL} + \${VD_CALLENDPOINT_UNKNOWN}]))\n";

  // Check for emergency shortcut
  if ( $parameters["EmergencyShortcut"] == true)
  {
    $buffer .= "\n";
    $buffer .= "; Emergency shortcut\n";
    $buffer .= "exten => 100,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Emergency shortcut)\n";
    $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_emergency,1(\${EXTEN},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SERVICES} + \${VD_CALLENDPOINT_EMERGENCY}]))\n";
    $buffer .= "exten => 128,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Emergency shortcut)\n";
    $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_emergency,1(\${EXTEN},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SERVICES} + \${VD_CALLENDPOINT_EMERGENCY}]))\n";
    $buffer .= "exten => 153,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Emergency shortcut)\n";
    $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_emergency,1(\${EXTEN},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SERVICES} + \${VD_CALLENDPOINT_EMERGENCY}]))\n";
    $buffer .= "exten => _19X,1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Emergency shortcut)\n";
    $buffer .= " same => n,GoSub(VoIPDomain-Tools,call_emergency,1(\${EXTEN},\$[\${VD_CALLTYPE_LOCAL} + \${VD_CALLENDPOINT_SERVICES} + \${VD_CALLENDPOINT_EMERGENCY}]))\n";
  }

  return $buffer;
}
?>
