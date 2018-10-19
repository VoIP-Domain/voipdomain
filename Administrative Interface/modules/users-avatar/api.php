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
 * VoIP Domain user login avatar api module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage User Avatar
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to get user information
 */
framework_add_hook ( "users_view", "avatar_users_view");

/**
 * Function to generate user informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function avatar_users_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Users");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search users
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $user = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["avatarid"] = $user["AvatarID"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get user avatar
 */
framework_add_hook ( "avatar_user_get", "avatar_user_get");
framework_add_api_call ( "/users/avatar/:user", "Read", "avatar_user_get", array ( "unauthenticated" => true));

/**
 * Function to check for user avatar path if available.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function avatar_user_get ( $buffer, $parameters)
{
  global $_in;

  /**
   * Search for user avatar
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `AvatarID` From `Users` WHERE `User` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["user"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process avatar information if returned
   */
  $data = array ( "result" => false);
  if ( $result->num_rows == 1)
  {
    $user = $result->fetch_assoc ();
    if ( preg_match ( "/(^|\|)" . $user["AvatarID"] . "($|\|)/", $_COOKIE["vca"]) && is_readable ( "img/avatars/profile-" . $user["AvatarID"] . ".jpg"))
    {
      $data = array ( "result" => true, "id" => $user["AvatarID"]);
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove user avatar
 */
framework_add_hook ( "avatar_user_remove", "avatar_user_remove");
framework_add_permission ( "avatar_user_remove", __ ( "Remove user avatar"));
framework_add_api_call ( "/users/avatar/:id", "Delete", "avatar_user_remove", array ( "permissions" => array ( "user", "avatar_user_remove")));

/**
 * Function to remove an user avatar.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function avatar_user_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search for user
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * From `Users` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $user = $result->fetch_assoc ();

  /**
   * Remove avatar ID from database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Users` SET `AvatarID` = '' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove old avatar file
   */
  @unlink ( dirname ( __FILE__) . "/../../img/avatars/profile-" . $user["AvatarID"] . ".jpg");

  /**
   * Update session
   */
  $_in["session"]["AvatarID"] = '';

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["avatarid"] = '';

  /**
   * Create audit record
   */
  audit ( "user-avatar", "del", array ( "ID" => $user["ID"], "AvatarID" => $user["AvatarID"]));

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to update user avatar
 */
framework_add_hook ( "avatar_user_set", "avatar_user_set");
framework_add_permission ( "avatar_user_set", __ ( "Change user avatar"));
framework_add_api_call ( "/users/avatar/:id", "Modify", "avatar_user_set", array ( "permissions" => array ( "user", "avatar_user_set")));
framework_add_api_call ( "/users/avatar/:id", "Edit", "avatar_user_set", array ( "permissions" => array ( "user", "avatar_user_set")));

/**
 * Function to set an user avatar.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function avatar_user_set ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search for user
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * From `Users` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $user = $result->fetch_assoc ();

  /**
   * Generate new avatar ID
   */
  $avatarid = uniqid ( "", true);
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Users` SET `AvatarID` = '" . $_in["mysql"]["id"]->real_escape_string ( $avatarid) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Write image to avatar directory. If not in JPEG format, convert it.
   */
  $parameters["avatar"] = base64_decode ( $parameters["avatar"]);
  if ( ! $image = imagecreatefromstring ( $parameters["avatar"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  list ( $width, $height) = getimagesizefromstring ( $parameters["avatar"]);
  $resized = imagecreatetruecolor ( 215, 215);
  imagecopyresampled ( $resized, $image, 0, 0, 0, 0, 215, 215, $width, $height);
  if ( ! imagejpeg ( $resized, dirname ( __FILE__) . "/../../img/avatars/profile-" . $avatarid . ".jpg"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  imagedestroy ( $resized);
  imagedestroy ( $image);

  /**
   * Remove old avatar, if exists
   */
  @unlink ( dirname ( __FILE__) . "/../../img/avatars/profile-" . $user["AvatarID"] . ".jpg");

  /**
   * Update session
   */
  $_in["session"]["AvatarID"] = $avatarid;

  /**
   * Create audit record
   */
  audit ( "user-avatar", "edit", array ( "ID" => $user["ID"], "AvatarID" => array ( "Old" => $user["AvatarID"], "New" => $avatarid)));

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["avatarid"] = $avatarid;

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
