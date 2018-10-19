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
 * VoIP Domain blocks actions module. This module add the Asterisk client actions
 * calls related to blocks.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Blocks
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "createblock", "blocks_create");
framework_add_hook ( "changeblock", "blocks_change");
framework_add_hook ( "removeblock", "blocks_remove");

/**
 * Function to create a new block (global number black list).
 * Required parameters are: Number, Description
 * Possible results:
 *   - 200: OK, block created
 *   - 400: Block already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Number", $parameters) || ! array_key_exists ( "Description", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if block exist
  if ( file_exists ( $_in["general"]["confdir"] . "/block-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Block already exist.");
  }

  // Create file structure
  $block = "exten => _X./+" . (int) $parameters["Number"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Blocked: " . str_replace ( "(", "\\(", str_replace ( "\n", "", str_replace ( "\r", "", $parameters["Description"]))) . "))\n" .
               " same => n,Macro(Blocked,\${EXTEN})\n";

  // Write block file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/block-" . (int) $parameters["Number"] . ".conf", $block))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Block created.");
}

/**
 * Function to change an existing block (global number black list).
 * Required parameters are: Number, Description, NewNumber
 * Possible results:
 *   - 200: OK, block changed
 *   - 400: Block doesn't exist
 *   - 401: Invalid parameters
 *   - 402: Block new number already exist
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Number", $parameters) || ! array_key_exists ( "NewNumber", $parameters) || ! array_key_exists ( "Description", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if block exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/block-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Block doesn't exist.");
  }

  // Verify if new block exist
  if ( (int) $parameters["Number"] != (int) $parameters["NewNumber"] && file_exists ( $_in["general"]["confdir"] . "/block-" . (int) $parameters["NewNumber"] . ".conf"))
  {
    return array ( "code" => 402, "message" => "Block new number already exist.");
  }

  // Remove block file
  if ( ! unlink ( $_in["general"]["confdir"] . "/block-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $block = "exten => _X./+" . (int) $parameters["NewNumber"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Blocked: " . str_replace ( "(", "\\(", str_replace ( "\n", "", str_replace ( "\r", "", $parameters["Description"]))) . "))\n" .
               " same => n,Macro(Blocked,\${EXTEN})\n";

  // Write block file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/block-" . (int) $parameters["NewNumber"] . ".conf", $block))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Block changed.");
}

/**
 * Function to remove an existing block (global number black list).
 * Required parameters are: Number
 * Possible results:
 *   - 200: OK, block removed
 *   - 400: Block doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Number", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if block exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/block-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Block doesn't exist.");
  }

  // Remove block file
  if ( ! unlink ( $_in["general"]["confdir"] . "/block-" . (int) $parameters["Number"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Block removed.");
}
?>
