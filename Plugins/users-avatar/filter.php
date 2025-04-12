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
 * VoIP Domain user login avatar module filters. This module add the filter calls
 * related to user login avatar.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage User Avatar
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add avatar's filters
 */
framework_add_filter ( "authentication_success", "avatar_authentication_success");
framework_add_filter ( "session_extend", "avatar_session_extend");

/**
 * Function to add cookie that allow user to view their avatar at next
 * authentication. This is a security measure that avoid anyone to see user's
 * avatar (that maybe contain sensitive information).
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Received data
 */
function avatar_authentication_success ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch user avatar from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Avatar` FROM `UserAvatar` WHERE `User` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $_in["session"]["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 1)
  {
    $_in["session"]["Avatar"] = $result->fetch_assoc ()["Avatar"];
  } else {
    $_in["session"]["Avatar"] = "";
  }

  /**
   * Verify if avatar is enabled to user browser, otherwise, add/update the cookie
   */
  if ( ! empty ( $_in["session"]["Avatar"]) && ( ! array_key_exists ( $_in["general"]["cookie"] . "_avatar", $_COOKIE) || ! preg_match ( "/(^|\|)" . $_in["session"]["Avatar"] . "($|\|)/", $_COOKIE[$_in["general"]["cookie"] . "_avatar"])))
  {
    setcookie ( $_in["general"]["cookie"] . "_avatar", ( array_key_exists ( $_in["general"]["cookie"] . "_avatar", $_COOKIE) && ! empty ( $_COOKIE[$_in["general"]["cookie"] . "_avatar"]) ? $_COOKIE[$_in["general"]["cookie"] . "_avatar"] . "|" : "") . $_in["session"]["Avatar"], time () + 31536000, "/" . ( PHP_VERSION_ID < 70300 ? "; SameSite=Strict" : ""));
  }

  /**
   * Return incoming buffer
   */
  return $buffer;
}

/**
 * Function to add avatar information on session variable.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Received data
 */
function avatar_session_extend ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch user avatar from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Avatar` FROM `UserAvatar` WHERE `User` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $_in["session"]["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 1)
  {
    $_in["session"]["Avatar"] = $result->fetch_assoc ()["Avatar"];
  } else {
    $_in["session"]["Avatar"] = "";
  }

  /**
   * Return incoming buffer
   */
  return $buffer;
}
?>
