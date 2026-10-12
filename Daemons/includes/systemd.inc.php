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
 * VoIP Domain systemd integration helpers (daemon side).
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Register SIGHUP handler so systemd reload exits and Restart=always applies.
 *
 * @return void
 */
function daemon_register_systemd_reload_handler ()
{
  if ( ! function_exists ( "pcntl_signal"))
  {
    return;
  }
  if ( function_exists ( "pcntl_async_signals"))
  {
    pcntl_async_signals ( true);
  }
  pcntl_signal ( SIGHUP, "daemon_systemd_reload_signal_handler");
}

/**
 * Signal handler for systemd reload requests.
 *
 * @param int $signo Signal number
 * @return void
 */
function daemon_systemd_reload_signal_handler ( $signo)
{
  writeLog ( "Systemd reload requested (signal " . $signo . "). Exiting for service restart.", VoIP_LOG_NOTICE);
  exit ( 0);
}

/**
 * Request reload trigger files for systemd path units.
 *
 * @param array $units Unit suffixes (client, fastagi, monitor, router)
 * @return array Reloaded unit names
 */
function daemon_trigger_systemd_reload ( $units)
{
  $reloaded = array ();
  foreach ( $units as $unit)
  {
    $unit = basename ( $unit);
    if ( ! preg_match ( "/^(client|fastagi|monitor|router)$/", $unit))
    {
      continue;
    }
    if ( ! is_dir ( "/run/voipdomain/reload"))
    {
      @mkdir ( "/run/voipdomain/reload", 0777, true);
    }
    if ( @touch ( "/run/voipdomain/reload/" . $unit))
    {
      $reloaded[] = $unit;
    }
  }
  return $reloaded;
}
?>
