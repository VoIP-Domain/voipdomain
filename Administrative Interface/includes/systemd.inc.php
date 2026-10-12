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
 * VoIP Domain systemd integration helpers (Administrative Interface).
 *
 * Daemon reload is handled by systemd units:
 *   - voipdomain-*.service defines ExecReload (SIGHUP)
 *   - voipdomain-daemon-reload@.path watches /run/voipdomain/reload/%i
 *   - voipdomain-daemon-reload@.service runs systemctl reload
 *
 * Application code only requests reload by touching trigger files or by
 * sending the daemon_reload Gearman event to remote servers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Map a daemon module directory type to a systemd unit suffix.
 *
 * @param string $moduletype Module directory type
 * @return string|false
 */
function systemd_daemon_unit_from_module_type ( $moduletype)
{
  switch ( $moduletype)
  {
    case "client-modules":
      return "client";
    case "fastagi-modules":
      return "fastagi";
    case "monitor-modules":
      return "monitor";
    case "router-modules":
      return "router";
    default:
      return false;
  }
}

/**
 * Normalize reload unit names.
 *
 * @param array $units Unit suffixes or module directory types
 * @return array
 */
function systemd_normalize_daemon_units ( $units)
{
  $normalized = array ();
  foreach ( $units as $unit)
  {
    if ( strpos ( $unit, "-modules") !== false)
    {
      $unit = systemd_daemon_unit_from_module_type ( $unit);
    }
    if ( $unit != false && preg_match ( "/^(client|fastagi|monitor|router)$/", $unit))
    {
      $normalized[] = $unit;
    }
  }
  return array_values ( array_unique ( $normalized));
}

/**
 * Request reload trigger files for systemd path units.
 *
 * @param array $units Unit suffixes (client, fastagi, monitor, router)
 * @return array Reloaded unit names
 */
function systemd_trigger_daemon_reload ( $units)
{
  $reloaded = array ();
  foreach ( systemd_normalize_daemon_units ( $units) as $unit)
  {
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

/**
 * Request daemon reload locally or on remote servers.
 *
 * @param array $units Unit suffixes or module directory types
 * @param int $serverid[optional] Server ID (0 = all servers). Null = local only.
 * @return array|false Local reload list or notify_server result
 */
function systemd_request_daemon_reload ( $units, $serverid = null)
{
  $units = systemd_normalize_daemon_units ( $units);
  if ( sizeof ( $units) == 0)
  {
    return array ();
  }
  if ( $serverid === null)
  {
    return systemd_trigger_daemon_reload ( $units);
  }
  return notify_server ( $serverid, "daemon_reload", array ( "Units" => $units));
}

/**
 * Extract systemd units from deployed daemon module paths.
 *
 * @param array $deployed Deployed entries like client-modules/plugin
 * @return array
 */
function systemd_units_from_deployed_modules ( $deployed)
{
  $units = array ();
  foreach ( $deployed as $entry)
  {
    if ( strpos ( $entry, "/") === false)
    {
      continue;
    }
    $moduletype = substr ( $entry, 0, strpos ( $entry, "/"));
    if ( $unit = systemd_daemon_unit_from_module_type ( $moduletype))
    {
      $units[] = $unit;
    }
  }
  return systemd_normalize_daemon_units ( $units);
}
?>
