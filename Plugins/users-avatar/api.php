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
  if ( ! $avatar = $result->fetch_assoc ())
  {
    return $buffer;
  }

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
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Username" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid user name.")
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
    "description" => __ ( "Get a system user avatar information. Note that the user avatar will be replied successfully only if the user was already logged in previously to the system."),
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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "avatar_user_get_start"))
  {
    $parameters = framework_call ( "avatar_user_get_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Users", "UserAvatar"));

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Username", $parameters) || empty ( $parameters["Username"]))
  {
    $data["Username"] = __ ( "Invalid user name.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "avatar_user_get_validate"))
  {
    $data = framework_call ( "avatar_user_get_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "avatar_user_get_sanitize"))
  {
    $parameters = framework_call ( "avatar_user_get_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "avatar_user_get_pre"))
  {
    $parameters = framework_call ( "avatar_user_get_pre", $parameters, false, $parameters);
  }

  /**
   * Search for user avatar
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `UserAvatar`.`Avatar` FROM `UserAvatar` LEFT JOIN `Users` ON `UserAvatar`.`User` = `Users`.`ID` WHERE `Users`.`Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $data = array ();
  if ( $avatar = $result->fetch_assoc ()["Avatar"])
  {
    if ( ( preg_match ( "/(^|\|)" . $avatar . "($|\|)/", $_COOKIE[$_in["general"]["cookie"] . "_avatar"]) || $_in["session"]["Authenticated"]) && is_readable ( $_in["general"]["storagedir"] . "/avatars/profile-" . $avatar . ".jpg"))
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
   * Call post hook if exist
   */
  if ( framework_has_hook ( "avatar_user_get_post"))
  {
    $data = framework_call ( "avatar_user_get_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "avatar_user_get_finish"))
  {
    framework_call ( "avatar_user_get_finish", $parameters);
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
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid user ID.")
            )
          )
        )
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
    "permissions" => array ( "User", "avatar_user_remove"),
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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "avatar_user_remove_start"))
  {
    $parameters = framework_call ( "avatar_user_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid user ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "avatar_user_remove_validate"))
  {
    $data = framework_call ( "avatar_user_remove_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "avatar_user_remove_sanitize"))
  {
    $parameters = framework_call ( "avatar_user_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if user avatar exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `UserAvatar` WHERE `User` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "avatar_user_remove_pre"))
  {
    $parameters = framework_call ( "avatar_user_remove_pre", $parameters, false, $parameters);
  }

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
  if ( $_in["session"]["Method"] == "Session" && $_in["session"]["Data"]["ID"] == $parameters["ID"])
  {
    $_in["session"]["Data"]["Avatar"] = "";
  }

  /**
   * Format data
   */
  $data = array ();
  $data["avatar"] = "";

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "avatar_user_remove_post"))
  {
    framework_call ( "avatar_user_remove_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "avatar_user_remove_finish"))
  {
    framework_call ( "avatar_user_remove_finish", $parameters, false);
  }

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
        "description" => __ ( "The system user avatar was successfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid user ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "avatar_user_set", __ ( "Change user avatar"));
framework_add_api_call (
  "/users/:ID/avatar",
  array ( "Modify", "Edit"),
  "avatar_user_set",
  array (
    "permissions" => array ( "User", "avatar_user_set"),
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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "avatar_user_set_start"))
  {
    $parameters = framework_call ( "avatar_user_set_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid user ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "avatar_user_set_validate"))
  {
    $data = framework_call ( "avatar_user_set_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "avatar_user_set_sanitize"))
  {
    $parameters = framework_call ( "avatar_user_set_sanitize", $parameters, false, $parameters);
  }

  /**
   * Search for user
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Users`.*, `UserAvatar`.`Avatar` FROM `Users` LEFT JOIN `UserAvatar` ON `Users`.`ID` = `UserAvatar`.`User` WHERE `Users`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $user = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "avatar_user_set_pre"))
  {
    $parameters = framework_call ( "avatar_user_set_pre", $parameters, false, $parameters);
  }

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
  if ( $_in["session"]["Method"] == "Session" && $_in["session"]["Data"]["ID"] == $parameters["ID"])
  {
    $_in["session"]["Data"]["Avatar"] = $avatar;
  }

  /**
   * Format data
   */
  $data = array ();
  $data["Avatar"] = $avatar;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "avatar_user_set_post"))
  {
    framework_call ( "avatar_user_set_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "avatar_user_set_finish"))
  {
    framework_call ( "avatar_user_set_finish", $parameters, false);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
