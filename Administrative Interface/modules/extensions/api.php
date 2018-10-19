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
 * VoIP Domain extensions api module. This module add the api calls related to
 * extensions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search extensions
 */
framework_add_hook ( "extensions_search", "extensions_search");
framework_add_permission ( "extensions_search", __ ( "Search extensions (select list standard)"));
framework_add_api_call ( "/extensions/search", "Read", "extensions_search", array ( "permissions" => array ( "user", "extensions_search")));

/**
 * Function to generate extension list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Extensions");

  /**
   * Search extensions
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` " . ( ! empty ( $parameters["q"]) ? "WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' OR `Extension` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "' " : "") . "ORDER BY `Name`, `Extension`"))
  {
    while ( $extension = $result->fetch_assoc ())
    {
      $data[] = array ( $extension["ID"], $extension["Name"] . " (" . $extension["Extension"] . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to search extensions (excluding myself)
 */
framework_add_hook ( "extensions_search_except", "extensions_search_except");
framework_add_api_call ( "/extensions/search/except/:id", "Read", "extensions_search_except", array ( "permissions" => array ( "user", "extensions_search")));

/**
 * Function to generate extension list (excluding myself) to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_search_except ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Extensions");

  /**
   * Search extensions
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` != " . (int) $parameters["id"] . ( ! empty ( $parameters["q"]) ? " AND (`Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' OR `Extension` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "')" : "") . " ORDER BY `Name`, `Extension`"))
  {
    while ( $extension = $result->fetch_assoc ())
    {
      $data[] = array ( $extension["ID"], $extension["Name"] . " (" . $extension["Extension"] . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch extensions listing
 */
framework_add_hook ( "extensions_fetch", "extensions_fetch");
framework_add_permission ( "extensions_fetch", __ ( "Request extensions listing"));
framework_add_api_call ( "/extensions/fetch", "Read", "extensions_fetch", array ( "permissions" => array ( "user", "extensions_fetch")));

/**
 * Function to generate extension list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Extensions", "Ranges", "Servers", "Groups"));

  /**
   * Search extensions
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Extension`, `Extensions`.`Name`, `Servers`.`Name` As `Server`, `Groups`.`Description` AS `Group` FROM `Extensions` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID` LEFT JOIN `Groups` ON `Extensions`.`Group` = `Groups`.`ID` ORDER BY `Name`, `Extension`"))
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
    $data[] = array ( $result["ID"], $result["Extension"], $result["Name"], $result["Server"], $result["Group"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get extension information
 */
framework_add_hook ( "extensions_view", "extensions_view");
framework_add_permission ( "extensions_view", __ ( "View extensions informations"));
framework_add_api_call ( "/extensions/:id", "Read", "extensions_view", array ( "permissions" => array ( "user", "extensions_view")));

/**
 * Function to generate extension informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Extensions", "Groups", "CostCenters", "Accounts", "Phones", "Equipments"));

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.*, `Groups`.`Description` AS `GroupName`, CONCAT(`CostCenters`.`Description`, ' (', `CostCenters`.`Code`, ')') AS `CostCenterName` FROM `Extensions` INNER JOIN `Groups` ON `Extensions`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . " AND `Extensions`.`Group` = `Groups`.`ID` LEFT JOIN `CostCenters` ON `Extensions`.`CostCenter` = `CostCenters`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension = $result->fetch_assoc ();
  $perms = json_decode ( $extension["Permissions"], true);

  /**
   * Search capture groups for the extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionCapture`.`Group`, `Groups`.`ID`, `Groups`.`Description`, `Groups`.`Code` FROM `ExtensionCapture` LEFT JOIN `Groups` ON `ExtensionCapture`.`Group` = `Groups`.`ID` WHERE `ExtensionCapture`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $captures = array ();
  while ( $capture = $result->fetch_assoc ())
  {
    $captures[$capture["ID"]] = $capture["Description"];
  }

  /**
   * Search transhipments for the extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Name`, `Extensions`.`Extension` FROM `ExtensionTranshipment` LEFT JOIN `Extensions` ON `ExtensionTranshipment`.`Transhipment` = `Extensions`.`ID`  WHERE `ExtensionTranshipment`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $transhipments = array ();
  while ( $transhipment = $result->fetch_assoc ())
  {
    $transhipments[$transhipment["ID"]] = $transhipment["Name"] . " (" . $transhipment["Extension"] . ")";
  }

  /**
   * Search hints for the extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Name`, `Extensions`.`Extension` FROM `ExtensionHint` LEFT JOIN `Extensions` ON `ExtensionHint`.`Hint` = `Extensions`.`ID`  WHERE `ExtensionHint`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $hints = array ();
  while ( $hint = $result->fetch_assoc ())
  {
    $hints[$hint["ID"]] = $hint["Name"] . " (" . $hint["Extension"] . ")";
  }

  /**
   * Search accounts for the extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Accounts`.*, `Equipments`.`ID` AS `EID`, `Equipments`.`Name`, `Equipments`.`Template`, `Equipments`.`Shortcuts`, `Equipments`.`Extensions`, `Phones`.`MAC` FROM `Accounts` LEFT JOIN `Phones` ON `Accounts`.`Phone` = `Phones`.`ID` LEFT JOIN `Equipments` ON `Phones`.`Type` = `Equipments`.`ID` WHERE `Accounts`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . " ORDER BY `Accounts`.`Username`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $accounts = array ();
  while ( $account = $result->fetch_assoc ())
  {
    if ( $account["Template"] == "softphone")
    {
      $accounts[] = array ( "type" => $account["EID"], "typename" => $account["Name"], "id" => $account["ID"], "username" => $account["Username"], "password" => $account["Password"]);
    } else {
      $accounts[] = array ( "type" => $account["EID"], "typename" => $account["Name"], "mac" => ( $account["MAC"] != "" ? substr ( $account["MAC"], 0, 2) . ":" . substr ( $account["MAC"], 2, 2) . ":" . substr ( $account["MAC"], 4, 2) . ":" . substr ( $account["MAC"], 6, 2) . ":" . substr ( $account["MAC"], 8, 2) . ":" . substr ( $account["MAC"], 10, 2) : ""));
    }
  }

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["extension"] = $extension["Extension"];
  $data["name"] = $extension["Name"];
  $data["email"] = $extension["Email"];
  $data["group"] = $extension["Group"];
  $data["groupname"] = $extension["GroupName"];
  $data["captures"] = $captures;
  $data["perms"] = array ();
  $data["perms"]["mobile"] = $perms["mobile"];
  $data["perms"]["longdistance"] = $perms["longdistance"];
  $data["perms"]["international"] = $perms["international"];
  $data["perms"]["nopass"] = $perms["nopass"];
  $data["voicemail"] = $perms["voicemail"];
  $data["voicemailpass"] = $extension["Password"];
  $data["transhipments"] = $transhipments;
  $data["accounts"] = $accounts;
  $data["costcenter"] = $extension["CostCenter"];
  $data["costcentername"] = $extension["CostCenterName"];
  $data["monitor"] = $perms["monitor"];
  $data["volrx"] = $perms["volrx"];
  $data["voltx"] = $perms["voltx"];
  $data["hints"] = $hints;

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get extension information
 */
framework_add_hook ( "extensions_account_view", "extensions_account_view");
framework_add_permission ( "extensions_account_view", __ ( "View extensions accounts informations"));
framework_add_api_call ( "/extensions/account/:id", "Read", "extensions_account_view", array ( "permissions" => array ( "user", "extensions_account_view")));

/**
 * Function to generate extension account informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_account_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Extensions", "Accounts", "Ranges", "Servers"));

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Servers`.`Domain`, `Servers`.`Address`, `Extensions`.`Name`, `Accounts`.`Username`, `Accounts`.`Password` FROM `Extensions` LEFT JOIN `Accounts` ON `Accounts`.`Extension` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Ranges`.`ID` = `Extensions`.`Range` LEFT JOIN `Servers` ON `Servers`.`ID` = `Ranges`.`Server` WHERE `Accounts`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["domain"] = $extension["Domain"];
  $data["serverip"] = $extension["Address"];
  $data["name"] = $extension["Name"];
  $data["username"] = $extension["Username"];
  $data["password"] = $extension["Password"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new extension
 */
framework_add_hook ( "extensions_add", "extensions_add");
framework_add_permission ( "extensions_add", __ ( "Add extensions"));
framework_add_api_call ( "/extensions", "Create", "extensions_add", array ( "permissions" => array ( "user", "extensions_add")));

/**
 * Function to add a new extension.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanitize incoming data
   */
  $data = array ();
  $data["result"] = true;
  if ( $parameters["extension"] != (int) $parameters["extension"])
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The informed extension is invalid.");
  }
  $parameters["extension"] = (int) $parameters["extension"];
  if ( empty ( $parameters["extension"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The extension is required.");
  }
  $parameters["name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["name"])));
  if ( empty ( $parameters["name"]))
  {
    $data["result"] = false;
    $data["name"] = __ ( "The name is required.");
  }
  if ( $parameters["group"] != (int) $parameters["group"])
  {
    $data["result"] = false;
    $data["group"] = __ ( "The selected group is invalid.");
  }
  $parameters["group"] = (int) $parameters["group"];
  if ( empty ( $parameters["group"]))
  {
    $data["result"] = false;
    $data["group"] = __ ( "The group is required.");
  }
  if ( ! is_array ( $parameters["capture"]))
  {
    if ( ! empty ( $parameters["capture"]))
    {
      $parameters["capture"] = array ( $parameters["capture"]);
    } else {
      $parameters["capture"] = array ();
    }
  }
  foreach ( $parameters["capture"] as $key => $value)
  {
    $parameters["capture"][$key] = (int) $value;
  }
  if ( sizeof ( $parameters["capture"]) == 0)
  {
    $data["result"] = false;
    $data["capture"] = __ ( "At least one capture group is required.");
  }
  $parameters["email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["email"])));
  if ( ! empty ( $parameters["email"]) && ! validateEmail ( $parameters["email"]))
  {
    $data["result"] = false;
    $data["email"] = __ ( "The informed email is invalid.");
  }
  if ( $parameters["voicemail"] == "on" && empty ( $parameters["email"]))
  {
    $data["result"] = false;
    $data["email"] = __ ( "The email is required when voice mail selected.");
  }
  if ( empty ( $parameters["voicemailpass"]))
  {
    $data["result"] = false;
    $data["voicemailpass"] = __ ( "The password is required.");
  } else {
    $parameters["voicemailpass"] = preg_replace ( "/[^0-9]/", "", $parameters["voicemailpass"]);
    if ( strlen ( $parameters["voicemailpass"]) != 6)
    {
      $data["result"] = false;
      $data["voicemailpass"] = __ ( "The password must have 6 digits.");
    }
  }
  if ( ! is_array ( $parameters["transhipments"]))
  {
    if ( ! empty ( $parameters["transhipments"]))
    {
      $parameters["transhipments"] = array ( $parameters["transhipments"]);
    } else {
      $parameters["transhipments"] = array ();
    }
  }
  foreach ( $parameters["transhipments"] as $key => $value)
  {
    $parameters["transhipments"][$key] = (int) $value;
  }
  if ( ! is_array ( $parameters["hints"]))
  {
    if ( ! empty ( $parameters["hints"]))
    {
      $parameters["hints"] = array ( $parameters["hints"]);
    } else {
      $parameters["hints"] = array ();
    }
  }
  foreach ( $parameters["hints"] as $key => $value)
  {
    $parameters["hints"][$key] = (int) $value;
  }

  /**
   * Check if extension number was already in use
   */
  if ( ! array_key_exists ( "extension", $data))
  {
    $check = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
    if ( sizeof ( $check) != 0)
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension was already in use.");
    }
  }

  /**
   * Check if extension number is inside a valid range
   */
  if ( ! array_key_exists ( "extension", $data))
  {
    $range = filters_call ( "search_range", array ( "number" => $parameters["extension"]));
    if ( sizeof ( $range) == 0)
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension is not inside a valid system range.");
    }
  }

  /**
   * Check if provided group exists
   */
  if ( ! array_key_exists ( "group", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["group"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $data["result"] = false;
      $data["group"] = __ ( "The informed group is invalid.");
    } else {
      $group = $result->fetch_assoc ();
    }
  }

  /**
   * Check if capture groups exists
   */
  if ( ! array_key_exists ( "capture", $data))
  {
    foreach ( $parameters["capture"] as $capture)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $capture)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 0)
      {
        $data["result"] = false;
        $data["capture"] = __ ( "One or more informed capture groups are invalid.");
        break;
      }
    }
  }

  /**
   * Check if transhipment extensions exists
   */
  $transhipments = array ();
  foreach ( $parameters["transhipments"] as $transhipment)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $transhipment)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $data["result"] = false;
      $data["transhipments"] = __ ( "One or more informed transhipment are invalid.");
    } else {
      $tmp = $result->fetch_assoc ();
      $transhipments[] = $tmp["Extension"];
    }
  }

  /**
   * Check if hint extensions exists
   */
  $hints = array ();
  foreach ( $parameters["hints"] as $hint)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $data["result"] = false;
      $data["hints"] = __ ( "One or more informed hint are invalid.");
    } else {
      $tmp = $result->fetch_assoc ();
      $hints[] = $tmp["Extension"];
    }
  }

  /**
   * Validate each account
   */
  $accounts = array ();
  foreach ( $parameters as $variable => $value)
  {
    if ( preg_match ( "/^account_[0-9]+_type\$/", $variable))
    {
      $id = (int) preg_replace ( "/[^0-9]/", "", $variable);
      if ( empty ( $parameters["account_" . $id . "_type"]))
      {
        $data["result"] = false;
        $data["account_" . $id . "_type"] = __ ( "Please select the equipment type.");
        continue;
      }
      $parameters["account_" . $id . "_type"] = (int) $parameters["account_" . $id . "_type"];
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["account_" . $id . "_type"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 0)
      {
        $data["result"] = false;
        $data["account_" . $id . "_type"] = __ ( "Equipment type is invalid.");
        continue;
      }
      $equipment = $result->fetch_assoc ();
      $parameters["account_" . $id . "_template"] = $equipment["Template"];
      if ( $equipment["AP"] == "Y")
      {
        if ( empty ( $parameters["account_" . $id . "_mac"]))
        {
          $data["result"] = false;
          $data["account_" . $id . "_mac"] = __ ( "Please inform the equipment MAC address.");
          continue;
        }
        $parameters["account_" . $id . "_mac"] = preg_replace ( "/[^0-9A-F]/", "", strtoupper ( $parameters["account_" . $id . "_mac"]));
        if ( strlen ( $parameters["account_" . $id . "_mac"]) != 12)
        {
          $data["result"] = false;
          $data["account_" . $id . "_mac"] = __ ( "Invalid MAC address.");
          continue;
        }
        if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Phones` WHERE `Type` = " . $_in["mysql"]["id"]->real_escape_string ( $equipment["ID"]) . " AND `MAC` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["account_" . $id . "_mac"]) . "'"))
        {
          header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
          exit ();
        }
        if ( $result->num_rows != 0)
        {
          $data["result"] = false;
          $data["account_" . $id . "_mac"] = __ ( "Equipment already in use.");
          continue;
        }
        $phone = $result->fetch_assoc ();
      } else {
        if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Phones` WHERE `Type` = " . $_in["mysql"]["id"]->real_escape_string ( $equipment["ID"])))
        {
          header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
          exit ();
        }
        if ( $result->num_rows == 0)
        {
          $phone = array ( "ID" => "", "Template" => $equipment["Template"], "Description" => $equipment["Description"]);
        } else {
          $phone = $result->fetch_assoc ();
        }
        $parameters["account_" . $id . "_mac"] = "";
      }
      $accounts[] = array ( "username" => "u" . $parameters["extension"] . "-" . sizeof ( $accounts), "password" => randomPassword ( 8), "equipment" => $parameters["account_" . $id . "_type"], "mac" => $parameters["account_" . $id . "_mac"], "phone" => $phone["ID"], "template" => $phone["Template"], "description" => $phone["Description"]);
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "extensions_add_sanitize"))
  {
    $data = framework_call ( "extensions_add_sanitize", $parameters, false, $data);
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
  $permissions = array (
    "mobile" => $parameters["perm_mobile"] == "on",
    "longdistance" => $parameters["perm_longdistance"] == "on",
    "international" => $parameters["perm_international"] == "on",
    "nopass" => $parameters["perm_nopass"] == "on",
    "voicemail" => $parameters["voicemail"] == "on",
    "monitor" => $parameters["monitor"] == "on",
    "volrx" => (int) ( $parameters["volrx"] >= -10 && $parameters["volrx"] <= 10 ? $parameters["volrx"] : 0),
    "voltx" => (int) ( $parameters["voltx"] >= -10 && $parameters["voltx"] <= 10 ? $parameters["voltx"] : 0)
  );

  /**
   * Call add pre hook, if exist
   */
  if ( framework_has_hook ( "extensions_add_pre"))
  {
    $parameters = framework_call ( "extensions_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new extension record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Extensions` (`Extension`, `Name`, `NameFon`, `Email`, `Range`, `Group`, `Password`, `Permissions`, `CostCenter`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["extension"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( fonetiza ( $parameters["name"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["email"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $range["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $group["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["voicemailpass"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $permissions)) . "', " . $_in["mysql"]["id"]->real_escape_string ( ( $parameters["costcenter"] != "" ? $parameters["costcenter"] : "null")) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Add each extension capture group
   */
  foreach ( $parameters["capture"] as $capture)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionCapture` (`Extension`, `Group`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $capture) . ")"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Add each extension transhipment extensions
   */
  foreach ( $parameters["transhipments"] as $transhipment)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionTranshipment` (`Extension`, `Transhipment`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $transhipment) . ")"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Add each extension hint extensions
   */
  foreach ( $parameters["hints"] as $hint)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionHint` (`Extension`, `Hint`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $hint) . ")"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Add each extension account
   */
  $id = 0;
  foreach ( $accounts as $account)
  {
    /**
     * If phone ID is empty, add it
     */
    if ( $account["phone"] == "")
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Phones` (`Type`, `MAC`, `Template`, `Description`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $account["equipment"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $account["mac"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $account["template"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $account["description"]) . "')"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $account["phone"] = $_in["mysql"]["id"]->insert_id;
    }

    /**
     * Add account
     */
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Accounts` (`Extension`, `Username`, `Password`, `Phone`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $account["username"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $account["password"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $account["phone"]) . ")"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }

    /**
     * Add new extension at Asterisk servers
     */
    $notify = array ( "Extension" => $parameters["extension"], "UID" => $parameters["id"], "Username" => $account["username"], "Password" => $account["password"], "Group" => $group["Code"], "Capture" => $parameters["capture"], "Name" => $parameters["name"], "Template" => $account["template"], "Transhipments" => $parameters["transhipments"], "CostCenter" => ( $parameters["costcenter"] != "" ? $parameters["costcenter"] : $group["CostCenter"]), "PhonePass" => $parameters["voicemailpass"], "Permissions" => $permissions);
    if ( framework_has_hook ( "extensions_add_notify"))
    {
      $notify = framework_call ( "extensions_add_notify", $parameters, false, $notify);
    }
    notify_server ( $range["Server"], "createextension", $notify);

    /**
     * Add auto provisioning file if supported
     */
    if ( ! empty ( $account["mac"]))
    {
      $notify = array ( "Extension" => $parameters["extension"], "Username" => $account["username"], "Password" => $account["password"], "Name" => $parameters["name"], "MAC" => $account["mac"], "Template" => $account["template"], "Domain" => $range["Domain"]);
      if ( framework_has_hook ( "extensions_add_ap_notify"))
      {
        $notify = framework_call ( "extensions_add_ap_notify", $parameters, false, $notify);
      }
      notify_server ( $range["Server"], "createap", $notify);
    }
  }

  /**
   * Create voicemail configuration if requested
   */
  if ( $parameters["voicemail"] == "on")
  {
    $notify = array ( "Extension" => $parameters["extension"], "Name" => $parameters["name"], "Password" => $parameters["voicemailpass"], "Email" => $parameters["email"]);
    if ( framework_has_hook ( "extensions_add_voicemail_notify"))
    {
      $notify = framework_call ( "extensions_add_voicemail_notify", $parameters, false, $notify);
    }
    notify_server ( $range["Server"], "createvoicemail", $notify);
  }

  /**
   * Create hint configuration if needed
   */
  foreach ( $parameters["hints"] as $hint)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension`, COUNT(*) AS `Total` FROM `ExtensionHint` LEFT JOIN `Extensions` ON `ExtensionHint`.`Hint` = `Extensions`.`ID` WHERE `ExtensionHint`.`Hint` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $tmp = $result->fetch_assoc ();
    if ( $tmp["Total"] == 1)
    {
      $notify = array ( "Extension" => $tmp["Extension"]);
      if ( framework_has_hook ( "extensions_add_hint_notify"))
      {
        $notify = framework_call ( "extensions_add_hint_notify", $parameters, false, $notify);
      }
      notify_server ( $range["Server"], "createhint", $notify);
    }
  }

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "extensions_add_post"))
  {
    framework_call ( "extensions_add_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Range" => $range["ID"], "Extension" => $parameters["extension"], "Name" => $parameters["name"], "Email" => $parameters["email"], "Group" => $group["ID"], "Password" => $parameters["voicemailpass"], "Permissions" => $permissions, "CostCenter" => ( $parameters["costcenter"] != "" ? $parameters["costcenter"] : ""), "Transhipments" => $parameters["transhipments"], "Capture" => $parameters["capture"], "Accounts" => $accounts, "Hints" => $parameters["hints"]);
  if ( framework_has_hook ( "extensions_add_audit"))
  {
    $audit = framework_call ( "extensions_add_audit", $parameters, false, $audit);
  }
  audit ( "extension", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "extensions/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing extension
 */
framework_add_hook ( "extensions_edit", "extensions_edit");
framework_add_permission ( "extensions_edit", __ ( "Edit extensions"));
framework_add_api_call ( "/extensions/:id", "Modify", "extensions_edit", array ( "permissions" => array ( "user", "extensions_edit")));
framework_add_api_call ( "/extensions/:id", "Edit", "extensions_edit", array ( "permissions" => array ( "user", "extensions_edit")));

/**
 * Function to edit an existing extension.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanitize incoming data
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  if ( $parameters["extension"] != (int) $parameters["extension"])
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The informed extension is invalid.");
  }
  $parameters["extension"] = (int) $parameters["extension"];
  if ( empty ( $parameters["extension"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The extension is required.");
  }
  $parameters["name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["name"])));
  if ( empty ( $parameters["name"]))
  {
    $data["result"] = false;
    $data["name"] = __ ( "The name is required.");
  }
  if ( $parameters["group"] != (int) $parameters["group"])
  {
    $data["result"] = false;
    $data["group"] = __ ( "The selected group is invalid.");
  }
  $parameters["group"] = (int) $parameters["group"];
  if ( empty ( $parameters["group"]))
  {
    $data["result"] = false;
    $data["group"] = __ ( "The group is required.");
  }
  if ( ! is_array ( $parameters["capture"]))
  {
    if ( ! empty ( $parameters["capture"]))
    {
      $parameters["capture"] = array ( $parameters["capture"]);
    } else {
      $parameters["capture"] = array ();
    }
  }
  foreach ( $parameters["capture"] as $key => $value)
  {
    $parameters["capture"][$key] = (int) $value;
  }
  if ( sizeof ( $parameters["capture"]) == 0)
  {
    $data["result"] = false;
    $data["capture"] = __ ( "At least one capture group is required.");
  }
  $parameters["email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["email"])));
  if ( ! empty ( $parameters["email"]) && ! validateEmail ( $parameters["email"]))
  {
    $data["result"] = false;
    $data["email"] = __ ( "The informed email is invalid.");
  }
  if ( $parameters["voicemail"] == "on" && empty ( $parameters["email"]))
  {
    $data["result"] = false;
    $data["email"] = __ ( "The email is required when voice mail selected.");
  }
  if ( empty ( $parameters["voicemailpass"]))
  {
    $data["result"] = false;
    $data["voicemailpass"] = __ ( "The password is required.");
  } else {
    $parameters["voicemailpass"] = preg_replace ( "/[^0-9]/", "", $parameters["voicemailpass"]);
    if ( strlen ( $parameters["voicemailpass"]) != 6)
    {
      $data["result"] = false;
      $data["voicemailpass"] = __ ( "The password must have 6 digits.");
    }
  }
  if ( ! is_array ( $parameters["transhipments"]))
  {
    if ( ! empty ( $parameters["transhipments"]))
    {
      $parameters["transhipments"] = array ( $parameters["transhipments"]);
    } else {
      $parameters["transhipments"] = array ();
    }
  }
  foreach ( $parameters["transhipments"] as $key => $value)
  {
    $parameters["transhipments"][$key] = (int) $value;
  }
  if ( ! is_array ( $parameters["hints"]))
  {
    if ( ! empty ( $parameters["hints"]))
    {
      $parameters["hints"] = array ( $parameters["hints"]);
    } else {
      $parameters["hints"] = array ();
    }
  }
  foreach ( $parameters["hints"] as $key => $value)
  {
    $parameters["hints"][$key] = (int) $value;
  }

  /**
   * Get actual extension from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension = $result->fetch_assoc ();
  $extension["Permissions"] = json_decode ( $extension["Permissions"], true);

  /**
   * Get actual extension range from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Ranges`.*, `Servers`.`Domain` FROM `Ranges` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID` WHERE `Ranges`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["Range"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $oldrange = $result->fetch_assoc ();

  /**
   * Get each actual accounts from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Accounts` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $oldaccounts = array ();
  while ( $account = $result->fetch_assoc ())
  {
    $oldaccounts[] = $account;
  }

  /**
   * Get each actual transhipments from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionTranshipment` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $oldtranshipments = array ();
  while ( $transhipment = $result->fetch_assoc ())
  {
    $oldtranshipments[] = $transhipment["Transhipment"];
  }

  /**
   * Get each actual hints from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionHint` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $oldhints = array ();
  while ( $hint = $result->fetch_assoc ())
  {
    $oldhints[] = $hint["Hint"];
  }

  /**
   * Get each actual capture groups from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionCapture` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $oldcapture = array ();
  while ( $capture = $result->fetch_assoc ())
  {
    $oldcapture[] = $capture["Group"];
  }

  /**
   * If extension number changed, check if was already in use
   */
  if ( $extension["Extension"] != $parameters["extension"] && ! array_key_exists ( "extension", $data))
  {
    $check = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
    if ( sizeof ( $check) != 0)
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension was already in use.");
    }
  }

  /**
   * Check if extension is inside a valid range
   */
  if ( ! array_key_exists ( "extension", $data))
  {
    $range = filters_call ( "search_range", array ( "number" => $parameters["extension"]));
    if ( sizeof ( $range) == 0)
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension is not inside a valid system range.");
    }
  }

  /**
   * Check if provided group exists
   */
  if ( ! array_key_exists ( "group", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["group"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $data["result"] = false;
      $data["group"] = __ ( "The informed group is invalid.");
    } else {
      $group = $result->fetch_assoc ();
    }
  }

  /**
   * Check if capture groups exists
   */
  if ( ! array_key_exists ( "capture", $data))
  {
    foreach ( $parameters["capture"] as $capture)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $capture)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 0)
      {
        $data["result"] = false;
        $data["capture"] = __ ( "One or more informed capture groups are invalid.");
        break;
      }
    }
  }

  /**
   * Check if transhipment extensions exists
   */
  $transhipments = array ();
  foreach ( $parameters["transhipments"] as $transhipment)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $transhipment)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0 || $transhipment == $extension["ID"])
    {
      $data["result"] = false;
      $data["transhipments"] = __ ( "One or more informed transhipment are invalid.");
    } else {
      $tmp = $result->fetch_assoc ();
      $transhipments[] = $tmp["Extension"];
    }
  }

  /**
   * Check if hint extensions exists
   */
  $hints = array ();
  foreach ( $parameters["hints"] as $hint)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0 || $hint == $extension["ID"])
    {
      $data["result"] = false;
      $data["hints"] = __ ( "One or more informed hint are invalid.");
    } else {
      $tmp = $result->fetch_assoc ();
      $hints[] = $tmp["Extension"];
    }
  }

  /**
   * Validate each account
   */
  $accounts = array ();
  foreach ( $parameters as $variable => $value)
  {
    if ( preg_match ( "/^account_[0-9]+_type\$/", $variable))
    {
      $id = preg_replace ( "/[^0-9]/", "", $variable);
      if ( empty ( $parameters["account_" . $id . "_type"]))
      {
        $data["result"] = false;
        $data["account_" . $id . "_type"] = __ ( "Please select the equipment type.");
        continue;
      }
      $parameters["account_" . $id . "_type"] = (int) $parameters["account_" . $id . "_type"];
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["account_" . $id . "_type"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 0)
      {
        $data["result"] = false;
        $data["account_" . $id . "_type"] = __ ( "Equipment type is invalid.");
        continue;
      }
      $equipment = $result->fetch_assoc ();
      $parameters["account_" . $id . "_template"] = $equipment["Template"];
      if ( $equipment["AP"] == "Y")
      {
        if ( empty ( $parameters["account_" . $id . "_mac"]))
        {
          $data["result"] = false;
          $data["account_" . $id . "_mac"] = __ ( "Please inform the equipment MAC address.");
          continue;
        }
        $parameters["account_" . $id . "_mac"] = preg_replace ( "/[^0-9A-F]/", "", strtoupper ( $parameters["account_" . $id . "_mac"]));
        if ( strlen ( $parameters["account_" . $id . "_mac"]) != 12)
        {
          $data["result"] = false;
          $data["account_" . $id . "_mac"] = __ ( "Invalid MAC address.");
          continue;
        }
        if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Phones`.*, `Accounts`.`Extension` FROM `Phones` LEFT JOIN `Accounts` ON `Accounts`.`Phone` = `Phones`.`ID` WHERE `Phones`.`Type` = " . $_in["mysql"]["id"]->real_escape_string ( $equipment["ID"]) . " AND `Phones`.`MAC` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["account_" . $id . "_mac"]) . "'"))
        {
          header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
          exit ();
        }
        if ( $result->num_rows == 0)
        {
          $phone = array ( "ID" => "", "Template" => $equipment["Template"], "Description" => $equipment["Description"]);
        } else {
          $phone = $result->fetch_assoc ();
          if ( $phone["Extension"] != null && $phone["Extension"] != $parameters["id"])
          {
            $data["result"] = false;
            $data["account_" . $id . "_mac"] = __ ( "Equipment already in use.");
            continue;
          }
        }
      } else {
        if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Phones` WHERE `Type` = " . $_in["mysql"]["id"]->real_escape_string ( $equipment["ID"])))
        {
          header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
          exit ();
        }
        if ( $result->num_rows == 0)
        {
          $phone = array ( "ID" => "", "Template" => $equipment["Template"], "Description" => $equipment["Description"]);
        } else {
          $phone = $result->fetch_assoc ();
        }
        $parameters["account_" . $id . "_mac"] = "";
      }
      $accounts[] = array ( "username" => "u" . $parameters["extension"] . "-" . sizeof ( $accounts), "password" => randomPassword ( 8), "equipment" => $parameters["account_" . $id . "_type"], "mac" => $parameters["account_" . $id . "_mac"], "phone" => $phone["ID"], "template" => $phone["Template"], "description" => $phone["Description"]);
    }
  }

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "extensions_edit_sanitize"))
  {
    $data = framework_call ( "extensions_edit_sanitize", $parameters, false, $data);
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
   * Check difference between accounts
   */
  $accountadd = array ();
  $accountremove = array ();
  $accountkeep = array ();
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Accounts` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $dbentry = $result->fetch_assoc ())
  {
    $accountremove[$dbentry["ID"]] = $dbentry;
  }
  foreach ( $accounts as $account)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Accounts` WHERE `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $account["username"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $accountadd[] = $account;
      continue;
    }
    $dbentry = $result->fetch_assoc ();
    if ( $dbentry["Phone"] != $account["phone"] || $dbentry["Extension"] != $parameters["id"])
    {
      $accountadd[] = $account;
      continue;
    }
    unset ( $accountremove[$dbentry["ID"]]);
    $accountkeep[$dbentry["ID"]] = $dbentry;
  }

  /**
   * Create permissions data
   */
  $permissions = array (
    "mobile" => $parameters["perm_mobile"] == "on",
    "longdistance" => $parameters["perm_longdistance"] == "on",
    "international" => $parameters["perm_international"] == "on",
    "nopass" => $parameters["perm_nopass"] == "on",
    "voicemail" => $parameters["voicemail"] == "on",
    "monitor" => $parameters["monitor"] == "on",
    "volrx" => (int) ( $parameters["volrx"] >= -10 && $parameters["volrx"] <= 10 ? $parameters["volrx"] : 0),
    "voltx" => (int) ( $parameters["voltx"] >= -10 && $parameters["voltx"] <= 10 ? $parameters["voltx"] : 0)
  );

  /**
   * Call edit pre hook, if exist
   */
  if ( framework_has_hook ( "extensions_edit_pre"))
  {
    $parameters = framework_call ( "extensions_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Update extension database record if something changed
   */
  if ( $parameters["extension"] != $extension["Extension"] || $parameters["name"] != $extension["Name"] || $parameters["email"] != $extension["Email"] || $parameters["group"] != $extension["Group"] || ! array_compare_with_keys ( $permissions, $extension["Permissions"]) || $parameters["costcenter"] != $extension["CostCenter"])
  {
    if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Extensions` SET `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["extension"]) . ", `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', `NameFon` = '" . $_in["mysql"]["id"]->real_escape_string ( fonetiza ( $parameters["name"])) . "', `Email` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["email"]) . "', `Range` = " . $_in["mysql"]["id"]->real_escape_string ( $range["ID"]) . ", `Group` = " . $_in["mysql"]["id"]->real_escape_string ( $group["ID"]) . ", `Password` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["voicemailpass"]) . "', `Permissions` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $permissions)) . "', `CostCenter` = " . $_in["mysql"]["id"]->real_escape_string ( ( $parameters["costcenter"] != "" ? $parameters["costcenter"] : "null")) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Update extension capture group records if something changed
   */
  if ( ! array_compare ( $oldcapture, $parameters["capture"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionCapture` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    foreach ( $parameters["capture"] as $capture)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionCapture` (`Extension`, `Group`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $capture) . ")"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
    }
  }

  /**
   * Update extension transhipment records if something changed
   */
  if ( ! array_compare ( $oldtranshipments, $parameters["transhipments"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionTranshipment` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    foreach ( $parameters["transhipments"] as $transhipment)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionTranshipment` (`Extension`, `Transhipment`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $transhipment) . ")"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
    }
  }

  /**
   * Update extension hint records if something changed
   */
  if ( ! array_compare ( $oldhints, $parameters["hints"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHint` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    foreach ( $parameters["hints"] as $hint)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionHint` (`Extension`, `Hint`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $hint) . ")"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
    }
  }

  /**
   * If there's any account to remove, do it
   */
  if ( sizeof ( $accountremove) != 0)
  {
    foreach ( $accountremove as $account)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Accounts` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $account["ID"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }

      /**
       * Notify server to remove account
       */
      $notify = array ( "Username" => $account["Username"]);
      if ( framework_has_hook ( "extensions_edit_notify"))
      {
        $notify = framework_call ( "extensions_edit_notify", $parameters, false, $notify);
      }
      notify_server ( $oldrange["Server"], "removeextension", $notify);
    }
  }

  /**
   * If there's any account to be added, do it
   */
  if ( sizeof ( $accountadd) != 0)
  {
    foreach ( $accountadd as $account)
    {
      /**
       * If phone ID is empty, add it
       */
      if ( $account["phone"] == "")
      {
        if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Phones` (`Type`, `MAC`, `Template`, `Description`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $account["equipment"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $account["mac"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $account["template"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $account["description"]) . "')"))
        {
          header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
          exit ();
        }
        $account["phone"] = $_in["mysql"]["id"]->insert_id;
      }

      /**
       * Add account
       */
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Accounts` (`Extension`, `Username`, `Password`, `Phone`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $account["username"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $account["password"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $account["phone"]) . ")"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }

      /**
       * Add new account to Asterisk server
       */
      $notify = array ( "Extension" => $parameters["extension"], "UID" => $extension["ID"], "Username" => $account["username"], "Password" => $account["password"], "Group" => $group["Code"], "Capture" => $parameters["capture"], "Name" => $parameters["name"], "Template" => $account["template"], "Transhipments" => $parameters["transhipments"], "CostCenter" => ( $parameters["costcenter"] != "" ? $parameters["costcenter"] : $group["CostCenter"]), "PhonePass" => $parameters["voicemailpass"], "Permissions" => $permissions);
      if ( framework_has_hook ( "extensions_add_notify"))
      {
        $notify = framework_call ( "extensions_add_notify", $parameters, false, $notify);
      }
      notify_server ( $range["Server"], "createextension", $notify);

      /**
       * Add auto provisioning file if supported
       */
      if ( ! empty ( $account["mac"]))
      {
        $notify = array ( "Extension" => $parameters["extension"], "Username" => $account["username"], "Password" => $account["password"], "Name" => $parameters["name"], "MAC" => $account["mac"], "Template" => $account["template"], "Domain" => $range["Domain"]);
        if ( framework_has_hook ( "extensions_add_ap_notify"))
        {
          $notify = framework_call ( "extensions_add_ap_notify", $parameters, false, $notify);
        }
        notify_server ( $range["Server"], "createap", $notify);
      }
    }
  }

  /**
   * Check if there's any remaining account that need update
   */
  if ( sizeof ( $accountkeep) != 0 && ( $parameters["group"] != $extension["Group"] || ! array_compare ( $oldcapture, $parameters["capture"]) || $parameters["name"] != $extension["Name"] || ! array_compare ( $oldtranshipments, $parameters["transhipments"]) || $parameters["costcenter"] != $extension["CostCenter"] || $parameters["voicemailpass"] != $extension["Password"] || ! array_compare_with_keys ( $permissions, $extension["Permissions"])))
  {
    /**
     * Notify server to update accounts
     */
    $notify = array ( "Username" => $account["username"], "UID" => $extension["ID"], "NewUsername" => $account["username"], "Group" => $group["Code"], "Capture" => $parameters["capture"], "Name" => $parameters["name"], "Template" => $account["template"], "Transhipments" => $parameters["transhipments"], "CostCenter" => ( $parameters["costcenter"] != "" ? $parameters["costcenter"] : $group["CostCenter"]), "PhonePass" => $parameters["voicemailpass"], "Permissions" => $permissions);
    if ( framework_has_hook ( "extensions_edit_notify"))
    {
      $notify = framework_call ( "extensions_edit_notify", $parameters, false, $notify);
    }
    notify_server ( $range["Server"], "changevoicemail", $notify);
  }

  /**
   * Check if there's any change with voicemail
   */
  if ( $permissions["voicemail"] != $extension["Permissions"]["voicemail"] || $parameters["name"] != $extension["Name"] || $parameters["voicemailpass"] != $extension["Password"] || $parameters["email"] != $extension["Email"])
  {
    if ( $permissions["voicemail"] == false)
    {
      /**
       * Remove voicemail from extension
       */
      $notify = array ( "Extension" => $parameters["extension"]);
      if ( framework_has_hook ( "extensions_remove_voicemail_notify"))
      {
        $notify = framework_call ( "extensions_remove_voicemail_notify", $parameters, false, $notify);
      }
      notify_server ( $range["Server"], "removevoicemail", $notify);
    } else {
      if ( $extension["Permissions"]["voicemail"] == false)
      {
        /**
         * Add voicemail feature to extension
         */
        $notify = array ( "Extension" => $parameters["extension"], "Name" => $parameters["name"], "Password" => $parameters["voicemailpass"], "Email" => $parameters["email"]);
        if ( framework_has_hook ( "extensions_add_voicemail_notify"))
        {
          $notify = framework_call ( "extensions_add_voicemail_notify", $parameters, false, $notify);
        }
        notify_server ( $range["Server"], "createvoicemail", $notify);
      } else {
        /**
         * Update extension voicemail
         */
        $notify = array ( "Extension" => $parameters["extension"], "NewExtension" => $parameters["extension"], "Name" => $parameters["name"], "Password" => $parameters["voicemailpass"], "Email" => $parameters["email"]);
        if ( framework_has_hook ( "extensions_edit_voicemail_notify"))
        {
          $notify = framework_call ( "extensions_edit_voicemail_notify", $parameters, false, $notify);
        }
        notify_server ( $range["Server"], "changevoicemail", $notify);
      }
    }
  }

  /**
   * Check if there's any change with hints
   */
  if ( ! array_compare ( $oldhints, $parameters["hints"]))
  {
    /**
     * Check if need to remove a hint
     */
    foreach ( array_diff ( $oldhints, $parameters["hints"]) as $hint)
    {
$data["hints"][] = "Adicionado hint: " . $hint;
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension`, COUNT(*) AS `Total` FROM `ExtensionHint` LEFT JOIN `Extensions` ON `ExtensionHint`.`Hint` = `Extensions`.`ID` WHERE `ExtensionHint`.`Hint` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $tmp = $result->fetch_assoc ();
      if ( $tmp["Total"] == 0)
      {
        $notify = array ( "Extension" => $tmp["Extension"]);
        if ( framework_has_hook ( "extensions_remove_hint_notify"))
        {
          $notify = framework_call ( "extensions_remove_hint_notify", $parameters, false, $notify);
        }
        notify_server ( $range["Server"], "removehint", $notify);
      }
    }

    /**
     * Check if need to add a hint
     */
    foreach ( array_diff ( $parameters["hints"], $oldhints) as $hint)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension`, COUNT(*) AS `Total` FROM `ExtensionHint` LEFT JOIN `Extensions` ON `ExtensionHint`.`Hint` = `Extensions`.`ID` WHERE `ExtensionHint`.`Hint` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $tmp = $result->fetch_assoc ();
      if ( $tmp["Total"] == 1)
      {
        $notify = array ( "Extension" => $tmp["Extension"]);
        if ( framework_has_hook ( "extensions_add_hint_notify"))
        {
          $notify = framework_call ( "extensions_add_hint_notify", $parameters, false, $notify);
        }
        notify_server ( 0, "createhint", $notify);
      }
    }
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "extensions_edit_post"))
  {
    framework_call ( "extensions_edit_post", $parameters);
  }

  /**
   * Check if any other extension need to update Asterisk configurations
   */
  if ( $parameters["extension"] != $extension["Extension"])
  {
    /**
     * First, check if extension exist at any other extension transhipment
     */
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension`, `Extensions`.`ID`, `Ranges`.`Server` FROM `ExtensionTranshipment` LEFT JOIN `Extensions` ON `ExtensionTranshipment`.`Extension` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `ExtensionTranshipment`.`Transhipment` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $data = $result->fetch_assoc ())
    {
      if ( ! $tmpresult = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension` FROM `ExtensionTranshipment` LEFT JOIN `Extensions` ON `ExtensionTranshipment`.`Transhipment` = `Extensions`.`ID` WHERE `ExtensionTranshipment`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $data["ID"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $notify = array ();
      $notify["Extension"] = $data["Extension"];
      $notify["Transhipments"] = array ();
      while ( $tmp = $tmpresult->fetch_assoc ())
      {
        $notify["Transhipments"][] = $tmp["Extension"];
      }
      notify_server ( $data["Server"], "changetranshipment", $notify);
    }

    /**
     * Second, check if extension exist at any other extension hint
     */
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension`, `Extensions`.`ID`, `Ranges`.`Server` FROM `ExtensionHint` LEFT JOIN `Extensions` ON `ExtensionHint`.`Extension` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `ExtensionHint`.`Hint` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      notify_server ( $oldrange["Server"], "removehint", array ( "Extension" => $extension["Extension"]));
      notify_server ( $range["Server"], "createhint", array ( "Extension" => $parameters["extension"]));
    }
  }

  /**
   * Call extension number change hooker if needed
   */
  if ( $parameters["extension"] != $extension["Extension"] && framework_has_hook ( "extensions_number_changed"))
  {
    framework_call ( "extensions_number_changed", array ( "ID" => $extension["ID"], "Old" => $extension["Extension"], "New" => $parameters["extension"], "OldServer" => $oldrange["Server"], "NewServer" => $range["Server"]));
  }

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $extension["ID"];
  if ( $parameters["extension"] != $extension["Extension"])
  {
    $audit["Extension"] = array ( "Original" => $extension["Extension"], "New" => $parameters["extension"]);
  } else {
    $audit["Extension"] = $extension["Extension"];
  }
  if ( $parameters["name"] != $extension["Name"])
  {
    $audit["Name"] = array ( "Original" => $extension["Name"], "New" => $parameters["name"]);
  }
  if ( $parameters["email"] != $extension["Email"])
  {
    $audit["Email"] = array ( "Original" => $extension["Email"], "New" => $parameters["email"]);
  }
  if ( $parameters["group"] != $extension["Group"])
  {
    $audit["Group"] = array ( "Original" => $extension["Group"], "New" => $parameters["group"]);
  }
  if ( ! array_compare ( $oldcapture, $parameters["capture"]))
  {
    $audit["Capture"] = array ( "Original" => $oldcapture, "New" => $parameters["catpure"]);
  }
  if ( ! array_compare_with_keys ( $permissions, $extension["Permissions"]))
  {
    $audit["Permissions"] = array ( "Original" => $extension["Permissions"], "New" => $permissions);
  }
  if ( $parameters["voicemailpass"] != $extension["Password"])
  {
    $audit["Password"] = array ( "Original" => $extension["Password"], "New" => $parameters["voicemailpass"]);
  }
  if ( ! array_compare ( $oldtranshipments, $parameters["transhipments"]))
  {
    $audit["Transhipments"] = array ( "Original" => $oldtranshipments, "New" => $parameters["transhipments"]);
  }
  if ( sizeof ( $accountsadd) != 0 || sizeof ( $accountsremove) != 0)
  {
    $audit["Accounts"] = array ( "Add" => $accountsadd, "Remove" => $accountsremove, "Keep" => $accountskeep);
  }
  if ( $parameters["costcenter"] != $extension["CostCenter"])
  {
    $audit["CostCenter"] = array ( "Original" => $extension["CostCenter"], "New" => $parameters["costcenter"]);
  }
  if ( ! array_compare ( $oldhints, $parameters["hints"]))
  {
    $audit["Hints"] = array ( "Original" => $oldhints, "New" => $parameters["hints"]);
  }
  if ( framework_has_hook ( "extensions_edit_audit"))
  {
    $audit = framework_call ( "extensions_edit_audit", $parameters, false, $audit);
  }
  audit ( "extension", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a extension
 */
framework_add_hook ( "extensions_remove", "extensions_remove");
framework_add_permission ( "extensions_remove", __ ( "Remove extensions"));
framework_add_api_call ( "/extensions/:id", "Delete", "extensions_remove", array ( "permissions" => array ( "user", "extensions_remove")));

/**
 * Function to remove an existing extension.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if extension exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.*, `Ranges`.`Server` FROM `Extensions` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `Extensions`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Get extension capture groups
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionCapture` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension["CaptureGroups"] = array ();
  while ( $capture = $result->fetch_assoc ())
  {
    $extension["CaptureGroups"][] = $capture["Group"];
  }

  /**
   * Get extension transhipment extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionTranshipment` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension["Transhipments"] = array ();
  while ( $transhipment = $result->fetch_assoc ())
  {
    $extension["Transhipments"][] = $transhipment["Transhipment"];
  }

  /**
   * Get extension hint extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionHint` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension["Hints"] = array ();
  while ( $hint = $result->fetch_assoc ())
  {
    $extension["Hints"][] = $hint["Hint"];
  }

  /**
   * Get extension accounts
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Accounts` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension["Accounts"] = array ();
  while ( $account = $result->fetch_assoc ())
  {
    $extension["Accounts"][] = $account;
  }

  /**
   * Call remove pre hook, if exist
   */
  if ( framework_has_hook ( "extensions_remove_pre"))
  {
    $parameters = framework_call ( "extensions_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove extension from other extensions hint
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHint` WHERE `Hint` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( intval ( $count->affected_rows) != 0)
  {
    $notify = array ( "Extension" => $extension["Extension"]);
    if ( framework_has_hook ( "extensions_remove_hint_notify"))
    {
      $notify = framework_call ( "extensions_remove_hint_notify", $parameters, false, $notify);
    }
    notify_server ( $extension["Server"], "removehint", $notify);
  }

  /**
   * Remove unique hints from extension
   */
  foreach ( $extension["Hints"] as $hint)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension`, `Ranges`.`Server`, COUNT(*) AS `Total` FROM `ExtensionHint` LEFT JOIN `Extensions` ON `ExtensionHint`.`Hint` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `ExtensionHint`.`Hint` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $hint)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $hintdata = $result->fetch_assoc ();
    if ( $hintdata["Total"] == 1)
    {
      $notify = array ( "Extension" => $hintdata["Extension"]);
      if ( framework_has_hook ( "extensions_remove_hint_notify"))
      {
        $notify = framework_call ( "extensions_remove_hint_notify", $parameters, false, $notify);
      }
      notify_server ( $hintdata["Server"], "removehint", $notify);
    }
  }
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHint` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove extension from other extensions transhipment
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension`, `Extensions`.`ID`, `Ranges`.`Server` FROM `ExtensionTranshipment` LEFT JOIN `Extensions` ON `Extensions`.`ID` = `ExtensionTranshipment`.`Extension` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `ExtensionTranshipment`.`Transhipment` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $transhipment = $result->fetch_assoc ())
  {
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension` FROM `ExtensionTranshipment` LEFT JOIN `Extensions` ON `ExtensionTranshipment`.`Transhipment` = `Extensions`.`ID` WHERE `ExtensionTranshipment`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $transhipment["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $transhipmentdata = array ();
    while ( $tmp = $result2->fetch_assoc ())
    {
      $transhipmentdata[] = $tmp["Extension"];
    }
    $notify = array ( "Extension" => $transhipment["Extension"], "Transhipments" => $transhipmentdata);
    if ( framework_has_hook ( "extensions_change_transhipment_notify"))
    {
      $notify = framework_call ( "extensions_change_transhipment_notify", $parameters, false, $notify);
    }
    notify_server ( $transhipment["Server"], "changetranshipment", $notify);
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionTranshipment` WHERE `Transhipment` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Remove extension database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $extension["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "extensions_remove_post"))
  {
    framework_call ( "extensions_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Extension" => $extension["Extension"]);
  if ( framework_has_hook ( "extensions_remove_notify"))
  {
    $notify = framework_call ( "extensions_remove_notify", $parameters, false, $notify);
  }
  notify_server ( $extension["Server"], "removeextension", $notify);

  /**
   * Insert audit registry
   */
  $audit = $extension;
  if ( framework_has_hook ( "extensions_remove_audit"))
  {
    $audit = framework_call ( "extensions_remove_audit", $parameters, false, $audit);
  }
  audit ( "extension", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to generate extension call's report
 */
framework_add_hook ( "extensions_report", "extensions_report");
framework_add_permission ( "extensions_report", __ ( "Extensions use report"));
framework_add_api_call ( "/extensions/:id/report", "Read", "extensions_report", array ( "permissions" => array ( "user", "extensions_report")));

/**
 * Function to generate report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Get user extension informations
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE (`src` = '" . $_in["mysql"]["id"]->real_escape_string ( $extension["Extension"]) . "' OR `dst` = '" . $_in["mysql"]["id"]->real_escape_string ( $extension["Extension"]) . "') AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $records->fetch_assoc ())
  {
    $data["extension"] = $extension["Extension"];
    $output[] = filters_call ( "process_call", $data);
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}

/**
 * API call to intercept new server and server reinstall
 */
framework_add_hook ( "servers_add_post", "extensions_server_reconfig");
framework_add_hook ( "servers_reinstall_config", "extensions_server_reconfig");

/**
 * Function to notify server to include all extensions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all extensions and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Extension`, `Extensions`.`Name`, `Extensions`.`Email`, `Extensions`.`Password`, `Extensions`.`Group`, `Groups`.`Code` AS `GroupCode`, `Extensions`.`Permissions`, `CostCenters`.`Code` AS `CostCenter`, GROUP_CONCAT(`ExtensionCapture`.`Group` SEPARATOR ',') AS `Captures`, `Servers`.`Domain` FROM `Extensions` LEFT JOIN `Groups` ON `Extensions`.`Group` = `Groups`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID` LEFT JOIN `CostCenters` ON `Extensions`.`CostCenter` = `CostCenters`.`ID` LEFT JOIN `ExtensionCapture` ON `ExtensionCapture`.`Extension` = `Extensions`.`ID` WHERE `Ranges`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"]) . " GROUP BY `Extensions`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $extension = $result->fetch_assoc ())
  {
    /**
     * Explode capture groups and permissions
     */
    $extension["Captures"] = explode ( ",", $extension["Captures"]);
    $extension["Permissions"] = json_decode ( $extension["Permissions"], true);

    /**
     * Fetch extension transhipments
     */
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`Extensions`.`Extension` SEPARATOR ',') AS `Transhipments` FROM `ExtensionTranshipment` LEFT JOIN `Extensions` ON `ExtensionTranshipment`.`Transhipment` = `Extensions`.`ID` WHERE `ExtensionTranshipment`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $extension["Transhipments"] = explode ( ",", $result2->fetch_assoc ()["Transhipments"]);

    /**
     * Fetch extension hints
     */
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`Extensions`.`Extension` SEPARATOR ',') AS `Hints` FROM `ExtensionHint` LEFT JOIN `Extensions` ON `ExtensionHint`.`Hint` = `Extensions`.`ID` WHERE `ExtensionHint`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $extension["Hints"] = explode ( ",", $result2->fetch_assoc ()["Hints"]);

    /**
     * If extension doesn't has cost center, get group default cost center
     */
    if ( $extension["CostCenter"] == "")
    {
      if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT `CostCenters`.`Code` FROM `Groups` LEFT JOIN `CostCenters` WHERE `Groups`.`CostCenter` = `CostCenters`.`ID` WHERE `Groups`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $extension["Group"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $extension["CostCenter"] = $result2->fetch_assoc ()["Code"];
    }

    /**
     * Fetch all accounts from extension
     */
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT `Accounts`.`ID`, `Accounts`.`Username`, `Accounts`.`Password`, `Phones`.`MAC`, `Phones`.`Template`, `Equipments`.`Template` AS `EquipmentTemplate`, `Equipments`.`AP` FROM `Accounts` LEFT JOIN `Phones` ON `Accounts`.`Phone` = `Phones`.`ID` LEFT JOIN `Equipments` ON `Phones`.`Type` = `Equipments`.`ID` WHERE `Accounts`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $account = $result2->fetch_assoc ())
    {
      /**
       * Add new extension at Asterisk servers
       */
      $notify = array ( "Extension" => $extension["Extension"], "UID" => $account["ID"], "Username" => $account["Username"], "Password" => $account["Password"], "Group" => $extension["GroupCode"], "Capture" => $extension["Capture"], "Name" => $extension["Name"], "Template" => $account["Template"], "Transhipments" => $extension["Transhipments"], "CostCenter" => $extension["CostCenter"], "PhonePass" => $extension["Permissions"]["voicemailpass"], "Permissions" => $extension["Permissions"]);
      if ( framework_has_hook ( "extensions_add_notify"))
      {
        $notify = framework_call ( "extensions_add_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["id"], "createextension", $notify);

      /**
       * Add auto provisioning file if supported
       */
      if ( ! empty ( $account["MAC"]))
      {
        $notify = array ( "Extension" => $extension["Extension"], "Username" => $account["Username"], "Password" => $account["Password"], "Name" => $extension["Name"], "MAC" => $account["MAC"], "Template" => $account["Template"], "Domain" => $extension["Domain"]);
        if ( framework_has_hook ( "extensions_add_ap_notify"))
        {
          $notify = framework_call ( "extensions_add_ap_notify", $parameters, false, $notify);
        }
        notify_server ( $parameters["id"], "createap", $notify);
      }
    }

    /**
     * Create voicemail configuration if needed
     */
    if ( $extension["Permissions"]["voicemail"] == true)
    {
      $notify = array ( "Extension" => $extension["Extension"], "Name" => $extension["Name"], "Password" => $extension["Password"], "Email" => $extension["Email"]);
      if ( framework_has_hook ( "extensions_add_voicemail_notify"))
      {
        $notify = framework_call ( "extensions_add_voicemail_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["id"], "createvoicemail", $notify);
    }
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
