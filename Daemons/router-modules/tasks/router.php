<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain tasks routes module. This module add the monitoring routes events related
 * to tasks.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call hooks
 */
framework_add_hook ( "server_task_reply", "server_task_reply");

/**
 * Function to process task results.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function server_task_reply ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check minimum parameters
   */
  if ( ! array_key_exists ( "payload", $parameters) || ! array_key_exists ( "iv", $parameters) || ! array_key_exists ( "key", $parameters) || ! array_key_exists ( "hmac", $parameters) || ! array_key_exists ( "sign", $parameters) || ! array_key_exists ( "sid", $parameters))
  {
    writeLog ( "Received event \"server_task_reply\" without minimum data. Ignoring event!", VoIP_LOG_WARNING);
    return $buffer;
  }
  $parameters["payload"] = base64_decode ( $parameters["payload"]);
  $parameters["iv"] = base64_decode ( $parameters["iv"]);
  $parameters["key"] = base64_decode ( $parameters["key"]);
  $parameters["hmac"] = base64_decode ( $parameters["hmac"]);
  $parameters["sign"] = base64_decode ( $parameters["sign"]);

  /**
   * Check if database connection are alive
   */
  mysql_check ();

  /**
   * Fetch server public key and tenant private key from database
   */
  if ( ! array_key_exists ( "server_" . $parameters["sid"], $_in["keys"]))
  {
    $_in["keys"]["server_" . $parameters["sid"]] = array ();
  }
  if ( ! array_key_exists ( "publicKey", $_in["keys"]["server_" . $parameters["sid"]]) || ! array_key_exists ( "tenantPrivateKey", $_in["keys"]["server_" . $parameters["sid"]]))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Servers`.`PublicKey`, `Tenants`.`PrivateKey` AS `TenantPrivateKey` FROM `Servers` LEFT JOIN `Tenants` ON `Servers`.`Tenant` = `Tenants`.`ID` WHERE `Servers`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["sid"])))
    {
      writeLog ( "Cannot fetch server \"" . $parameters["sid"] . "\" keys from database.");
      return $buffer;
    }
    $row = $result->fetch_assoc ();
    if ( ! $row)
    {
      writeLog ( "Server \"" . $parameters["sid"] . "\" not found in database.");
      return $buffer;
    }
    $_in["keys"]["server_" . $parameters["sid"]]["publicKey"] = $row["PublicKey"];
    $_in["keys"]["server_" . $parameters["sid"]]["tenantPrivateKey"] = $row["TenantPrivateKey"];
  }

  /**
   * Decrypt key (client encrypts with tenant public key, so decrypt with tenant private key)
   */
  if ( ! openssl_private_decrypt ( $parameters["key"], $key, $_in["keys"]["server_" . $parameters["sid"]]["tenantPrivateKey"]))
  {
    writeLog ( "Cannot decrypt AES key from server \"" . $parameters["sid"] . "\". Tenant private key mismatch!", VoIP_LOG_WARNING);
    return $buffer;
  }

  /**
   * Check job payload content sign
   */
  $signcheck = openssl_verify ( $key . $parameters["iv"] . $parameters["hmac"] . $parameters["payload"], $parameters["sign"], $_in["keys"]["server_" . $parameters["sid"]]["publicKey"], OPENSSL_ALGO_SHA256);
  if ( $signcheck != 1)
  {
    writeLog ( "Received event \"server_task_reply\" from server \"" . $parameters["sid"] . "\" with wrong server signature. Ignoring event!", VoIP_LOG_WARNING);
    return $buffer;
  }

  /**
   * Validate HMAC
   */
  $calcmac = hash_hmac ( "sha256", $parameters["payload"], $key, true);
  if ( ! hash_equals ( $parameters["hmac"], $calcmac))
  {
    writeLog ( "Received task with wrong HMAC signature. Ignoring task!", VoIP_LOG_WARNING);
    return;
  }

  /**
   * Decrypt information
   */
  $payload = json_decode ( openssl_decrypt ( $parameters["payload"], "aes-256-cbc", $key, OPENSSL_RAW_DATA, $parameters["iv"]), true);

  /**
   * Check server task return event
   */
  if ( ! array_key_exists ( "data", $payload) || ! array_key_exists ( "event", $payload))
  {
    writeLog ( "Received event \"server_task_reply\" from server \"" . $parameters["sid"] . "\" without minimum data. Ignoring event!", VoIP_LOG_WARNING);
    return $buffer;
  }
  switch ( $payload["event"])
  {
    case "task_replay":
      /**
       * Get all commands stalled at database with server and tenant keys
       */
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Commands`.*, `Servers`.`PublicKey` AS `ServerPublicKey`, `Tenants`.`PrivateKey` AS `TenantPrivateKey` FROM `Commands` LEFT JOIN `Servers` ON `Commands`.`Server` = `Servers`.`ID` LEFT JOIN `Tenants` ON `Servers`.`Tenant` = `Tenants`.`ID` WHERE `Commands`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["sid"])))
      {
        writeLog ( "Cannot fetch stalled commands to replay to server \"" . $parameters["sid"] . "\"!", VoIP_LOG_WARNING);
        return $buffer;
      }
      if ( $result->num_rows == 0)
      {
        writeLog ( "No stalled commands to server \"" . $parameters["sid"] . "\"!");
        return $buffer;
      }

      /**
       * Connect to Gearman if not connected
       */
      if ( ! $_in["gearman"]["client"])
      {
        $gearman_host = ! empty ( $_in["gearman"]["hostname"]) ? $_in["gearman"]["hostname"] : "127.0.0.1";
        $gearman_port = ! empty ( $_in["gearman"]["port"]) ? (int) $_in["gearman"]["port"] : 4730;
        $_in["gearman"]["client"] = new GearmanClient ();
        if ( ! $_in["gearman"]["client"]->addServer ( $gearman_host, $gearman_port))
        {
          writeLog ( "Failed connecting to Gearman as a client!", VoIP_LOG_WARNING);
          return $buffer;
        }
      }

      /**
       * Resend all messages
       */
      while ( $command = $result->fetch_assoc ())
      {
        if ( empty ( $command["ServerPublicKey"]) || empty ( $command["TenantPrivateKey"]))
        {
          writeLog ( "Cannot replay command ID \"" . $command["ID"] . "\": missing server public key or tenant private key!", VoIP_LOG_WARNING);
          continue;
        }
        $commandpayload = json_encode ( array ( "event" => $command["Event"], "id" => $command["ID"], "data" => json_decode ( $command["Data"], true)));
        $iv = secure_rand ( 16, true);
        $key = secure_rand ( 256, true);
        $encrypted = openssl_encrypt ( $commandpayload, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac ( "sha256", $encrypted, $key, true);
        openssl_public_encrypt ( $key, $encryptedkey, $command["ServerPublicKey"]);
        openssl_sign ( $key . $iv . $hmac . $encrypted, $sign, $command["TenantPrivateKey"], OPENSSL_ALGO_SHA256);
        $_in["gearman"]["client"]->doBackground ( gearman_client_task_name ( $command["Server"]), json_encode ( array ( "payload" => base64_encode ( $encrypted), "iv" => base64_encode ( $iv), "key" => base64_encode ( $encryptedkey), "hmac" => base64_encode ( $hmac), "sign" => base64_encode ( $sign))));
      }
      writeLog ( "Replayed stalled commands to server \"" . $parameters["sid"] . "\".");
      break;
    case "task_finished":
      if ( ! array_key_exists ( "id", $payload["data"]) || ! array_key_exists ( "result", $payload["data"]))
      {
        writeLog ( "Received event \"server_task_reply\" from server \"" . $parameters["sid"] . "\" without minimum data for sub event \"task_finished\". Ignoring event!", VoIP_LOG_WARNING);
        return $buffer;
      }
      $code = (int) $payload["data"]["result"]["code"];
      $is_success = $code >= 200 && $code <= 299;
      $is_permanent_error = $code >= 400 && $code <= 499;
      if ( $is_success || $is_permanent_error)
      {
        $commandId = (int) $payload["data"]["id"];
        $serverId = (int) $parameters["sid"];

        /**
         * Decrement GroupedCommands counter if this command belongs to a group
         */
        if ( $gcResult = @$_in["mysql"]["id"]->query ( "SELECT `Group` FROM `GroupCommand` WHERE `Command` = " . $commandId))
        {
          if ( $gcResult->num_rows != 0)
          {
            $groupId = (int) $gcResult->fetch_assoc ()["Group"];
            @$_in["mysql"]["id"]->query ( "UPDATE `GroupedCommands` SET `Left` = `Left` - 1 WHERE `ID` = " . $groupId);
          }
        }

        /**
         * Remove command from database (cascades to GroupCommand)
         */
        if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Commands` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $commandId) . " AND `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $serverId)))
        {
          writeLog ( "Cannot remove command ID \"" . $commandId . "\" from database!", VoIP_LOG_WARNING);
          return $buffer;
        }

        /**
         * Clean up completed GroupedCommands entries (Left = 0)
         */
        @$_in["mysql"]["id"]->query ( "DELETE FROM `GroupedCommands` WHERE `Server` = " . $serverId . " AND `Left` <= 0 AND `Total` > 0");

        if ( $is_success)
        {
          writeLog ( "Removed command ID \"" . $commandId . "\" from database (success).");
        } else {
          writeLog ( "Removed command ID \"" . $commandId . "\" from database (permanent error: " . $code . " - " . $payload["data"]["result"]["message"] . ").");
        }
      } else {
        writeLog ( "Keeping command ID \"" . (int) $payload["data"]["id"] . "\" for retry (temporary error: " . $code . " - " . $payload["data"]["result"]["message"] . ").", VoIP_LOG_WARNING);
      }
      break;
    default:
      writeLog ( "Received event \"server_task_reply\" from server \"" . $parameters["sid"] . "\" with unknown sub event \"" . $payload["event"] . "\". Ignoring event!", VoIP_LOG_WARNING);
      return $buffer;
      break;
  }

  // End event:
  return $buffer;
}
?>
