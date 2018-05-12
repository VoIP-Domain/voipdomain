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
 * FastAGI agents module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk FastAGI Agents Application
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * FastAGI agents application
 */
register_app ( "agents_password", "agents_password", array ( "title" => "Agents Password Checker", "fork" => false));
framework_add_hook ( "agents_password", "agents_password");

/**
 * Function to check for an agent password.
 *
 * Expected parameters:
 * Arg 1: Agent number
 * Arg 2: Agent password
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_password ( $buffer, $parameters)
{
  // Filter basic parameters:
  $agent = preg_replace ( "/[^0-9]/", "", $parameters["arg_1"]);
  $password = preg_replace ( "/[^0-9]/", "", $parameters["arg_2"]);

  // Check if agent exist:
  if ( ! check_config ( "datafile", "agent-" . $agent))
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "SET VARIABLE \"AGENTCHECK\" \"0\""));
  }

  // Read agent data:
  $data = read_config ( "datafile", "agent-" . $agent);

  // Check agent password:
  if ( $data["Password"] != $password)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "SET VARIABLE \"AGENTCHECK\" \"0\""));
  } else {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "SET VARIABLE \"AGENTCHECK\" \"1\""));
  }
}
?>
