#!/usr/bin/php -q
<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * FastAGI debug tool to check LCR application.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk FastAGI Application Daemon
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR);
ini_set ( "display_errors", "false");

/**
 * Show software version header
 */
echo chr ( 27) . "[1;37mVoIP Central FastAGI Daemon Debugger" . chr ( 27) . "[1;0m v1.0\n";
echo "\n";

/**
 * Check parameters
 */
if ( $_SERVER["argc"] != 4)
{
  echo "Error: You must provide three parameters, the server IP, the debugging number (E.164 standard) and the default gateways (example: 1,2).\n";
  exit ( 1);
}

/**
 * Connect to the FastAGI server
 */
echo "Conecting to FastAGI server \"" . $_SERVER["argv"][1] . ":1234\"... ";
if ( ! $fp = fsockopen ( $_SERVER["argv"][1], 1234, $errno, $errstr, 30))
{
  echo "error!\n";
  exit ( 2);
}
echo "OK!\n";

/**
 * Send FastAGI request headers
 */
echo "Sending FastAGI request headers... ";
fwrite ( $fp, "agi_network: yes\n");
fwrite ( $fp, "agi_network_script: lcr\n");
fwrite ( $fp, "agi_request: agi://" . $_SERVER["argv"][1] . ":1234/lcr\n");
fwrite ( $fp, "agi_channel: SIP/u999-0-00000099\n");
fwrite ( $fp, "agi_language: pt_BR\n");
fwrite ( $fp, "agi_type: SIP\n");
fwrite ( $fp, "agi_uniqueid: 1469805779.155\n");
fwrite ( $fp, "agi_version: 11.23.0\n");
fwrite ( $fp, "agi_callerid: 999\n");
fwrite ( $fp, "agi_calleridname: Teste\n");
fwrite ( $fp, "agi_callingpres: 0\n");
fwrite ( $fp, "agi_callingani2: 0\n");
fwrite ( $fp, "agi_callington: 0\n");
fwrite ( $fp, "agi_callingtns: 0\n");
fwrite ( $fp, "agi_dnid: 032103787\n");
fwrite ( $fp, "agi_rdnis: unknown\n");
fwrite ( $fp, "agi_context: VoIPDomain-local\n");
fwrite ( $fp, "agi_extension: 030859480\n");
fwrite ( $fp, "agi_priority: 3\n");
fwrite ( $fp, "agi_enhanced: 0.0\n");
fwrite ( $fp, "agi_accountcode: test\n");
fwrite ( $fp, "agi_threadid: 140422749710080\n");
fwrite ( $fp, "agi_arg_1: " . $_SERVER["argv"][2] . "\n");
fwrite ( $fp, "agi_arg_2: " . $_SERVER["argv"][3] . "\n");
fwrite ( $fp, "\n");
echo "OK!\n";

/**
 * Process response
 */
echo "Processing response... ";
$routes = array ();
$data = "";
while ( ! feof ( $fp))
{
  $data = fgets ( $fp, 1024);
  if ( substr ( $data, 0, 12) == "SET VARIABLE")
  {
    $tmp = explode ( "\"", $data);
    $id = substr ( $tmp[1], strpos ( $tmp[1], "_") + 1);
    $id = (int) substr ( $id, 0, strpos ( $id, "_"));
    if ( ! array_key_exists ( $id, $routes))
    {
      $routes[$id] = array ();
    }
    $routes[$id][substr ( $tmp[1], strrpos ( $tmp[1], "_") + 1)] = $tmp[3];
  }
  fwrite ( $fp, "200 result=1\n");
}
fclose ( $fp);
echo "OK!\n";
echo "\n";

/**
 * Show report
 */
echo "=== LCR START ===\n";
foreach ( $routes as $id => $route)
{
  echo " Route weight " . $id . ": GW = " . $route["gw"] . ", Dial = " . $route["dial"] . ", Cost = " . $route["cm"] . ", Discard = " . $route["td"] . ", Minimum = " . $route["tm"] . ", Fraction = " . $route["tf"] . "\n";
}
echo "=== LCR END ===\n";
?>
