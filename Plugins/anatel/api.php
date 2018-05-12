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
 * VoIP Domain gateways Brazilian ANATEL regulatory agency standard module. This
 * module add the api calls related to gateways.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Anatel
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to get gateway information
 */
framework_add_hook ( "gateways_view", "gateways_view_anatel");

/**
 * Function to generate gateway informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_view_anatel ( $buffer, $parameters)
{
  global $_in;

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
   * Return if config type is not ANATEL
   */
  if ( $gateway["Config"] != "ANATEL")
  {
    return $buffer;
  }

  /**
   * Format data
   */
  $anatel = json_decode ( $gateway["ANATEL"], true);
  $data = array ();
  $data["anatel_local"] = sprintf ( "%.5f", $anatel["local"]);
  $data["anatel_interstate"] = $anatel["interstate"];
  $data["anatel_international"] = $anatel["international"];
  for ( $x = 1; $x <= 4; $x++)
  {
    $data["anatel_dc" . $x . "d"] = sprintf ( "%.5f", $anatel["dc" . $x . "d"]);
    $data["anatel_dc" . $x . "n"] = sprintf ( "%.5f", $anatel["dc" . $x . "n"]);
    $data["anatel_dc" . $x . "r"] = sprintf ( "%.5f", $anatel["dc" . $x . "r"]);
    $data["anatel_dc" . $x . "s"] = sprintf ( "%.5f", $anatel["dc" . $x . "s"]);
  }
  foreach ( $_in["anatel"]["operators"] as $operator)
  {
    $key = preg_replace ( "/[^a-z0-9]/", "", strtolower ( $operator));
    $data["anatel_mobile_" . $key . "_1"] = sprintf ( "%.5f", $anatel["mobile_" . $key . "_1"]);
    $data["anatel_mobile_" . $key . "_2"] = sprintf ( "%.5f", $anatel["mobile_" . $key . "_2"]);
    $data["anatel_mobile_" . $key . "_3"] = sprintf ( "%.5f", $anatel["mobile_" . $key . "_3"]);
  }
  for ( $x = 1; $x <= 9; $x++)
  {
    $data["anatel_ldi" . $x] = sprintf ( "%.5f", $anatel["ldi" . $x]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API calls to add a new gateway
 */
framework_add_hook ( "gateways_add_sanitize", "gateways_anatel_add_sanitize");
framework_add_hook ( "gateways_add_post", "gateways_anatel_add_post");
framework_add_hook ( "gateways_add_audit", "gateways_anatel_add_audit");

/**
 * Function to sanitize a new gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_anatel_add_sanitize ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  if ( $parameters["config"] != "ANATEL")
  {
    return $buffer;
  }

  /**
   * Check if basic values are provided
   */
  $data = array ();
  if ( empty ( $parameters["anatel_interstate"]))
  {
    $data["result"] = false;
    $data["anatel_interstate"] = __ ( "The LDN operator code is required.");
  }
  if ( empty ( $parameters["anatel_international"]))
  {
    $data["result"] = false;
    $data["anatel_international"] = __ ( "The LDI operator code is required.");
  }

  /**
   * Check if gateway number is valid in Brazil
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Centrais`.`Localidade`, `CNL`.`Latitude`, `CNL`.`Longitude` FROM `ANATEL`.`CNL` LEFT JOIN `ANATEL`.`Centrais` ON `Centrais`.`Localidade` = `CNL`.`Codigo` WHERE `Centrais`.`Inicial` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " AND `Centrais`.`Final` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " LIMIT 0, 1"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["result"] = false;
    $data["number"] = __ ( "The informed number is invalid.");
  }

  /**
   * Return OK to user
   */
  if ( is_array ( $buffer) && array_key_exists ( "result", $data))
  {
    unset ( $buffer["result"]);
  }
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to update a new inserted gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_anatel_add_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  if ( $parameters["config"] != "ANATEL")
  {
    return $buffer;
  }

  /**
   * Create ANATEL values array
   */
  $anatel = array ();
  foreach ( $parameters as $key => $parameter)
  {
    if ( substr ( $key, 0, 7) == "anatel_")
    {
      $anatel[substr ( $key, 7)] = $parameter;
    }
  }
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if gateway number is valid in Brazil
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Centrais`.`Localidade`, `CNL`.`Latitude`, `CNL`.`Longitude` FROM `ANATEL`.`CNL` LEFT JOIN `ANATEL`.`Centrais` ON `Centrais`.`Localidade` = `CNL`.`Codigo` WHERE `Centrais`.`Inicial` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " AND `Centrais`.`Final` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " LIMIT 0, 1"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $data = $result->fetch_assoc ();
  $anatel["CNL"] = $data["Localidade"];
  $anatel["Longitude"] = $data["Longitude"];
  $anatel["Latitude"] = $data["Latitude"];

  /**
   * Update gateway record to insert ANATEL variables
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Gateways` SET `Anatel` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $anatel)) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Return OK
   */
  return $buffer;
}

/**
 * Function to generate audit record on a new inserted gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_anatel_add_audit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  if ( $parameters["config"] != "ANATEL")
  {
    return $buffer;
  }

  /**
   * Create ANATEL values array
   */
  $biffer["anatel"] = array ();
  foreach ( $parameters as $key => $parameter)
  {
    if ( substr ( $key, 0, 7) == "anatel_")
    {
      $buffer["anatel"][substr ( $key, 7)] = $parameter;
    }
  }
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if gateway number is valid in Brazil
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Centrais`.`Localidade`, `CNL`.`Latitude`, `CNL`.`Longitude` FROM `ANATEL`.`CNL` LEFT JOIN `ANATEL`.`Centrais` ON `Centrais`.`Localidade` = `CNL`.`Codigo` WHERE `Centrais`.`Inicial` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " AND `Centrais`.`Final` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " LIMIT 0, 1"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $data = $result->fetch_assoc ();
  $buffer["anatel"]["CNL"] = $data["Localidade"];
  $buffer["anatel"]["Longitude"] = $data["Longitude"];
  $buffer["anatel"]["Latitude"] = $data["Latitude"];

  /**
   * Return audit data
   */
  return $buffer;
}

/**
 * API calls to edit an existing gateway
 */
framework_add_hook ( "gateways_edit_sanitize", "gateways_anatel_edit_sanitize");
framework_add_hook ( "gateways_edit_post", "gateways_anatel_edit_post");

/**
 * Function to sanitize an editing gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_anatel_edit_sanitize ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  if ( $parameters["config"] != "ANATEL")
  {
    return $buffer;
  }

  /**
   * Check if basic values are provided
   */
  $data = array ();
  if ( empty ( $parameters["anatel_interstate"]))
  {
    $data["result"] = false;
    $data["anatel_interstate"] = __ ( "The LDN operator code is required.");
  }
  if ( empty ( $parameters["anatel_international"]))
  {
    $data["result"] = false;
    $data["anatel_international"] = __ ( "The LDI operator code is required.");
  }

  /**
   * Check if gateway number is valid in Brazil
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Centrais`.`Localidade`, `CNL`.`Latitude`, `CNL`.`Longitude` FROM `ANATEL`.`CNL` LEFT JOIN `ANATEL`.`Centrais` ON `Centrais`.`Localidade` = `CNL`.`Codigo` WHERE `Centrais`.`Inicial` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " AND `Centrais`.`Final` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " LIMIT 0, 1"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["result"] = false;
    $data["number"] = __ ( "The informed number is invalid.");
  }

  /**
   * Return OK to user
   */
  if ( is_array ( $buffer) && array_key_exists ( "result", $data))
  {
    unset ( $buffer["result"]);
  }
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to update an edited gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_anatel_edit_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  if ( $parameters["config"] != "ANATEL")
  {
    return $buffer;
  }
  $parameters["id"] = (int) $parameters["id"];

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
  $gateway["ANATEL"] = json_decode ( $gateway["ANATEL"], true);

  /**
   * Create ANATEL values array
   */
  $anatel = array ();
  foreach ( $parameters as $key => $parameter)
  {
    if ( substr ( $key, 0, 7) == "anatel_")
    {
      $anatel[substr ( $key, 7)] = $parameter;
    }
  }

  /**
   * Check if gateway number is valid in Brazil
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Centrais`.`Localidade`, `CNL`.`Latitude`, `CNL`.`Longitude` FROM `ANATEL`.`CNL` LEFT JOIN `ANATEL`.`Centrais` ON `Centrais`.`Localidade` = `CNL`.`Codigo` WHERE `Centrais`.`Inicial` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " AND `Centrais`.`Final` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " LIMIT 0, 1"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $data = $result->fetch_assoc ();
  $anatel["CNL"] = $data["Localidade"];
  $anatel["Longitude"] = $data["Longitude"];
  $anatel["Latitude"] = $data["Latitude"];

  /**
   * If there's no change, return OK to user
   */
  if ( array_compare ( $gateway["ANATEL"], $anatel))
  {
    return $buffer;
  }

  /**
   * Update gateway record to new ANATEL variables
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Gateways` SET `Anatel` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $anatel)) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Return buffer
   */
  return $buffer;
}

/**
 * Function to generate audit record on update an edited gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_anatel_edit_audit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  if ( $parameters["config"] != "ANATEL")
  {
    return $buffer;
  }
  $parameters["id"] = (int) $parameters["id"];

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
  $gateway["ANATEL"] = json_decode ( $gateway["ANATEL"], true);

  /**
   * Create ANATEL values array
   */
  $anatel = array ();
  foreach ( $parameters as $key => $parameter)
  {
    if ( substr ( $key, 0, 7) == "anatel_")
    {
      $anatel[substr ( $key, 7)] = $parameter;
    }
  }

  /**
   * Check if gateway number is valid in Brazil
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Centrais`.`Localidade`, `CNL`.`Latitude`, `CNL`.`Longitude` FROM `ANATEL`.`CNL` LEFT JOIN `ANATEL`.`Centrais` ON `Centrais`.`Localidade` = `CNL`.`Codigo` WHERE `Centrais`.`Inicial` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " AND `Centrais`.`Final` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $parameters["number"], 3)) . " LIMIT 0, 1"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $data = $result->fetch_assoc ();
  $anatel["CNL"] = $data["Localidade"];
  $anatel["Longitude"] = $data["Longitude"];
  $anatel["Latitude"] = $data["Latitude"];

  /**
   * Add ANATEL changes if needed
   */
  if ( array_compare ( $gateway["ANATEL"], $anatel))
  {
    $buffer["ANATEL"] = array ( "Old" => $gateway["ANATEL"], "New" => $anatel);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
