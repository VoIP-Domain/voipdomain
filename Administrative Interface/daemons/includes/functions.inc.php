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
 * Generic functions to IntelliNews Framework.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Generic Functions
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
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
function explodeQOS ( $string)
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
function isJSON ( $json)
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
function isXML ( $xml)
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
function cidrMatch ( $ip, $cidr)
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
  global $_in, $_lastlog, $_lasttime;

  // Check if last message are equal
  if ( $_lastlog == $msg)
  {
    $_lasttime++;
    return;
  } else {
    if ( $_lasttime > 0)
    {
      // Register the number of times
      $msgdate = date ( "M") . " " . sprintf ( "% 2s", date ( "j")) . " " . date ( "H:i:s") . " " . php_uname ( "n") . " voipdomain[" . getmypid () . "]: ";
      $message = "WARNING: Last message repeated " . $_lasttime . " time" . ( $_lasttime == 1 ? "" : "s") . ".\n";
      file_put_contents ( $_in["general"]["logfile"], $msgdate . $message, FILE_APPEND);
      echo $message;
      $_lasttime = 0;
    }
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

  /**
   * Initialize socket
   */
  $data = json_encode ( array ( "rid" => $rid, "result" => $code));
  $socket = curl_init ();
  curl_setopt ( $socket, CURLOPT_URL, $_in["daemon"]["apiurl"] . ( substr ( $_in["daemon"]["apiurl"], -1) != "/" ? "/" : "") . "sys/return");
  curl_setopt ( $socket, CURLOPT_USERAGENT, "VoIP Domain Client v" . $_in["version"] . " (Linux; U)");
  curl_setopt ( $socket, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ( $socket, CURLOPT_TIMEOUT, 10);
  curl_setopt ( $socket, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt ( $socket, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt ( $socket, CURLOPT_HTTPHEADER, array ( "Accept: application/json", "Content-Type: application/json", "Content-Length: " . strlen ( $data), "X-HTTP-Method-Override: POST", "X-VD-SID: " . $_in["daemon"]["serverid"], "X-VD-SPWD: " . hash ( "sha256", $_in["daemon"]["key"])));
  curl_setopt ( $socket, CURLOPT_POST, true);
  curl_setopt ( $socket, CURLOPT_POSTFIELDS, $data);
  $result = curl_exec ( $socket);
  $status = curl_getinfo ( $socket, CURLINFO_HTTP_CODE);
  curl_close ( $socket);

  // Log if response wasn't 200
  if ( $status != 200)
  {
    writeLog ( "Result code \"" . $status . "\" returned to event ID \"" . $rid . "\" (" . $code . ").", VoIP_LOG_WARNING);
  }

  // Return status
  return $status == 200;
}

/**
 * Function to send command to the Asterisk server.
 *
 * @param string $command Command to be sent
 * @return void
 */
function asterisk_exec ( $command)
{
  exec ( "/sbin/asterisk -r -x \"" . addslashes ( $command) . "\"");
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

  if ( ! @$_in["mysql"]["id"]->ping ())
  {
    @$_in["mysql"]["id"]->close ();
    $_in["mysql"]["id"] = @new mysqli ( $_in["mysql"]["hostname"] . ( ! empty ( $_in["mysql"]["port"]) ? ":" . $_in["mysql"]["port"] : ""), $_in["mysql"]["username"], $_in["mysql"]["password"], $_in["mysql"]["database"]);
    if ( $_in["mysql"]["id"]->connect_errno)
    {
      writeLog ( "Cannot reconnect to database!", VoIP_LOG_WARNING);
    } else {
      writeLog ( "Reconnecting to MySQL database!", VoIP_LOG_WARNING);
    }
  }
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
?>
