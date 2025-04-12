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
 * VoIP Domain extensions IVRs module API. This module add the API calls
 * related to extensions IVRs.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions IVRs
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API hook to extend extensions addition of IVR type
 */
framework_add_function_documentation (
  "extensions_search",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "ivr"),
          "example" => "ivr"
        )
      )
    ),
    "response" => array (
      200 => array (
        "schema" => array (
          "items" => array (
            "properties" => array (
              "Type" => array (
                "enum" => array ( "ivr"),
                "example" => "ivr"
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "extensions_view_ivr",
  "extensions_view_ivr"
);
framework_add_function_documentation (
  "extensions_view",
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "properties" => array (
            "Type" => array (
              "enum" => array ( "ivr"),
              "example" => array ( "ivr")
            ),
            "oneOf" => array (
              array (
                "type" => "object",
                "description" => __ ( "An object with IVR information."),
                "properties" => array (
                  "IVR" => array (
                    "type" => "object",
                    "properties" => array (
                      "ID" => array (
                        "type" => "integer",
                        "description" => __ ( "The IVR internal system unique identifier."),
                        "example" => 1
                      ),
                      "Name" => array (
                        "type" => "string",
                        "description" => __ ( "The IVR name."),
                        "example" => __ ( "Sales")
                      ),
                      "Description" => array (
                        "type" => "integer",
                        "description" => __ ( "The IVR description."),
                        "example" => __ ( "Sales IVR workflow.")
                      )
                    )
                  )
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
  "extensions_add_ivr_validate",
  "extensions_ivr_validate"
);
framework_add_hook (
  "extensions_edit_ivr_validate",
  "extensions_ivr_validate"
);
framework_add_hook (
  "extensions_add_ivr_post",
  "extensions_add_ivr_post"
);
framework_add_hook (
  "extensions_edit_ivr_post",
  "extensions_edit_ivr_post"
);
framework_add_hook (
  "extensions_add_ivr_audit",
  "extensions_add_ivr_audit"
);
framework_add_hook (
  "extensions_edit_ivr_audit",
  "extensions_edit_ivr_audit"
);
framework_add_function_documentation (
  "extensions_add",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "ivr")
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "IVR" => array (
                "type" => "integer",
                "description" => __ ( "The IVR system unique identifier."),
                "required" => true
              )
            )
          )
        )
      )
    ),
    "response" => array (
      422 => array (
        "schema" => array (
          "properties" => array (
            "anyOf" => array (
              array (
                "type" => "object",
                "properties" => array (
                  "IVR" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The selected IVR is invalid.")
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_function_documentation (
  "extensions_edit",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "ivr")
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "IVR" => array (
                "type" => "integer",
                "description" => __ ( "The IVR system unique identifier."),
                "required" => true
              )
            )
          )
        )
      )
    ),
    "response" => array (
      422 => array (
        "schema" => array (
          "properties" => array (
            "anyOf" => array (
              array (
                "type" => "object",
                "properties" => array (
                  "IVR" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The selected IVR is invalid.")
                  )
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
  "extensions_remove_ivr_pre",
  "extensions_remove_ivr_pre"
);
framework_add_hook (
  "extensions_remove_ivr_audit",
  "extensions_remove_ivr_audit"
);

/**
 * Function to extend extensions with IVR information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_view_ivr ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch IVR information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `IVRs`.`ID`, `IVRs`.`Name`, `IVRs`.`Description` FROM `IVRs` LEFT JOIN `ExtensionIVR` ON `ExtensionIVR`.`IVR` = `IVRs`.`ID` WHERE `ExtensionIVR`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $ivr = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["IVR"] = array ( "ID" => $ivr["ID"], "Name" => $ivr["Name"], "Description" => $ivr["Description"]);

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to extend extensions addition/edition validate of IVR type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_ivr_validate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Validate parameters
   */
  if ( $parameters["IVR"] != (int) $parameters["IVR"])
  {
    $buffer["IVR"] = __ ( "The selected IVR is invalid.");
  }
  $parameters["IVR"] = (int) $parameters["IVR"];
  if ( ! array_key_exists ( "IVR", $buffer) && empty ( $parameters["IVR"]))
  {
    $buffer["IVR"] = __ ( "The IVR is required.");
  }

  /**
   * Check if IVR exists
   */
  if ( ! array_key_exists ( "IVR", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `IVRs` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["IVR"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $buffer["IVR"] = __ ( "The selected IVR is invalid.");
    }
  }

  /**
   * If it's an edition, fetch old IVR
   */
  if ( array_key_exists ( "ORIGINAL", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `IVR` FROM `ExtensionIVR` WHERE `ExtensionIVR`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
      exit ();
    }
    $buffer["ORIGINAL"]["IVR"] = $result->fetch_assoc ()["IVR"];
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post addition of IVR type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_ivr_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add new extension IVR record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionIVR` (`IVR`, `Extension`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["IVR"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ")"))
  {
    framework_call ( "extensions_add_abort", $parameters);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Get IVR name
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `IVRs` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["IVR"])))
  {
    framework_call ( "extensions_add_abort", $parameters);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    framework_call ( "extensions_add_abort", $parameters);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $ivr = $result->fetch_assoc ();

  /**
   * Associate IVR to extension at Asterisk server
   */
  $notify = array ( "Number" => $parameters["Number"], "IVR" => $ivr["Name"]);
  if ( framework_has_hook ( "extension_ivr_add_notify"))
  {
    $notify = framework_call ( "extension_ivr_add_notify", $parameters, false, $notify);
  }
  notify_server ( $range["Server"], "extension_ivr_add", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions audit addition of IVR type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_ivr_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  $buffer["IVR"] = $parameters["IVR"];

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post edition of IVR type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_ivr_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check if there's IVR change, if changed, update database
   */
  if ( ! array_compare ( $parameters["ORIGINAL"]["IVR"], $parameters["IVR"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `ExtensionIVR` SET `IVR` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["IVR"]) . " WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      framework_call ( "extensions_edit_abort", $parameters);
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Get IVR name
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `IVRs` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["IVR"])))
  {
    framework_call ( "extensions_edit_abort", $parameters);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    framework_call ( "extensions_edit_abort", $parameters);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $ivr = $result->fetch_assoc ();

  /**
   * Notify server about change
   */
  $notify = array ( "Number" => $parameters["ORIGINAL"]["Number"], "NewNumber" => $parameters["Number"], "IVR" => $ivr["Name"]);
  if ( framework_has_hook ( "extension_ivr_edit_notify"))
  {
    $notify = framework_call ( "extension_ivr_edit_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["range"]["Server"], "extension_ivr_change", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions audit edition of IVR type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_ivr_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  if ( $parameters["ORIGINAL"]["IVR"] != $parameters["IVR"])
  {
    $buffer["IVR"] = array ( "Old" => $parameters["ORIGINAL"]["IVR"], "New" => $parameters["IVR"]);
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions pre remotion of IVR type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove_ivr_pre ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get extension IVR to audit record
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `IVR` FROM `ExtensionIVR` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $buffer["ORIGINAL"]["IVR"] = $result->fetch_assoc ()["IVR"];

  /**
   * Remove extension IVR from database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionIVR` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Get extension number to notify server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number` FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Number" => $result->fetch_assoc ()["Number"]);
  if ( framework_has_hook ( "extension_ivr_remove_notify"))
  {
    $notify = framework_call ( "extension_ivr_remove_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["ORIGINAL"]["range"]["Server"], "extension_ivr_remove", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions audit remotion of IVR type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove_ivr_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  $buffer["IVR"] = $parameters["ORIGINAL"]["IVR"];

  /**
   * Return data
   */
  return $buffer;
}

framework_add_hook ( "servers_rebuild_config", "extensions_ivr_server_reconfig");

/**
 * Function to rebuild extensions of IVR type on server rebuild.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_ivr_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all extension IVRs and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionIVR`.`IVR`, `Extensions`.`Number` FROM `ExtensionIVR` LEFT JOIN `Extensions` ON `ExtensionIVR`.`Extension` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `Ranges`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $ivr = $result->fetch_assoc ())
  {
    $notify = array ( "Number" => $ivr["Number"], "IVR" => $ivr["IVR"]);
    if ( framework_has_hook ( "extension_ivr_add_notify"))
    {
      $notify = framework_call ( "extension_ivr_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["ID"], "extension_ivr_add", $notify);
  }

  /**
   * Return data
   */
  return $buffer;
}
?>
