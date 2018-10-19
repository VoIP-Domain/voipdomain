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
 * VoIP Domain main framework interface API module. This module has all basic
 * system API call implementations.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Interface
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add framework daemon return controll API hooks.
 */
framework_add_hook ( "daemons_return", "daemons_return", IN_HOOK_INSERT_FIRST);
framework_add_api_call ( "/sys/return", "Create", "daemons_return", array ( "permissions" => array ( "server")));

/**
 * Function to process remote Asterisk controller daemon return data.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of execution
 */
function daemons_return ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["rid"] = (int) $parameters["rid"];
  $parameters["result"] = (int) $parameters["result"];

  /**
   * If result is 200 (OK), remove event (if rid = 0, don't remove, it's a pingback)
   */
  if ( $parameters["result"] == 200 && $parameters["rid"] != 0)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Commands` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["rid"]) . " AND `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $_in["server"]["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Check for unprocessed events in queue and resend
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Commands`.*, `Servers`.`Password` FROM `Commands`, `Servers` WHERE `Servers`.`ID` = `Commands`.`Server` AND `Commands`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( $_in["server"]["ID"]) . ( $parameters["rid"] != 0 ? " AND `Commands`.`ID` < " . $_in["mysql"]["id"]->real_escape_string ( $parameters["rid"]) : "")))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    return array ( "result" => true);
  }
  while ( $data = $result->fetch_assoc ())
  {
    $tmp = unserialize ( $data["Data"]);
    $ch = curl_init ();
    curl_setopt_array ( $ch, array (
                               CURLOPT_URL => $_in["general"]["pushpub"] . "?id=" . urlencode ( $data["Server"]),
                               CURLOPT_RETURNTRANSFER => true,
                               CURLOPT_POST => true,
                               CURLOPT_POSTFIELDS => serialize ( array ( "event" => @openssl_encrypt ( serialize ( array_merge ( ( is_array ( $tmp) ? $tmp : array ( $tmp)), array ( "event" => $data["Event"], "id" => $data["ID"]))), "AES-256-CBC", $data["Password"], OPENSSL_RAW_DATA)))
                      ));
    @curl_exec ( $ch);
    curl_close ( $ch);
  }

  // Return
  return array ( "result" => true);
}

/**
 * Add framework session control API hooks.
 */
framework_add_hook ( "api_login", "api_do_login", IN_HOOK_INSERT_FIRST);
framework_add_api_call ( "/sys/session", "Create", "api_login", array ( "unauthenticated" => true));
framework_add_hook ( "api_logout", "api_do_logout", IN_HOOK_INSERT_FIRST);
framework_add_api_call ( "/sys/session", "Delete", "api_logout", array ( "permissions" => array ( "user")));

/**
 * Function to remove user session. Basically, destroy PHP session control.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of execution
 */
function api_do_logout ( $buffer, $parameters)
{
  global $_in;

  /**
   * Insert audit entry
   */
  audit ( "system", "logout", array ( "ID" => $_in["session"]["ID"], "User" => $_in["session"]["User"], "Reason" => "Logged off"));

  /**
   * Remove system cookie and destroy global configuration session informations
   */
  setcookie ( $_in["general"]["cookie"], null, -1, "/");
  $_in["session"] = array ();

  /**
   * Always return ok
   */
  return array ( "result" => true);
}

/**
 * Function to authenticate a user and create a new session. The session are
 * controlled with PHP session functions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of execution
 */
function api_do_login ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for required parameters
   */
  if ( empty ( $parameters["user"]) || empty ( $parameters["pass"]))
  {
    return array ( "result" => false);
  }
  $parameters["user"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["user"])));

  /**
   * Call pre authentication plugin modules if exists
   */
  filters_call ( "authentication_start");

  /**
   * Validate user into database
   */
  if ( ! $result = $_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `User` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["user"]) . "'"))
  {
    return array ( "result" => false, "message" => __ ( "Error accessing database server."));
  }
  if ( $result->num_rows != 1)
  {
    /**
     * Call authentication failure plugin modules if exists
     */
    filters_call ( "authentication_failure");

    /**
     * And show the login page with message
     */
    return array ( "result" => false, "message" => __ ( "Invalid username and/or password."));
  }

  /**
   * Check if password match
   */
  $data = $result->fetch_assoc ();
  if ( $data["Pass"] != hash_pbkdf2 ( "sha256", $parameters["pass"], $data["Salt"], $data["Iterations"], 64))
  {
    /**
     * Call authentication failure plugin modules if exists
     */
    filters_call ( "authentication_failure");

    /**
     * And show the login page with message
     */
    return array ( "result" => false, "message" => __ ( "Invalid username and/or password."));
  }

  /**
   * Create global configuration session informations
   */
  $_in["session"] = $data;
  $_in["session"]["SID"] = hash ( "sha256", uniqid ( "", true));
  $_in["session"]["LastSeen"] = time ();
  $_in["session"]["Permissions"] = json_decode ( $_in["session"]["Permissions"], true);

  /**
   * Create session at database
   */
  if ( ! $_in["mysql"]["id"]->query ( "INSERT INTO `Sessions` (`SID`, `User`, `LastSeen`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $_in["session"]["SID"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $_in["session"]["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( time ()) . ")"))
  {
    return array ( "result" => false, "message" => __ ( "Error accessing database server."));
  }

  /**
   * Insert audit entry
   */
  audit ( "system", "login", array ( "ID" => $_in["session"]["ID"], "User" => $_in["session"]["User"], "IP" => $_SERVER["REMOTE_ADDR"]));

  /**
   * Start user session. If "remember me" checkbox is checked at login, set the
   * cookie to expire in 30 days, otherwise, we'll use session cookie, that
   * expires when user closes the browser.
   */
  setcookie ( $_in["general"]["cookie"], $_in["session"]["SID"], ( ! empty ( $parameters["remember"]) ? 2592000 : 0), "/");

  /**
   * Call post authentication plugin modules if exists
   */
  filters_call ( "authentication_success");

  /**
   * Return OK
   */
  return array ( "result" => true);
}

/**
 * Add framework user notifications API hooks.
 */
framework_add_hook ( "api_read_notifiations", "api_read_notifications", IN_HOOK_INSERT_FIRST);
framework_add_api_call ( "/sys/notifications", "Read", "api_read_notifications", array ( "permissions" => array ( "user")));
framework_add_hook ( "api_remove_notifiation", "api_remove_notification", IN_HOOK_INSERT_FIRST);
framework_add_api_call ( "/sys/notifications/:id", "Delete", "api_delete_notification", array ( "permissions" => array ( "user")));

/**
 * Function to retrieve user notifications.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of execution
 */
function api_read_notifications ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch user notifications from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `User` = " . $_in["mysql"]["id"]->real_escape_string ( $_SESSION["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Prepare output
   */
  $output = array ();
  while ( $notification = $result->fetch_assoc ())
  {
    $output[] = $notification;
  }

  return $output;
}

/**
 * Function to delete a user notification.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of execution
 */
function api_delete_notification ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if message exist first
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `User` = " . $_in["mysql"]["id"]->real_escape_string ( $_SESSION["ID"]) . " AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    return array ( "result" => false, "message" => _ ( "Message not found."));
  }

  /**
   * Remove user notification from database
   */
  if ( @$_in["mysql"]["id"]->query ( "DELETE FROM `Notifications` WHERE `User` = " . $_in["mysql"]["id"]->real_escape_string ( $_SESSION["ID"]) . " AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  return array ( "result" => true);
}

/**
 * Add framework dashboard informations hook.
 */
framework_add_hook ( "api_read_dashboard", "api_read_dashboard", IN_HOOK_INSERT_FIRST);
framework_add_api_call ( "/sys/dashboard", "Read", "api_read_dashboard", array ( "permissions" => array ( "user")));

/**
 * Function to generate dashboard informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $page Buffer from plugin system if processed by other function
 *                     before
 * @param array $parameters Framework page structure
 * @return array Framework page structure with generated content
 */
function api_read_dashboard ( $page, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = ( ! empty ( $parameters["start"]) ? mktime ( 0, 0, 0, substr ( $parameters["start"], 0, 2), substr ( $parameters["start"], 3, 2), substr ( $parameters["start"], 6, 4)) : strtotime ( "29 days ago"));
  $parameters["end"] = ( ! empty ( $parameters["end"]) ? mktime ( 0, 0, 0, substr ( $parameters["end"], 0, 2), substr ( $parameters["end"], 3, 2), substr ( $parameters["end"], 6, 4)) : time ());

  /**
   * Initialize output variable
   */
  $data = array ();

  /**
   * Get ASR (Answer-Seizure Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'ANSWERED' AND `calldate` >= '" . date ( "Y-m-d", $parameters["start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["end"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $asr = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `calldate` >= '" . date ( "Y-m-d", $parameters["start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["end"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $total = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["asr"] = array ( "percent" => ( $total != 0 ? round (( $asr * 100) / $total) : 0), "value" => $asr, "total" => $total);

  /**
   * Get NER (Network Efficiency Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE ( `disposition` = 'ANSWERED' OR `disposition` = 'NO ANSWER' OR `disposition` = 'BUSY') AND `calldate` >= '" . date ( "Y-m-d", $parameters["start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["end"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $ner = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["ner"] = array ( "percent" => ( $total != 0 ? round (( $ner * 100) / $total) : 0), "value" => $ner, "total" => $total);

  /**
   * Get SBR (Subscriber Busy Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'BUSY' AND `calldate` >= '" . date ( "Y-m-d", $parameters["start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["end"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $sbr = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE ( `disposition` = 'ANSWERED' OR `disposition` = 'BUSY') AND `calldate` >= '" . date ( "Y-m-d", $parameters["start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["end"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $total = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["sbr"] = array ( "percent" => ( $total != 0 ? round (( $sbr * 100) / $total) : 0), "value" => $sbr, "total" => $total);

  /**
   * Get SCR (Short Calls Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'ANSWERED' AND `billsec` <= 60 AND `calldate` >= '" . date ( "Y-m-d", $parameters["start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["end"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $scr = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'ANSWERED' AND `calldate` >= '" . date ( "Y-m-d", $parameters["start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["end"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $total = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["scr"] = array ( "percent" => ( $total != 0 ? round (( $scr * 100) / $total) : 0), "value" => $scr, "total" => $total);

  /**
   * Get LCR (Long Calls Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'ANSWERED' AND `billsec` >= 300 AND `calldate` >= '" . date ( "Y-m-d", $parameters["start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["end"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $lcr = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["lcr"] = array ( "percent" => ( $total != 0 ? round (( $lcr * 100) / $total) : 0), "value" => $lcr, "total" => $total);

  /**
   * Get allocations percentage
   */
  $allocations = filters_call ( "count_allocations");
  $total = 0;
  foreach ( $allocations as $allocation)
  {
    $total += $allocation;
  }
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $ranges = 0;
  while ( $range = $result->fetch_assoc ())
  {
    $ranges += $range["Finish"] - $range["Start"];
  }
  $result->free ();
  $data["allocation"] = array ( "percent" => round (( $total * 100) / $ranges), "value" => $total, "total" => $ranges);

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get call information
 */
framework_add_hook ( "calls_view", "calls_view");
framework_add_permission ( "calls_view", __ ( "View call informations"));
framework_add_api_call ( "/calls/:id", "Read", "calls_view", array ( "permissions" => array ( "user", "calls_view")));

/**
 * Function to generate call informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function calls_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["id"] = preg_replace ( "/[^0-9-\.]/", "", $paremeters["id"]);

  /**
   * Check if call exist into database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `cdr`.*, `Gateways`.`Description` AS `GatewayDescription`, `Servers`.`Name` AS `ServerName`, `CostCenters`.`ID` AS `CostCenterID`, `CostCenters`.`Description` AS `CostCenterDescription` FROM `cdr` LEFT JOIN `Gateways` ON `Gateways`.`ID` = `cdr`.`gateway` LEFT JOIN `Servers` ON `Servers`.`ID` = `cdr`.`server` LEFT JOIN `CostCenters` ON `CostCenters`.`Code` = `cdr`.`accountcode` WHERE `cdr`.`uniqueid` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $call = $result->fetch_assoc ();

  /**
   * Format data
   */
  $result = array ();
  $result["result"] = true;
  $result["date"] = format_db_datetime ( $call["calldate"]);
  $result["clid"] = $call["clid"];
  $result["src"] = $call["src"];
  $result["dst"] = $call["dst"];
  $result["duration"] = format_secs_to_string ( $call["duration"]);
  $result["billsec"] = format_secs_to_string ( $call["billsec"]);
  if ( $call["userfield"] == "DND")
  {
    $call["disposition"] = "NO ANSWER";
  }
  switch ( $call["disposition"])
  {
    case "ANSWERED":
      $result["disposition"] = __ ( "Answered");
      if ( $call["lastapp"] == "VoiceMail")
      {
        $result["disposition"] .= " (" . __ ( "Voice mail") . ")";
      }
      break;
    case "NO ANSWER":
      $result["disposition"] = __ ( "Not answered");
      break;
    case "BUSY":
      $result["disposition"] = __ ( "Busy");
      break;
    case "FAILED":
      $result["disposition"] = __ ( "Call failed");
      break;
    default:
      $result["disposition"] = __ ( "Unknown") . ": " . $call["disposition"];
      break;
  }
  $result["cc"] = $call["CostCenterID"];
  $result["ccdesc"] = $call["CostCenterDesc"] . " (" . $call["accountcode"] . ")";
  $result["userfield"] = $call["userfield"];
  $result["uniqueid"] = $call["uniqueid"];
  $result["server"] = $call["server"];
  $result["serverdesc"] = $call["ServerName"];
  $result["type"] = $call["calltype"];
  switch ( $call["calltype"])
  {
    case "1":
      $result["typedesc"] = __ ( "Extension");
      break;
    case "2":
      $result["typedesc"] = __ ( "Landline");
      break;
    case "3":
      $result["typedesc"] = __ ( "Mobile");
      break;
    case "4":
      $result["typedesc"] = __ ( "Interstate");
      break;
    case "5":
      $result["typedesc"] = __ ( "International");
      break;
    case "6":
      $result["typedesc"] = __ ( "Special");
      break;
    case "7":
      $result["typedesc"] = __ ( "Toll free");
      break;
    case "8":
      $result["typedesc"] = __ ( "Services");
      break;
    default:
      $result["typedesc"] = __ ( "Unknown");
      break;
  }
  $result["gw"] = $call["gateway"];
  $result["gwdesc"] = $call["GatewayDescription"];
  if ( $call["processed"])
  {
    $result["value"] = sprintf ( "%.5f", $call["value"]);
  } else {
    $result["value"] = __ ( "N/A");
  }
  $result["codec"] = $call["codec"];
  $result["QOSa"] = $call["QOSa"];
  $result["QOSb"] = $call["QOSb"];
  $result["monitor"] = $call["monitor"];
  $result["userfieldextra"] = $call["userfieldextra"];
  $result["SIPID"] = $call["SIPID"];

  /**
   * Check if there's call capture file (if has, process it)
   */
  $result["siptrace"] = array ();
  $files = scandir ( "/var/spool/pcapsipdump/" . date ( "Ymd", format_db_timestamp ( $call["calldate"])) . "/" . date ( "H", format_db_timestamp ( $call["calldate"])) . "/");
  foreach ( $files as $file)
  {
    if ( strpos ( $file, "-" . $call["SIPID"] . ".pcap") !== false)
    {
      $result["siptrace"] = filters_call ( "process_sipdump", array ( "filename" => "/var/spool/pcapsipdump/" . date ( "Ymd", format_db_timestamp ( $call["calldate"])) . "/" . date ( "H", format_db_timestamp ( $call["calldate"])) . "/" . $file));
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $result);
}
?>
