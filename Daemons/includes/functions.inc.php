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
 * Generic functions to IntelliNews Framework.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Generic Functions
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Declare system constants
 */
define ( "VoIP_LOG_NOTICE", 0);
define ( "VoIP_LOG_WARNING", 1);
define ( "VoIP_LOG_ERROR", 2);
define ( "VoIP_LOG_FATAL", 3);

/**
 * Function to calculate the distance between two geographic positions
 * (longitude/latitude).
 *
 * @param $lat1 float Latitude of first position
 * @param $lon1 float Longitude of first position
 * @param $lat2 float Latitude of second position
 * @param $lon2 float Longitude of second position
 * @param $unit[optional] string Return unity (default "miles"), using "M" to
 *                               miles, "K" for kilometers and "N" for nautic
 *                               miles
 * @return float
 */
function distance ( $lat1, $lon1, $lat2, $lon2, $unit = "m")
{
  $miles = rad2deg ( acos ( sin ( deg2rad ( $lat1)) * sin ( deg2rad ( $lat2)) + cos ( deg2rad ( $lat1)) * cos ( deg2rad ( $lat2)) * cos ( deg2rad ( $lon1 - $lon2)))) * 60 * 1.1515;

  switch ( strtolower ( $unit))
  {
    case "k":
      return $miles * 1.609344;
      break;
    case "n":
      return $miles * 0.8684;
      break;
    default:
      return $miles;
      break;
  }
}

/**
 * Function to translate a geographic position from degrees, minutes and seconds
 * to decimal form.
 *
 * @param $deg int Degrees
 * @param $min int Minutes
 * @param $sec int Seconds
 * @return float
 */
function DMStoDEC ( $deg, $min, $sec)
{
  return $deg + ((( $min * 60) + ( $sec)) / 3600);
}

/**
 * Function to translate a geographic position from decimal form to degrees,
 * minutes and seconds.
 *
 * @param $dec float Longitude/Latitude
 * @return array ( $deg, $min, $sec)
 */
function DECtoDMS ( $dec)
{
  $vars = explode ( ".", $dec);
  $deg = $vars[0];
  $tempma = "0." . $vars[1];

  $tempma = $tempma * 3600;
  $min = floor ( $tempma / 60);
  $sec = $tempma - ( $min * 60);

  return array ( "deg" => $deg, "min" => $min, "sec" => $sec);
}

/**
 * Function to compare two arrays. If equal, return true, otherwise return false.
 *
 * @param $array1 array First array
 * @param $array2 array Second array
 * @param $strict[optional] boolean Strict mode (compare variable type)
 * @return boolean
 */
function array_compare ( $array1, $array2, $strict = false)
{
  if ( ! is_array ( $array1) || ! is_array ( $array2))
  {
    return false;
  }
  foreach ( $array1 as $value)
  {
    $key = array_search ( $value, $array2, $strict);
    if ( $key === false)
    {
      return false;
    }
    unset ( $array2[$key]);
  }
  return sizeof ( $array2) == 0;
}

/**
 * Function to compare two arrays, including keys. If equal, return true,
 * otherwise return false.
 *
 * @param $array1 array First array
 * @param $array2 array Second array
 * @param $strict[optional] boolean Strict mode (compare variable type)
 * @return boolean
 */
function array_compare_with_keys ( $array1, $array2, $strict = false)
{
  if ( ! is_array ( $array1) || ! is_array ( $array2))
  {
    return false;
  }
  foreach ( $array1 as $key => $value)
  {
    $search = array_search ( $value, $array2, $strict);
    if ( $search != $key)
    {
      return false;
    }
    unset ( $array2[$key]);
  }
  return sizeof ( $array2) == 0;
}

/**
 * Function to explode a string to array, respecting the key and value of each
 * field. Usefull to Asterisk QOS fields.
 *
 * @param $string string String to be exploded
 * @return array
 */
function explode_QOS ( $string)
{
  $array = explode ( ";", $string);
  $result = array ();
  foreach ( $array as $value)
  {
    $result[substr ( $value, 0, strpos ( $value, "="))] = substr ( $value, strpos ( $value, "=") + 1);
  }

  /**
   * There's a bug into Asterisk RTP that RTT value got an absolute value like
   * 65535.999000, but it's 0. We'll fix here.
   */
  if ( array_key_exists ( "rtt", $result) && $result["rtt"] >= 65535)
  {
    $result["rtt"] = "0.000000";
  }

  return $result;
}

/**
 * Function to convert internal tone database format (Sipura/Linksys format) to
 * Zaptel/Asterisk format.
 *
 * @param $tones string Tone pattern
 * @return string
 */
function tone2zaptel ( $tones)
{
  // Unique execution?
  if ( substr ( $tones, 0, 1) == "!")
  {
    $onetime = true;
    $tones = substr ( $tones, 1);
  } else {
    $onetime = false;
  }

  // Process the frequencies
  $freqs = array ();
  $levels = array ();
  foreach ( explode ( ",", substr ( $tones, 0, strpos ( $tones, ";"))) as $freq)
  {
    $freqs[] = substr ( $freq, 0, strpos ( $freq, "@"));
    $levels[] = substr ( $freq, strpos ( $freq, "@") + 1);
  }

  // Timeout
  $timeout = substr ( $tones, strpos ( $tones, ";") + 1, strpos ( $tones, "(", strpos ( $tones, ";" + 1)) - strpos ( $tones, ";") - 1);

  // Process the cadencies
  $cadences = array ();
  foreach ( explode ( ",", substr ( $tones, strpos ( $tones, "(") + 1, strpos ( $tones, ")") - strpos ( $tones, "(") - 1)) as $cadence)
  {
    $cadences[] = array ( "on" => ( substr ( $cadence, 0, strpos ( $cadence, "/")) == "*" ? 30000 : (float) substr ( $cadence, 0, strpos ( $cadence, "/")) * 1000), "off" => (float) substr ( $cadence, strpos ( $cadence, "/") + 1, strpos ( $cadence, "/", strpos ( $cadence, "/") + 1) - strpos ( $cadence, "/") - 1) * 1000, "tones" => substr ( $cadence, strrpos ( $cadence, "/") + 1));
  }

  // Get the result
  $buffer = "";
  foreach ( $cadences as $cid => $cadence)
  {
    if ( $cid > 0)
    {
      $buffer .= ",";
    }
    foreach ( explode ( "+", $cadence["tones"]) as $tid => $tone)
    {
      if ( $tid > 0)
      {
        $buffer .= "+";
      }
      $buffer .= $freqs[$tone - 1];
    }
    $buffer .= "/" . $cadence["on"];
    if ( $cadence["off"] != 0)
    {
      $buffer .= ",0/" . $cadence["off"];
    }
  }
  return $buffer;
}

/**
 * Function to convert internal tone database format (Sipura/Linksys format) to
 * Polycom format.
 *
 * @param $tones string Tone pattern
 * @return string
 */
function tone2polycom ( $tones)
{
  // Unique execution?
  if ( substr ( $tones, 0, 1) == "!")
  {
    $onetime = true;
    $tones = substr ( $tones, 1);
  } else {
    $onetime = false;
  }

  // Process the frequencies
  $freqs = array ();
  $levels = array ();
  foreach ( explode ( ",", substr ( $tones, 0, strpos ( $tones, ";"))) as $freq)
  {
    $freqs[] = substr ( $freq, 0, strpos ( $freq, "@"));
    $levels[] = substr ( $freq, strpos ( $freq, "@") + 1);
  }

  // Timeout
  $timeout = substr ( $tones, strpos ( $tones, ";") + 1, strpos ( $tones, "(", strpos ( $tones, ";" + 1)) - strpos ( $tones, ";") - 1);

  // Process the cadencies
  $cadences = array ();
  foreach ( explode ( ",", substr ( $tones, strpos ( $tones, "(") + 1, strpos ( $tones, ")") - strpos ( $tones, "(") - 1)) as $cadence)
  {
    $cadences[] = array ( "on" => ( substr ( $cadence, 0, strpos ( $cadence, "/")) == "*" ? 30000 : (float) substr ( $cadence, 0, strpos ( $cadence, "/")) * 1000), "off" => (float) substr ( $cadence, strpos ( $cadence, "/") + 1, strpos ( $cadence, "/", strpos ( $cadence, "/") + 1) - strpos ( $cadence, "/") - 1) * 1000, "tones" => substr ( $cadence, strrpos ( $cadence, "/") + 1));
  }

  // Get the result
  $result = array ();
  $result["freqs"] = array ();
  $result["levels"] = array ();
  $result["on"] = array ();
  $result["off"] = array ();
  $result["repeat"] = array ();
  foreach ( $cadences as $cid => $cadence)
  {
    foreach ( explode ( "+", $cadence["tones"]) as $tid => $tone)
    {
      $result["freqs"][$tid] = $freqs[$tone - 1];
      $result["levels"][$tid] = $freqs[$tone - 1];
    }
    $buffer["on"][] = $cadence["on"];
    $buffer["off"][] = $cadence["on"];
    $buffer["repeat"][] = ( $onetime == true ? 1 : 0);
  }
  return $buffer;
}

/**
 * Function to check if a given string is a JSON encoded string. Regular
 * expression got from https://regex101.com/library/tA9pM8
 *
 * @param string $json String to test for
 * @return boolean True if is a JSON encoded string, otherwise, false
 */
function is_JSON ( $json)
{
  $re = '/(?(DEFINE)';
  $re .= '(?<json>(?>\s*(?&object)\s*|\s*(?&array)\s*))';
  $re .= '(?<object>(?>\{\s*(?>(?&pair)(?>\s*,\s*(?&pair))*)?\s*\}))';
  $re .= '(?<pair>(?>(?&STRING)\s*:\s*(?&value)))';
  $re .= '(?<array>(?>\[\s*(?>(?&value)(?>\s*,\s*(?&value))*)?\s*\]))';
  $re .= '(?<value>(?>true|false|null|(?&STRING)|(?&NUMBER)|(?&object)|(?&array)))';
  $re .= '(?<STRING>(?>"(?>\\\\(?>["\\\\\/bfnrt]|u[a-fA-F0-9]{4})|[^"\\\\\0-\x1F\x7F]+)*"))';
  $re .= '(?<NUMBER>(?>-?(?>0|[1-9][0-9]*)(?>\.[0-9]+)?(?>[eE][+-]?[0-9]+)?))';
  $re .= ')';
  $re .= '\A(?&json)\z/x';

  return preg_match ( $re, $json);
}

/**
 * Function to check if a given string is a XML encoded string.
 *
 * @param string $xml String to test for
 * @return boolean True if is a XML encoded string, otherwise, false
 */
function is_XML ( $xml)
{
  if ( @simplexml_load_string ( $xml))
  {
    return true;
  } else {
    return false;
  }
}

/**
 * Function to convert an array into a XML document.
 * Originally from https://stackoverflow.com/questions/9152176/convert-an-array-to-xml-or-json
 *
 * @param $array array The content to be converted.
 * @param $node_name[optional] string The root node name. Default "root".
 * @return string XML document
 */
function array2xml ( $array, $node_name = "root")
{
  $dom = new DOMDocument ( "1.0", "UTF-8");
  $dom->formatOutput = true;
  $root = $dom->createElement ( $node_name);
  $dom->appendChild ( $root);

  $array2xml = function ( $node, $array) use ( $dom, &$array2xml)
  {
    foreach ( $array as $key => $value)
    {
      if ( is_array ( $value))
      {
        $n = $dom->createElement ( $key);
        $node->appendChild ( $n);
        $array2xml ( $n, $value);
      } else {
        $attr = $dom->createAttribute ( $key);
        if ( is_bool ( $value))
        {
          $attr->value = ( $value ? "true" : "false");
        } else {
          $attr->value = $value;
        }
        $node->appendChild ( $attr);
      }
    }
  };

  $array2xml ( $root, $array);

  return $dom->saveXML ();
}

/**
 * Function to check if an IP address is on a CIDR.
 *
 * $param $ip string IP address
 * $param $cidr string The network CID
 * @return boolean
 */
function cidr_match ( $ip, $cidr)
{
  list ( $subnet, $bits) = split ( "/", $cidr);
  $ip = ip2long ( $ip);
  $subnet = ip2long ( $subnet);
  $mask = -1 << (32 - $bits);
  $subnet &= $mask;
  return ( $ip & $mask) == $subnet;
}

/**
 * For PHP versions <= 5.5.0, we doesn't have hash_pbkdf2 function, so, implement it if needed.
 */
if ( ! function_exists ( "hash_pbkdf2"))
{
  /**
   * Function to generate PBKDF2 hash.
   *
   * @param $algo String Cryptografic hash algorith to be used
   * @param $password String The password to be hashed
   * @param $salt String Salt to be used
   * @param $count int Number of iterations
   * @param $length int Length of the desired hash (in bytes)
   * @param $raw_output boolean[optional] Raw output option (default false)
   * @return string Resulting hash
   */
  function hash_pbkdf2 ( $algo, $password, $salt, $count, $length = 0, $raw_output = false)
  {
    if ( ! in_array ( strtolower ( $algo), hash_algos ()))
    {
      trigger_error ( __FUNCTION__ . "(): Unknown hashing algorithm: " . $algo, E_USER_WARNING);
    }
    if ( ! is_numeric ( $count))
    {
      trigger_error ( __FUNCTION__ . "(): expects parameter 4 to be long, " . gettype ( $count) . " given", E_USER_WARNING);
    }
    if ( ! is_numeric ( $length))
    {
      trigger_error ( __FUNCTION__ . "(): expects parameter 5 to be long, " . gettype ( $length) . " given", E_USER_WARNING);
    }
    if ( $count <= 0)
    {
      trigger_error ( __FUNCTION__ . "(): Iterations must be a positive integer: " . $count, E_USER_WARNING);
    }
    if ( $length < 0)
    {
      trigger_error ( __FUNCTION__ . "(): Length must be greater than or equal to 0: " . $length, E_USER_WARNING);
    }

    $output = "";
    $block_count = $length ? ceil ( $length / strlen ( hash ( $algo, "", $raw_output))) : 1;
    for ( $i = 1; $i <= $block_count; $i++)
    {
      $last = $xorsum = hash_hmac ( $algo, $salt . pack ( "N", $i), $password, true);
      for ( $j = 1; $j < $count; $j++)
      {
        $xorsum ^= ( $last = hash_hmac ( $algo, $last, $password, true));
      }
      $output .= $xorsum;
    }

    if ( ! $raw_output)
    {
      $output = bin2hex ( $output);
    }
    return $length ? substr ( $output, 0, $length) : $output;
  }
}

/**
 * Function to write messages to log file, with pre defined constant levels.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_lastlog Global variable that keep last log message
 * @global array $_lasttime Global variable that keep last log message timestamp
 * @param $msg String Mensage to be recorded
 * @param $severity int[Optional] Error level. Default VoIP_LOG_NOTICE, that only
 *                                register. If used VoIP_LOG_ERROR, end the
 *                                current process. If usedr VoIP_LOG_FATAL, end
 *                                all system processes
 * @return void
 */
function writeLog ( $msg, $severity = VoIP_LOG_NOTICE)
{
  global $_in, $_lastlog, $_lasttime, $_lastdate;

  // Check if last message are equal
  if ( $_lastlog == $msg)
  {
    $_lasttime++;
    return;
  } else {
    if ( $_lasttime == 1)
    {
      file_put_contents ( $_in["general"]["logfile"], $_lastdate . $_lastlog, FILE_APPEND);
    }
    if ( $_lasttime > 1)
    {
      // Register the number of times
      $msgdate = date ( "M") . " " . sprintf ( "% 2s", date ( "j")) . " " . date ( "H:i:s") . " " . php_uname ( "n") . " voipdomain[" . getmypid () . "]: ";
      $message = "WARNING: Last message repeated " . $_lasttime . " times.\n";
      file_put_contents ( $_in["general"]["logfile"], $msgdate . $message, FILE_APPEND);
      echo $message;
    }
    $_lasttime = 0;
    $_lastlog = $msg;
  }

  // Write message to log file
  $msgdate = date ( "M") . " " . sprintf ( "% 2s", date ( "j")) . " " . date ( "H:i:s") . " " . php_uname ( "n") . " voipdomain[" . getmypid () . "]: ";
  $message = ( $severity == VoIP_LOG_ERROR ? "ERROR: " : "") . ( $severity == VoIP_LOG_WARNING ? "WARNING: " : "") . ( $severity == VoIP_LOG_FATAL ? "FATAL: " : "") . $msg . "\n";
  file_put_contents ( $_in["general"]["logfile"], $msgdate . $message, FILE_APPEND);
  echo $message;
  if ( $severity == VoIP_LOG_FATAL || $severity == VoIP_LOG_ERROR)
  {
    exit ( 1);
  }
  $_lastdate = $msgdate;
}

/**
 * Function to send action response to main administrative interface.
 *
 * @global array $_in Framework global configuration variable
 * @param $rid int Event request ID
 * @param $code int Return code
 * @return null
 */
function reply_event ( $rid, $code)
{
  global $_in;

  if ( ! system_event ( "task_finished", array ( "id" => $rid, "result" => $code)))
  {
    writeLog ( "Fail replying to event ID \"" . $rid . "\" (" . $code . ").", VoIP_LOG_WARNING);
    return false;
  }
  return true;
}

/**
 * Function to enable Asterisk server commands buffering.
 *
 * @global array $_in Framework global configuration variable
 * @return void
 */
function asterisk_exec_enable_buffer ()
{
  if ( ! is_array ( $_in["AsteriskExecBuffer"]))
  {
    $_in["AsteriskExecBuffer"] = array ();
  }
}

/**
 * Function to empty Asterisk server commands buffering.
 *
 * @global array $_in Framework global configuration variable
 * @global boolean[optional] Disable buffer (default false)
 * @return void
 */
function asterisk_exec_empty_buffer ( $disable = false)
{
  if ( $disable)
  {
    unset ( $_in["AsteriskExecBuffer"]);
  } else {
    $_in["AsteriskExecBuffer"] = array ();
  }
}

/**
 * Function to flush Asterisk server commands buffering.
 *
 * @global array $_in Framework global configuration variable
 * @global boolean[optional] Disable buffer (default true)
 * @global boolean[optional] Remove duplicated commands (default true)
 * @return void
 */
function asterisk_exec_flush_buffer ( $disable = true, $removeduplicated = true)
{
  // Execute all commands
  $executed = array ();
  foreach ( $_in["AsteriskExecBuffer"] as $command)
  {
    if ( in_array ( $command, $executed) && $removeduplicated)
    {
      continue;
    }
    exec ( "/sbin/asterisk -r -x \"" . addslashes ( $command) . "\" 1>/dev/null 2>&1");
    $executed[] = $command;
  }

  if ( $disable)
  {
    unset ( $_in["AsteriskExecBuffer"]); 
  } else {
    $_in["AsteriskExecBuffer"] = array ();
  }
}

/**
 * Function to send command to the Asterisk server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $command Command to be sent
 * @return void
 */
function asterisk_exec ( $command)
{
  global $_in;

  if ( is_array ( $_in["AsteriskExecBuffer"]))
  {
    $_in["AsteriskExecBuffer"][] = $command;
  } else {
    exec ( "/sbin/asterisk -r -x \"" . addslashes ( $command) . "\" 1>/dev/null 2>&1");
  }
}

/**
 * Function to check database connection. If the connection hangs out, reconnect.
 *
 * @global array $_in Framework global configuration variable
 * @param void
 * @return void
 */
function mysql_check ()
{
  global $_in;

  if ( ! @$_in["mysql"]["id"] || ! @$_in["mysql"]["id"]->ping ())
  {
    if ( @$_in["mysql"]["id"])
    {
      @$_in["mysql"]["id"]->close ();
    }
    $_in["mysql"]["id"] = @new mysqli ( $_in["mysql"]["hostname"] . ( ! empty ( $_in["mysql"]["port"]) ? ":" . $_in["mysql"]["port"] : ""), $_in["mysql"]["username"], $_in["mysql"]["password"], $_in["mysql"]["database"]);
    if ( $_in["mysql"]["id"]->connect_errno)
    {
      writeLog ( "Cannot reconnect to database!", VoIP_LOG_WARNING);
    } else {
      writeLog ( "Reconnecting to MySQL database!", VoIP_LOG_WARNING);
    }
  }

  return;
}

/**
 * Function to call system API.
 *
 * @global array $_in Framework global configuration variable
 * @param $path string Path of API call endpoint.
 * @param $method string Method to be used.
 * @param $data array Array of parameters.
 * @return array Array containing the result code and content.
 */
function api_call ( $path, $method = "GET", $data = array ())
{
  global $_in;

  /**
   * Initialize socket
   */
  $data = json_encode ( $data);
  $socket = curl_init ();
  curl_setopt ( $socket, CURLOPT_URL, $_in["daemon"]["apiurl"] . ( substr ( $_in["daemon"]["apiurl"], -1) != "/" ? "/" : "") . ( substr ( $path, 0, 1) == "/" ? substr ( $path, 1) : $path));
  curl_setopt ( $socket, CURLOPT_USERAGENT, "VoIP Domain Client v" . $_in["version"] . " (Linux; U)");
  curl_setopt ( $socket, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ( $socket, CURLOPT_TIMEOUT, 10);
  curl_setopt ( $socket, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt ( $socket, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt ( $socket, CURLOPT_HTTPHEADER, array ( "Accept: application/json", "Content-Type: application/json", "X-HTTP-Method-Override: " . $method, "X-VD-SID: " . $_in["daemon"]["serverid"], "X-VD-SPWD: " . hash ( "sha256", $_in["daemon"]["key"])));
  curl_setopt ( $socket, CURLOPT_POST, true);
  curl_setopt ( $socket, CURLOPT_POSTFIELDS, $data);
  $result = curl_exec ( $socket);
  $status = curl_getinfo ( $socket, CURLINFO_HTTP_CODE);
  curl_close ( $socket);

  // Return status
  return array ( "Status" => $status, "Result" => $result);
}

/**
 * Function to remove file or directory recursively if needed.
 *
 * @param string $filename Full path to file or directory to be excluded
 * @return null
 */
function unlink_recursive ( $filename)
{
  if ( is_dir ( $filename))
  {
    $files = scandir ( $filename);
    foreach ( $files as $file)
    {
      if ( $file != "." && $file != "..")
      {
        unlink_recursive ( $filename . "/" . $file);
      }
    }
    rmdir ( $filename);
  } else {
    unlink ( $filename);
  }
}

/**
 * Function to send an event to system.
 *
 * @global array $_in Framework global configuration variable
 * @param string $event Event name
 * @param array $data Event data
 * @return string Gearman task job handle (or false if failed)
 */
function system_event ( $event, $data)
{
  global $_in;

  /**
   * Connect to gearman as client (if not connected)
   */
  if ( ! $_in["gearman"]["client"])
  {
    $_in["gearman"]["client"] = new GearmanClient ();
    if ( ! $_in["gearman"]["client"]->addServer ( $_in["gearman"]["servers"]))
    {
      unset ( $_in["gearman"]["client"]);
      return false;
    }
  }

  /**
   * Check certificates
   */
  if ( ! array_key_exists ( "privateKey", $_in["keys"]))
  {
    $_in["keys"]["privateKey"] = file_get_contents ( $_in["general"]["privateKey"]);
  }
  if ( ! array_key_exists ( "publicKey", $_in["keys"]))
  {
    $_in["keys"]["publicKey"] = file_get_contents ( $_in["general"]["publicKey"]);
  }
  if ( ! array_key_exists ( "masterPublicKey", $_in["keys"]))
  {
    $_in["keys"]["masterPublicKey"] = file_get_contents ( $_in["general"]["masterPublicKey"]);
  }

  /**
   * Prepare payload
   */
  $payload = json_encode ( array ( "event" => $event, "data" => $data));
  $iv = secure_rand ( 16, true);
  $key = secure_rand ( 256, true);
  $encrypted = openssl_encrypt ( $payload, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
  $hmac = hash_hmac ( "sha256", $encrypted, $key, true);
  openssl_public_encrypt ( $key, $encryptedkey, $_in["keys"]["masterPublicKey"]);
  openssl_sign ( $key . $iv . $hmac . $encrypted, $sign, $_in["keys"]["privateKey"], OPENSSL_ALGO_SHA1);

  /**
   * Send event data
   */
  return $_in["gearman"]["client"]->doBackground ( "server_task_reply", json_encode ( array ( "payload" => base64_encode ( $encrypted), "iv" => base64_encode ( $iv), "key" => base64_encode ( $encryptedkey), "hmac" => base64_encode ( $hmac), "sign" => base64_encode ( $sign), "sid" => $_in["daemon"]["serverid"])));
}

/**
 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
 * keys to arrays rather than overwriting the value in the first array with the duplicate
 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
 * this happens (documented behavior):
 *
 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('org value', 'new value'));
 *
 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
 * Matching keys' values in the second array overwrite those in the first array, as is the
 * case with array_merge, i.e.:
 *
 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('new value'));
 *
 * Parameters are passed by reference, though only for performance reasons. They're not
 * altered by this function.
 *
 * @param array $array1
 * @param array $array2
 * @return array
 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
 */
function array_merge_recursive_distinct ( array &$array1, array &$array2)
{
  $merged = $array1;

  foreach ( $array2 as $key => &$value)
  {
    if ( is_array ( $value) && isset ( $merged[$key]) && is_array ( $merged[$key]))
    {
      $merged[$key] = array_merge_recursive_distinct ( $merged[$key], $value);
    } else {
      $merged[$key] = $value;
    }
  }

  return $merged;
}

/**
 * Function to return a secure random string.
 *
 * @param $size int[optional] Size of the string (default 64 bytes)
 * @param $raw_output boolean[optional] Return raw data (default false)
 * @return string
 */
function secure_rand ( $size = 64, $raw_output = false)
{
  if ( function_exists ( "openssl_random_pseudo_bytes"))
  {
    $rnd = openssl_random_pseudo_bytes ( $size, $strong);
    if ( $strong === TRUE)
    {
      return $raw_output != false ? $rnd : bin2hex ( $rnd);
    }
  }
  $sha = "";
  $rnd = "";
  if ( file_exists ( "/dev/urandom"))
  {
    if ( $fp = fopen ( "/dev/urandom", "rb"))
    {
      if ( function_exists ( "stream_set_read_buffer"))
      {
        stream_set_read_buffer ( $fp, 0);
      }
      $sha = fread ( $fp, $size);
      fclose ( $fp);
    }
  }
  for ( $i = 0; $i < $size; $i++)
  {
    $sha = hash ( "sha256", $sha . mt_rand ());
    $char = mt_rand ( 0, 62);
    $rnd .= chr ( hexdec ( $sha[$char] . $sha[$char + 1]));
  }
  return $raw_output != false ? $rnd : bin2hex ( $rnd);
}

/**
 * Compares two strings using the same time whether they're equal or not.
 * This function is implemented on PHP < 5.6.0.
 *
 * @author asphp (at) dsgml (dot) com
 *
 * @param string $known_string The string of known length to compare against.
 * @param string $user_string The user-supplied string.
 * @return boolean Result of operation.
 */
if ( ! function_exists ( "hash_equals"))
{
  function hash_equals ( $known_string, $user_string)
  {
    if ( strlen ( $known_string) != strlen ( $user_string))
    {
      return false;
    }
    $result = $known_string ^ $user_string;
    $return = 0;
    for ( $x = strlen ( $result) - 1; $x >= 0; $x--)
    {
      $return |= ord ( $result[$x]);
    }
    return ! $return;
  }
}

/**
 * Function to format a string from a content based on a pattern. The pattern
 * could be any string, and will "cut" pieces of the content using {X} (specific
 * character at position X), {X,Y} (characters between position X with Y length)
 * and {X,} (characters from X position to finish of the content).
 * Example:
 *   str_format ( "+5551992425885", "({3,2}) {5,5}-{10,}")
 *   will output "(51) 99242-5885"
 *
 * @param $content string Content to be cutted.
 * @param $pattern string Pattern to be used.
 * @result string Result of the pattern and content.
 */
function str_format ( $content, $pattern)
{
  $output = "";
  while ( strpos ( $pattern, "{") !== false)
  {
    $output .= substr ( $pattern, 0, strpos ( $pattern, "{"));
    $pattern = substr ( $pattern, strpos ( $pattern, "{"));
    $block = substr ( $pattern, 0, strpos ( $pattern, "}") + 1);
    $pattern = substr ( $pattern, strlen ( $block));
    if ( strpos ( $block, ",") !== false)
    {
      $start = (int) substr ( $block, 1, strpos ( $block, ","));
      if ( substr ( $block, strlen ( $start) + 2, 1) == "}")
      {
        $output .= substr ( $content, $start);
      } else {
        $output .= substr ( $content, $start, (int) substr ( str_replace ( "}", "", $block), strpos ( $block, ",") + 1));
      }
    } else {
      $output .= substr ( $content, (int) str_replace ( "{", "", str_replace ( "}", "", $block)), 1);
    }
  }
  $output .= $pattern;

  return $output;
}

/**
 * Function to search for a substring inside an array.
 *
 * @param $needle string The string to search for.
 * @param $haystack array The array to search in.
 * @return boolean True if substring was found in any array value, otherwise false.
 */
function array_search_substring ( $needle, $haystack)
{
  foreach ( $haystack as $value)
  {
    if ( strpos ( $value, $needle) !== false)
    {
      return true;
    }
  }
  return false;
}

/**
 * Function to generate a dialplan pattern from start and finish numbers.
 *
 * @param $start int The starting number.
 * @param $finish int The finishing numbert
 * @return array An array with generated dialplan patterns matching start and
 *               finish numbers.
 */
function parse_dialplan ( $start, $finish)
{
  function strBreakPoint ( $t)
  {
    return str_pad ( $t, strlen ( ( $t + 1)), "0", STR_PAD_LEFT);
  }

  function reformatArray ( $t)
  {
    $arrReturn = [];
    for ( $i = 0; $i < count ( $t); $i++)
    {
      $page = count ( $t[$i]) / 2;
      for ( $a = 0; $a < $page; $a++)
      {
        $arrReturn[] = array_slice ( $t[$i], ( 2 * $a), 2);
      }
    }
    return $arrReturn;
  }

  function parseStartRange ( $t, $r)
  {
    if ( strlen ( $t) === strlen ( $r))
    {
      return [[$t, $r]];
    }
    $break = pow ( 10, strlen ( $t)) - 1;
    return array_merge ( [[$t, $break]], parseStartRange ( $break + 1, $r));
  }

  function parseEndRange ( $t, $r)
  {
    if ( strlen ( $t) == 1)
    {
      return [$t, $r];
    }
    if ( str_repeat ( "0", strlen ( $t)) === "0" . substr ( $t, 1))
    {
      if ( str_repeat ( "9", strlen ( $r)) == "9" . substr ( $r, 1))
      {
        return [$t, $r];
      }
      if ( (int) substr ( $t, 0, 1) < (int) substr ( $r, 0, 1))
      {
        $e = intval ( substr ( $r, 0, 1) . str_repeat ( "0", strlen ( $r) - 1)) - 1;
        return array_merge ( [$t, strBreakPoint ( $e)], parseEndRange ( strBreakPoint ( $e + 1), $r));
      }
    }
    if ( str_repeat ( "9", strlen ( $r)) === "9" . substr ( $r, 1) && (int) substr ( $t, 0, 1) < (int) substr ( $r, 0, 1))
    {
      $e = intval ( intval ( (int) substr ( $t, 0, 1) + 1) . "" . str_repeat ( "0", strlen ( $r) - 1)) - 1;
      return array_merge ( parseEndRange ( $t, strBreakPoint ( $e)), [strBreakPoint ( $e + 1), $r]);
    }
    if ( (int) substr ( $t, 0, 1) < (int) substr ( $r, 0, 1))
    {
      $e = intval ( intval ( (int) substr ( $t, 0, 1) + 1) . "" . str_repeat ( "0", strlen ( $r) - 1)) - 1;
      return array_merge ( parseEndRange ( $t, strBreakPoint ( $e)), parseEndRange ( strBreakPoint ( $e + 1), $r));
    }
    $a = (int) substr ( $t, 0, 1);
    $o = parseEndRange ( substr ( $t, 1), substr ( $r, 1));
    $h = [];
    for ( $u = 0; $u < count ( $o); $u++)
    {
      $h[] = ( $a . $o[$u]);
    }
    return $h;
  }

  function parseIntoDialplan ( $t)
  {
    if ( ! is_array ( $t))
    {
      throw new Exception ( "Argument needs to be an array!");
    }
    $r = [];
    for ( $i = 0; $i < count ( $t); $i++)
    {
      $e = str_split ( $t[$i][0]);
      $n = str_split ( $t[$i][1]);
      $h = "";
      for ( $a = 0; $a < count ( $e); $a++)
      {
        if ( $e[$a] === $n[$a])
        {
          $h .= $e[$a];
        } else {
          if ( ( intval ( $e[$a]) + 1) === intval ( $n[$a]))
          {
            $h .= "[" . $e[$a] . $n[$a] . "]";
          } else {
            if ( $n[$a] == "9")
            {
              switch ( $e[$a])
              {
                case "0":
                  $h .= "X";
                  break;
                case "1":
                  $h .= "Z";
                  break;
                case "2":
                  $h .= "N";
                  break;
                default:
                  $h .= "[" . $e[$a] . "-" . $n[$a] . "]";
                  break;
              }
            } else {
              $h .= "[" . $e[$a] . "-" . $n[$a] . "]";
            }
          }
        }
      }
      $r[] = $h;
    }
    return $r;
  }

  $min = (int) $start;
  $max = (int) $finish;
  if ( $min == $max)
  {
    return array ( (string) $min);
  }
  $s = [];
  $x = parseStartRange ( $min, $max);
  foreach ( $x as $o)
  {
    $s[] = parseEndRange ( $o[0], $o[1]);
  }
  $n = reformatArray ( $s);
  $h = parseIntoDialplan ( $n);
  $result = array ();
  foreach ( $h as $p)
  {
    $result[] = $p;
  }

  return $result;
}

/**
 * Function to generate a cryptographic salt.
 *
 * @param int $size[optional] Size of the salt (default 8).
 * @return string The salt itself.
 */
function gensalt ( $size = 8)
{
  $validchars = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
  $validlength = strlen ( $validchars) - 1;
  $salt = "";
  for ( $x = 0; $x < $size; $x++)
  {
    $salt .= $validchars[rand ( 0, $validlength)];
  }
  return $salt;
}

/**
 * Function to transform an UTF-8 encoded string to HTML entities in decimal or
 * hexadecimal ISO equivalent character.
 *
 * @param string $string The string to be converted.
 * @param int $hex[optional] If output encoding must use hexadecimal (default false).
 * @return string The encoded string.
 */
function string2isonumericentities ( $string, $hex = false)
{
  $string = htmlentities ( $string, ENT_XHTML, "UTF-8", true);
  preg_match_all ( "/(&[a-zA-Z]*;)/", $string, $matches, PREG_OFFSET_CAPTURE);
  $pos = 0;
  $result = "";
  foreach ( $matches[0] as $entry)
  {
    $result .= substr ( $string, 0, $entry[1] - $pos);
    $string = substr ( $string, $entry[1] - $pos + strlen ( $entry[0]));
    $pos = $entry[1] + strlen ( $entry[0]);
    $result .= "&#" . ( $hex ? bin2hex ( utf8_decode ( html_entity_decode ( $entry[0]))) : ord ( utf8_decode ( html_entity_decode ( $entry[0])))) . ";";
  }
  return $result . $string;
}

/**
 * Function to check if domain name is valid. https://stackoverflow.com/a/4694816
 *
 * @param string $domain The domain name to be validated
 * @return boolean If the domain is valid or not
 */
function is_domainname ( $domain)
{
  return ( preg_match ( "/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain) // Valid characters check
        && preg_match ( "/^.{1,253}$/", $domain) // Overall length check
        && preg_match ( "/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain)); // Length of each label
}

/**
 * Function to validate an email address.
 *
 * @param string $email Address to be validated
 * @return boolean Result
 */
function validate_email ( $email)
{
  $isValid = true;
  $atIndex = strrpos ( $email, "@");
  if ( is_bool ( $atIndex) && ! $atIndex)
  {
    $isValid = false;
  } else {
    $domain = substr ( $email, $atIndex + 1);
    $local = substr ( $email, 0, $atIndex);
    $localLen = strlen ( $local);
    $domainLen = strlen ( $domain);
    if ( $localLen < 1 || $localLen > 64)
    {
      // local part length exceeded
      $isValid = false;
    }
    if ( $domainLen < 1 || $domainLen > 255)
    {
       // domain part length exceeded
       $isValid = false;
    }
    if ( $local[0] == "." || $local[$localLen - 1] == ".")
    {
      // local part starts or ends with "."
      $isValid = false;
    }
    if ( preg_match ( "/\\.\\./", $local))
    {
      // local part has two consecutive dots
      $isValid = false;
    }
    if ( ! preg_match ( "/^[A-Za-z0-9\\-\\.]+$/", $domain))
    {
      // character not valid in domain part
      $isValid = false;
    }
    if ( preg_match ( "/\\.\\./", $domain))
    {
      // domain part has two consecutive dots
      $isValid = false;
    }
    if ( ! preg_match ( "/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/", str_replace ( "\\\\", "", $local)))
    {
      // character not valid in local part unless
      // local part is quoted
      if ( ! preg_match ( "/^\"(\\\\\"|[^\"])+\"$/", str_replace ( "\\\\", "", $local)))
      {
        $isValid = false;
      }
    }
    /**
     * DISABLED. We can't rely that system has internet access!
     *   if ( $isValid && ! ( checkdnsrr ( $domain, "MX") || checkdnsrr ( $domain, "A")))
     *   {
     *     // domain not found in DNS
     *     $isValid = false;
     *   }
     */
  }
  return $isValid;
}

/**
 * Function to fetch configuration value.
 *
 * @global array $_in Framework global configuration variable
 * @param string $context The context of configuration.
 * @param string $variable The variable name.
 * @return mixed False is not found or the value.
 */
function fetch_config ( $context, $variable)
{
  global $_in;

  if ( ! array_key_exists ( $context, $_in) || ! array_key_exists ( $variable, $_in[$context]))
  {
    return false;
  } else {
    return $_in[$context][$variable];
  }
}

/**
 * Function to get a list of available VoIP Domain configuration entries.
 *
 * @global array $_in Framework global configuration variable
 * @param string $search[optional] Mask to search. Default search all
 * @return array List of matched configurations
 */
function list_config ( $type, $search = "*")
{
  global $_in;

  // Check configuration type (to add directory):
  switch ( $type)
  {
    case "config":
      $dir = $_in["general"]["confdir"];
      $prefix = "";
      $postfix = ".conf";
      $search = basename ( $search);
      break;
    case "configdb":
      $dir = $_in["general"]["confdir"];
      $prefix = "dialplan-db-";
      $postfix = ".conf";
      $search = basename ( $search);
      break;
    case "audio":
      $dir = $_in["general"]["soundsdir"];
      $prefix = "";
      $postfix = "";
      if ( strpos ( "/", $search) !== false)
      {
        $search = dirname ( str_replace ( "..", "", $search)) . "/" . basename ( $search);
      } else {
        $search = basename ( $search);
      }
      break;
    case "ap":
      $dir = $_in["general"]["tftpdir"];
      $prefix = "";
      $postfix = "";
      if ( strpos ( "/", $search) !== false)
      {
        $search = dirname ( str_replace ( "..", "", $search)) . "/" . basename ( $search);
      } else {
        $search = basename ( $search);
      }
      break;
    case "datafile":
      $dir = $_in["general"]["datadir"];
      $prefix = "";
      $postfix = ".json";
      $search = basename ( $search);
      break;
    default:
      writeLog ( "Config list: Invalid configuration type \"" . $type . "\"!", VoIP_LOG_WARNING);
      return false;
      break;
  }

  // Be sure that the directory has a trailing "/":
  $dir = preg_replace ( "/\/+$/", "", $dir) . "/";

  // Get list of files:
  $files = glob ( $dir . $prefix . $search . $postfix);

  // Post process configuration file list:
  switch ( $type)
  {
    case "config":
    case "configdb":
    case "datafile":
      foreach ( $files as $index => $file)
      {
        $files[$index] = substr ( basename ( $file), 0, strlen ( basename ( $file)) - strlen ( $postfix));
      }
      break;
    case "audio":
    case "ap":
      foreach ( $files as $index => $file)
      {
        $files[$index] = substr ( $file, strlen ( $dir));
      }
      break;
  }

  return $files;
}

/**
 * Function to check if configuration file exist.
 *
 * @global array $_in Framework global configuration variable
 * @param string $type The configuration type ("config", "ap", "apdir", "audio",
 *                     "audiodir" or "datafile")
 * @param string $filename The file to be checked
 * @return boolean If configuration file exists
 */
function check_config ( $type, $filename)
{
  global $_in;

  // Check configuration type (to add directory):
  switch ( $type)
  {
    case "config":
      $dir = $_in["general"]["confdir"];
      $prefix = "";
      $postfix = ".conf";
      break;
    case "configdb":
      $dir = $_in["general"]["confdir"];
      $prefix = "dialplan-db-";
      $postfix = ".conf";
      break;
    case "audio":
    case "audiodir":
      $dir = $_in["general"]["soundsdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "ap":
    case "apdir":
      $dir = $_in["general"]["tftpdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "datafile":
      $dir = $_in["general"]["datadir"];
      $prefix = "";
      $postfix = ".json";
      break;
    default:
      writeLog ( "Config check: Invalid configuration type \"" . $type . "\"!", VoIP_LOG_WARNING);
      return false;
      break;
  }

  // Be sure that the directory has a trailing "/":
  $dir = preg_replace ( "/\/+$/", "", $dir) . "/";

  // Add directory name and remove trailing "/" from filename:
  $filename = $dir . $prefix . preg_replace ( "/^\/+/", "", $filename) . $postfix;

  // Return if file or directory exists:
  return ( $type == "audiodir" || $type == "apdir" ? is_dir ( $filename) : is_file ( $filename));
}

/**
 * Function to copy configuration files. Note: The config type will be the same.
 *
 * @global array $_in Framework global configuration variable
 * @param string $type The configuration type ("config", "ap", "apdir", "audio",
 *                     "audiodir" or "datafile")
 * @param string $source The source filename
 * @param string $target The target filename
 * @return boolean If operation was sucessfully done
 */
function copy_config ( $type, $source, $target)
{
  global $_in;

  // Check configuration type (to add directory):
  switch ( $type)
  {
    case "config":
      $dir = $_in["general"]["confdir"];
      $prefix = "";
      $postfix = ".conf";
      break;
    case "configdb":
      $dir = $_in["general"]["confdir"];
      $prefix = "dialplan-db-";
      $postfix = ".conf";
      break;
    case "audio":
      $dir = $_in["general"]["soundsdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "ap":
      $dir = $_in["general"]["tftpdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "datafile":
      $dir = $_in["general"]["datadir"];
      $prefix = "";
      $postfix = ".json";
      break;
    default:
      writeLog ( "Config copy: Invalid configuration type \"" . $type . "\"!", VoIP_LOG_WARNING);
      return false;
      break;
  }

  // Be sure that the directory has a trailing "/":
  $dir = preg_replace ( "/\/+$/", "", $dir) . "/";

  // Add directory name and remove trailing "/" from filename:
  $source = $dir . $prefix . preg_replace ( "/^\/+/", "", $source) . $postfix;
  $target = $dir . $prefix . preg_replace ( "/^\/+/", "", $target) . $postfix;

  // Copy file content:
  return copy ( $source, $target);
}

/**
 * Function to rename configuration files. Note: The config type will be the same.
 *
 * @global array $_in Framework global configuration variable
 * @param string $type The configuration type ("config", "ap", "apdir", "audio",
 *                     "audiodir" or "datafile")
 * @param string $source The source filename
 * @param string $target The target filename
 * @return boolean If operation was sucessfully done
 */
function rename_config ( $type, $source, $target)
{
  global $_in;

  // Check configuration type (to add directory):
  switch ( $type)
  {
    case "config":
      $dir = $_in["general"]["confdir"];
      $prefix = "";
      $postfix = ".conf";
      break;
    case "configdb":
      $dir = $_in["general"]["confdir"];
      $prefix = "dialplan-db-";
      $postfix = ".conf";
      break;
    case "audio":
      $dir = $_in["general"]["soundsdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "ap":
      $dir = $_in["general"]["tftpdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "datafile":
      $dir = $_in["general"]["datadir"];
      $prefix = "";
      $postfix = ".json";
      break;
    default:
      writeLog ( "Config rename: Invalid configuration type \"" . $type . "\"!", VoIP_LOG_WARNING);
      return false;
      break;
  }

  // Be sure that the directory has a trailing "/":
  $dir = preg_replace ( "/\/+$/", "", $dir) . "/";

  // Add directory name and remove trailing "/" from filename:
  $source = $dir . $prefix . preg_replace ( "/^\/+/", "", $source) . $postfix;
  $target = $dir . $prefix . preg_replace ( "/^\/+/", "", $target) . $postfix;

  // Copy file content:
  return rename ( $source, $target);
}

/**
 * Function to read configuration files.
 *
 * @global array $_in Framework global configuration variable
 * @param string $type The configuration type ("config", "ap", "audio" or
 *                     "datafile")
 * @param string $filename The file to be written
 * @param binary $content The file content
 * @return mixed The configuration file content
 */
function read_config ( $type, $filename, $content)
{
  global $_in;

  // Check configuration type (to add directory):
  switch ( $type)
  {
    case "config":
      $dir = $_in["general"]["confdir"];
      $prefix = "";
      $postfix = ".conf";
      break;
    case "configdb":
      $dir = $_in["general"]["confdir"];
      $prefix = "dialplan-db-";
      $postfix = ".conf";
      break;
    case "audio":
      $dir = $_in["general"]["soundsdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "ap":
      $dir = $_in["general"]["tftpdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "datafile":
      $dir = $_in["general"]["datadir"];
      $prefix = "";
      $postfix = ".json";
      break;
    default:
      writeLog ( "Config read: Invalid configuration type \"" . $type . "\"!", VoIP_LOG_WARNING);
      return false;
      break;
  }

  // Be sure that the directory has a trailing "/":
  $dir = preg_replace ( "/\/+$/", "", $dir) . "/";

  // Add directory name and remove trailing "/" from filename:
  $filename = $dir . $prefix . preg_replace ( "/^\/+/", "", $filename) . $postfix;

  // Return false if file doesn't exist:
  if ( ! is_readable ( $filename))
  {
    return false;
  }

  // Read file content:
  $content = file_get_contents ( $filename);

  // Parse special contents:
  switch ( $type)
  {
    case "configdb":
      $output = array ( "name" => "", "data" => array ());
      $output["name"] = substr ( $content, 9, strpos ( $content, ",") - 9);
      foreach ( preg_split ( "/((\r?\n)|(\r\n?))/", $content) as $line)
      {
        if ( substr ( $line, 0, 17) == " same => n,ExecIf")
        {
          $key = substr ( $line, 50, strpos ( $line, "\"", 51) - 50);
          $value = substr ( $line, strpos ( $line, "vd_" . $key . "=") + 4 + strlen ( $key), -2);
          $output["data"][$key] = $value;
        }
      }
      $content = $output;
      break;
    case "datafile":
      $content = json_decode ( $content, true);
      break;
  }

  // Return content:
  return $content;
}

/**
 * Function to write configuration files.
 *
 * @global array $_in Framework global configuration variable
 * @param string $type The configuration type ("config", "ap", "audio" or
 *                     "datafile")
 * @param string $filename The file to be written
 * @param binary $content The file content
 * @return boolean If operation was sucessfully done
 */
function write_config ( $type, $filename, $content)
{
  global $_in;

  // Check configuration type (to add directory):
  switch ( $type)
  {
    case "config":
      $dir = $_in["general"]["confdir"];
      $prefix = "";
      $postfix = ".conf";
      break;
    case "configdb":
      $dir = $_in["general"]["confdir"];
      $prefix = "dialplan-db-";
      $postfix = ".conf";
      $output = "exten => " . $content["name"] . ",1,ExecIf(\"\${ARG1}\" != \"\"]?Set(prefix=\${ARG1}_):Set(prefix=_))\n";
      foreach ( $content["data"] as $key => $value)
      {
        $output .= " same => n,ExecIf(\$[\"\${ARG2}\" = \"\" | \"\${ARG2}\" = \"" . $key . "\"]?Set(\${prefix}vd_" . $key . "=" . $value . "))\n";
      }
      $output .= " same => n,Return()\n";
      $content = $output;
      break;
    case "audio":
      $dir = $_in["general"]["soundsdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "ap":
      $dir = $_in["general"]["tftpdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "datafile":
      $dir = $_in["general"]["datadir"];
      $prefix = "";
      $postfix = ".json";
      $content = json_encode ( $content);
      break;
    default:
      writeLog ( "Config write: Invalid configuration type \"" . $type . "\"!", VoIP_LOG_WARNING);
      return false;
      break;
  }

  // Be sure that the directory has a trailing "/":
  $dir = preg_replace ( "/\/+$/", "", $dir) . "/";

  // Add directory name and remove trailing "/" from filename:
  $filename = $dir . $prefix . preg_replace ( "/^\/+/", "", $filename) . $postfix;

  // If it's an audio or ap file, check if directory exists, if not, create it:
  if ( ( $type == "audio" || $type == "ap") && ! file_exists ( dirname ( $filename)))
  {
    @mkdir ( dirname ( $filename), 0775, true);
  }

  // Write file content:
  return file_put_contents ( $filename, $content);
}

/**
 * Function to remove configuration files.
 *
 * @global array $_in Framework global configuration variable
 * @param string $type The configuration type ("config", "ap", "apdir", "audio",
 *                     "audiodir" or "datafile")
 * @param string $filename The file to be removed
 * @return boolean If operation was sucessfully done
 */
function unlink_config ( $type, $filename)
{
  global $_in;

  // Check configuration type (to add directory):
  switch ( $type)
  {
    case "config":
      $dir = $_in["general"]["confdir"];
      $prefix = "";
      $postfix = ".conf";
      break;
    case "configdb":
      $dir = $_in["general"]["confdir"];
      $prefix = "dialplan-db-";
      $postfix = ".conf";
      break;
    case "audio":
    case "audiodir":
      $dir = $_in["general"]["soundsdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "ap":
    case "apdir":
      $dir = $_in["general"]["tftpdir"];
      $prefix = "";
      $postfix = "";
      break;
    case "datafile":
      $dir = $_in["general"]["datadir"];
      $prefix = "";
      $postfix = ".json";
      break;
    default:
      writeLog ( "Config unlink: Invalid configuration type \"" . $type . "\"!", VoIP_LOG_WARNING);
      return false;
      break;
  }

  // Be sure that the directory has a trailing "/":
  $dir = preg_replace ( "/\/+$/", "", $dir) . "/";

  // Process special types:
  switch ( $type)
  {
    case "audiodir":
      $dir .= preg_replace ( "/^\/+/", "", $filename);
      if ( ! @rmdir ( $dir))
      {
        writeLog ( "Config unlink: Directory \"" . $dir . "\" could not be removed!", VoIP_LOG_WARNING);
        return false;
      }
      return true;
      break;
    case "apdir":
      $dir .= preg_replace ( "/^\/+/", "", $filename);
      if ( ! @rmdir ( $dir))
      {
        writeLog ( "Config unlink: Directory \"" . $dir . "\" could not be removed!", VoIP_LOG_WARNING);
        return false;
      }
      return true;
      break;
  }

  // Add directory name and remove trailing "/" from filename:
  $filename = $dir . $prefix . preg_replace ( "/^\/+/", "", $filename) . $postfix;

  // Check if file exists:
  if ( ! is_file ( $filename))
  {
    writeLog ( "Config unlink: File \"" . $filename . "\" not found!", VoIP_LOG_WARNING);
    return false;
  }

  // Remove file:
  if ( ! @unlink ( $filename))
  {
    writeLog ( "Config unlink: File \"" . $filename . "\" could not be removed!", VoIP_LOG_WARNING);
    return false;
  }
  // If reached here, file was removed:
  return true;
}
?>
