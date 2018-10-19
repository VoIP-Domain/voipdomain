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
 * VoIP Domain call audio fetch file. This file provides access to audio from
 * recorded calls. This request method is not available through API because it
 * returns binary content, which isn't compatible with REST API.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR);
ini_set ( "display_errors", "false");
// error_reporting ( E_ALL); ini_set ( "display_errors", "true");

/**
 * Include main configuration parser and functions
 */
require_once ( "includes/config.inc.php");

/**
 * Check if user is authenticated
 */
$_in["session"] = array ();
if ( array_key_exists ( $_in["general"]["cookie"], $_COOKIE) && $result = @$_in["mysql"]["id"]->query ( "SELECT `Sessions`.`SID`, `Sessions`.`LastSeen`, `Users`.* FROM `Sessions`, `Users` WHERE `Sessions`.`SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $_COOKIE[$_in["general"]["cookie"]]) . "' AND `Sessions`.`User` = `Users`.`ID`"))
{
  $_in["session"] = $result->fetch_assoc ();

  /**
   * Check if session has expired
   */
  if ( $_in["general"]["timeout"] > 0 && $_in["session"]["LastSeen"] + $_in["general"]["timeout"] < time ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Update session last seen
   */
  $_in["session"]["LastSeen"] = time ();
  @$_in["mysql"]["id"]->query ( "UPDATE `Sessions` SET `LastSeen` = '" . $_in["mysql"]["id"]->real_escape_string ( time ()) . "' WHERE `SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $_in["session"]["SID"]) . "'");

  /**
   * Create user permissions variable
   */
  $_in["permissions"] = array_merge ( array ( "user" => true), json_decode ( $_in["session"]["Permissions"], true));
}

/**
 * Extract call UniqueID from request path
 */
$id = substr ( $_SERVER["REQUEST_URI"], strrpos ( $_SERVER["REQUEST_URI"], "/") + 1);
if ( strpos ( $id, ".mp3") !== false)
{
  $id = substr ( $id, 0, strpos ( $id, ".mp3"));
}

/**
 * Check for call record into database
 */
if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `uniqueid` = '" . $_in["mysql"]["id"]->real_escape_string ( $id) . "'"))
{
  header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
  exit ();
}
if ( $result->num_rows != 1)
{
  header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
  exit ();
}
$call = $result->fetch_assoc ();

/**
 * Check if audio file exists
 */
if ( ! is_readable ( "/var/spool/asterisk/monitor/" . $call["monitor"] . ".mp3"))
{
  header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit ();
}

/**
 * Set page result headers
 */
audit ( "audio", "request", array ( "UniqueID" => $id));
header ( "Content-Type: audio/mpeg");
header ( "Content-Disposition: attachment;filename=\"" . $id . ".mp3\"");
header ( "Content-length: " . filesize ( "/var/spool/asterisk/monitor/" . $call["monitor"] . ".mp3"));
header ( "Cache-Control: no-cache");
header ( "Content-Transfer-Encoding: chunked");

/**
 * Send file to user
 */
readfile ( "/var/spool/asterisk/monitor/" . $call["monitor"] . ".mp3");

/**
 * Finish execution here
 */
exit ();
?>
