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
 * VoIP Domain extensions actions module. This module add the Asterisk client
 * actions calls related to extensions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "createextension", "extensions_create");
framework_add_hook ( "changeextension", "extensions_change");
framework_add_hook ( "removeextension", "extensions_remove");
framework_add_hook ( "createvoicemail", "voicemail_create");
framework_add_hook ( "changevoicemail", "voicemail_change");
framework_add_hook ( "removevoicemail", "voicemail_remove");
framework_add_hook ( "createap", "ap_create");
framework_add_hook ( "changeap", "ap_change");
framework_add_hook ( "removeap", "ap_remove");
framework_add_hook ( "createhint", "hint_create");
framework_add_hook ( "removehint", "hint_remove");
framework_add_hook ( "changetranshipment", "transhipment_change");

/**
 * Function to create a new extension.
 * Required parameters are: Extension, UID, Username, Password, Group, Capture[], Name, Template, Transhipments[], CostCenter, PhonePass, Permissions[]
 * Possible results:
 *   - 200: OK, extension created
 *   - 400: Extension already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "UID", $parameters) || ! array_key_exists ( "Username", $parameters) || ! array_key_exists ( "Password", $parameters) || ! array_key_exists ( "Group", $parameters) || ! array_key_exists ( "Capture", $parameters) || ! is_array ( $parameters["Capture"]) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "Template", $parameters) || ! array_key_exists ( "Transhipments", $parameters) || ! is_array ( $parameters["Transhipments"]) || ! array_key_exists ( "CostCenter", $parameters) || ! array_key_exists ( "PhonePass", $parameters) || ! array_key_exists ( "Permissions", $parameters) || ! is_array ( $parameters["Permissions"]))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Filter username variable
  $parameters["Filename"] = preg_replace ( "/[^0-9]/", "", $parameters["Username"]);

  // Verify if extension exist
  if ( file_exists ( $_in["general"]["confdir"] . "/sip-user-" . $parameters["Filename"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Extension already exist.");
  }

  // Create file structure
  $extension = "[" . $parameters["Username"] . "](g_" . $parameters["Group"] . ( $parameters["Template"] ? ",t_" . $parameters["Template"] : "") . ")\n" .
               "secret=" . $parameters["Password"] . "\n" .
               "callerid=\"" . $parameters["Name"] . "\" <" . $parameters["Extension"] . ">\n" .
               "description=" . $parameters["Name"] . "\n" .
               "call-limit=5\n" .
               "namedpickupgroup=" . implode ( ",", $parameters["Capture"]) . "\n";
  if ( $parameters["Permissions"]["voicemail"] == true)
  {
    $extension .= "mailbox=" . $parameters["Extension"] . "\n";
  }
  $extension .= "setvar=p_uid=" . $parameters["UID"] . "\n" .
                "setvar=p_password=" . $parameters["PhonePass"] . "\n" .
                "setvar=p_mobile=" . ( $parameters["Permissions"]["mobile"] == true ? "yes" : "no") . "\n" .
                "setvar=p_longdistance=" . ( $parameters["Permissions"]["longdistance"] == true ? "yes" : "no") . "\n" .
                "setvar=p_international=" . ( $parameters["Permissions"]["international"] == true ? "yes" : "no") . "\n" .
                "setvar=voltx=" . (int) $parameters["Permissions"]["voltx"] . "\n" .
                "setvar=volrx=" . (int) $parameters["Permissions"]["volrx"] . "\n" .
                "setvar=monitor=" . ( $parameters["Permissions"]["monitor"] == true ? "yes" : "no") . "\n";
  if ( array_key_exists ( "nopass", $parameters["Permissions"]))
  {
    $extension .= "setvar=p_nopass=" . ( $parameters["Permissions"]["nopass"] == true ? "yes" : "no") . "\n";
  }
  if ( sizeof ( $parameters["Transhipments"]) != 0)
  {
    $extension .= "setvar=transhipment=" . implode ( ",", $parameters["Transhipments"]) . "\n";
  }
  if ( $parameters["CostCenter"])
  {
    $extension .= "accountcode=" . $parameters["CostCenter"] . "\n";
  }

  // Write extension file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/sip-user-" . $parameters["Filename"] . ".conf", $extension))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Extension created.");
}

/**
 * Function to create an extension voicemail.
 * Required parameters are: Extension, Name, Password, Email
 * Possible results:
 *   - 200: OK, voicemail created
 *   - 400: Voicemail already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function voicemail_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "Password", $parameters) || ! array_key_exists ( "Email", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if voicemail exist
  if ( file_exists ( $_in["general"]["confdir"] . "/voicemail-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Voicemail already exist.");
  }

  // Create file structure
  $voicemail = $parameters["Extension"] . " = " . $parameters["Password"] . "," . $parameters["Name"] . "," . $parameters["Email"] . ",,delete=yes\n";

  // Write voicemail file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/voicemail-" . (int) $parameters["Extension"] . ".conf", $voicemail))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk voicemail configuration
  asterisk_exec ( "voicemail reload");

  // Finish event
  return array ( "code" => 200, "message" => "Voicemail created.");
}

/**
 * Function to create an extension auto provision file.
 * Required parameters are: Extension, Username, Password, Name, MAC, Template, Domain
 * Possible results:
 *   - 200: OK, ap created
 *   - 400: Invalid parameters
 *   - 500: Template file not found
 *   - 501: Error writing file
 * Note:
 *   - The auto provision template files must be located at /var/lib/tftpboot/template-{Template}.cfg
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "Username", $parameters) || ! array_key_exists ( "Password", $parameters) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "MAC", $parameters) || ! preg_match ( "/^[0-9a-f]{12}$/i", $parameters["MAC"]) || ! array_key_exists ( "Template", $parameters) || ! array_key_exists ( "Domain", $parameters))
  {
    return array ( "code" => 400, "message" => "Invalid parameters.");
  }

  // Verify if template exist
  if ( ! file_exists ( "/var/lib/tftpboot/template-" . $parameters["Template"] . ".cfg"))
  {
    return array ( "code" => 500, "message" => "Template file not found.");
  }

  // Read template file
  $ap = file_get_contents ( "/var/lib/tftpboot/template-" . $parameters["Template"] . ".cfg");

  // Change template values
  $ap = str_replace ( "{{{USER}}}", $parameters["Username"], $ap);
  $ap = str_replace ( "{{{PASS}}}", $parameters["Password"], $ap);
  $ap = str_replace ( "{{{NAME}}}", $parameters["Name"], $ap);
  $ap = str_replace ( "{{{DOMAIN}}}", $parameters["Domain"], $ap);
  $ap = str_replace ( "{{{NUMBER}}}", $parameters["Extension"], $ap);
  $ap = str_replace ( "{{{MAC}}}", $parameters["MAC"], $ap);

  // Write auto provision file
  switch ( $parameters["Template"])
  {
    case "spip301":
    case "spip320":
    case "spip321":
    case "spip330":
    case "spip331":
    case "spip430":
      $filename = strtolower ( $parameters["MAC"]) . "-phone.cfg";
      break;
    case "gxp1625":
      $filename = "cfg" . strtolower ( $parameters["MAC"]) . ".xml";
      break;
    default:
      $filename = strtolower ( $parameters["MAC"]) . ".cfg";
      break;
  }
  if ( ! file_put_contents ( "/var/lib/tftpboot/" . $filename, $ap))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Auto provision created.");
}

/**
 * Function to create an extension hint.
 * Required parameters are: Extension
 * Possible results:
 *   - 200: OK, hint created
 *   - 400: Hint already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function hint_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if hint exist
  if ( file_exists ( $_in["general"]["confdir"] . "/hint-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Hint already exist.");
  }

  // Create file structure
  $hint = "exten => " . (int) $parameters["Extension"] . ",hint,SIP/u" . (int) $parameters["Extension"] . "-0&SIP/u" . (int) $parameters["Extension"] . "-1&SIP/u" . (int) $parameters["Extension"] . "-2&SIP/u" . (int) $parameters["Extension"] . "-3&SIP/u" . (int) $parameters["Extension"] . "-4&SIP/u" . (int) $parameters["Extension"] . "-5&SIP/u" . (int) $parameters["Extension"] . "-6&SIP/u" . (int) $parameters["Extension"] . "-7&SIP/u" . (int) $parameters["Extension"] . "-8&SIP/u" . (int) $parameters["Extension"] . "-9\n";

  // Write hint file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/hint-" . (int) $parameters["Extension"] . ".conf", $hint))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan configuration
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Hint created.");
}

/**
 * Function to change an existing extension.
 * Required parameters are: Extension, UID, Username, NewUsername, Password, Group, Capture[], Name, Template, Transhipments[], CostCenter, PhonePass, Permissions[]
 * Possible results:
 *   - 200: OK, extension changed
 *   - 400: Extension doesn't exist
 *   - 401: Invalid parameters
 *   - 402: Extension new number already exist
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "UID", $parameters) || ! array_key_exists ( "Username", $parameters) || ! array_key_exists ( "NewUsername", $parameters) || ! array_key_exists ( "Password", $parameters) || ! array_key_exists ( "Group", $parameters) || ! array_key_exists ( "Capture", $parameters) || ! is_array ( $parameters["Capture"]) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "Template", $parameters) || ! array_key_exists ( "Transhipments", $parameters) || ! is_array ( $parameters["Transhipments"]) || ! array_key_exists ( "CostCenter", $parameters) || ! array_key_exists ( "PhonePass", $parameters) || ! array_key_exists ( "Permissions", $parameters) || ! is_array ( $parameters["Permissions"]))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Filter username variable
  $parameters["Filename"] = preg_replace ( "/[^0-9]/", "", $parameters["Username"]);
  $parameters["NewFilename"] = preg_replace ( "/[^0-9]/", "", $parameters["NewUsername"]);

  // Verify if extension exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/sip-user-" . $parameters["Filename"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Extension doesn't exist.");
  }

  // Verify if new extension exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/sip-user-" . $parameters["NewFilename"] . ".conf"))
  {
    return array ( "code" => 402, "message" => "Extension new number already exist.");
  }

  // Remove extension file
  if ( ! unlink ( $_in["general"]["confdir"] . "/sip-user-" . $parameters["Filename"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $extension = "[" . $parameters["NewUsername"] . "](g_" . $parameters["Group"] . ( $parameters["Template"] ? ",t_" . $parameters["Template"] : "") . ")\n" .
               "secret=" . $parameters["Password"] . "\n" .
               "callerid=\"" . $parameters["Name"] . "\" <" . $parameters["Extension"] . ">\n" .
               "description=" . $parameters["Name"] . "\n" .
               "call-limit=5\n" .
               "namedpickupgroup=" . implode ( ",", $parameters["Capture"]) . "\n";
  if ( $parameters["Permissions"]["voicemail"] == true)
  {
    $extension .= "mailbox=" . $parameters["Extension"] . "\n";
  }
  $extension .= "setvar=p_uid=" . $parameters["UID"] . "\n" .
                "setvar=p_password=" . $parameters["PhonePass"] . "\n" .
                "setvar=p_mobile=" . ( $parameters["Permissions"]["mobile"] == true ? "yes" : "no") . "\n" .
                "setvar=p_longdistance=" . ( $parameters["Permissions"]["longdistance"] == true ? "yes" : "no") . "\n" .
                "setvar=p_international=" . ( $parameters["Permissions"]["international"] == true ? "yes" : "no") . "\n" .
                "setvar=voltx=" . (int) $parameters["Permissions"]["voltx"] . "\n" .
                "setvar=volrx=" . (int) $parameters["Permissions"]["volrx"] . "\n" .
                "setvar=monitor=" . ( $parameters["Permissions"]["monitor"] == true ? "yes" : "no") . "\n";
  if ( array_key_exists ( "nopass", $parameters["Permissions"]))
  {
    $extension .= "setvar=p_nopass=" . ( $parameters["Permissions"]["nopass"] == true ? "yes" : "no") . "\n";
  }
  if ( sizeof ( $parameters["Transhipments"]) != 0)
  {
    $extension .= "setvar=transhipment=" . implode ( ",", $parameters["Transhipments"]) . "\n";
  }
  if ( $parameters["CostCenter"])
  {
    $extension .= "accountcode=" . $parameters["CostCenter"] . "\n";
  }

  // Write extension file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/sip-user-" . $parameters["NewFilename"] . ".conf", $extension))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Extension changed.");
}

/**
 * Function to change an existing voicemail.
 * Required parameters are: Extension, NewExtension, Name, Password, Email
 * Possible results:
 *   - 200: OK, voicemail changed
 *   - 400: Extension doesn't exist
 *   - 401: Invalid parameters
 *   - 402: Extension new number already exist
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function voicemail_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "NewExtension", $parameters) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "Password", $parameters) || ! array_key_exists ( "Email", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if voicemail exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/voicemail-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Voicemail doesn't exist.");
  }

  // Verify if new voicemail exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/voicemail-" . (int) $parameters["NewExtension"] . ".conf"))
  {
    return array ( "code" => 402, "message" => "Voicemail new number already exist.");
  }

  // Remove voicemail file
  if ( ! unlink ( $_in["general"]["confdir"] . "/voicemail-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $voicemail = $parameters["NewExtension"] . " = " . $parameters["Password"] . "," . $parameters["Name"] . "," . $parameters["Email"] . ",,delete=yes\n";

  // Write voicemail file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/voicemail-" . (int) $parameters["NewExtension"] . ".conf", $voicemail))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Reload Asterisk voicemail configuration
  asterisk_exec ( "voicemail reload");

  // Finish event
  return array ( "code" => 200, "message" => "Voicemail changed.");
}

/**
 * Function to change an existing auto provision file.
 * Required parameters are: Extension, Username, Password, Name, MAC, NewMAC, Template, Domain
 * Possible results:
 *   - 200: OK, AP changed
 *   - 400: Template file not found
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "Username", $parameters) || ! array_key_exists ( "Password", $parameters) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "MAC", $parameters) || ! preg_match ( "/^[0-9a-f]{12}$/i", $parameters["MAC"]) || ! array_key_exists ( "NewMAC", $parameters) || ! preg_match ( "/^[0-9a-f]{12}$/i", $parameters["NewMAC"]) || ! array_key_exists ( "Template", $parameters) || ! array_key_exists ( "Domain", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if template exist
  if ( ! file_exists ( "/var/lib/tftpboot/template-" . $parameters["Template"] . ".cfg"))
  {
    return array ( "code" => 400, "message" => "Template file not found.");
  }

  // Remove old AP file
  switch ( $parameters["Template"])
  {
    case "spip301":
    case "spip320":
    case "spip321":
    case "spip330":
    case "spip331":
    case "spip430":
      $filename = strtolower ( $parameters["MAC"]) . "-phone.cfg";
      break;
    case "gxp1625":
      $filename = "cfg" . strtolower ( $parameters["MAC"]) . ".xml";
      break;
    default:
      $filename = strtolower ( $parameters["MAC"]) . ".cfg";
      break;
  }
  if ( ! unlink ( "/var/lib/tftpboot/" . $filename))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Read template file
  $ap = file_get_contents ( "/var/lib/tftpboot/template-" . $parameters["Template"] . ".cfg");

  // Change template values
  $ap = str_replace ( "{{{USER}}}", $parameters["Username"], $ap);
  $ap = str_replace ( "{{{PASS}}}", $parameters["Password"], $ap);
  $ap = str_replace ( "{{{NAME}}}", $parameters["Name"], $ap);
  $ap = str_replace ( "{{{DOMAIN}}}", $parameters["Domain"], $ap);
  $ap = str_replace ( "{{{NUMBER}}}", $parameters["Extension"], $ap);
  $ap = str_replace ( "{{{MAC}}}", $parameters["NewMAC"], $ap);

  // Write auto provision file
  switch ( $parameters["Template"])
  {
    case "spip301":
    case "spip320":
    case "spip321":
    case "spip330":
    case "spip331":
    case "spip430":
      $filename = strtolower ( $parameters["NewMAC"]) . "-phone.cfg";
      break;
    case "gxp1625":
      $filename = "cfg" . strtolower ( $parameters["NewMAC"]) . ".xml";
      break;
    default:
      $filename = strtolower ( $parameters["NewMAC"]) . ".cfg";
      break;
  }
  if ( ! file_put_contents ( "/var/lib/tftpboot/" . $filename, $ap))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Auto provision changed.");
}

/**
 * Function to remove an existing extension.
 * Required parameters are: Username
 * Possible results:
 *   - 200: OK, extension removed
 *   - 400: Extension doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Username", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Filter username variable
  $parameters["Filename"] = preg_replace ( "/[^0-9]/", "", $parameters["Username"]);

  // Verify if extension exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/sip-user-" . $parameters["Filename"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Extension doesn't exist.");
  }

  // Remove extension file
  if ( ! unlink ( $_in["general"]["confdir"] . "/sip-user-" . $parameters["Filename"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Extension removed.");
}

/**
 * Function to remove an existing voicemail.
 * Required parameters are: Extension
 * Possible results:
 *   - 200: OK, voicemail removed
 *   - 400: Voicemail doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function voicemail_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if voicemail exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/voicemail-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Voicemail doesn't exist.");
  }

  // Remove voicemail file
  if ( ! unlink ( $_in["general"]["confdir"] . "/voicemail-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Voicemail removed.");
}

/**
 * Function to remove an existing auto provisioning file.
 * Required parameters are: MAC
 * Possible results:
 *   - 200: OK, AP file removed
 *   - 400: AP doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "MAC", $parameters) || ! preg_match ( "/^[0-9a-f]{12}$/i", $parameters["MAC"]))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check for AP file
  $filename = "";
  if ( file_exists ( "/var/lib/tftpboot/" . strtolower ( $parameters["MAC"]) . ".cfg"))
  {
    $filename = strtolower ( $parameters["MAC"]) . ".cfg";
  }
  if ( file_exists ( "/var/lib/tftpboot/" . strtolower ( $parameters["MAC"]) . "-phone.cfg"))
  {
    $filename = strtolower ( $parameters["MAC"]) . "-phone.cfg";
  }
  if ( file_exists ( "/var/lib/tftpboot/cfg" . strtolower ( $parameters["MAC"]) . ".xml"))
  {
    $filename = "cfg" . strtolower ( $parameters["MAC"]) . ".xml";
  }
  if ( $filename == "" || ! file_exists ( "/var/lib/tftpboot/" . $filename))
  {
    return array ( "code" => 400, "message" => "Auto provision file not found.");
  }

  // Remove auto provisioning file
  if ( ! unlink ( "/var/lib/tftpboot/" . $filename))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Auto provision removed.");
}

/**
 * Function to remove an existing hint file.
 * Required parameters are: Extension
 * Possible results:
 *   - 200: OK, hint file removed
 *   - 400: Hint doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function hint_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if hint exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/hint-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Hint doesn't exist.");
  }

  // Remove hint file
  if ( ! unlink ( $_in["general"]["confdir"] . "/hint-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan configuration
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Hint removed.");
}

/**
 * Function to change an extension transhipments.
 * Required parameters are: Extension, Transhipments[]
 * Possible results:
 *   - 200: OK, extension file changed
 *   - 400: Extension doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function transhipment_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "Transhipments", $parameters) || ! is_array ( $parameters["Transhipments"]))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Change each extension account:
  $exist = false;
  for ( $account = 0; $account < 10; $account++)
  {
    if ( file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . $account . ".conf"))
    {
      $exist = true;
      $content = file_get_contents ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . $account . ".conf");
      if ( preg_match ( "/^setvar=transhipment=([\d|,])+$/m", $content))
      {
        $content = preg_replace ( "/(.*setvar=transhipment=)[\d|,]+(.*)/m", "\${1}" . implode ( ",", $parameters["Transhipments"]) . "\${2}", $content);
      } else {
        $content .= "setvar=transhipment=" . implode ( ",", $parameters["Transhipments"]) . "\n";
      }
      if ( ! file_put_contents ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . $account . ".conf", $content))
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
    }
  }
  if ( ! $exist)
  {
    return array ( "code" => 400, "message" => "Extension doesn't exist.");
  }

  // Reload Asterisk SIP configuration
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Extension changed.");
}
?>
