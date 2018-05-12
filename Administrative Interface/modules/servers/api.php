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
 * VoIP Domain servers api module. This module add the api calls related to
 * servers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Servers
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search servers
 */
framework_add_hook ( "servers_search", "servers_search");
framework_add_permission ( "servers_search", __ ( "Servers search (select list standard)"));
framework_add_api_call ( "/servers/search", "Read", "servers_search", array ( "permissions" => array ( "user", "servers_search")));

/**
 * Function to generate server list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Servers");

  /**
   * Search servers
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Name`, `Domain` FROM `Servers` " . ( ! empty ( $parameters["q"]) ? "WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' OR `Domain` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' " : "") . "ORDER BY `Name`"))
  {
    while ( $server = $result->fetch_assoc ())
    {
      $data[] = array ( $server["ID"], $server["Name"] . " (" . $server["Domain"] . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch servers listing
 */
framework_add_hook ( "servers_fetch", "servers_fetch");
framework_add_permission ( "servers_fetch", __ ( "Request server listing"));
framework_add_api_call ( "/servers/fetch", "Read", "servers_fetch", array ( "permissions" => array ( "user", "servers_fetch")));

/**
 * Function to generate server list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Servers", "Ranges", "Extensions"));

  /**
   * Search servers
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Servers`.*, COUNT(`Extensions`.`Range`) AS `Total` FROM `Servers` LEFT JOIN `Ranges` ON `Ranges`.`Server` = `Servers`.`ID` LEFT JOIN `Extensions` ON `Extensions`.`Range` = `Ranges`.`ID` GROUP BY `Servers`.`ID`"))
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
    $data[] = array ( $result["ID"], $result["Name"], $result["Address"], $result["Domain"], $result["Total"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get server information
 */
framework_add_hook ( "servers_view", "servers_view");
framework_add_permission ( "servers_view", __ ( "View servers informations"));
framework_add_api_call ( "/servers/:id", "Read", "servers_view", array ( "permissions" => array ( "user", "servers_view")));

/**
 * Function to generate server informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Servers", "Countries", "Gateways"));

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search servers
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Servers`.*, `Countries`.`Name` AS `CountryName`, `Gateways`.`Description` AS `NGGWDescription`, `Gateways`.`Type` AS `NGGWType` FROM `Servers` LEFT JOIN `Countries` ON `Servers`.`Country` = `Countries`.`Code` LEFT JOIN `Gateways` ON `Servers`.`NGGW` = `Gateways`.`ID` WHERE `Servers`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $server = $result->fetch_assoc ();

  /**
   * Get gateways informations
   */
  $gateways = array ();
  foreach ( explode ( ",", $server["DefaultGW"]) as $gw)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $gw)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 1)
    {
      $gateway = $result->fetch_assoc ();
      $gateways[$gw] = $gateway["Description"] . " (" . $gateway["Type"] . ")";
    }
  }

  /**
   * Get blocked gateways informations
   */
  $blockeds = array ();
  if ( ! empty ( $server["BlockedGW"]))
  {
    foreach ( explode ( ",", $server["BlockedGW"]) as $gw)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $gw)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows != 1)
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $gateway = $result->fetch_assoc ();
      $blockeds[$gw] = $gateway["Description"] . " (" . $gateway["Type"] . ")";
    }
  }

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["name"] = $server["Name"];
  $data["address"] = $server["Address"];
  $data["domain"] = $server["Domain"];
  $data["country"] = $server["Country"];
  $data["countryname"] = $server["CountryName"];
  $data["areacode"] = $server["AreaCode"];
  $data["nggw"] = $server["NGGW"];
  $data["nggwname"] = $server["NGGWDescription"] . " (" . $server["NGGWType"] . ")";
  $data["blockeds"] = $blockeds;
  $data["gateways"] = $gateways;
  $data["window"] = $server["TransfStart"] != NULL;
  $data["start"] = $server["TransfStart"];
  $data["finish"] = $server["TransfEnd"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new server
 */
framework_add_hook ( "servers_add", "servers_add");
framework_add_permission ( "servers_add", __ ( "Add servers"));
framework_add_api_call ( "/servers", "Create", "servers_add", array ( "permissions" => array ( "user", "servers_add")));

/**
 * Function to add a new server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_add ( $buffer, $parameters)
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
    $data["name"] = __ ( "The server name is required.");
  }
  $parameters["address"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["address"])));
  if ( empty ( $parameters["address"]))
  {
    $data["result"] = false;
    $data["address"] = __ ( "The server address is required.");
  }
  if ( ! empty ( $parameters["address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $parameters["address"]) && gethostbyname ( $parameters["address"]) == $parameters["address"])
  {
    $data["result"] = false;
    $data["address"] = __ ( "The server address is invalid.");
  }
  $parameters["domain"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["domain"])));
  if ( empty ( $parameters["domain"]))
  {
    $data["result"] = false;
    $data["domain"] = __ ( "The server domain is required.");
  }
  $parameters["country"] = (int) $parameters["country"];
  if ( empty ( $parameters["country"]))
  {
    $data["result"] = false;
    $data["country"] = __ ( "The server country is required.");
  }
  $parameters["areacode"] = (int) $parameters["areacode"];
  if ( empty ( $parameters["areacode"]))
  {
    $data["result"] = false;
    $data["areacode"] = __ ( "The server area code is required.");
  }
  $parameters["nggw"] = (int) $parameters["nggw"];
  if ( empty ( $parameters["nggw"]))
  {
    $data["result"] = false;
    $data["nggw"] = __ ( "The server non geographic gateway calls is required.");
  }
  if ( ! is_array ( $parameters["blockeds"]))
  {
    if ( ! empty ( $parameters["blockeds"]))
    {
      $parameters["blockeds"] = array ( $parameters["blockeds"]);
    } else {
      $parameters["blockeds"] = array ();
    }
  }
  foreach ( $parameters["blockeds"] as $key => $value)
  {
    $parameters["blockeds"][$key] = (int) $value;
  }
  if ( ! is_array ( $parameters["gateways"]))
  {
    if ( ! empty ( $parameters["gateways"]))
    {
      $parameters["gateways"] = array ( $parameters["gateways"]);
    } else {
      $parameters["gateways"] = array ();
    }
  }
  foreach ( $parameters["gateways"] as $key => $value)
  {
    $parameters["gateways"][$key] = (int) $value;
  }

  /**
   * Check if country exists
   */
  if ( ! array_key_exists ( "country", $data))
  {
    $check = filters_call ( "get_countries", array ( "code" => $parameters["country"]));
    if ( sizeof ( $check) != 1)
    {
      $data["result"] = false;
      $data["country"] = __ ( "The selected country is invalid.");
    }
  }

  /**
   * Check if transfer window enabled, and time window provided
   */
  if ( $parameters["window"] == "on" && empty ( $parameters["start"]))
  {
    $data["result"] = false;
    $data["start"] = __ ( "The start hour is required.when transfer window are enabled.");
  }
  if ( $parameters["window"] == "on" && empty ( $parameters["finish"]))
  {
    $data["result"] = false;
    $data["finish"] = __ ( "The finish hour is required.when transfer window are enabled.");
  }

  /**
   * If provided some gateway, check if exists
   */
  if ( array_key_exists ( "gateways", $parameters) && sizeof ( $parameters["gateways"]) != 0)
  {
    $gateways = array ();
    foreach ( $parameters["gateways"] as $gateway)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $gateway)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 1)
      {
        $gateways[] = $gateway;
      }
    }
  }

  /**
   * If provided some blocked gateway, check if exists
   */
  if ( array_key_exists ( "blockeds", $parameters) && sizeof ( $parameters["blockeds"]) != 0)
  {
    $blockeds = array ();
    foreach ( $parameters["blockeds"] as $gateway)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $gateway)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 1)
      {
        $blockeds[] = $gateway;
      }
    }
  }

  /**
   * If provided a non geographic gateway, check if exist
   */
  if ( ! array_key_exists ( "nggw", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["nggw"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["result"] = false;
      $data["nggw"] = __ ( "The selected non geographic calls gateway is invalid.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "servers_add_sanitize"))
  {
    $data = framework_call ( "servers_add_sanitize", $parameters, false, $data);
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
   * Create a random password for server criptography
   */
  $password = randomPassword ( 12);

  /**
   * Add new server record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Servers` (`Name`, `Address`, `Domain`, `Country`, `AreaCode`, `Password`, `NGGW`, `DefaultGW`, `BlockedGW`, `TransfStart`, `TransfEnd`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["address"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["domain"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["country"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["areacode"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $password) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["nggw"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $gateways)) . "', '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $blockeds)) . "', " . ( $parameters["window"] == "on" ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . ":00'" : "NULL") . ", " . ( $parameters["window"] == "on" ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["finish"]) . ":59'" : "NULL") . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "servers_add_post"))
  {
    framework_call ( "servers_add_post", $parameters);
  }

  /**
   * Add new server at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["id"], "Name" => $parameters["name"], "Address" => $parameters["address"]);
  if ( framework_has_hook ( "servers_add_notify"))
  {
    $notify = framework_call ( "servers_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "createserver", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Name" => $parameters["name"], "Address" => $parameters["address"], "Domain" => $parameters["domain"], "Country" => $parameters["country"], "AreaCode" => $parameters["areacode"], "Password" => $password, "NGGW" => $parameters["nggw"], "Gateways" => $gateways, "Blockeds" => $blockeds, "Window" => ( $parameters["window"] == "on"), "Start" => $parameters["start"], "Finish" => $parameters["finish"]);
  if ( framework_has_hook ( "servers_add_audit"))
  {
    $audit = framework_call ( "servers_add_audit", $parameters, false, $audit);
  }
  audit ( "server", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "servers/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing server
 */
framework_add_hook ( "servers_edit", "servers_edit");
framework_add_permission ( "servers_edit", __ ( "Edit servers"));
framework_add_api_call ( "/servers/:id", "Modify", "servers_edit", array ( "permissions" => array ( "user", "servers_edit")));
framework_add_api_call ( "/servers/:id", "Edit", "servers_edit", array ( "permissions" => array ( "user", "servers_edit")));

/**
 * Function to edit an existing server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_edit ( $buffer, $parameters)
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
    $data["name"] = __ ( "The server name is required.");
  }
  $parameters["address"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["address"])));
  if ( empty ( $parameters["address"]))
  {
    $data["result"] = false;
    $data["address"] = __ ( "The server address is required.");
  }
  if ( ! empty ( $parameters["address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $parameters["address"]) && gethostbyname ( $parameters["address"]) == $parameters["address"])
  {
    $data["result"] = false;
    $data["address"] = __ ( "The server address is invalid.");
  }
  $parameters["domain"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["domain"])));
  if ( empty ( $parameters["domain"]))
  {
    $data["result"] = false;
    $data["domain"] = __ ( "The server domain is required.");
  }
  $parameters["country"] = (int) $parameters["country"];
  if ( empty ( $parameters["country"]))
  {
    $data["result"] = false;
    $data["country"] = __ ( "The server country is required.");
  }
  $parameters["areacode"] = (int) $parameters["areacode"];
  if ( empty ( $parameters["areacode"]))
  {
    $data["result"] = false;
    $data["areacode"] = __ ( "The server area code is required.");
  }
  $parameters["nggw"] = (int) $parameters["nggw"];
  if ( empty ( $parameters["nggw"]))
  {
    $data["result"] = false;
    $data["nggw"] = __ ( "The server non geographic gateway calls is required.");
  }
  if ( ! is_array ( $parameters["blockeds"]))
  {
    if ( ! empty ( $parameters["blockeds"]))
    {
      $parameters["blockeds"] = array ( $parameters["blockeds"]);
    } else {
      $parameters["blockeds"] = array ();
    }
  }
  foreach ( $parameters["blockeds"] as $key => $value)
  {
    $parameters["blockeds"][$key] = (int) $value;
  }
  if ( ! is_array ( $parameters["gateways"]))
  {
    if ( ! empty ( $parameters["gateways"]))
    {
      $parameters["gateways"] = array ( $parameters["gateways"]);
    } else {
      $parameters["gateways"] = array ();
    }
  }
  foreach ( $parameters["gateways"] as $key => $value)
  {
    $parameters["gateways"][$key] = (int) $value;
  }

  /**
   * Check if server exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $server = $result->fetch_assoc ();

  /**
   * Check if country exists
   */
  $check = filters_call ( "get_countries", array ( "code" => $parameters["country"]));
  if ( sizeof ( $check) != 1)
  {
    $data["result"] = false;
    $data["country"] = __ ( "The selected country is invalid.");
  }

  /**
   * Check if transfer window enabled, and time window provided
   */
  if ( $parameters["window"] == "on" && ! array_key_exists ( "start", $parameters))
  {
    $data["result"] = false;
    $data["start"] = __ ( "The start hour is required.when transfer window are enabled.");
  }
  if ( $parameters["window"] == "on" && ! array_key_exists ( "finish", $parameters))
  {
    $data["result"] = false;
    $data["finish"] = __ ( "The finish hour is required.when transfer window are enabled.");
  }

  /**
   * If provided some gateway, check if exists
   */
  if ( array_key_exists ( "gateways", $parameters) && sizeof ( $parameters["gateways"]) != 0)
  {
    $gateways = array ();
    foreach ( $parameters["gateways"] as $gateway)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $gateway)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 1)
      {
        $gateways[] = $gateway;
      }
    }
  }

  /**
   * If provided some blocked gateway, check if exists
   */
  if ( array_key_exists ( "blockeds", $parameters) && sizeof ( $parameters["blockeds"]) != 0)
  {
    $blockeds = array ();
    foreach ( $parameters["blockeds"] as $gateway)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $gateway)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 1)
      {
        $blockeds[] = $gateway;
      }
    }
  }

  /**
   * If provided a non geographic gateway, check if exist
   */
  if ( ! array_key_exists ( "nggw", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["nggw"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["result"] = false;
      $data["nggw"] = __ ( "The selected non geographic calls gateway is invalid.");
    }
  }

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "servers_edit_sanitize"))
  {
    $data = framework_call ( "servers_edit_sanitize", $parameters, false, $data);
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
   * Change server record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Servers` SET `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', `Address` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["address"]) . "', `Domain` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["domain"]) . "', `Country` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["country"]) . ", `AreaCode` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["areacode"]) . ", `NGGW` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["nggw"]) . ", `DefaultGW` = '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $gateways)) . "', `BlockedGW` = '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $blockeds)) . "', `TransfStart` = " . ( $parameters["window"] == "on" ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . ":00'" : "NULL") . ", `TransfEnd` = " . ( $parameters["window"] == "on" ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["finish"]) . ":59'" : "NULL") . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $server["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "servers_edit_post"))
  {
    framework_call ( "servers_edit_post", $parameters);
  }

  /**
   * Notify servers about change (if needed)
   */
  if ( $server["Address"] != $parameters["address"] || $server["Name"] != $parameters["name"])
  {
    $notify = array ( "ID" => $server["ID"], "Name" => $parameters["name"], "Address" => $parameters["address"]);
    if ( framework_has_hook ( "servers_edit_notify"))
    {
      $notify = framework_call ( "servers_edit_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "changeserver", $notify);
  }

// ***TODO***: Aqui tem que verificar o que alterou no servidor, e enviar estas alterações pra ele... isso é complexo, pois se mudar o país, muda muita coisa no dialplan também! mudança de gateways, area code, etc..

  /**
   * Insert audit record
   */
  $audit = array ();
  $audit["ID"] = $server["ID"];
  if ( $parameters["name"] == $server["Name"])
  {
    $audit["Name"] = array ( "Original" => $server["Name"], "New" => $parameters["name"]);
  }
  if ( $parameters["address"] != $server["Address"])
  {
    $audit["Address"] = array ( "Original" => $server["Address"], "New" => $parameters["address"]);
  }
  if ( $parameters["domain"] != $server["Domain"])
  {
    $audit["Domain"] = array ( "Original" => $server["Domain"], "New" => $parameters["domain"]);
  }
  if ( $parameters["country"] != $server["Country"])
  {
    $audit["Country"] = array ( "Original" => $server["Country"], "New" => $parameters["country"]);
  }
  if ( $parameters["areacode"] != $server["AreaCode"])
  {
    $audit["AreaCode"] = array ( "Original" => $server["AreaCode"], "New" => $parameters["areacode"]);
  }
  if ( $parameters["nggw"] != $server["NGGW"])
  {
    $audit["NGGW"] = array ( "Original" => $server["NGGW"], "New" => $parameters["nggw"]);
  }
  if ( implode ( ",", $gateways) != $server["DefaultGW"])
  {
    $audit["DefaultGW"] = array ( "Original" => explode ( ",", $server["DefaultGW"]), "New" => $gateways);
  }
  if ( implode ( ",", $blockeds) != $server["BlockedGW"])
  {
    $audit["BlockedGW"] = array ( "Original" => explode ( ",", $server["BlockedGW"]), "New" => $blockeds);
  }
  if ( ( $parameters["window"] == "on" ? $parameters["start"] . ":00" : "NULL") != $server["TransfStart"])
  {
    $audit["TransfStart"] = array ( "Original" => $server["TransfStart"], "New" => ( $parameters["window"] == "on" ? $parameters["start"] . ":00" : "NULL"));
  }
  if ( ( $parameters["window"] == "on" ? $parameters["finish"] . ":59" : "NULL") != $server["TransfEnd"])
  {
    $audit["TransfEnd"] = array ( "Original" => $server["TransfEnd"], "New" => ( $parameters["window"] == "on" ? $parameters["finish"] . ":59" : "NULL"));
  }
  if ( framework_has_hook ( "servers_edit_audit"))
  {
    $audit = framework_call ( "servers_edit_audit", $parameters, false, $audit);
  }
  audit ( "server", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a server
 */
framework_add_hook ( "servers_remove", "servers_remove");
framework_add_permission ( "servers_remove", __ ( "Remove servers"));
framework_add_api_call ( "/servers/:id", "Delete", "servers_remove", array ( "permissions" => array ( "user", "servers_remove")));

/**
 * Function to remove an existing server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if server exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $server = $result->fetch_assoc ();

  /**
   * Check if server has any active range
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    exit ();
  }

  /**
   * Remove server database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "servers_remove_post"))
  {
    framework_call ( "servers_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $server["ID"]);
  if ( framework_has_hook ( "servers_remove_notify"))
  {
    $notify = framework_call ( "servers_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "removeserver", $notify);

  /**
   * Insert audit registry
   */
  $audit = $server;
  if ( framework_has_hook ( "servers_remove_audit"))
  {
    $audit = framework_call ( "servers_remove_audit", $parameters, false, $audit);
  }
  audit ( "server", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}
?>
