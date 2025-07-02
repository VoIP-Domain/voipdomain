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
 * VoIP Domain call audio fetch file. This file provides access to audio from
 * recorded calls. This request method is not available through API because it
 * returns binary content, which isn't compatible with REST API.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR | E_USER_ERROR);
ini_set ( "display_errors", 0);
// error_reporting ( E_ALL); ini_set ( "display_errors", 1);

/**
 * Include main configuration parser and functions
 */
require_once ( "includes/config.inc.php");

/**
 * Check if user is authenticated
 */
if ( array_key_exists ( $_in["general"]["cookie"], $_COOKIE))
{
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Sessions`.`SID`, `Sessions`.`LastSeen`, `Users`.* FROM `Sessions` LEFT JOIN `Users` ON `Sessions`.`User` = `Users`.`ID` WHERE `Sessions`.`SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $_COOKIE[$_in["general"]["cookie"
]]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $session = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 401 Unauthorized");
    exit ();
  }

  /**
   * Set session variables
   */
  $_in["session"]["Authenticated"] = true;
  $_in["session"]["Method"] = "Session";
  $_in["session"]["Language"] = $session["Language"];
  $_in["session"]["Data"]["ID"] = $session["ID"];
  $_in["session"]["Data"]["Username"] = $session["Username"];
  $_in["session"]["Data"]["Name"] = $session["Name"];
  $_in["session"]["Data"]["Email"] = $session["Email"];
  $_in["session"]["Data"]["Since"] = $session["Since"];
  $_in["session"]["Data"]["LastSeen"] = $session["LastSeen"];
  $_in["session"]["Data"]["Expires"] = time () + $_in["general"]["timeout"];
  $_in["session"]["Permissions"][] = "User";

  /**
   * Inject user permissions in session
   */
  foreach ( json_decode ( $session["Permissions"], true) as $permission)
  {
    $_in["session"]["Permissions"][] = $permission;
  }

  /**
   * Set system language if user has different language than system default
   */
  if ( ! empty ( $session["Language"]) && array_key_exists ( $session["Language"], $_in["languages"]))
  {
    $_in["general"]["language"] = $session["Language"];
  }

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "user_session_validate_start"))
  {
    $parameters = framework_call ( "user_session_validate_start", $parameters);
  }

  /**
   * Extend session variables
   */
  filters_call ( "session_extend");

  /**
   * Check if session has expired
   */
  if ( $_in["general"]["timeout"] > 0 && $_in["session"]["Data"]["LastSeen"] + $_in["general"]["timeout"] < time ())
  {
    /**
     * Call session timeout hook if exist
     */
    if ( framework_has_hook ( "user_session_timeout"))
    {
      framework_call ( "user_session_timeout", $parameters);
    }

    /**
     * Return error
     */
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "user_session_validate_pre"))
  {
    $parameters = framework_call ( "user_session_validate_pre", $parameters);
  }

  /**
   * Update session last seen
   */
  @$_in["mysql"]["id"]->query ( "UPDATE `Sessions` SET `LastSeen` = '" . $_in["mysql"]["id"]->real_escape_string ( time ()) . "' WHERE `SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $session["SID"]) . "'");

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "user_session_validate_post"))
  {
    framework_call ( "user_session_validate_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "user_session_validate_finish"))
  {
    framework_call ( "user_session_validate_finish", $parameters);
  }
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
if ( ! $call = $result->fetch_assoc ())
{
  header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
  exit ();
}

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
