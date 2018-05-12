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
 * VoIP Domain gateways actions module. This module add the Asterisk client actions
 * calls related to gateways.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Gateways
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "creategateway", "gateways_create");
framework_add_hook ( "changegateway", "gateways_change");
framework_add_hook ( "removegateway", "gateways_remove");

/**
 * Function to create a new gateway.
 * Required parameters are: ID, Description, Domain, Username, Password, Address, Port, Qualify, NAT, RPID
 * Possible results:
 *   - 200: OK, gateway created
 *   - 400: Gateway already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Description", $parameters) || ! array_key_exists ( "Domain", $parameters) || ! array_key_exists ( "Username", $parameters) || ! array_key_exists ( "Password", $parameters) || ! array_key_exists ( "Address", $parameters) || ! array_key_exists ( "Port", $parameters) || ! array_key_exists ( "Qualify", $parameters) || ! array_key_exists ( "NAT", $parameters) || ! array_key_exists ( "RPID", $parameters) || ! is_bool ( $parameters["Qualify"]) || ! is_bool ( $parameters["NAT"]) || ! is_bool ( $parameters["RPID"]))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if gateway exist
  if ( file_exists ( $_in["general"]["confdir"] . "/sip-gateway-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Gateway already exist.");
  }

  // Create file structure
  $gateway = "[gateway_" . $parameters["ID"] . "]\n" .
             "type=peer\n" .
             "secret=" . $parameters["Password"] . "\n" .
             "username=" . $parameters["Username"] . "\n" .
             "host=" . $parameters["Address"] . "\n" .
             "port=" . $parameters["Port"] . "\n" .
             "fromuser=" . $parameters["Username"] . "\n" .
             "fromdomain=" . $parameters["Domain"] . "\n" .
             "directmedia=no\n" .
             "insecure=invite,port\n" .
             "description=" . $parameters["Description"] . "\n" .
             "qualify=" . ( $parameters["Qualify"] ? "yes" : "no") . "\n" .
             "sendrpid=" . ( $parameters["RPID"] ? "yes" : "no") . "\n" .
             "nat=" . ( $parameters["NAT"] ? "force_rport,comedia" : "no") . "\n";

  // Write gateway file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/sip-gateway-" . (int) $parameters["ID"] . ".conf", $gateway))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Gateway created.");
}

/**
 * Function to change an existing gateway.
 * Required parameters are: ID, Description, Domain, Username, Password, Address, Port, Qualify, NAT, RPID
 * Possible results:
 *   - 200: OK, gateway changed
 *   - 400: Gateway doesn't exist
 *   - 401: Invalid parameters
 *   - 402: Gateway new number already exist
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Description", $parameters) || ! array_key_exists ( "Domain", $parameters) || ! array_key_exists ( "Username", $parameters) || ! array_key_exists ( "Password", $parameters) || ! array_key_exists ( "Address", $parameters) || ! array_key_exists ( "Port", $parameters) || ! array_key_exists ( "Qualify", $parameters) || ! array_key_exists ( "NAT", $parameters) || ! array_key_exists ( "RPID", $parameters) || ! is_bool ( $parameters["Qualify"]) || ! is_bool ( $parameters["NAT"]) || ! is_bool ( $parameters["RPID"]))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if gateway exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/sip-gateway-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Gateway doesn't exist.");
  }

  // Change gateway parameters
  $gateway = file_get_contents ( $_in["general"]["confdir"] . "/sip-gateway-" . (int) $parameters["ID"] . ".conf");
  if ( preg_match ( "/^username=(.*)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(username=)(.*)(\n.*)/m", "\${1}\${2}" . $parameters["Username"] . "\${4}", $gateway);
  } else {
    $gateway .= "username=" . $parameters["Username"] . "\n";
  }
  if ( preg_match ( "/^fromuser=(.*)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(fromuser=)(.*)(\n.*)/m", "\${1}\${2}" . $parameters["Username"] . "\${4}", $gateway);
  } else {
    $gateway .= "fromuser=" . $parameters["Username"] . "\n";
  }
  if ( preg_match ( "/^secret=(.*)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(secret=)(.*)(\n.*)/m", "\${1}\${2}" . $parameters["Password"] . "\${4}", $gateway);
  } else {
    $gateway .= "secret=" . $parameters["Password"] . "\n";
  }
  if ( preg_match ( "/^host=(.*)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(host=)(.*)(\n.*)/m", "\${1}\${2}" . $parameters["Address"] . "\${4}", $gateway);
  } else {
    $gateway .= "host=" . $parameters["Address"] . "\n";
  }
  if ( preg_match ( "/^port=(.*)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(port=)(.*)(\n.*)/m", "\${1}\${2}" . $parameters["Port"] . "\${4}", $gateway);
  } else {
    $gateway .= "port=" . $parameters["Port"] . "\n";
  }
  if ( preg_match ( "/^fromdomain=(.*)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(fromdomain=)(.*)(\n.*)/m", "\${1}\${2}" . $parameters["Domain"] . "\${4}", $gateway);
  } else {
    $gateway .= "fromdomain=" . $parameters["Domain"] . "\n";
  }
  if ( preg_match ( "/^description=(.*)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(description=)(.*)(\n.*)/m", "\${1}\${2}" . $parameters["Description"] . "\${4}", $gateway);
  } else {
    $gateway .= "description=" . $parameters["Description"] . "\n";
  }
  if ( preg_match ( "/^qualify=(yes|no)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(qualify=)(yes|no)(.*)/m", "\${1}\${2}" . ( $parameters["Qualify"] ? "yes" : "no") . "\${4}", $gateway);
  } else {
    $gateway .= "qualify=" . ( $parameters["Qualify"] ? "yes" : "no") . "\n";
  }
  if ( preg_match ( "/^sendrpid=(yes|no)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(sendrpid=)(yes|no)(.*)/m", "\${1}\${2}" . ( $parameters["RPID"] ? "yes" : "no") . "\${4}", $gateway);
  } else {
    $gateway .= "sendrpid=" . ( $parameters["RPID"] ? "yes" : "no") . "\n";
  }
  if ( preg_match ( "/^nat=(force_rport,comedia|no)$/m", $gateway))
  {
    $gateway = preg_replace ( "/(.*)(nat=)(force_rport,comedia|no)(.*)/m", "\${1}\${2}" . ( $parameters["NAT"] ? "force_rport,comedia" : "no") . "\${4}", $gateway);
  } else {
    $gateway .= "nat=" . ( $parameters["NAT"] ? "force_rport,comedia" : "no") . "\n";
  }

  // Write gateway file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/sip-gateway-" . (int) $parameters["ID"] . ".conf", $gateway))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Gateway changed.");
}

/**
 * Function to remove an existing gateway.
 * Required parameters are: ID
 * Possible results:
 *   - 200: OK, gateway removed
 *   - 400: Gateway doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if gateway exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/sip-gateway-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Gateway doesn't exist.");
  }

  // Remove gateway file
  if ( ! unlink ( $_in["general"]["confdir"] . "/sip-gateway-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Gateway removed.");
}
?>
