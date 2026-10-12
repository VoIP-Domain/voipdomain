<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain CEL (Channel Event Logging) bootstrap configuration module.
 *
 * Registers hooks that generate the Asterisk CEL configuration:
 *   - cel.conf [general]: enable CEL and select tracked events/apps
 *   - cel.conf [manager]: enable the AMI CEL backend
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CEL
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Register CEL bootstrap hooks
 */
framework_add_hook ( "asterisk_cel_cel_general", "cel_hook_general");
framework_add_hook ( "asterisk_cel_cel_manager", "cel_hook_manager");

/**
 * CEL engine general configuration.
 *
 * Tracks all channel events including DTMF for detailed call flow
 * reconstruction. Applications tracked: Dial, Park, Queue, ConfBridge,
 * VoiceMail.
 */
function cel_hook_general ( $buffer, $parameters)
{
  $buffer .= "[general]\n";
  $buffer .= "enable=yes\n";
  $buffer .= "apps=Dial,Park,Queue,ConfBridge,VoiceMail\n";
  $buffer .= "events=ALL\n";
  $buffer .= "\n";

  return $buffer;
}

/**
 * CEL Manager backend — publishes CEL events over AMI so the monitor
 * daemon can capture detailed channel lifecycle events in real time.
 */
function cel_hook_manager ( $buffer, $parameters)
{
  $buffer .= "[manager]\n";
  $buffer .= "enabled=yes\n";
  $buffer .= "\n";

  return $buffer;
}
?>
