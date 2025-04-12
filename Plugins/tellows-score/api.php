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
 * VoIP Domain Tellows score configuration module API. This module add the key
 * configuration to enable Tellows score to block spammers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Tellows Score
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API hook to extend system configurations view to support Tellows Score system
 */
framework_add_function_documentation (
  "configs_view",
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "properties" => array (
            "Tellows" => array (
              "type" => "object",
              "description" => __ ( "Tellows Score system global configuration variables."),
              "properties" => array (
                "Key" => array (
                  "type" => "string",
                  "description" => __ ( "Tellows API MD5 key."),
                  "pattern" => "/^[A-Fa-f0-9]{32}$/",
                  "minLength" => 32,
                  "maxLength" => 32,
                  "example" => "1a79a4d60de6718e8e5b326e338ae533"
                ),
                "AntiSpam" => array (
                  "type" => "boolean",
                  "description" => __ ( "If the system should drop calls with score equal or higher than the Score variable value."),
                  "example" => true
                ),
                "Score" => array (
                  "type" => "integer",
                  "description" => __ ( "If selected to drop calls based on score, set the minimum score to drop. Recommended value is 9."),
                  "minimum" => 6,
                  "maximum" => 10,
                  "example" => 9
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "configs_view",
  "tellows_view"
);

/**
 * Function to generate Tellows system configurations information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tellows_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get configurations from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` = 'Tellows'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $data = array ( "Tellows" => array ( "Key" => "", "AntiSpam" => "", "Score" => ""));
  while ( $entry = $result->fetch_assoc ())
  {
    if ( is_json ( $entry["Data"]))
    {
      $entry["Data"] = json_decode ( $entry["Data"], true);
    }
    $data[$entry["Key"]] = $entry["Data"];
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API hook to extend system configurations edit to support Tellows Score system
 */
framework_add_function_documentation (
  "configs_edit",
  array (
    "requests" => array (
      "properties" => array (
        "Tellows" => array (
          "type" => "object",
          "properties" => array (
            "Key" => array (
              "type" => "string",
              "description" => __ ( "Tellows API MD5 key."),
              "pattern" => "/^[A-Fa-f0-9]{32}$/",
              "minLength" => 32,
              "maxLength" => 32,
              "example" => "1a79a4d60de6718e8e5b326e338ae533"
            ),
            "AntiSpam" => array (
              "type" => "boolean",
              "description" => __ ( "If the system should drop calls with score equal or higher than the Score variable value."),
              "example" => true
            ),
            "Score" => array (
              "type" => "integer",
              "description" => __ ( "If selected to drop calls based on score, set the minimum score to drop. Recommended value is 9."),
              "minimum" => 6,
              "maximum" => 10,
              "example" => 9
            )
          )
        )
      )
    )
  )
);

/**
 * API calls to edit configurations
 */
framework_add_hook ( "configs_edit_sanitize", "tellows_edit_sanitize");
framework_add_hook ( "configs_edit_pre", "tellows_edit_pre");
framework_add_hook ( "configs_edit_post", "tellows_edit_post");
framework_add_hook ( "configs_edit_audit", "tellows_edit_audit");

/**
 * Function to sanitize Tellows system configurations information change.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tellows_edit_sanitize ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check Tellows parameters
   */
  if ( is_array ( $parameters["Tellows"]))
  {
    $data = array ();
    if ( array_key_exists ( "Key", $parameters["Tellows"]) && ! empty ( $parameters["Tellows"]["Key"]) && ! preg_match ( "/^[A-Fa-f0-9]{32}$/", $parameters["Tellows"]["Key"]))
    {
      $data["TellowsKey"] = __ ( "Invalid Tellows MD5 key.");
    }
    if ( array_key_exists ( "AntiSpam", $parameters["Tellows"]))
    {
      $parameters["Tellows"]["AntiSpam"] = $parameters["Tellows"]["AntiSpam"] == "true";
      if ( array_key_exists ( "TellowsKey", $data) && $parameters["Tellows"]["AntiSpam"] == true)
      {
        $data["TellowsAntiSpam"] = __ ( "Tellows AntiSpam cannot be enabled without a valid key.");
      }
    }
    if ( array_key_exists ( "Score", $parameters["Tellows"]))
    {
      $parameters["Tellows"]["Score"] = (int) $parameters["Tellows"]["Score"];
      if ( $parameters["Tellows"]["Score"] < 6 || $parameters["Tellows"]["Score"] > 10)
      {
        $data["TellowsScore"] = __ ( "Tellows score must be between 6 and 10.");
      }
    }
  }

  /**
   * Return data to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to filter Tellows system configurations information change.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tellows_edit_pre ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get configurations from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` = 'Tellows'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $data = array ( "ORIGINAL" => array ( "Tellows" => array ( "Key" => "", "AntiSpam" => "", "Score" => "")));
  while ( $entry = $result->fetch_assoc ())
  {
    if ( is_json ( $entry["Data"]))
    {
      $entry["Data"] = json_decode ( $entry["Data"], true);
    }
    $data["ORIGINAL"][$entry["Key"]] = $entry["Data"];
  }

  /**
   * Sanitize Tellows variables
   */
  $data["Tellows"] = array ( "Key" => $parameters["Tellows"]["Key"], "AntiSpam" => (boolean) $parameters["Tellows"]["AntiSpam"], "Score" => (int) $parameters["Tellows"]["Score"]);
  unset ( $buffer["Tellows"]);

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to update Tellows system configurations information change.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tellows_edit_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Change Tellows configuration entry
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Data`) VALUES ('Tellows', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Tellows"])) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Tellows"])) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Key" => $parameters["Tellows"]["Key"], "AntiSpam" => $parameters["Tellows"]["AntiSpam"], "Score" => $parameters["Tellows"]["Score"]);
  if ( framework_has_hook ( "configs_tellows_notify"))
  {
    $notify = framework_call ( "configs_tellows_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "configs_tellows", $notify);

  /**
   * Return OK
   */
  return $buffer;
}

/**
 * Function to generate audit record on Tellows system configurations information change.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tellows_config_audit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add Tellows changes if needed
   */
  if ( $parameters["Tellows"] !== $parameters["ORIGINAL"]["Tellows"])
  {
    $buffer["Tellows"] = array ( "Old" => $parameters["ORIGINAL"]["Tellows"], "New" => $parameters["Tellows"]);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
