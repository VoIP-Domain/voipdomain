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
 * VoIP Domain user login avatar module API. This module add the API calls
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
 * API call to get user information
 */
framework_add_hook (
  "users_view",
  "avatar_users_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Avatar" => array (
              "type" => "string",
              "description" => __ ( "The avatar hash of the user."),
              "example" => "5ea5ab3f519287.34757697"
            )
          )
        )
      )
    )
  )
);

/**
 * Function to generate user information.
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
   * Search user avatar
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Avatar` FROM `UserAvatar` WHERE `User` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    return $buffer;
  }
  $avatar = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["Avatar"] = $avatar["Avatar"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get user avatar
 */
framework_add_hook (
  "avatar_user_get",
  "avatar_user_get",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system user avatar."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Hash" => array (
              "type" => "string",
              "example" => "default",
              "description" => __ ( "The md5 hash of the image.")
            ),
            "Avatar" => array (
              "type" => "string",
              "format" => "byte",
              "description" => __ ( "The base64 encoded avatar JPEG image file.")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/users/:Username/avatar",
  "Read",
  "avatar_user_get",
  array (
    "unauthenticated" => true,
    "title" => __ ( "View users avatar"),
    "description" => __ ( "Get a system user avatar information. Note that the user avatar will be replyed sucessfully only if the user was already logged in previously to the system."),
    "parameters" => array (
      array (
        "name" => "Username",
        "type" => "string",
        "description" => __ ( "The username of the system user."),
        "example" => __ ( "admin")
      )
    )
  )
);

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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `UserAvatar`.`Avatar` FROM `UserAvatar` LEFT JOIN `Users` ON `UserAvatar`.`User` = `Users`.`ID` WHERE `Users`.`Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process avatar information if returned
   */
  $data = array ();
  if ( $result->num_rows == 1)
  {
    $avatar = $result->fetch_assoc ()["Avatar"];
    if ( ( preg_match ( "/(^|\|)" . $avatar . "($|\|)/", $_COOKIE[$_in["general"]["cookie"] . "_avatar"]) || ( array_key_exists ( "token", $_in) || array_key_exists ( "session", $_in) || array_key_exists ( "server", $_in))) && is_readable ( $_in["general"]["storagedir"] . "/avatars/profile-" . $avatar . ".jpg"))
    {
      $data["Avatar"] = base64_encode ( file_get_contents ( $_in["general"]["storagedir"] . "/avatars/profile-" . $avatar . ".jpg"));
      $data["Hash"] = md5 ( file_get_contents ( $_in["general"]["storagedir"] . "/avatars/profile-" . $avatar . ".jpg"));
    }
  }

  /**
   * If no avatar found, get default one
   */
  if ( ! array_key_exists ( "Avatar", $data))
  {
    $data["Avatar"] = base64_encode ( file_get_contents ( $_in["general"]["storagedir"] . "/avatars/profile-default.jpg"));
    $data["Hash"] = md5 ( file_get_contents ( $_in["general"]["storagedir"] . "/avatars/profile-default.jpg"));
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove user avatar
 */
framework_add_hook (
  "avatar_user_remove",
  "avatar_user_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system user avatar was removed.")
      )
    )
  )
);
framework_add_permission ( "avatar_user_remove", __ ( "Remove user avatar"));
framework_add_api_call (
  "/users/:ID/avatar",
  "Delete",
  "avatar_user_remove",
  array (
    "permissions" => array ( "user", "avatar_user_remove"),
    "title" => __ ( "Remove users avatar"),
    "description" => __ ( "Remove a system user avatar."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The user internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

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
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Search for user
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `UserAvatar` WHERE `User` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $avatar = $result->fetch_assoc ();

  /**
   * Remove user avatar from database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `UserAvatar` WHERE `User` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove old avatar file
   */
  @unlink ( $_in["general"]["storagedir"] . "/avatars/profile-" . $avatar["Avatar"] . ".jpg");

  /**
   * Update session
   */
  if ( $_in["session"]["ID"] == $parameters["ID"])
  {
    $_in["session"]["Avatar"] = '';
  }

  /**
   * Format data
   */
  $data = array ();
  $data["avatar"] = "";

  /**
   * Create audit record
   */
  audit ( "user-avatar", "del", array ( "ID" => $parameters["ID"], "Avatar" => $avatar["Avatar"]));

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to update user avatar
 */
framework_add_hook (
  "avatar_user_set",
  "avatar_user_set",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Avatar" => array (
          "type" => "byte",
          "description" => __ ( "The user avatar image in base 64 format."),
          "required" => true
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system user avatar was sucessfully updated.")
      )
    )
  )
);
framework_add_permission ( "avatar_user_set", __ ( "Change user avatar"));
framework_add_api_call (
  "/users/:ID/avatar",
  "Modify",
  "avatar_user_set",
  array (
    "permissions" => array ( "user", "avatar_user_set"),
    "title" => __ ( "Edit users avatar"),
    "description" => __ ( "Change a system user avatar.")
  )
);
framework_add_api_call (
  "/users/:ID/avatar",
  "Edit",
  "avatar_user_set",
  array (
    "permissions" => array ( "user", "avatar_user_set"),
    "title" => __ ( "Edit users avatar"),
    "description" => __ ( "Change a system user avatar.")
  )
);

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
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Search for user
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Users`.*, `UserAvatar`.`Avatar` FROM `Users` LEFT JOIN `UserAvatar` ON `Users`.`ID` = `UserAvatar`.`User` WHERE `Users`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $user = $result->fetch_assoc ();

  /**
   * Generate new avatar ID
   */
  $avatar = uniqid ( "", true);
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `UserAvatar` (`User`, `Avatar`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $avatar) . "') ON DUPLICATE KEY UPDATE `Avatar` = '" . $_in["mysql"]["id"]->real_escape_string ( $avatar) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Write image to avatar directory. If not in JPEG format, convert it.
   */
  $parameters["Avatar"] = base64_decode ( $parameters["Avatar"]);
  if ( ! $image = imagecreatefromstring ( $parameters["Avatar"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  list ( $width, $height) = getimagesizefromstring ( $parameters["Avatar"]);
  $resized = imagecreatetruecolor ( 215, 215);
  imagecopyresampled ( $resized, $image, 0, 0, 0, 0, 215, 215, $width, $height);
  if ( ! imagejpeg ( $resized, $_in["general"]["storagedir"] . "/avatars/profile-" . $avatar . ".jpg"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  imagedestroy ( $resized);
  imagedestroy ( $image);

  /**
   * Remove old avatar, if exists
   */
  @unlink ( $_in["general"]["storagedir"] . "/avatars/profile-" . $user["Avatar"] . ".jpg");

  /**
   * Update session
   */
  $_in["session"]["Avatar"] = $avatar;

  /**
   * Create audit record
   */
  audit ( "user-avatar", "edit", array ( "ID" => $parameters["ID"], "Avatar" => array ( "Old" => $user["Avatar"], "New" => $avatar)));

  /**
   * Format data
   */
  $data = array ();
  $data["Avatar"] = $avatar;

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
