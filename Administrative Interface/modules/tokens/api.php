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
 * VoIP Domain tokens api module. This module add the api calls related to tokens.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Tokens
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch token permissions (datatables compatible response)
 */
framework_add_hook ( "tokens_permissions_fetch", "tokens_permissions_fetch");
framework_add_api_call ( "/tokens/permissions", "Read", "tokens_permissions_fetch", array ( "permissions" => array ( "user", "token")));

/**
 * Function to generate token permissions list to select box.
 *
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_permissions_fetch ( $buffer, $parameters)
{
  global $_api;

  /**
   * Format data
   */
  $data = array ();
  foreach ( $_api["permissions"] as $token => $description)
  {
    $data[] = array ( $token, $description);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch tokens listing
 */
framework_add_hook ( "tokens_fetch", "tokens_fetch");
framework_add_permission ( "tokens_fetch", __ ( "Request tokens list"));
framework_add_api_call ( "/tokens/fetch", "Read", "tokens_fetch", array ( "permissions" => array ( "administrator", "tokens_fetch")));

/**
 * Function to generate token list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Tokens");

  /**
   * Search tokens
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens`"))
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
    $data[] = array ( $result["ID"], $result["Description"], format_db_timestamp ( $result["Expire"]), format_db_date ( $result["Expire"]));
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get token information
 */
framework_add_hook ( "tokens_view", "tokens_view");
framework_add_permission ( "tokens_view", __ ( "View token informations"));
framework_add_api_call ( "/tokens/:id", "Read", "tokens_view", array ( "permissions" => array ( "administrator", "tokens_view")));

/**
 * Function to generate token informations.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_view ( $buffer, $parameters)
{
  global $_in, $_api;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Tokens");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search tokens
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $token = $result->fetch_assoc ();
  $permissions = explode ( ",", $token["Permissions"]);

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $token["Description"];
  $data["token"] = $token["Token"];
  $data["access"] = $token["Access"];
  $data["permissions"] = array ();
  foreach ( $permissions as $permission)
  {
    $data["permissions"][$permission] = $_api["permissions"][$permission];
  }
  $data["validity"] = format_db_date ( $token["Expire"]);
  $data["language"] = ( $token["Language"] != "" ? $token["Language"] : "default");

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new token
 */
framework_add_hook ( "tokens_add", "tokens_add");
framework_add_permission ( "tokens_add", __ ( "Add tokens"));
framework_add_api_call ( "/tokens", "Create", "tokens_add", array ( "permissions" => array ( "administrator", "tokens_add")));

/**
 * Function to add a new token.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_add ( $buffer, $parameters)
{
  global $_in, $_api;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["description"])));
  if ( empty ( $parameters["description"]))
  {
    $data["result"] = false;
    $data["description"] = __ ( "The token description is required.");
  }
  $parameters["token"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["token"])));
  if ( empty ( $parameters["token"]))
  {
    $data["result"] = false;
    $data["token"] = __ ( "The token is required.");
  }
  if ( ! array_key_exists ( "token", $data) && ! preg_match ( "/^[a-z0-9]{16}-[a-z0-9]{8}-[a-z0-9]{8}-[a-z0-9]{8}-[a-z0-9]{24}\$/", $parameters["token"]))
  {
    $data["result"] = false;
    $data["token"] = __ ( "The provided token is invalid.");
  }
  $parameters["access"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["access"])));
  if ( empty ( $parameters["access"]))
  {
    $data["result"] = false;
    $data["access"] = __ ( "The token access CIDR is required.");
  }
  if ( ! array_key_exists ( "access", $data) && inet_pton ( substr ( $parameters["access"], 0, strpos ( $parameters["access"], "/"))) === false)
  {
    $data["result"] = false;
    $data["access"] = __ ( "The access CIDR provided is invalid.");
  }
  if ( ! is_array ( $parameters["permissions"]))
  {
    if ( ! empty ( $parameters["permissions"]))
    {
      $parameters["permissions"] = array ( $parameters["permissions"]);
    } else {
      $parameters["permissions"] = array ();
    }
  }
  foreach ( $parameters["permissions"] as $key => $value)
  {
    if ( ! array_key_exists ( $value, $_api["permissions"]))
    {
      $data["result"] = false;
      $data["permissions"] = __ ( "Invalid permission.");
    }
  }
  if ( ! array_key_exists ( "permissions", $data) && sizeof ( $parameters["permissions"]) == 0)
  {
    $data["result"] = false;
    $data["permissions"] = __ ( "At least one permission is required.");
  }
  if ( empty ( $parameters["validity"]))
  {
    $data["result"] = false;
    $data["validity"] = __ ( "The token validity is required.");
  }
  $parameters["validity"] = format_form_datetime ( empty ( $parameters["validity"]) ? date ( "d/m/Y 23:59", time ()) : urldecode ( $parameters["validity"]));

  /**
   * Check if token was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `Token` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["token"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["result"] = false;
    $data["token"] = __ ( "The provided token is already in use.");
  }
  if ( $parameters["language"] == "default")
  {
    $parameters["language"] = "";
  } else {
    if ( ! array_key_exists ( $parameters["language"], $_in["languages"]))
    {
      $data["result"] = false;
      $data["language"] = __ ( "The select language are invalid.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "tokens_add_sanitize"))
  {
    $data = framework_call ( "tokens_add_sanitize", $parameters, false, $data);
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
   * Call add pre hook, if exist
   */
  if ( framework_has_hook ( "tokens_add_pre"))
  {
    $parameters = framework_call ( "tokens_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new token record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Tokens` (`Description`, `Token`, `Access`, `Permissions`, `Expire`, `Language`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["token"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["access"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $parameters["permissions"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["validity"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["language"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "tokens_add_post"))
  {
    framework_call ( "tokens_add_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Token" => $parameters["token"], "Access" => $parameters["access"], "Permissions" => $parameters["permissions"], "Expire" => $parameters["validity"], "Language" => $parameters["language"]);
  if ( framework_has_hook ( "tokens_add_audit"))
  {
    $audit = framework_call ( "tokens_add_audit", $parameters, false, $audit);
  }
  audit ( "token", "add", $audit);

  /**
   * Return OK to token
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "tokens/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing token
 */
framework_add_hook ( "tokens_edit", "tokens_edit");
framework_add_permission ( "tokens_edit", __ ( "Edit tokens"));
framework_add_api_call ( "/tokens/:id", "Modify", "tokens_edit", array ( "permissions" => array ( "administrator", "tokens_edit")));
framework_add_api_call ( "/tokens/:id", "Edit", "tokens_edit", array ( "permissions" => array ( "administrator", "tokens_edit")));

/**
 * Function to edit an existing token.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_edit ( $buffer, $parameters)
{
  global $_in, $_api;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["description"])));
  if ( empty ( $parameters["description"]))
  {
    $data["result"] = false;
    $data["description"] = __ ( "The token description is required.");
  }
  $parameters["token"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["token"])));
  if ( empty ( $parameters["token"]))
  {
    $data["result"] = false;
    $data["token"] = __ ( "The token is required.");
  }
  if ( ! array_key_exists ( "token", $data) && ! preg_match ( "/^[a-z0-9]{16}-[a-z0-9]{8}-[a-z0-9]{8}-[a-z0-9]{8}-[a-z0-9]{24}\$/", $parameters["token"]))
  {
    $data["result"] = false;
    $data["token"] = __ ( "The provided token is invalid.");
  }
  $parameters["access"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["access"])));
  if ( empty ( $parameters["access"]))
  {
    $data["result"] = false;
    $data["access"] = __ ( "The token access CIDR is required.");
  }
  if ( ! array_key_exists ( "access", $data) && inet_pton ( substr ( $parameters["access"], 0, strpos ( $parameters["access"], "/"))) === false)
  {
    $data["result"] = false;
    $data["access"] = __ ( "The access CIDR provided is invalid.");
  }
  if ( ! is_array ( $parameters["permissions"]))
  {
    if ( ! empty ( $parameters["permissions"]))
    {
      $parameters["permissions"] = array ( $parameters["permissions"]);
    } else {
      $parameters["permissions"] = array ();
    }
  }
  foreach ( $parameters["permissions"] as $key => $value)
  {
    if ( ! array_key_exists ( $value, $_api["permissions"]))
    {
      $data["result"] = false;
      $data["permissions"] = __ ( "Invalid permission.");
    }
  }
  if ( ! array_key_exists ( "permissions", $data) && sizeof ( $parameters["permissions"]) == 0)
  {
    $data["result"] = false;
    $data["permissions"] = __ ( "At least one permission is required.");
  }
  if ( empty ( $parameters["validity"]))
  {
    $data["result"] = false;
    $data["validity"] = __ ( "The token validity is required.");
  }
  $parameters["validity"] = format_form_datetime ( empty ( $parameters["validity"]) ? date ( "d/m/Y 23:59", time ()) : urldecode ( $parameters["validity"]));
  if ( $parameters["language"] == "default")
  {
    $parameters["language"] = "";
  } else {
    if ( ! array_key_exists ( $parameters["language"], $_in["languages"]))
    {
      $data["result"] = false;
      $data["language"] = __ ( "The select language are invalid.");
    }
  }

  /**
   * Check if token was already in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `Token` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["token"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["result"] = false;
    $data["token"] = __ ( "The provided token is already in use.");
  }

  /**
   * Check if token exist (could be removed by other token meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $token = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "tokens_edit_sanitize"))
  {
    $data = framework_call ( "tokens_edit_sanitize", $parameters, false, $data);
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
   * Call edit pre hook, if exist
   */
  if ( framework_has_hook ( "tokens_edit_pre"))
  {
    $parameters = framework_call ( "tokens_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change token record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Tokens` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Token` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["token"]) . "', `Access` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["access"]) . "', `Permissions` = '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $parameters["permissions"])) . "', `Expire` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["validity"]) . "', `Language` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["language"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "tokens_edit_post"))
  {
    framework_call ( "tokens_edit_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $token["ID"];
  if ( $token["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $token["Description"], "New" => $parameters["description"]);
  }
  if ( $token["Token"] != $parameters["token"])
  {
    $audit["Token"] = array ( "Old" => $token["Token"], "New" => $parameters["token"]);
  }
  if ( $token["Access"] != $parameters["access"])
  {
    $audit["Access"] = array ( "Old" => $token["Access"], "New" => $parameters["access"]);
  }
  if ( ! array_compare ( explode ( ",", $token["Permissions"]), $parameters["permissions"]))
  {
    $audit["Permissions"] = array ( "Old" => explode ( ",", $token["Permissions"]), "New" => $parameters["permissions"]);
  }
  if ( $token["Expire"] != $parameters["validity"])
  {
    $audit["Expire"] = array ( "Old" => $token["Expire"], "New" => $parameters["validity"]);
  }
  if ( $token["Language"] != $parameters["language"])
  {
    $audit["Language"] = array ( "Old" => $token["Language"], "New" => $parameters["language"]);
  }
  if ( framework_has_hook ( "tokens_edit_audit"))
  {
    $audit = framework_call ( "tokens_edit_audit", $parameters, false, $audit);
  }
  audit ( "token", "edit", $audit);

  /**
   * Return OK to token
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a token
 */
framework_add_hook ( "tokens_remove", "tokens_remove");
framework_add_permission ( "tokens_remove", __ ( "Remove tokens"));
framework_add_api_call ( "/tokens/:id", "Delete", "tokens_remove", array ( "permissions" => array ( "administrator", "tokens_remove")));

/**
 * Function to remove an existing token.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if token exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $token = $result->fetch_assoc ();

  /**
   * Call remove pre hook, if exist
   */
  if ( framework_has_hook ( "tokens_remove_pre"))
  {
    $parameters = framework_call ( "tokens_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove token database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Tokens` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "tokens_remove_post"))
  {
    framework_call ( "tokens_remove_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = $token;
  if ( framework_has_hook ( "tokens_remove_audit"))
  {
    $audit = framework_call ( "tokens_remove_audit", $parameters, false, $audit);
  }
  audit ( "token", "remove", $audit);

  /**
   * Retorn OK to token
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}
?>
