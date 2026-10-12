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
 * VoIP Domain systemd reload client handlers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Systemd
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

framework_add_hook ( "daemon_reload", "daemon_reload");

/**
 * Request daemon reload through systemd path units.
 *
 * @param string $buffer Buffer from plugin system if processed by other function before
 * @param array $parameters Optional parameters to the function
 * @return array
 */
function daemon_reload ( $buffer, $parameters)
{
  if ( ! array_key_exists ( "Units", $parameters) || ! is_array ( $parameters["Units"]) || sizeof ( $parameters["Units"]) == 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => array ( "Units" => "Missing parameter"));
  }

  $reloaded = daemon_trigger_systemd_reload ( $parameters["Units"]);
  if ( sizeof ( $reloaded) == 0)
  {
    return array ( "code" => 500, "message" => "Unable to request systemd reload.");
  }

  return array ( "code" => 200, "message" => "Systemd reload requested.", "content" => array ( "Units" => $reloaded));
}
?>
