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
 * VoIP Domain CDR bootstrap configuration generation module.
 *
 * Registers hooks that generate the Asterisk CDR configuration files:
 *   - cdr.conf: CDR engine general settings
 *   - cdr_manager.conf: AMI CDR event backend with custom field mappings
 *   - cdr_sqlite3_custom.conf: Local SQLite3 storage for crash recovery
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CDR
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Register CDR bootstrap hooks
 */
framework_add_hook ( "asterisk_cdr_cdr_general", "cdr_hook_general");
framework_add_hook ( "asterisk_cdr_manager_cdr_manager", "cdr_hook_manager");
framework_add_hook ( "asterisk_cdr_sqlite3_custom_cdr_sqlite3_custom", "cdr_hook_sqlite3_custom");

/**
 * CDR engine general configuration.
 */
function cdr_hook_general ( $buffer, $parameters)
{
  $buffer .= "[general]\n";
  $buffer .= "enable=yes\n";
  $buffer .= "unanswered=yes\n";
  $buffer .= "congestion=yes\n";
  $buffer .= "endbeforehexten=no\n";
  $buffer .= "initiatedseconds=yes\n";
  $buffer .= "batch=no\n";
  $buffer .= "\n";

  return $buffer;
}

/**
 * CDR Manager backend configuration — sends CDR events over AMI with
 * custom field mappings so the monitor daemon receives all VoIP Domain
 * specific CDR variables.
 */
function cdr_hook_manager ( $buffer, $parameters)
{
  $buffer .= "[general]\n";
  $buffer .= "enabled=yes\n";
  $buffer .= "\n";
  $buffer .= "[mappings]\n";
  $buffer .= "server => Server\n";
  $buffer .= "sourcetype => SourceType\n";
  $buffer .= "calltype => CallType\n";
  $buffer .= "gateway => Gateway\n";
  $buffer .= "value => Value\n";
  $buffer .= "processed => BillingProcessed\n";
  $buffer .= "nativecodec => NativeCodec\n";
  $buffer .= "readcodec => ReadCodec\n";
  $buffer .= "writecodec => WriteCodec\n";
  $buffer .= "QOS => QOS\n";
  $buffer .= "WhoHungUp => WhoHungUp\n";
  $buffer .= "SIPID => SIPID\n";
  $buffer .= "flags => Flags\n";
  $buffer .= "monitor => Monitor\n";
  $buffer .= "userfieldextra => UserFieldExtra\n";
  $buffer .= "hangupcause => HangupCause\n";
  $buffer .= "dialstatus => DialStatus\n";
  $buffer .= "encryption => Encryption\n";
  $buffer .= "\n";

  return $buffer;
}

/**
 * CDR SQLite3 Custom backend — stores CDR records locally on the Asterisk
 * server for crash recovery. The 'processed' column tracks whether the
 * monitor daemon has successfully forwarded the record to the central router.
 */
function cdr_hook_sqlite3_custom ( $buffer, $parameters)
{
  $buffer .= "[master]\n";
  $buffer .= "table=cdr\n";
  $buffer .= "columns => calldate,clid,src,dst,dcontext,channel,dstchannel,lastapp,lastdata,duration,billsec,disposition,amaflags,accountcode,uniqueid,linkedid,sequence,peeraccount,userfield,server,sourcetype,calltype,gateway,value,billing_processed,nativecodec,readcodec,writecodec,QOS,WhoHungUp,SIPID,flags,monitor,userfieldextra,hangupcause,dialstatus,encryption,processed\n";
  $buffer .= "values => '\${CDR(start)}','\${CDR(clid)}','\${CDR(src)}','\${CDR(dst)}','\${CDR(dcontext)}','\${CDR(channel)}','\${CDR(dstchannel)}','\${CDR(lastapp)}','\${CDR(lastdata)}',\${CDR(duration)},\${CDR(billsec)},'\${CDR(disposition)}',\${CDR(amaflags)},'\${CDR(accountcode)}','\${CDR(uniqueid)}','\${CDR(linkedid)}','\${CDR(sequence)}','\${CDR(peeraccount)}','\${CDR(userfield)}','\${CDR(server)}','\${CDR(sourcetype)}','\${CDR(calltype)}','\${CDR(gateway)}','\${CDR(value)}','\${CDR(processed)}','\${CDR(nativecodec)}','\${CDR(readcodec)}','\${CDR(writecodec)}','\${CDR(QOS)}','\${CDR(WhoHungUp)}','\${CDR(SIPID)}','\${CDR(flags)}','\${CDR(monitor)}','\${CDR(userfieldextra)}','\${CDR(hangupcause)}','\${CDR(dialstatus)}','\${CDR(encryption)}',0\n";
  $buffer .= "\n";

  return $buffer;
}
?>
