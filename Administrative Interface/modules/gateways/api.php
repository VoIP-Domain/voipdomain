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
 * VoIP Domain gateways api module. This module add the api calls related to
 * gateways.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Gateways
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search gateways (datatables compatible response)
 */
framework_add_hook ( "gateways_search", "gateways_search");
framework_add_permission ( "gateways_search", __ ( "Search gateways (select list standard)"));
framework_add_api_call ( "/gateways/search", "Read", "gateways_search", array ( "permissions" => array ( "user", "gateways_search")));

/**
 * Function to generate central list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Gateways");

  /**
   * Create query string
   */
  $query = "";
  if ( array_key_exists ( "inactive", $parameters))
  {
    $query .= " AND `Active` = '" . ( $parameters["inactive"] != "N" ? "Y" : "N") . "'";
  }
  if ( array_key_exists ( "q", $parameters))
  {
    $query .= " AND `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' OR `Type` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "' OR `Number` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%'";
  }

  /**
   * Search gateways
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Description`, `Active`, `Type`, `CountryCode`, `AreaCode`, `Number` FROM `Gateways`" . ( ! empty ( $query) ? " WHERE" . substr ( $query, 4) : "") . " ORDER BY `Description`, `Type`"))
  {
    while ( $gateway = $result->fetch_assoc ())
    {
      $data[] = array ( $gateway["ID"], $gateway["Description"], ( $gateway["Active"] == "Y" ? true : false), __ ( $gateway["Type"]), "+" . $gateway["CountryCode"] . $gateway["AreaCode"] . $gateway["Number"], "");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch gateways listing
 */
framework_add_hook ( "gateways_fetch", "gateways_fetch");
framework_add_permission ( "gateways_fetch", __ ( "Request gateways listing"));
framework_add_api_call ( "/gateways/fetch", "Read", "gateways_fetch", array ( "permissions" => array ( "user", "gateways_fetch")));

/**
 * Function to generate gateway list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Gateways");

  /**
   * Search gateways
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways`"))
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
    $data[] = array ( $result["ID"], $result["Description"], $result["Extension"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch gateways fares listing
 */
framework_add_hook ( "gateways_fares_fetch", "gateways_fares_fetch");
framework_add_permission ( "gateways_fares_fetch", __ ( "Request fares listing"));
framework_add_api_call ( "/gateways/fares/fetch", "Read", "gateways_fares_fetch", array ( "permissions" => array ( "user", "gateways_fares_fetch")));

/**
 * Function to generate gateway fares list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_fares_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Files");

  /**
   * Search fares
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Files` WHERE `Type` = 'fares'"))
  {
    while ( $fare = $result->fetch_assoc ())
    {
      $data[] = array ( $fare["ID"], $fare["Name"]);
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch gateways fares file
 */
framework_add_hook ( "gateways_fares_file", "gateways_fares_file");
framework_add_permission ( "gateways_fares_file", __ ( "Request fares file"));
framework_add_api_call ( "/gateways/fares/:id", "Read", "gateways_fares_file", array ( "permissions" => array ( "user", "gateways_fares_file")));

/**
 * Function to generate gateway fares file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_fares_file ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Files");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search fare
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Files` WHERE `Type` = 'fares' AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $fare = $result->fetch_assoc ();

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), json_decode ( $fare["Content"], true));
}

/**
 * API call to get gateway information
 */
framework_add_hook ( "gateways_view", "gateways_view");
framework_add_permission ( "gateways_view", __ ( "View gateways informations"));
framework_add_api_call ( "/gateways/:id", "Read", "gateways_view", array ( "permissions" => array ( "user", "gateways_view")));

/**
 * Function to generate gateway informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Gateways", "Countries"));

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search gateways
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
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

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $gateway["Description"];
  $data["state"] = ( $gateway["Active"] == "Y" ? true : false);
  $data["type"] = $gateway["Type"];
  $data["priority"] = $gateway["Priority"];
  $data["config"] = $gateway["Config"];
  $data["number"] = "+" . $gateway["CountryCode"] . $gateway["AreaCode"] . $gateway["Number"];
  $data["address"] = $gateway["Address"];
  $data["port"] = $gateway["Port"];
  $data["username"] = $gateway["Username"];
  $data["password"] = $gateway["Password"];
  $data["nat"] = ( $gateway["NAT"] == "Y" ? true : false);
  $data["rpid"] = ( $gateway["RPID"] == "Y" ? true : false);
  $data["qualify"] = ( $gateway["Qualify"] == "Y" ? true : false);
  $data["discard"] = $gateway["Discard"];
  $data["minimum"] = $gateway["Minimum"];
  $data["fraction"] = $gateway["Fraction"];
  $data["routes"] = array ();
  $routes = json_decode ( $gateway["Routes"], true);
  foreach ( $routes as $route)
  {
    $data["routes"][] = array ( "route" => $route["route"], "cost" => sprintf ( "%.5f", $route["cost"]));
  }
  $data["translations"] = array ();
  $translations = json_decode ( $gateway["Translations"], true);
  foreach ( $translations as $translation)
  {
    $data["translations"][] = array ( "pattern" => $translation["pattern"], "remove" => $translation["remove"], "add" => $translation["add"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new gateway
 */
framework_add_hook ( "gateways_add", "gateways_add");
framework_add_permission ( "gateways_add", __ ( "Add gateways"));
framework_add_api_call ( "/gateways", "Create", "gateways_add", array ( "permissions" => array ( "user", "gateways_add")));

/**
 * Function to add a new gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["description"])));
  if ( empty ( $parameters["description"]))
  {
    $data["result"] = false;
    $data["description"] = __ ( "The gateway description is required.");
  }
  $parameters["type"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["type"])));
  if ( empty ( $parameters["type"]))
  {
    $data["result"] = false;
    $data["type"] = __ ( "The gateway type is required.");
  }
  if ( ! empty ( $parameters["type"]) && ! in_array ( $parameters["type"], $_in["gwtypes"]))
  {
    $data["result"] = false;
    $data["type"] = __ ( "The gateway type is invalid.");
  }
  $parameters["priority"] = (int) $parameters["priority"];
  if ( empty ( $parameters["priority"]))
  {
    $data["result"] = false;
    $data["priority"] = __ ( "The gateway priority is required.");
  }
  if ( ! empty ( $parameters["priority"]) && $parameters["priority"] < 1 && $parameters["priority"] > 3)
  {
    $data["result"] = false;
    $data["priority"] = __ ( "The informed priority is invalid.");
  }
  $parameters["number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["number"])));
  if ( empty ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The gateway number is required.");
  }
  if ( ! array_key_exists ( "number", $data) && ! validateE164 ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The number must be in E.164 format, including the + prefix.");
  }
  $parameters["address"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["address"])));
  if ( empty ( $parameters["address"]))
  {
    $data["result"] = false;
    $data["address"] = __ ( "The gateway address is required.");
  }
  if ( ! empty ( $parameters["address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $parameters["address"]) && gethostbyname ( $parameters["address"]) == $parameters["address"])
  {
    $data["result"] = false;
    $data["address"] = __ ( "The gateway address is invalid.");
  }
  $parameters["port"] = (int) $parameters["port"];
  if ( empty ( $parameters["port"]))
  {
    $data["result"] = false;
    $data["port"] = __ ( "The gateway port is required.");
  }
  if ( ! empty ( $parameters["port"]) && ( $parameters["port"] < 1 || $parameters["port"] > 65535))
  {
    $data["result"] = false;
    $data["port"] = __ ( "The informed port is invalid.");
  }
  $parameters["fraction"] = (int) $parameters["fraction"];
  $parameters["minimum"] = (int) $parameters["minimum"];
  $parameters["discard"] = (int) $parameters["discard"];

  /**
   * Sanitize incoming float point variables
   */
  foreach ( $parameters as $key => $value)
  {
    if ( preg_match ( "/^[\d+|\d{1,3}\.]+,\d{2,}$/m", $value))
    {
      $parameters[$key] = (float) str_replace ( ",", ".", str_replace ( ".", "", $value));
    }
  }

  /**
   * Process provided routes
   */
  $routes = array ();
  foreach ( $parameters as $key => $value)
  {
    if ( substr ( $key, 0, 6) == "route_" && ! empty ( $value))
    {
      $id = substr ( $key, 6);
      if ( ! array_key_exists ( "cost_" . $id, $parameters))
      {
        $data["result"] = false;
        $data["cost_" . $id] = __ ( "The route cost is required.");
        continue;
      }
      $routes[] = array ( "route" => $value, "cost" => (float) $parameters["cost_" . $id]);
    }
  }
  if ( $parameters["config"] == "manual" && sizeof ( $routes) == 0)
  {
    $data["result"] = false;
    $data["route_1"] = __ ( "At least one route must be created.");
  }

  /**
   * Process provided translations
   */
  $translations = array ();
  foreach ( $parameters as $key => $value)
  {
    if ( substr ( $key, 0, 8) == "pattern_" && ! empty ( $value))
    {
      $id = substr ( $key, 8);
      if ( empty ( $parameters["remove_" . $id]) && empty ( $parameters["add_" . $id]))
      {
        $data["result"] = false;
        $data["pattern_" . $id] = __ ( "The translation must have a remotion, addition or both procedures.");
        continue;
      }
      $translations[] = array ( "pattern" => $value, "remove" => preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["remove_" . $id]))), "add" => preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["add_" . $id]))));
    }
  }

  /**
   * Check if provided number is recognized by the system
   */
  if ( ! array_key_exists ( "number", $data))
  {
    $number = filters_call ( "e164_identify", array ( "number" => $parameters["number"]));
    if ( ! array_key_exists ( "country", $number) || ! array_key_exists ( "areacode", $number) || ! array_key_exists ( "number", $number))
    {
      $data["result"] = false;
      $data["number"] = __ ( "The informed number is invalid.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "gateways_add_sanitize"))
  {
    $data = framework_call ( "gateways_add_sanitize", $parameters, false, $data);
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
  if ( framework_has_hook ( "gateways_add_pre"))
  {
    $parameters = framework_call ( "gateways_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new gateway record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Gateways` (`Description`, `Config`, `Active`, `Country`, `CountryCode`, `AreaCode`, `Number`, `Type`, `Priority`, `Address`, `Port`, `Username`, `Password`, `Routes`, `Translations`, `Discard`, `Minimum`, `Fraction`, `NAT`, `RPID`, `Qualify`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["state"] == "on" ? "Y" : "N") . "', " . $_in["mysql"]["id"]->real_escape_string ( $number["country"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $number["countrycode"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $number["areacode"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $number["number"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["type"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["priority"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["address"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["port"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["username"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["password"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $routes)) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $translations)) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["discard"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["minimum"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["fraction"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["nat"] == "on" ? "Y" : "N") . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["rpid"] == "on" ? "Y" : "N") . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["qualify"] == "on" ? "Y" : "N") . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "gateways_add_post"))
  {
    framework_call ( "gateways_add_post", $parameters);
  }

  /**
   * Add new gateway at Asterisk servers
   */
  if ( $parameters["state"] == "on")
  {
    $notify = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Domain" => $_in["general"]["domain"], "Username" => $parameters["username"], "Password" => $parameters["password"], "Address" => $parameters["address"], "Port" => $parameters["port"], "Qualify" => $parameters["qualify"] == "on", "NAT" => $parameters["nat"] == "on", "RPID" => $parameters["rpid"] == "on");
    if ( framework_has_hook ( "gateways_add_notify"))
    {
      $notify = framework_call ( "gateways_add_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "creategateway", $notify);
  }

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Config" => $parameters["config"], "Active" => $parameters["state"] == "on", "Country" => $number["country"], "CountryCode" => $number["countrycode"], "AreaCode" => $number["areacode"], "Number" => $number["number"], "Type" => $parameters["type"], "Priority" => $parameters["priority"], "Address" => $parameters["address"], "Port" => $parameters["port"], "Username" => $parameters["username"], "Password" => $parameters["password"], "Routes" => $routes, "Translations" => $translations, "Discard" => $parameters["discard"], "Minimum" => $parameters["minimum"], "Fraction" => $parameters["fraction"], "NAT" => ( $parameters["nat"] == "on" ? "Y" : "N"), "RPID" => ( $parameters["rpid"] == "on" ? "Y" : "N"), "Qualify" => ( $parameters["qualify"] == "on" ? "Y" : "N"));
  if ( framework_has_hook ( "gateways_add_audit"))
  {
    $audit = framework_call ( "gateways_add_audit", $parameters, false, $audit);
  }
  audit ( "gateway", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "gateways/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing gateway
 */
framework_add_hook ( "gateways_edit", "gateways_edit");
framework_add_permission ( "gateways_edit", __ ( "Edit gateways"));
framework_add_api_call ( "/gateways/:id", "Modify", "gateways_edit", array ( "permissions" => array ( "user", "gateways_edit")));
framework_add_api_call ( "/gateways/:id", "Edit", "gateways_edit", array ( "permissions" => array ( "user", "gateways_edit")));

/**
 * Function to edit an existing gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_edit ( $buffer, $parameters)
{
  global $_in;

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
    $data["description"] = __ ( "The gateway description is required.");
  }
  $parameters["type"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["type"])));
  if ( empty ( $parameters["type"]))
  {
    $data["result"] = false;
    $data["type"] = __ ( "The gateway type is required.");
  }
  if ( ! empty ( $parameters["type"]) && ! in_array ( $parameters["type"], $_in["gwtypes"]))
  {
    $data["result"] = false;
    $data["type"] = __ ( "The gateway type is invalid.");
  }
  $parameters["priority"] = (int) $parameters["priority"];
  if ( empty ( $parameters["priority"]))
  {
    $data["result"] = false;
    $data["priority"] = __ ( "The gateway priority is required.");
  }
  if ( ! empty ( $parameters["priority"]) && $parameters["priority"] < 1 && $parameters["priority"] > 3)
  {
    $data["result"] = false;
    $data["priority"] = __ ( "The informed priority is invalid.");
  }
  $parameters["number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["number"])));
  if ( empty ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The gateway number is required.");
  }
  if ( ! array_key_exists ( "number", $data) && ! validateE164 ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The number must be in E.164 format, including the + prefix.");
  }
  $parameters["address"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["address"])));
  if ( empty ( $parameters["address"]))
  {
    $data["result"] = false;
    $data["address"] = __ ( "The gateway address is required.");
  }
  if ( ! empty ( $parameters["address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $parameters["address"]) && gethostbyname ( $parameters["address"]) == $parameters["address"])
  {
    $data["result"] = false;
    $data["address"] = __ ( "The gateway address is invalid.");
  }
  $parameters["port"] = (int) $parameters["port"];
  if ( empty ( $parameters["port"]))
  {
    $data["result"] = false;
    $data["port"] = __ ( "The gateway port is required.");
  }
  if ( ! empty ( $parameters["port"]) && ( $parameters["port"] < 1 || $parameters["port"] > 65535))
  {
    $data["result"] = false;
    $data["port"] = __ ( "The informed port is invalid.");
  }
  $parameters["fraction"] = (int) $parameters["fraction"];
  $parameters["minimum"] = (int) $parameters["minimum"];
  $parameters["discard"] = (int) $parameters["discard"];

  /**
   * Sanitize incoming float point variables
   */
  foreach ( $parameters as $key => $value)
  {
    if ( preg_match ( "/^[\d+|\d{1,3}\.]+,\d{2,}$/m", $value))
    {
      $parameters[$key] = (float) str_replace ( ",", ".", str_replace ( ".", "", $value));
    }
  }

  /**
   * Process provided routes
   */
  $routes = array ();
  foreach ( $parameters as $key => $value)
  {
    if ( substr ( $key, 0, 6) == "route_" && ! empty ( $value))
    {
      $id = substr ( $key, 6);
      if ( ! array_key_exists ( "cost_" . $id, $parameters))
      {
        $data["result"] = false;
        $data["cost_" . $id] = __ ( "The route cost is required.");
        continue;
      }
      $routes[] = array ( "route" => $value, "cost" => (float) $parameters["cost_" . $id]);
    }
  }
  if ( $parameters["config"] == "manual" && sizeof ( $routes) == 0)
  {
    $data["result"] = false;
    $data["route_1"] = __ ( "At least one route must be created.");
  }

  /**
   * Process provided translations
   */
  $translations = array ();
  foreach ( $parameters as $key => $value)
  {
    if ( substr ( $key, 0, 8) == "pattern_" && ! empty ( $value))
    {
      $id = substr ( $key, 8);
      if ( empty ( $parameters["remove_" . $id]) && empty ( $parameters["add_" . $id]))
      {
        $data["result"] = false;
        $data["pattern_" . $id] = __ ( "The translation must have a remotion, addition or both procedures.");
        continue;
      }
      $translations[] = array ( "pattern" => $value, "remove" => preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["remove_" . $id]))), "add" => preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["add_" . $id]))));
    }
  }

  /**
   * Check if provided number is recognized by the system
   */
  if ( ! array_key_exists ( "number", $data))
  {
    $number = filters_call ( "e164_identify", array ( "number" => $parameters["number"]));
    if ( ! array_key_exists ( "country", $number) || ! array_key_exists ( "areacode", $number) || ! array_key_exists ( "number", $number))
    {
      $data["result"] = false;
      $data["number"] = __ ( "The informed number is invalid.");
    }
  }

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "gateways_edit_sanitize"))
  {
    $data = framework_call ( "gateways_edit_sanitize", $parameters, false, $data);
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
  if ( framework_has_hook ( "gateways_edit_pre"))
  {
    $parameters = framework_call ( "gateways_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Get gateway informations from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
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

  /**
   * Update gateway record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Gateways` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Config` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]) . "', `Active` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["state"] == "on" ? "Y" : "N") . "', `Country` = " . $_in["mysql"]["id"]->real_escape_string ( $number["country"]) . ", `CountryCode` = " . $_in["mysql"]["id"]->real_escape_string ( $number["countrycode"]) . ", `AreaCode` = " . $_in["mysql"]["id"]->real_escape_string ( $number["areacode"]) . ", `Number` = " . $_in["mysql"]["id"]->real_escape_string ( $number["number"]) . ", `Type` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["type"]) . "', `Priority` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["priority"]) . ", `Address` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["address"]) . "', `Port` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["port"]) . ", `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["username"]) . "', `Password` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["password"]) . "', `Routes` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $routes)) . "', `Translations` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $translations)) . "', `Discard` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["discard"]) . ", `Minimum` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["minimum"]) . ", `Fraction` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["fraction"]) . ", `NAT` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["nat"] == "on" ? "Y" : "N") . "', `RPID` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["rpid"] == "on" ? "Y" : "N") . "', `Qualify` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["qualify"] == "on" ? "Y" : "N") . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "gateways_edit_post"))
  {
    framework_call ( "gateways_edit_post", $parameters);
  }

  /**
   * If gateway were active, notify Asterisk servers about changes
   */
  if ( $gateway["Active"] == "Y")
  {
    if ( $parameters["state"] == "on")
    {
      if ( $gateway["Description"] != $parameters["description"] || $gateway["Address"] != $parameters["address"] || $gateway["Port"] != $parameters["port"] || $gateway["Username"] != $parameters["username"] || $gateway["Password"] != $parameters["password"] || ( $gateway["NAT"] == "Y") != ( $parameters["nat"] == "on") || ( $gateway["RPID"] == "Y") != ( $parameters["rpid"] == "on") || ( $gateway["Qualify"] == "Y") != ( $parameters["qualify"] == "on"))
      {
        $notify = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Domain" => $_in["general"]["domain"], "Username" => $parameters["username"], "Password" => $parameters["password"], "Address" => $parameters["address"], "Port" => $parameters["port"], "Qualify" => $parameters["qualify"] == "on", "NAT" => $parameters["nat"] == "on", "RPID" => $parameters["rpid"] == "on");
        if ( framework_has_hook ( "gateways_edit_notify"))
        {
          $notify = framework_call ( "gateways_edit_notify", $parameters, false, $notify);
        }
        notify_server ( 0, "changegateway", $notify);
      }
    } else {
      $notify = array ( "ID" => $gateway["ID"]);
      if ( framework_has_hook ( "gateways_remove_notify"))
      {
        $notify = framework_call ( "gateways_remove_notify", $parameters, false, $notify);
      }
      notify_server ( 0, "removegateway", $notify);
    }
  }

  /**
   * If gateway were inactive and has activated, notify Asterisk servers about changes
   */
  if ( $gateway["Active"] == "N" && $parameters["state"] == "on")
  {
    $notify = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Domain" => $_in["general"]["domain"], "Username" => $parameters["username"], "Password" => $parameters["password"], "Address" => $parameters["address"], "Port" => $parameters["port"], "Qualify" => $parameters["qualify"] == "on", "NAT" => $parameters["nat"] == "on", "RPID" => $parameters["rpid"] == "on");
    if ( framework_has_hook ( "gateways_add_notify"))
    {
      $notify = framework_call ( "gateways_add_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "creategateway", $notify);
  }

  /**
   * Add audit record
   */
  $audit["ID"] = $gateway["ID"];
  if ( $gateway["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $gateway["Description"], "New" => $parameters["description"]);
  }
  if ( ( $gateway["Active"] == "Y") != ( $parameters["state"] == "on"))
  {
    $audit["Active"] = array ( "Old" => ( $gateway["Active"] == "Y"), "New" => ( $parameters["state"] == "on"));
  }
  if ( $gateway["Type"] != $parameters["type"])
  {
    $audit["Type"] = array ( "Old" => $gateway["Type"], "New" => $parameters["type"]);
  }
  if ( $gateway["Priority"] != $parameters["priority"])
  {
    $audit["Priority"] = array ( "Old" => $gateway["Priority"], "New" => $parameters["priority"]);
  }
  if ( $gateway["Config"] != $parameters["config"])
  {
    $audit["Config"] = array ( "Old" => $gateway["Config"], "New" => $parameters["config"]);
  }
  if ( "+" . $gateway["CountryCode"] . $gateway["AreaCode"] . $gateway["Number"] != $parameters["number"])
  {
    $audit["Number"] = array ( "Old" => "+" . $gateway["CountryCode"] . $gateway["AreaCode"] . $gateway["Number"], "New" => $parameters["number"]);
  }
  if ( $gateway["Address"] != $parameters["address"])
  {
    $audit["Address"] = array ( "Old" => $gateway["Address"], "New" => $parameters["address"]);
  }
  if ( $gateway["Port"] != $parameters["port"])
  {
    $audit["Port"] = array ( "Old" => $gateway["Port"], "New" => $parameters["port"]);
  }
  if ( $gateway["Username"] != $parameters["username"])
  {
    $audit["Username"] = array ( "Old" => $gateway["Username"], "New" => $parameters["username"]);
  }
  if ( $gateway["Password"] != $parameters["password"])
  {
    $audit["Password"] = array ( "Old" => $gateway["Password"], "New" => $parameters["password"]);
  }
  if ( ( $gateway["NAT"] == "Y") != ( $parameters["nat"] == "on"))
  {
    $audit["NAT"] = array ( "Old" => ( $gateway["NAT"] == "Y"), "New" => ( $parameters["nat"] == "on"));
  }
  if ( ( $gateway["RPID"] == "Y") != ( $parameters["rpid"] == "on"))
  {
    $audit["RPID"] = array ( "Old" => ( $gateway["RPID"] == "Y"), "New" => ( $parameters["rpid"] == "on"));
  }
  if ( ( $gateway["Qualify"] == "Y") != ( $parameters["qualify"] == "on"))
  {
    $audit["Qualify"] = array ( "Old" => ( $gateway["Qualify"] == "Y"), "New" => ( $parameters["qualify"] == "on"));
  }
  if ( $gateway["Discard"] != $parameters["discard"])
  {
    $audit["Discard"] = array ( "Old" => $gateway["Discard"], "New" => $parameters["discard"]);
  }
  if ( $gateway["Minimum"] != $parameters["minimum"])
  {
    $audit["Minimum"] = array ( "Old" => $gateway["Minimum"], "New" => $parameters["minimum"]);
  }
  if ( $gateway["Fraction"] != $parameters["fraction"])
  {
    $audit["Fraction"] = array ( "Old" => $gateway["Fraction"], "New" => $parameters["fraction"]);
  }
  if ( ! array_compare ( json_decode ( $gateway["Routes"], true), $routes))
  {
    $audit["Routes"] = array ( "Old" => json_decode ( $gateway["Routes"], true), "New" => $routes);
  }
  if ( ! array_compare ( json_decode ( $gateway["Translations"], true), $translations))
  {
    $audit["Translations"] = array ( "Old" => json_decode ( $gateway["Translations"], true), "New" => $translations);
  }
  if ( framework_has_hook ( "gateways_edit_audit"))
  {
    $audit = framework_call ( "gateways_edit_audit", $parameters, false, $audit);
  }
  audit ( "gateway", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a gateway
 */
framework_add_hook ( "gateways_remove", "gateways_remove");
framework_add_permission ( "gateways_remove", __ ( "Remove gateways"));
framework_add_api_call ( "/gateways/:id", "Delete", "gateways_remove", array ( "permissions" => array ( "user", "gateways_remove")));

/**
 * Function to remove an existing gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if gateway exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
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

  /**
   * Call remove pre hook, if exist
   */
  if ( framework_has_hook ( "gateways_remove_pre"))
  {
    $parameters = framework_call ( "gateways_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove gateway database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "gateways_remove_post"))
  {
    framework_call ( "gateways_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $gateway["ID"]);
  if ( framework_has_hook ( "gateways_remove_notify"))
  {
    $notify = framework_call ( "gateways_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "removegateway", $notify);

  /**
   * Insert audit registry
   */
  $audit = $gateway;
  if ( framework_has_hook ( "gateways_remove_audit"))
  {
    $audit = framework_call ( "gateways_remove_audit", $parameters, false, $audit);
  }
  audit ( "gateway", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to intercept new server and server reinstall
 */
framework_add_hook ( "servers_add_post", "gateways_server_reconfig");
framework_add_hook ( "servers_reinstall_config", "gateways_server_reconfig");

/**
 * Function to notify server to include all gateways.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all gateways and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $gateway = $result->fetch_assoc ())
  {
    if ( $gateway["Active"] == "Y")
    {
      $notify = array ( "ID" => $gateway["ID"], "Description" => $gateway["Description"], "Domain" => $_in["general"]["domain"], "Username" => $gateway["Username"], "Password" => $gateway["Password"], "Address" => $gateway["Address"], "Port" => $gateway["Port"], "Qualify" => $gateway["Qualify"] == "Y", "NAT" => $gateway["NAT"] == "Y", "RPID" => $gateway["RPID"] == "Y");
      if ( framework_has_hook ( "gateways_add_notify"))
      {
        $notify = framework_call ( "gateways_add_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["id"], "creategateway", $notify);
    }
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
