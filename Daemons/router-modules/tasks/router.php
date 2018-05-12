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
 * VoIP Domain tasks routes module. This module add the monitoring routes events related
 * to tasks.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
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
   * Check certificates
   */
  if ( ! array_key_exists ( "privateKey", $_in["keys"]))
  {
    if ( ! $_in["keys"]["privateKey"] = file_get_contents ( $_in["general"]["privateKey"]))
    {
      writeLog ( "Cannot read master server private key!", VoIP_LOG_FATAL);
      return $buffer;
    }
  }
  if ( ! array_key_exists ( "publicKey", $_in["keys"]["server_" . $parameters["sid"]]["publicKey"]))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `PublicKey` FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["sid"])))
    {
      writeLog ( "Cannot fetch server \"" . $parameters["sid"] . "\" public key from database.");
      return $buffer;
    }
    $_in["keys"]["server_" . $parameters["sid"]]["publicKey"] = $result->fetch_assoc ()["PublicKey"];
  }

  /**
   * Decrypt key
   */
  openssl_private_decrypt ( $parameters["key"], $key, $_in["keys"]["privateKey"]);

  /**
   * Check job payload content sign
   */
  $signcheck = openssl_verify ( $key . $parameters["iv"] . $parameters["hmac"] . $parameters["payload"], $parameters["sign"], $_in["keys"]["server_" . $parameters["sid"]]["publicKey"], OPENSSL_ALGO_SHA1);
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
       * Get all commands stalled at database
       */
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Commands` WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["sid"])))
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
        $_in["gearman"]["client"] = new GearmanClient ();
        if ( ! $_in["gearman"]["client"]->addServer ( $_in["gearman"]["servers"]))
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
        $commandpayload = json_encode ( array ( "event" => $command["Event"], "id" => $command["ID"], "data" => json_decode ( $command["Data"], true)));
        $iv = secure_rand ( 16, true);
        $key = secure_rand ( 256, true);
        $encrypted = openssl_encrypt ( $commandpayload, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac ( "sha256", $encrypted, $key, true);
        openssl_public_encrypt ( $key, $encryptedkey, $_in["keys"]["server_" . $command["Server"]]["publicKey"]);
        openssl_sign ( $key . $iv . $hmac . $encrypted, $sign, $_in["keys"]["privateKey"], OPENSSL_ALGO_SHA1);
        $_in["gearman"]["client"]->doBackground ( "vd_server_" . $command["Server"] . "_task", json_encode ( array ( "payload" => base64_encode ( $encrypted), "iv" => base64_encode ( $iv), "key" => base64_encode ( $encryptedkey), "hmac" => base64_encode ( $hmac), "sign" => base64_encode ( $sign))));
      }
      writeLog ( "Replayed stalled commands to server \"" . $parameters["sid"] . "\".");
      break;
    case "task_finished":
      if ( ! array_key_exists ( "id", $payload["data"]) || ! array_key_exists ( "result", $payload["data"]))
      {
        writeLog ( "Received event \"server_task_reply\" from server \"" . $parameters["sid"] . "\" without minimum data for sub event \"task_finished\". Ignoring event!", VoIP_LOG_WARNING);
        return $buffer;
      }
      if ( $payload["data"]["result"]["code"] && $payload["data"]["result"]["code"] >= 200 && $payload["data"]["result"]["code"] <= 299)
      {
        if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Commands` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $payload["data"]["id"]) . " AND `Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["sid"])))
        {
          writeLog ( "Cannot remove command ID \"" . (int) $payload["data"]["id"] . "\" from database!", VoIP_LOG_WARNING);
          return $buffer;
        }
        writeLog ( "Removed command ID \"" . (int) $payload["data"]["id"] . "\" from database.");
      } else {
        writeLog ( "Returned fail result for command ID \"" . (int) $payload["data"]["id"] . "\" with code \"" . (int) $payload["data"]["result"]["code"] . "\" and message \"" . $payload["data"]["result"]["message"] . "\".");
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
