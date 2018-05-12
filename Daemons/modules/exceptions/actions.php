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
 * VoIP Domain exceptions actions module. This module add the Asterisk client actions
 * calls related to exceptions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Exceptions
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "createexception", "exceptions_create");
framework_add_hook ( "changeexception", "exceptions_change");
framework_add_hook ( "removeexception", "exceptions_remove");

/**
 * Function to create a new exception (global number black list).
 * Required parameters are: Number, Description
 * Possible results:
 *   - 200: OK, exception created
 *   - 400: Exception already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Number", $parameters) || ! array_key_exists ( "Description", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if exception exist
  if ( file_exists ( $_in["general"]["confdir"] . "/exception-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Exception already exist.");
  }

  // Create file structure
  $exception = "exten => +" . (int) $parameters["Number"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Exception: " . str_replace ( "(", "\\(", str_replace ( "\n", "", str_replace ( "\r", "", $parameters["Description"]))) . "))\n" .
               " same => n,Set(CDR(userfield)=nopass)\n" .
               " same => n,Macro(" . $_in["macros"]["lcr"] . ",\${EXTEN})\n";

  // Write exception file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/exception-" . (int) $parameters["Number"] . ".conf", $exception))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Exception created.");
}

/**
 * Function to change an existing exception (global number black list).
 * Required parameters are: Number, Description, NewNumber
 * Possible results:
 *   - 200: OK, exception changed
 *   - 400: Exception doesn't exist
 *   - 401: Invalid parameters
 *   - 402: Exception new number already exist
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Number", $parameters) || ! array_key_exists ( "NewNumber", $parameters) || ! array_key_exists ( "Description", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if exception exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/exception-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Exception doesn't exist.");
  }

  // Verify if new exception exist
  if ( (int) $parameters["Number"] != (int) $parameters["NewNumber"] && file_exists ( $_in["general"]["confdir"] . "/exception-" . (int) $parameters["NewNumber"] . ".conf"))
  {
    return array ( "code" => 402, "message" => "Exception new number already exist.");
  }

  // Remove exception file
  if ( ! unlink ( $_in["general"]["confdir"] . "/exception-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $exception = "exten => +" . (int) $parameters["NewNumber"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Exception: " . str_replace ( "(", "\\(", str_replace ( "\n", "", str_replace ( "\r", "", $parameters["Description"]))) . "))\n" .
               " same => n,Set(CDR(userfield)=nopass)\n" .
               " same => n,Macro(" . $_in["macros"]["lcr"] . ",\${EXTEN})\n";

  // Write exception file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/exception-" . (int) $parameters["NewNumber"] . ".conf", $exception))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Exception changed.");
}

/**
 * Function to remove an existing exception (global number black list).
 * Required parameters are: Number
 * Possible results:
 *   - 200: OK, exception removed
 *   - 400: Exception doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Number", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if exception exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/exception-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Exception doesn't exist.");
  }

  // Remove exception file
  if ( ! unlink ( $_in["general"]["confdir"] . "/exception-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Exception removed.");
}
?>
