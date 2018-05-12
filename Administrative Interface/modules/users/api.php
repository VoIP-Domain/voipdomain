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
 * VoIP Domain users api module. This module add the api calls related to users.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Users
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch users listing
 */
framework_add_hook ( "users_fetch", "users_fetch");
framework_add_permission ( "users_fetch", __ ( "Request users listing"));
framework_add_api_call ( "/users/fetch", "Read", "users_fetch", array ( "permissions" => array ( "administrator", "users_fetch")));

/**
 * Function to generate user list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Users");

  /**
   * Search users
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create table structure
   */
  $data = array ();
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = array ( $result["ID"], $result["Name"], $result["User"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get user information
 */
framework_add_hook ( "users_view", "users_view");
framework_add_permission ( "users_view", __ ( "View users informations"));
framework_add_api_call ( "/users/:id", "Read", "users_view", array ( "permissions" => array ( "administrator", "users_view")));

/**
 * Function to generate user informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_view ( $buffer, $parameters)
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
  $user["Permissions"] = json_decode ( $user["Permissions"], true);

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["name"] = $user["Name"];
  $data["user"] = $user["User"];
  $data["email"] = $user["Email"];
  $data["administrator"] = $user["Permissions"]["administrator"];
  $data["auditor"] = $user["Permissions"]["auditor"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new user
 */
framework_add_hook ( "users_add", "users_add");
framework_add_permission ( "users_add", __ ( "Add users"));
framework_add_api_call ( "/users", "Create", "users_add", array ( "permissions" => array ( "administrator", "users_add")));

/**
 * Function to add a new user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["name"])));
  if ( empty ( $parameters["name"]))
  {
    $data["result"] = false;
    $data["name"] = __ ( "The user name is required.");
  }
  $parameters["user"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["user"])));
  if ( empty ( $parameters["user"]))
  {
    $data["result"] = false;
    $data["user"] = __ ( "The user login name is required.");
  }
  $parameters["email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["email"])));
  if ( empty ( $parameters["email"]))
  {
    $data["result"] = false;
    $data["email"] = __ ( "The user e-mail is required.");
  }
  if ( ! empty ( $parameters["email"]) && ! validateEmail ( $parameters["email"]))
  {
    $data["result"] = false;
    $data["email"] = __ ( "The informed e-mail is invalid.");
  }
  if ( empty ( $parameters["password"]))
  {
    $data["result"] = false;
    $data["password"] = __ ( "The user password is required.");
  }
  if ( empty ( $parameters["confirmation"]))
  {
    $data["result"] = false;
    $data["confirmation"] = __ ( "The user confirmation password is required.");
  }
  if ( ! empty ( $parameters["password"]) && ! empty ( $parameters["confirmation"]) && $parameters["password"] != $parameters["confirmation"])
  {
    $data["result"] = false;
    $data["confirmation"] = __ ( "The passwords didn't match.");
  }

  /**
   * Check if user was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `User` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["user"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["result"] = false;
    $data["user"] = __ ( "The user login name is already in use.");
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "users_add_sanitize"))
  {
    $data = framework_call ( "users_add_sanitize", $parameters, false, $data);
  }

  /**
   * Return error data if some error ocurred
   */
  if ( $data["result"] == false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Create permissions data
   */
  $permissions = array ();
  if ( $parameters["administrator"] == "on")
  {
    $permissions["administrator"] = true;
  }
  if ( $parameters["auditor"] == "on")
  {
    $permissions["auditor"] = true;
  }

  /**
   * Add new user record
   */
  $salt = secure_rand ( 32);
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Users` (`Name`, `User`, `Pass`, `Permissions`, `Email`, `Since`, `Salt`, `Iterations`, `AvatarID`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["user"]) . "', '" . hash_pbkdf2 ( "sha256", $parameters["password"], $salt, ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000), 64) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $permissions)) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["email"]) . "', NOW(), '" . $_in["mysql"]["id"]->real_escape_string ( $salt) . "', " . $_in["mysql"]["id"]->real_escape_string ( ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000)) . ", '')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "users_add_post"))
  {
    framework_call ( "users_add_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Name" => $parameters["name"], "User" => $parameters["user"], "Password" => $parameters["password"], "Permissions" => $permissions, "Email" => $parameters["email"], "Salt" => $salt, "Iterations" => ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000));
  if ( framework_has_hook ( "users_add_audit"))
  {
    $audit = framework_call ( "users_add_audit", $parameters, false, $audit);
  }
  audit ( "user", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "users/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing user
 */
framework_add_hook ( "users_edit", "users_edit");
framework_add_permission ( "users_edit", __ ( "Edit users"));
framework_add_api_call ( "/users/:id", "Modify", "users_edit", array ( "permissions" => array ( "administrator", "users_edit")));
framework_add_api_call ( "/users/:id", "Edit", "users_edit", array ( "permissions" => array ( "administrator", "users_edit")));

/**
 * Function to edit an existing user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["name"])));
  if ( empty ( $parameters["name"]))
  {
    $data["result"] = false;
    $data["name"] = __ ( "The user name is required.");
  }
  $parameters["user"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["user"])));
  if ( empty ( $parameters["user"]))
  {
    $data["result"] = false;
    $data["user"] = __ ( "The user login name is required.");
  }
  $parameters["email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["email"])));
  if ( empty ( $parameters["email"]))
  {
    $data["result"] = false;
    $data["email"] = __ ( "The user e-mail is required.");
  }
  if ( ! empty ( $parameters["email"]) && ! validateEmail ( $parameters["email"]))
  {
    $data["result"] = false;
    $data["email"] = __ ( "The informed e-mail is invalid.");
  }
  if ( ! empty ( $parameters["password"]) && empty ( $parameters["confirmation"]))
  {
    $data["result"] = false;
    $data["confirmation"] = __ ( "The user confirmation password is required.");
  }
  if ( ! empty ( $parameters["password"]) && ! empty ( $parameters["confirmation"]) && $parameters["password"] != $parameters["confirmation"])
  {
    $data["result"] = false;
    $data["confirmation"] = __ ( "The passwords didn't match.");
  }

  /**
   * Check if user was already in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `User` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["user"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["result"] = false;
    $data["user"] = __ ( "The user login name is already in use.");
  }

  /**
   * Check if user exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $user = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "users_edit_sanitize"))
  {
    $data = framework_call ( "users_edit_sanitize", $parameters, false, $data);
  }

  /**
   * Return error data if some error ocurred
   */
  if ( $data["result"] == false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Create permissions data
   */
  $permissions = array ();
  if ( $parameters["administrator"] == "on")
  {
    $permissions["administrator"] = true;
  }
  if ( $parameters["auditor"] == "on")
  {
    $permissions["auditor"] = true;
  }

  /**
   * Change user record
   */
  $salt = secure_rand ( 32);
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Users` SET `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', `User` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["user"]) . "', `Permissions` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $permissions)) . "', `Email` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["email"]) . "'" . ( ! empty ( $parameters["password"]) ? ", `Pass` = '" . hash_pbkdf2 ( "sha256", $parameters["password"], $salt, ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000), 64) . "', `Salt` = '" . $_in["mysql"]["id"]->real_escape_string ( $salt) . "', `Iterations` = " . $_in["mysql"]["id"]->real_escape_string ( ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000)) : "") . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "users_edit_post"))
  {
    framework_call ( "users_edit_post", $parameters);
  }

  /**
   * Insert audit record
   */
  $audit = array ();
  $audit["ID"] = $user["ID"];
  if ( $user["Name"] != $parameters["name"])
  {
    $audit["Name"] = array ( "Old" => $user["Name"], "New" => $parameters["name"]);
  }
  if ( $user["User"] != $parameters["user"])
  {
    $audit["User"] = array ( "Old" => $user["User"], "New" => $parameters["user"]);
  }
  if ( ! array_compare ( json_decode ( $user["Permissions"], true), $permissions))
  {
    $audit["Permissions"] = array ( "Old" => json_decode ( $user["Permissions"], true), "New" => $permissions);
  }
  if ( $user["Email"] != $parameters["email"])
  {
    $audit["Email"] = array ( "Old" => $user["Email"], "New" => $parameters["email"]);
  }
  if ( ! empty ( $parameters["password"]))
  {
    $audit["Password"] = array ( "Old" => array ( "Password" => $user["Password"], "Salt" => $user["Salt"], "Iterations" => $user["Iterations"]), "New" => array ( "Password" => $parameters["password"], "Salt" => $salt, "Iterations" => ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000)));
  }
  if ( framework_has_hook ( "users_edit_audit"))
  {
    $audit = framework_call ( "users_edit_audit", $parameters, false, $audit);
  }
  audit ( "user", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a user
 */
framework_add_hook ( "users_remove", "users_remove");
framework_add_permission ( "users_remove", __ ( "Remove users"));
framework_add_api_call ( "/users/:id", "Delete", "users_remove", array ( "permissions" => array ( "administrator", "users_remove")));

/**
 * Function to remove an existing user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if are removing itself
   */
  if ( $parameters["id"] == $_in["session"]["id"])
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Check if user exists
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
   * Remove user database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Users` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "users_remove_post"))
  {
    framework_call ( "users_remove_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = $user;
  if ( framework_has_hook ( "users_remove_audit"))
  {
    $audit = framework_call ( "users_remove_audit", $parameters, false, $audit);
  }
  audit ( "user", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}
?>
