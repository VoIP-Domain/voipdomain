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
 * FastAGI Least Cost Route (LCR) module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk FastAGI LCR Application
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Function to translate a number based on an indexed array.
 *
 * @param $translations Array An array containing the translations. Each
 *                            translation must be an array with keys "pattern",
 *                            "remove" and "add".
 * @param $number String Number to be translated.
 * @return String
 */
function translateNumber ( $translations, $number)
{
  $number = preg_replace ( "/[^0-9+]/", "", $number);
  foreach ( $translations as $translation)
  {
    $pattern = preg_replace ( "/[^0-9+]/", "", $translation["pattern"]);
    $remove = preg_replace ( "/[^0-9+]/", "", $translation["remove"]);
    $add = preg_replace ( "/[^0-9+]/", "", $translation["add"]);
    if ( substr ( $number, 0, strlen ( $pattern)) == $pattern)
    {
      return $add . substr ( $number, strlen ( $remove));
    }
  }
  return $number;
}

/**
 * Function to validate a number based on a mask (Asterisk standard). 0 to 9, +,
 * X (0 to 9), Z (1 to 9), N (2 to 9) and groups [1236-9] for example.
 *
 * @param $mask String Mask to be applied.
 * @param $number String Number to validate.
 * @return boolean
 */
function matchMask ( $mask, $number)
{
  $number = preg_replace ( "/[^0-9+]/", "", $number);
  $mask = preg_replace ( "/[^0-9(\[.*\-.*\])+XZN]/", "", strtoupper ( $mask));

  $pos = 0;
  for ( $x = 0; $x < strlen ( $mask); $x++)
  {
    $digit = substr ( $mask, $x, 1);
    $match = substr ( $number, $pos, 1);
    $pos++;
    switch ( $digit)
    {
      case "0":         // Digit 0
      case "1":         // Digit 1
      case "2":         // Digit 2
      case "3":         // Digit 3
      case "4":         // Digit 4
      case "5":         // Digit 5
      case "6":         // Digit 6
      case "7":         // Digit 7
      case "8":         // Digit 8
      case "9":         // Digit 9
      case "+":         // Digit +
        if ( $match != $digit)
        {
          return false;
        }
        break;
      case "X":         // Any number between 0 to 9
        if ( ord ( $digit) < 48 && ord ( $digit) > 57)
        {
          return false;
        }
        break;
      case "Z":         // Any number between 1 to 9
        if ( ord ( $digit) < 48 && ord ( $digit) > 57)
        {
          return false;
        }
        break;
      case "N":         // Any number between 2 to 9
        if ( ord ( $digit) < 49 && ord ( $digit) > 57)
        {
          return false;
        }
        break;
      case "[":         // Group
        $group = array ();
        $last = 48;
        while ( $digit != 93)
        {
          $x++;
          $digit = ord ( substr ( $mask, $x, 1));
          if ( $x > strlen ( $mask))
          {
            return false;
          }
          if ( $digit >= 48 && $digit <= 57)
          {
            $group[] = $digit;
            $last = $digit;
          }
          if ( $digit == 45 && ( ord ( substr ( $mask, $x + 1, 1)) >= 48 && ord ( substr ( $mask, $x + 1, 1)) <= 57))
          {
            $last++;
            $x++;
            $digit = ord ( substr ( $mask, $x, 1));
            if ( $digit < $last)
            {
              $digit = $last;
              $last = ord ( substr ( $mask, $x, 1));
            }
            for ( $y = $last; $y <= $digit; $y++)
            {
              $group[] = $y;
            }
          }
        }
        if ( ! in_array ( ord ( $match), $group))
        {
          return false;
        }
        break;
    }
  }
  return true;
}

/**
 * Function to ordenate routes based on cost, type and priority.
 *
 * @param $a mixed Cost a.
 * @param $b mixed Cost b.
 * @return int
 */
function costOrder ( $a, $b)
{
  global $defaultgw;

  if ( $a["cost"] == $b["cost"])
  {
    if ( $a["type"] == $b["type"])
    {
      if ( $a["priority"] == $b["priority"])
      {
        if ( in_array ( $b["gw"], $defaultgw))
        {
          return 1;
        }
        return 0;
      }
      return ( $a["priority"] > $b["priority"] ? -1 : 1);
    }
    if ( $a["type"] == "Digital")
    {
      return -1;
    }
    if ( $a["type"] == "Mobile")
    {
      if ( $b["type"] == "Digital")
      {
        return 1;
      }
      return -1;
    }
    if ( $a["type"] == "VoIP")
    {
      if ( $b["type"] == "Digital" || $b["type"] == "Mobile")
      {
        return 1;
      }
      return -1;
    }
    return 1;
  }
  return ( $a["cost"] < $b["cost"] ? -1 : 1);
}

/**
 * FastAGI application LCR
 */
register_app ( "lcr", "lcr", array ( "title" => "Least Cost Route"));
framework_add_hook ( "lcr", "lcr");

/**
 * Function to process Least Cost Route system gateways output for an E.164
 * destination number.
 *
 * Expected parameters:
 * Arg 1: Destination (E.164 standard number)
 * Arg 2: Comma separated preffered gateway list
 * Arg 3: Comma separated blocked gateway list
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function lcr ( $buffer, $parameters)
{
  // Filter basic parameters:
  $num = preg_replace ( "/[^0-9+]/", "", $parameters["arg_1"]);
  $defaultgw = explode ( ",", $parameters["arg_2"]);
  $blockedgw = explode ( ",", $parameters["arg_3"]);

  // Calculate LCR only if it's an E.164 number
  if ( substr ( $num, 0, 1) != "+")
  {
    return $buffer;
  }

  // Create route to each valid gateway:
  $routes = array ();

  // Update gateways:
  update_gateways ();

  // Proccess each system gateway:
  foreach ( fetch_config ( "db", "gateway") as $gateway)
  {
    // Avoid blocked gateways:
    if ( in_array ( $gateway["ID"], $blockedgw))
    {
      continue;
    }

    // Proccess "manual" gateways:
    if ( $gateway["Config"] == "manual")
    {
      // Check if gateway has route to requested number:
      foreach ( $gateway["Routes"] as $route)
      {
        if ( matchMask ( $route["Route"], $num))
        {
          $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => translateNumber ( $gateway["Translations"], $num), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => $route["Cost"]);
          break;
        }
      }
    } else {
      if ( ! framework_has_hook ( "lcr_" . $gateway["Config"]))
      {
        writelog ( $parameters["AGI_Client"] . "Gateway #" . $gateway["ID"] . " with unprocessable type \"" . $gateway["Config"] . "\" to LCR request.");
      } else {
        $data = framework_call ( "lcr_" . $gateway["Config"], array_merge_recursive ( $parameters, array ( "num" => $num, "GW" => $gateway)));
        if ( sizeof ( $data) != 0)
        {
          $routes = array_merge_recursive ( $routes, $data);
        }
      }
    }
  }

  // Add resulted routes to reply to client:
  usort ( $routes, "costOrder");
  $data = array ();
  foreach ( $routes as $id => $route)
  {
    $id++;
    writeLog ( $parameters["AGI_Client"] . "Route weight " . $id . ": GW = " . $route["gw"] . ", Dial = " . $route["dial"] . ", Cost = " . sprintf ( "%.5f", $route["cost"]));
    $data[] = "SET VARIABLE \"r_" . $id . "_gw\" \"" . $route["gw"] . "\"";
    $data[] = "SET VARIABLE \"r_" . $id . "_dial\" \"" . $route["dial"] . "\"";
    $data[] = "SET VARIABLE \"r_" . $id . "_td\" \"" . $route["td"] . "\"";
    $data[] = "SET VARIABLE \"r_" . $id . "_tm\" \"" . $route["tm"] . "\"";
    $data[] = "SET VARIABLE \"r_" . $id . "_tf\" \"" . $route["tf"] . "\"";
    $data[] = "SET VARIABLE \"r_" . $id . "_cm\" \"" . $route["cost"] . "\"";
  }
  if ( sizeof ( $routes) == 0)
  {
    writelog ( $parameters["AGI_Client"] . "No route found.");
  }

  // Merge buffer and return data:
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
