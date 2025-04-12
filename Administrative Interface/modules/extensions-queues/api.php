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
 * VoIP Domain extensions queues module API. This module add the API calls
 * related to extensions queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API hook to extend extensions addition of queue type
 */
framework_add_function_documentation (
  "extensions_search",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "queue"),
          "example" => "queue"
        )
      )
    ),
    "response" => array (
      200 => array (
        "schema" => array (
          "items" => array (
            "properties" => array (
              "Type" => array (
                "enum" => array ( "queue"),
                "example" => "queue"
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "extensions_view_queue",
  "extensions_view_queue"
);
framework_add_function_documentation (
  "extensions_view",
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "properties" => array (
            "Type" => array (
              "enum" => array ( "queue"),
              "example" => array ( "queue")
            ),
            "oneOf" => array (
              array (
                "type" => "object",
                "description" => __ ( "An object with queue information."),
                "properties" => array (
                  "Queue" => array (
                    "type" => "object",
                    "properties" => array (
                      "ID" => array (
                        "type" => "integer",
                        "description" => __ ( "The queue internal system unique identifier."),
                        "example" => 1
                      ),
                      "Description" => array (
                        "type" => "integer",
                        "description" => __ ( "The queue description."),
                        "example" => __ ( "Sales")
                      ),
                      "Strategy" => array (
                        "type" => "string",
                        "enum" => array ( "ringall", "roundrobin", "leastrecent", "fewestcalls", "random", "rrmemory"),
                        "description" => __ ( "The queue ring strategy of the queue."),
                        "example" => "rrmemory"
                      ),
                      "StrategyText" => array (
                        "type" => "string",
                        "description" => __ ( "The queue ring strategy description of the queue."),
                        "example" => __ ( "Round Robin Memory")
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
  "extensions_add_queue_validate",
  "extensions_queue_validate"
);
framework_add_hook (
  "extensions_edit_queue_validate",
  "extensions_queue_validate"
);
framework_add_hook (
  "extensions_add_queue_post",
  "extensions_add_queue_post"
);
framework_add_hook (
  "extensions_edit_queue_post",
  "extensions_edit_queue_post"
);
framework_add_hook (
  "extensions_add_queue_audit",
  "extensions_add_queue_audit"
);
framework_add_hook (
  "extensions_edit_queue_audit",
  "extensions_edit_queue_audit"
);
framework_add_function_documentation (
  "extensions_add",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "queue")
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "Queue" => array (
                "type" => "integer",
                "description" => __ ( "The queue system unique identifier."),
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
                  "Queue" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The selected queue is invalid.")
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
          "enum" => array ( "queue")
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "Queue" => array (
                "type" => "integer",
                "description" => __ ( "The queue system unique identifier."),
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
                  "Queue" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The selected queue is invalid.")
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
  "extensions_remove_queue_pre",
  "extensions_remove_queue_pre"
);
framework_add_hook (
  "extensions_remove_queue_audit",
  "extensions_remove_queue_audit"
);

/**
 * Function to extend extensions with queue information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_view_queue ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch queue information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Queues`.`ID`, `Queues`.`Description`, `Queues`.`Strategy` FROM `Queues` LEFT JOIN `ExtensionQueue` ON `ExtensionQueue`.`Queue` = `Queues`.`ID` WHERE `ExtensionQueue`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $queue = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["Queue"] = array ( "ID" => $queue["ID"], "Description" => $queue["Description"], "Strategy" => $queue["Strategy"], "StrategyText" => __ ( $_in["queuestypes"][$queue["Strategy"]]));

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to extend extensions addition/edition validate of queue type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_queue_validate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Validate parameters
   */
  if ( $parameters["Queue"] != (int) $parameters["Queue"])
  {
    $buffer["Queue"] = __ ( "The selected queue is invalid.");
  }
  $parameters["Queue"] = (int) $parameters["Queue"];
  if ( ! array_key_exists ( "Queue", $buffer) && empty ( $parameters["Queue"]))
  {
    $buffer["Queue"] = __ ( "The queue is required.");
  }

  /**
   * Check if queue exists
   */
  if ( ! array_key_exists ( "Queue", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Queue"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $buffer["Queue"] = __ ( "The selected queue is invalid.");
    }
  }

  /**
   * If it's an edition, fetch old queue
   */
  if ( array_key_exists ( "ORIGINAL", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Queue` FROM `ExtensionQueue` WHERE `ExtensionQueue`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
      exit ();
    }
    $buffer["ORIGINAL"]["Queue"] = $result->fetch_assoc ()["Queue"];
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post addition of queue type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_queue_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add new extension queue record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionQueue` (`Queue`, `Extension`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Queue"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]) . ")"))
  {
    framework_call ( "extensions_add_abort", $parameters);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Get queue name
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"])))
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
  $queue = $result->fetch_assoc ();

  /**
   * Associate queue to extension at Asterisk server
   */
  $notify = array ( "Number" => $parameters["Number"], "Queue" => $queue["Name"]);
  if ( framework_has_hook ( "extension_queue_add_notify"))
  {
    $notify = framework_call ( "extension_queue_add_notify", $parameters, false, $notify);
  }
  notify_server ( $range["Server"], "extension_queue_add", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions audit addition of queue type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_queue_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  $buffer["Queue"] = $parameters["Queue"];

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post edition of queue type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_queue_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check if there's queue change, if changed, update database
   */
  if ( ! array_compare ( $parameters["ORIGINAL"]["Queue"], $parameters["Queue"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `ExtensionQueue` SET `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Queue"]) . " WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      framework_call ( "extensions_edit_abort", $parameters);
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Get queue name
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"])))
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
  $queue = $result->fetch_assoc ();

  /**
   * Notify server about change
   */
  $notify = array ( "Number" => $parameters["ORIGINAL"]["Number"], "NewNumber" => $parameters["Number"], "Queue" => $queue["Name"]);
  if ( framework_has_hook ( "extensions_queue_change_notify"))
  {
    $notify = framework_call ( "extensions_queue_change_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["range"]["Server"], "extension_queue_change", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions audit edition of queue type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_queue_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  if ( $parameters["ORIGINAL"]["Queue"] != $parameters["Queue"])
  {
    $buffer["Queue"] = array ( "Old" => $parameters["ORIGINAL"]["Queue"], "New" => $parameters["Queue"]);
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions pre remotion of queue type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove_queue_pre ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get extension queue to audit record
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Queue` FROM `ExtensionQueue` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $buffer["ORIGINAL"]["Queue"] = $result->fetch_assoc ()["Queue"];

  /**
   * Remove extension queue from database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionQueue` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
  if ( framework_has_hook ( "extension_queue_remove_notify"))
  {
    $notify = framework_call ( "extension_queue_remove_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["ORIGINAL"]["range"]["Server"], "extension_queue_remove", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions audit remotion of queue type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove_queue_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  $buffer["Queue"] = $parameters["ORIGINAL"]["Queue"];

  /**
   * Return data
   */
  return $buffer;
}

framework_add_hook ( "servers_rebuild_config", "extensions_queue_server_reconfig");

/**
 * Function to rebuild extensions of queue type on server rebuild.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_queue_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all extension queues and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionQueue`.`Queue`, `Extensions`.`Number` FROM `ExtensionQueue` LEFT JOIN `Extensions` ON `ExtensionQueue`.`Extension` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `Ranges`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $queue = $result->fetch_assoc ())
  {
    $notify = array ( "Number" => $queue["Number"], "Queue" => $queue["Queue"]);
    if ( framework_has_hook ( "extension_queue_add_notify"))
    {
      $notify = framework_call ( "extension_queue_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["id"], "extension_queue_add", $notify);
  }

  /**
   * Return data
   */
  return $buffer;
}
?>
