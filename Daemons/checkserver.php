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
 * This daemon connects to Asterisk through AMI interface, and do a complete
 * Asterisk server checkup.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage AMI Interface
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR);
ini_set ( "display_errors", "false");
// error_reporting ( E_ALL); ini_set ( "display_errors", "true");

/**
 * Check if script is running from CLI
 */
if ( ! defined ( "STDIN"))
{
  echo "This script must be executed from the CLI!\n";
  exit ( 1);
}

/**
 * Include functions library
 */
require_once ( dirname ( __FILE__) . "/includes/asterisk.inc.php");

/**
 * Show software version header
 */
echo chr ( 27) . "[1;37mVoIP Domain Asterisk Server Checker" . chr ( 27) . "[1;0m v1.0\n";
echo "\n";

/**
 * Read configuration file
 */
if ( is_readable ( $_SERVER["HOME"] . "/.amicli"))
{
  $conf = parse_ini_file ( $_SERVER["HOME"] . "/.amicli", true);
} else {
  $conf = array ();
}
if ( ! array_key_exists ( "hostname", $conf) || empty ( $conf["hostname"]))
{
  $conf["hostname"] = "localhost";
}
if ( ! array_key_exists ( "port", $conf) || empty ( $conf["port"]))
{
  $conf["port"] = 5038;
}
if ( ! array_key_exists ( "events", $conf) || ( empty ( $conf["events"]) && $conf["events"] !== false))
{
  $conf["events"] = true;
}
if ( ! array_key_exists ( "filters", $conf) || empty ( $conf["filters"]))
{
  $conf["filters"] = array ();
} else {
  $conf["filters"] = explode ( " ", $conf["filters"]);
}

/**
 * Process parameters
 */
for ( $x = 1; $x < $argc; $x++)
{
  switch ( $_SERVER["argv"][$x])
  {
    case "--username":
    case "-u":
      $x++;
      $conf["username"] = $_SERVER["argv"][$x];
      break;
    case "--secret":
    case "-s":
      $x++;
      $conf["secret"] = $_SERVER["argv"][$x];
      break;
    case "--hostname":
    case "-h":
      $x++;
      $conf["hostname"] = $_SERVER["argv"][$x];
      break;
    case "--port":
    case "-p":
      $x++;
      $conf["port"] = (int) $_SERVER["argv"][$x];
      break;
    case "--help":
      echo "Usage: " . basename ( $_SERVER["argv"][0]) . " [--help] [--debug|-d]\n";
      echo "  --help:        Show this help information\n";
      echo "  --username|-u: AMI username\n";
      echo "  --secret|-s:   AMI secret (password)\n";
      echo "  --hostname|-h: AMI server hostname/IP address (default localhost)\n";
      echo "  --port|-p:     AMI server port (default 5038)\n";
      exit ();
      break;
    default:
      echo "ERROR: Invalid parameter \"" . $_SERVER["argv"][$x] . "\"!\n";
      exit ( -1);
      break;
  }
}

/**
 * Show start of operations message
 */
echo "Connecting to AMI server... ";
$ami = new asterisk ( array ( "username" => $conf["username"], "password" => $conf["secret"], "hostname" => $conf["hostname"], "port" => $conf["port"], "connect_timeout" => 1, "events" => $conf["events"]));
if ( ! $ami->connect ())
{
  echo chr ( 27) . "[1;37mERROR: Cannot connect to AMI server, check your configurations!" . chr ( 27) . "[1;0m\n";
  exit ( 1);
}
echo chr ( 27) . "[1;37mConnected!" . chr ( 27) . "[1;0m\n";

// **TODO**: Mapear os comandos que fornecem informações com uma matriz de versões onde o comando está disponível, também os resultados retornados, ex.:
// $commands = array (
//   "core show version" => array (
//     "Versions" => array (
//       array ( ">=1.0.0 && <=17.99.99")
//     ),
//     "Variables" =>
//       "Version" => array (
//         array ( ">=1.6.0 && <=17.99.99")
//       )
//     )
//   )
// );
// Com isto, podemos executar os comandos, e trabalhar as informações retornadas, e warning se não estiver no padrão...

/**
 * Check basic server information first
 */
$server = array ();
$version = $ami->request ( "Command", array ( "Command" => "core show version"));
$server["Version"] = substr ( $version["Output"], 0, strpos ( $version["Output"], " ", 10));
$uptime = $ami->request ( "Command", array ( "Command" => "core show uptime"));
$server["Uptime"] = substr ( $uptime["Output"], 0, strpos ( $uptime["Output"], "\n"));
$server["Uptime"] = substr ( $server["Uptime"], strpos ( $server["Uptime"], ":") + 2);
$server["LastReload"] = substr ( $uptime["Output"], strpos ( $uptime["Output"], "\n") + 1);
$server["LastReload"] = substr ( $server["LastReload"], strpos ( $server["LastReload"], ":") + 2);

/**
 * Get all server codecs
 */
$codecs = $ami->request ( "Command", array ( "Command" => "core show codecs"));
$server["Codecs"] = array ();
$inheader = true;
foreach ( explode ( PHP_EOL, $codecs["Output"]) as $line)
{
  // Check if it's on header or a codec:
  $line = explode ( " ", preg_replace ( "/ ( )+/", " ", trim ( $line)));
  if ( $inheader)
  {
    if ( substr ( $line[0], 0, 10) == "----------")
    {
      $inheader = false;
    }
    continue;
  }

  // Extract codec information:
  $newrec = array (
    "ID" => $line[0],
    "Type" => $line[1],
    "Name" => $line[2],
    "Format" => $line[3],
    "Description" => preg_replace ( "/\)$/", "", substr ( $line, strpos ( $line, "(") + 1)),
    "Transcoding" => false
  );

  // Fix some Asterisk codec mistakes:
  switch ( $newrec["Format"])
  {
    case "slin":
      $newrec["Format"] = "slin8";
      $newrec["Description"] = $newrec["Description"] . " (8kHz)";
      break;
    case "speex":
      $newrec["Format"] = "speex8";
      $newrec["Description"] = $newrec["Description"] . " 8kHz";
      break;
  }

  // Add codec to server codec list:
  $server["Codecs"][$newrec["ID"]] = $newrec;
}

/**
 * Get available server codecs
 */
$codecs = $ami->request ( "Command", array ( "Command" => "core show translation recalc 1"));
foreach ( explode ( PHP_EOL, $codecs["Output"]) as $line)
{
  $line = trim ( $line);
  if ( preg_match ( "/^[a-z]/", $line))
  {
    $found = false;
    foreach ( $server["Codecs"] as $id => $codec)
    {
      if ( $codec["Format"] == substr ( $line, 0, strpos ( $line, " ")))
      {
        $server["Codecs"][$id]["Transcoding"] = true;
        $found = true;
      }
    }
    if ( ! $found)
    {
      echo "Warning: Cannot find server codec \"" . substr ( $line, 0, strpos ( $line, " ")) . "\"!\n";
    }
  }
}

/**
 * Get server file formats
 */
$formats = $ami->request ( "Command", array ( "Command" => "core show file formats"));
$server["Formats"] = array ();
foreach ( explode ( PHP_EOL, $formats["Output"]) as $line)
{
  $line = trim ( $line);
  if ( ! preg_match ( "/^\d+ file formats registered\.$/", $line) && substr ( $line, 0, 6) != "Format" && substr ( $line, 0, 6) != "------")
  {
    $line = explode ( " ", preg_replace ( "/( )+/", " ", $line));
    $server["Formats"][] = array ( "Format" => $line[0], "Name" => $line[1], "Extensions" => explode ( "|", $line[2]));
  }
}

/**
 * Dump information
 */
var_dump ( $server);
?>
