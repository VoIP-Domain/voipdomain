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
 * VoIP Domain CRM api module. This module add the api calls related to
 * the CRM.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CRM
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to create a new CRM session
 */
framework_add_hook ( "crm_create", "crm_create");
framework_add_permission ( "crm_create", __ ( "Create CRM session"));
framework_add_api_call ( "/crm", "Create", "crm_create", array ( "permissions" => array ( "user", "crm_create")));

/**
 * Function to add a new CRM session.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function crm_create ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["queue"] = (int) $parameters["queue"];
  if ( empty ( $parameters["queue"]))
  {
    $data["result"] = false;
    $data["queue"] = __ ( "The queue number is required.");
  }
  $parameters["extension"] = (int) $parameters["extension"];
  if ( empty ( $parameters["extension"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The extension number is required.");
  }
  $parameters["password"] = (int) $parameters["password"];
  if ( empty ( $parameters["password"]))
  {
    $data["result"] = false;
    $data["password"] = __ ( "The extension password is required.");
  }

  /**
   * Fetch queue
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["queue"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["result"] = false;
    $data["queue"] = __ ( "Invalid queue number.");
  }
  $queue = $result->fetch_assoc ();

  /**
   * Fetch user extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["extension"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "Invalid extension number.");
  }
  $extension = $result->fetch_assoc ();

  /**
   * Validate user password
   */
  if ( $extension["Password"] != $parameters["password"])
  {
    $data["result"] = false;
    $data["password"] = __ ( "Invalid password.");
  }

  /**
   * Call create sanitize hook, if exist
   */
  if ( framework_has_hook ( "crm_create_sanitize"))
  {
    $data = framework_call ( "crm_create_sanitize", $parameters, false, $data);
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
   * Create new session ID and write it to database
   */
  $data["sid"] = md5 ( uniqid ( rand (), true));
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `CRM` (`Queue`, `Extension`, `SID`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( (int) $queue["Extension"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["extension"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $data["sid"]) . "') ON DUPLICATE KEY UPDATE `SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $data["sid"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create push channel
   */
  $ch = curl_init ();
  curl_setopt_array ( $ch, array (
                             CURLOPT_URL => $_in["general"]["pushpub"] . "?id=" . urlencode ( $data["sid"]),
                             CURLOPT_RETURNTRANSFER => true,
                             CURLOPT_POST => true,
                             CURLOPT_POSTFIELDS => json_encode ( array ( "event" => "noop"))
                    ));
  @curl_exec ( $ch);
  curl_close ( $ch);

  /**
   * Call create post hook, if exist
   */
  if ( framework_has_hook ( "crm_create_post"))
  {
    framework_call ( "crm_create_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ( "SID" => $data["sid"], "Queue" => $queue["Extension"], "Extension" => $parameters["extension"]);
  if ( framework_has_hook ( "crm_create_audit"))
  {
    $audit = framework_call ( "crm_create_audit", $parameters, false, $audit);
  }
  audit ( "CRM", "create", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to intercept system notification events
 */
framework_add_hook ( "notifications_event", "crm_event");

/**
 * Function to check a notify message of router server to intercept CRM messages.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function crm_event ( $buffer, $parameters)
{
  global $_in;

  /**
   * Exit function if it's not a CRM event (don't waste CPU processing what we don't want)
   */
  if ( $parameters["eventname"] != "QueueMemberRemoved" && $parameters["eventname"] != "CampaignCallAnswered")
  {
    return $buffer;
  }

  /**
   * Get CRM session ID
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CRM` WHERE `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"]) . " AND `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Extension"])))
  {
    return $buffer;
  }
  if ( $result->num_rows != 1)
  {
    return $buffer;
  }
  $crm = $result->fetch_assoc ();

  /**
   * Process each event
   */
  switch ( $parameters["eventname"])
  {
    case "QueueMemberRemoved":
      /**
       * Remove CRM session from database
       */
      @$_in["mysql"]["id"]->query ( "DELETE FROM `CRM` WHERE `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"]) . " AND `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Extension"]));

      /**
       * Delete HTTP PUSH session
       */
      $ch = curl_init ();
      curl_setopt_array ( $ch, array (
                                 CURLOPT_URL => $_in["general"]["pushpub"] . "?id=" . urlencode ( $crm["SID"]),
                                 CURLOPT_RETURNTRANSFER => true,
                                 CURLOPT_CUSTOMREQUEST => "DELETE"
                        ));
      @curl_exec ( $ch);
      curl_close ( $ch);
      break;
    case "CampaignCallAnswered":
      /**
       * Fetch campaign entry
       */
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntries` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["eid"])))
      {
        return $buffer;
      }
      if ( $result->num_rows != 1)
      {
        return $buffer;
      }
      $entry = $result->fetch_assoc ();
      $data = array ();
      $data["event"] = $parameters["eventname"];
      $data["eid"] = (int) $parameters["eid"];
      $data["description"] = $entry["Description"];
      $data["grouper"] = $entry["Grouper"];
      $data["extra"] = $entry["Extra"];
      $data["register"] = $entry["Registry"];
      $data["result"] = $entry["Result"];
      $data["entries"] = array ();

      /**
       * Fetch all campaign grouper entries
       */
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntries` WHERE `Grouper` = '" . $_in["mysql"]["id"]->real_escape_string ( $entry["Grouper"]) . "' ORDER BY `Number`"))
      {
        return $buffer;
      }
      while ( $entry = $result->fetch_assoc ())
      {
        $data["entries"][$entry["ID"]] = array ( "state" => $entry["State"], "number" => $entry["Number"], "tries" => $entry["Tries"], "tabtelecom" => $entry["Detail"]);
      }

      $ch = curl_init ();
      curl_setopt_array ( $ch, array (
                                 CURLOPT_URL => $_in["general"]["pushpub"] . "?id=" . urlencode ( $crm["SID"]),
                                 CURLOPT_RETURNTRANSFER => true,
                                 CURLOPT_CUSTOMREQUEST => "POST",
                                 CURLOPT_POSTFIELDS => json_encode ( $data)
                        ));
      @curl_exec ( $ch);
      curl_close ( $ch);
      break;
  }

  /**
   * Retorn OK to notification
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}
?>
