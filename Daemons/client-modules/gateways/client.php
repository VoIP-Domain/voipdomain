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
 * VoIP Domain gateways actions module. This module add the Asterisk client actions
 * calls related to gateways.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Gateways
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "gateway_add", "gateway_add");
framework_add_hook ( "gateway_change", "gateway_change");
framework_add_hook ( "gateway_remove", "gateway_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "gateway_wipe", "gateway_wipe");
cleanup_register ( "Gateways", "gateway_wipe");

/**
 * Function to create a new gateway.
 * Required parameters are: (int) ID, (string) Description, (string) Address, (int) Port, (string) Username, (string) Password, (bool) Qualify, (bool) NAT, (bool) RPID, (string) Config, (string) Number, (string) Type, (int) Priority, (array (array (string) Route, (float) Cost)) Routes, (array (array (string) Pattern, (string) Remove, (string) Add)) Translations, (int) Discard, (int) Minimum, (int) Fraction
 * Possible results:
 *   - 200: OK, gateway created (overwritten)
 *   - 201: OK, gateway created
 *   - 400: Gateway already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function gateway_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateway_add_start"))
  {
    $parameters = framework_call ( "gateway_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters))
  {
    $data["ID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "ID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["ID"]))
  {
    $data["ID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Address", $parameters))
  {
    $data["Address"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Address", $data) && ! preg_match ( "/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^(?:(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-fA-F]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:(?:[0-9a-fA-F]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,1}(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:(?:[0-9a-fA-F]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,2}(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:(?:[0-9a-fA-F]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,3}(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:[0-9a-fA-F]{1,4})):)(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,4}(?:(?:[0-9a-fA-F]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,5}(?:(?:[0-9a-fA-F]{1,4})))?::)(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,6}(?:(?:[0-9a-fA-F]{1,4})))?::))))$/", $parameters["Address"]))
  {
    $data["Address"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Port", $parameters))
  {
    $data["Port"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Port", $data) && ( $parameters["Port"] < 0 || $parameters["Port"] > 65535))
  {
    $data["Port"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Username", $parameters))
  {
    $data["Username"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Username", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Username"]))
  {
    $data["Username"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Qualify", $parameters))
  {
    $data["Qualify"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Qualify", $data) && ! is_bool ( $parameters["Qualify"]))
  {
    $data["Qualify"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NAT", $parameters))
  {
    $data["NAT"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NAT", $data) && ! is_bool ( $parameters["NAT"]))
  {
    $data["NAT"] = "Invalid content";
  }
  if ( ! array_key_exists ( "RPID", $parameters))
  {
    $data["RPID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "RPID", $data) && ! is_bool ( $parameters["RPID"]))
  {
    $data["RPID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Config", $parameters))
  {
    $data["Config"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Config", $data) && ! in_array ( $parameters["Config"], fetch_config ( "gateways", "configtypes")))
  {
    $data["Config"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^\+[1-9][0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Type", $parameters))
  {
    $data["Type"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Type", $data) && ! in_array ( $parameters["Type"], fetch_config ( "gateways", "types")))
  {
    $data["Type"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Priority", $parameters))
  {
    $data["Priority"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Priority", $data) && ( $parameters["Priority"] < 0 || $parameters["Priority"] > 2))
  {
    $data["Priority"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Routes", $parameters))
  {
    $data["Routes"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Routes", $data) && ! is_array ( $parameters["Routes"]))
  {
    $data["Routes"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Routes", $data))
  {
    foreach ( $parameters["Routes"] as $route)
    {
      if ( ! array_key_exists ( "Route", $route) || ! preg_match ( "/^\+[1-9]?[0-9]*$/", $route["Route"]) || ! array_key_exists ( "Cost", $route) || $route["Cost"] != (float) $route["Cost"])
      {
        $data["Routes"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Translations", $parameters))
  {
    $data["Translations"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Translations", $data) && ! is_array ( $parameters["Translations"]))
  {
    $data["Translations"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Translations", $data))
  {
    foreach ( $parameters["Translations"] as $translation)
    {
      if ( ! array_key_exists ( "Pattern", $translation) || ! preg_match ( "/^\+?[0-9]+$/", $translation["Pattern"]) || ! array_key_exists ( "Remove", $translation) || ! preg_match ( "/^\+?[0-9]+$/", $translation["Remove"]) || ! array_key_exists ( "Add", $translation) || ! preg_match ( "/^[0-9]+$/", $translation["Add"]))
      {
        $data["Translations"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Discard", $parameters))
  {
    $data["Discard"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Discard", $data) && ( $parameters["Discard"] < 0 || $parameters["Discard"] > 255))
  {
    $data["Discard"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Minimum", $parameters))
  {
    $data["Minimum"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Minimum", $data) && ( $parameters["Minimum"] < 0 || $parameters["Minimum"] > 255))
  {
    $data["Minimum"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Fraction", $parameters))
  {
    $data["Fraction"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Fraction", $data) && ( $parameters["Fraction"] < 0 || $parameters["Fraction"] > 255))
  {
    $data["Fraction"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateway_add_validate"))
  {
    $data = framework_call ( "gateway_add_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateway_add_sanitize"))
  {
    $parameters = framework_call ( "gateway_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateway_add_pre"))
  {
    $parameters = framework_call ( "gateway_add_pre", $parameters, false, $parameters);
  }

  // Verify if gateway exist
  if ( check_config ( "config", "sip-gateway-" . $parameters["ID"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Gateway already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Create SIP file structure
  $gateway = "";
  if ( ! empty ( $parameters["Username"]))
  {
    $gateway .= "[vd_gateway_" . $parameters["ID"] . "]\n" .
                "type=auth\n" .
                "auth_type=userpass\n" .
                "username=" . $parameters["Username"] . "\n" .
                "password=" . $parameters["Password"] . "\n" .
                "\n";
  }
  $gateway .= "[vd_gateway_" . $parameters["ID"] . "]\n" .
              "type=aor\n" .
              "contact=sip:" . $parameters["Address"] . ":" . $parameters["Port"] . "\n" .
              "max_contacts=1\n";
  if ( $parameters["Qualify"])
  {
  $gateway .= "qualify_frequency=60\n" .
              "qualify_timeout=0.5\n";
  }
  $gateway .= "\n" .
              "[vd_gateway_" . $parameters["ID"] . "]\n" .
              "type=endpoint\n";
  if ( ! empty ( $parameters["Username"]))
  {
    $gateway .= "from_user=" . $parameters["Username"] . "\n" .
                "from_domain=" . $_in["general"]["address"] . "\n" .
                "outbound_auth=vd_gateway_" . $parameters["ID"] . "\n";
  }
  $gateway .= "context=VoIPDomain-Gateway-" . $parameters["ID"] . "_inbound\n" .
              "disallow=all\n" .
              "allow=all\n" .
              "aors=vd_gateway_" . $parameters["ID"] . "\n" .
              "rtp_symmetric=" . ( $parameters["NAT"] ? "yes" : "no") . "\n" .
              "force_rport=" . ( $parameters["NAT"] ? "yes" : "no") . "\n" .
              "rewrite_contact=" . ( $parameters["NAT"] ? "yes" : "no") . "\n" .
              "direct_media=" . ( $parameters["NAT"] ? "no" : "yes") . "\n" .
              "send_rpid=" . ( $parameters["RPID"] ? "yes" : "no") . "\n" .
              "\n" .
              "[vd_gateway_" . $parameters["ID"] . "]\n" .
              "type=identify\n" .
              "endpoint=vd_gateway_" . $parameters["ID"] . "\n" .
              "match=" . $parameters["Address"] . "\n";

  // Write gateway file structure
  if ( ! write_config ( "config", "sip-gateway-" . $parameters["ID"], $gateway))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create dialplan file structure
  $dialplan = "[VoIPDomain-Gateway-" . $parameters["ID"] . "_inbound]\n" .
              "exten => _.X,1,Set(CDR(Gateway)=" . $parameters["ID"] . ")\n" .
              " same => n,GoTo(${EXTEN}," . fetch_config ( "contexts", "public") . ")\n";

  // Write dialplan file
  if ( ! write_config ( "config", "dialplan-gateway-" . $parameters["ID"], $dialplan))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create gateway data file
  if ( ! write_config ( "datafile", "gateway-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateway_add_post") && ! framework_call ( "gateway_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateway_add_finish"))
  {
    framework_call ( "gateway_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Gateway created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Gateway created.");
  }
}

/**
 * Function to change an existing gateway.
 * Required parameters are: (int) ID, (string) Description, (string) Address, (int) Port, (string) Username, (string) Password, (bool) Qualify, (bool) NAT, (bool) RPID, (string) Config, (string) Number, (string) Type, (int) Priority, (array (array (string) Route, (float) Cost)) Routes, (array (array (string) Pattern, (string) Remove, (string) Add)) Translations, (int) Discard, (int) Minimum, (int) Fraction
 * Possible results:
 *   - 200: OK, gateway changed
 *   - 404: Gateway doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function gateway_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateway_change_start"))
  {
    $parameters = framework_call ( "gateway_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters))
  {
    $data["ID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "ID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["ID"]))
  {
    $data["ID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Address", $parameters))
  {
    $data["Address"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Address", $data) && ! preg_match ( "/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^(?:(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-fA-F]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:(?:[0-9a-fA-F]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,1}(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:(?:[0-9a-fA-F]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,2}(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:(?:[0-9a-fA-F]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,3}(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:[0-9a-fA-F]{1,4})):)(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,4}(?:(?:[0-9a-fA-F]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,5}(?:(?:[0-9a-fA-F]{1,4})))?::)(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,6}(?:(?:[0-9a-fA-F]{1,4})))?::))))$/", $parameters["Address"]))
  {
    $data["Address"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Port", $parameters))
  {
    $data["Port"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Port", $data) && ( $parameters["Port"] < 0 || $parameters["Port"] > 65535))
  {
    $data["Port"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Username", $parameters))
  {
    $data["Username"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Username", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Username"]))
  {
    $data["Username"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Qualify", $parameters))
  {
    $data["Qualify"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Qualify", $data) && ! is_bool ( $parameters["Qualify"]))
  {
    $data["Qualify"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NAT", $parameters))
  {
    $data["NAT"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NAT", $data) && ! is_bool ( $parameters["NAT"]))
  {
    $data["NAT"] = "Invalid content";
  }
  if ( ! array_key_exists ( "RPID", $parameters))
  {
    $data["RPID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "RPID", $data) && ! is_bool ( $parameters["RPID"]))
  {
    $data["RPID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Config", $parameters))
  {
    $data["Config"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Config", $data) && ! in_array ( $parameters["Config"], fetch_config ( "gateways", "configtypes")))
  {
    $data["Config"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^\+[1-9][0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Type", $parameters))
  {
    $data["Type"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Type", $data) && ! in_array ( $parameters["Type"], fetch_config ( "gateways", "types")))
  {
    $data["Type"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Priority", $parameters))
  {
    $data["Priority"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Priority", $data) && ( $parameters["Priority"] < 0 || $parameters["Priority"] > 2))
  {
    $data["Priority"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Routes", $parameters))
  {
    $data["Routes"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Routes", $data) && ! is_array ( $parameters["Routes"]))
  {
    $data["Routes"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Routes", $data))
  {
    foreach ( $parameters["Routes"] as $route)
    {
      if ( ! array_key_exists ( "Route", $route) || ! preg_match ( "/^\+[1-9]?[0-9]*$/", $route["Route"]) || ! array_key_exists ( "Cost", $route) || $route["Cost"] != (float) $route["Cost"])
      {
        $data["Routes"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Translations", $parameters))
  {
    $data["Translations"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Translations", $data) && ! is_array ( $parameters["Translations"]))
  {
    $data["Translations"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Translations", $data))
  {
    foreach ( $parameters["Translations"] as $translation)
    {
      if ( ! array_key_exists ( "Pattern", $translation) || ! preg_match ( "/^\+?[0-9]+$/", $translation["Pattern"]) || ! array_key_exists ( "Remove", $translation) || ! preg_match ( "/^\+?[0-9]+$/", $translation["Remove"]) || ! array_key_exists ( "Add", $translation) || ! preg_match ( "/^[0-9]+$/", $translation["Add"]))
      {
        $data["Translations"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "Discard", $parameters))
  {
    $data["Discard"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Discard", $data) && ( $parameters["Discard"] < 0 || $parameters["Discard"] > 255))
  {
    $data["Discard"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Minimum", $parameters))
  {
    $data["Minimum"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Minimum", $data) && ( $parameters["Minimum"] < 0 || $parameters["Minimum"] > 255))
  {
    $data["Minimum"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Fraction", $parameters))
  {
    $data["Fraction"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Fraction", $data) && ( $parameters["Fraction"] < 0 || $parameters["Fraction"] > 255))
  {
    $data["Fraction"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateway_change_validate"))
  {
    $data = framework_call ( "gateway_change_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateway_change_sanitize"))
  {
    $parameters = framework_call ( "gateway_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateway_change_pre"))
  {
    $parameters = framework_call ( "gateway_change_pre", $parameters, false, $parameters);
  }

  // Verify if gateway exist
  if ( ! check_config ( "config", "sip-gateway-" . $parameters["ID"]) || ! check_config ( "config", "dialplan-gateway-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Gateway doesn't exist.");
  }

  // Create SIP file structure
  $gateway = "";
  if ( ! empty ( $parameters["Username"]))
  {
    $gateway .= "[vd_gateway_" . $parameters["ID"] . "]\n" .
                "type=auth\n" .
                "auth_type=userpass\n" .
                "username=" . $parameters["Username"] . "\n" .
                "password=" . $parameters["Password"] . "\n" .
                "\n";
  }
  $gateway .= "[vd_gateway_" . $parameters["ID"] . "]\n" .
              "type=aor\n" .
              "contact=sip:" . $parameters["Address"] . ":" . $parameters["Port"] . "\n" .
              "max_contacts=1\n";
  if ( $parameters["Qualify"])
  {
  $gateway .= "qualify_frequency=60\n" .
              "qualify_timeout=0.5\n";
  }
  $gateway .= "\n" .
              "[vd_gateway_" . $parameters["ID"] . "]\n" .
              "type=endpoint\n";
  if ( ! empty ( $parameters["Username"]))
  {
    $gateway .= "from_user=" . $parameters["Username"] . "\n" .
                "from_domain=" . $_in["general"]["address"] . "\n" .
                "outbound_auth=vd_gateway_" . $parameters["ID"] . "\n";
  }
  $gateway .= "context=VoIPDomain-Gateway-" . $parameters["ID"] . "_inbound\n" .
              "disallow=all\n" .
              "allow=all\n" .
              "aors=vd_gateway_" . $parameters["ID"] . "\n" .
              "rtp_symmetric=" . ( $parameters["NAT"] ? "yes" : "no") . "\n" .
              "force_rport=" . ( $parameters["NAT"] ? "yes" : "no") . "\n" .
              "rewrite_contact=" . ( $parameters["NAT"] ? "yes" : "no") . "\n" .
              "direct_media=" . ( $parameters["NAT"] ? "no" : "yes") . "\n" .
              "send_rpid=" . ( $parameters["RPID"] ? "yes" : "no") . "\n" .
              "\n" .
              "[vd_gateway_" . $parameters["ID"] . "]\n" .
              "type=identify\n" .
              "endpoint=vd_gateway_" . $parameters["ID"] . "\n" .
              "match=" . $parameters["Address"] . "\n";

  // Write gateway file structure
  if ( ! write_config ( "config", "sip-gateway-" . $parameters["ID"], $gateway))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create dialplan file structure
  $dialplan = "[VoIPDomain-Gateway-" . $parameters["ID"] . "_inbound]\n" .
              "exten => _.X,1,Set(CDR(Gateway)=" . $parameters["ID"] . ")\n" .
              " same => n,GoTo(${EXTEN}," . $_in["contexts"]["public"] . ")\n";

  // Write dialplan file
  if ( ! write_config ( "config", "dialplan-gateway-" . $parameters["ID"], $dialplan))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create gateway data file
  if ( ! write_config ( "datafile", "gateway-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateway_change_post") && ! framework_call ( "gateway_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk configurations
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateway_change_finish"))
  {
    framework_call ( "gateway_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Gateway changed.");
}

/**
 * Function to remove an existing gateway.
 * Required parameters are: (int) ID
 * Possible results:
 *   - 200: OK, gateway removed
 *   - 404: Gateway doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function gateway_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateway_remove_start"))
  {
    $parameters = framework_call ( "gateway_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters))
  {
    $data["ID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "ID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["ID"]))
  {
    $data["ID"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateway_remove_validate"))
  {
    $data = framework_call ( "gateway_remove_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateway_remove_sanitize"))
  {
    $parameters = framework_call ( "gateway_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateway_remove_pre"))
  {
    $parameters = framework_call ( "gateway_remove_pre", $parameters, false, $parameters);
  }

  // Verify if gateway exist
  if ( ! check_config ( "config", "sip-gateway-" . $parameters["ID"]) || ! check_config ( "config", "dialplan-gateway-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Gateway doesn't exist.");
  }

  // Remove gateway files
  if ( ! unlink_config ( "config", "sip-gateway-" . $parameters["ID"]) || ! unlink_config ( "datafile", "gateway-" . $parameters["ID"]) || ! unlink_config ( "config", "dialplan-gateway-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateway_remove_post") && ! framework_call ( "gateway_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk configurations
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateway_remove_finish"))
  {
    framework_call ( "gateway_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Gateway removed.");
}

/**
 * Function to remove all existing gateways configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function gateway_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateway_wipe_start"))
  {
    $parameters = framework_call ( "gateway_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateway_wipe_pre"))
  {
    framework_call ( "gateway_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "gateway") as $filename)
  {
    $entryid = (int) substr ( $filename, strrpos ( $filename, "-") + 1);
    unlink_config ( "config", "sip-gateway-" . $entryid);
    unlink_config ( "config", "dialplan-gateway-" . $entryid);
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateway_wipe_post"))
  {
    framework_call ( "gateway_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk configurations
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateway_wipe_finish"))
  {
    framework_call ( "gateway_wipe_finish", $parameters);
  }
}
?>
