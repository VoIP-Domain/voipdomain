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
 * VoIP Domain core module version registry. Defines the current version of each
 * core module for the install/upgrade system to track.
 *
 * When bumping a core module version (e.g., adding a new column), update the
 * constant here and create the corresponding upgrade_db hook in that module's
 * install.php or a dedicated update.php file.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

define ( "MODULE_CORE_VERSION", "1.0.0");
define ( "MODULE_AGENTS_VERSION", "1.0.0");
define ( "MODULE_AUDIOS_VERSION", "1.0.0");
define ( "MODULE_AUTHENTICATION_VERSION", "1.0.0");
define ( "MODULE_BLOCKS_VERSION", "1.0.0");
define ( "MODULE_CONFERENCES_VERSION", "1.0.0");
define ( "MODULE_CONFIG_VERSION", "1.0.0");
define ( "MODULE_COSTCENTERS_VERSION", "1.0.0");
define ( "MODULE_COUNTRYDB_VERSION", "1.0.0");
define ( "MODULE_CURRENCIES_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_AUDIOCODES_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_CISCO_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_FANVIL_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_GRANDSTREAM_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_HTEK_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_KHOMP_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_POLYCOM_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_SNOM_VERSION", "1.0.0");
define ( "MODULE_EQUIPMENTS_YEALINK_VERSION", "1.0.0");
define ( "MODULE_EXCEPTIONS_VERSION", "1.0.0");
define ( "MODULE_EXTENSIONS_VERSION", "1.0.0");
define ( "MODULE_EXTENSIONS_CONFERENCES_VERSION", "1.0.0");
define ( "MODULE_EXTENSIONS_HUNTS_VERSION", "1.0.0");
define ( "MODULE_EXTENSIONS_IVR_VERSION", "1.0.0");
define ( "MODULE_EXTENSIONS_PHONES_VERSION", "1.0.0");
define ( "MODULE_EXTENSIONS_QUEUES_VERSION", "1.0.0");
define ( "MODULE_GATEWAYS_VERSION", "1.0.0");
define ( "MODULE_GROUPS_VERSION", "1.0.0");
define ( "MODULE_IVR_VERSION", "1.0.0");
define ( "MODULE_LOCALEDB_VERSION", "1.0.0");
define ( "MODULE_NOTIFICATIONS_VERSION", "1.0.0");
define ( "MODULE_PROFILES_VERSION", "1.0.0");
define ( "MODULE_QUEUES_VERSION", "1.0.0");
define ( "MODULE_RANGES_VERSION", "1.0.0");
define ( "MODULE_SERVERS_VERSION", "1.0.0");
define ( "MODULE_TENANTS_VERSION", "1.0.0");
define ( "MODULE_TOKENS_VERSION", "1.0.0");
define ( "MODULE_USERS_VERSION", "1.0.0");

/**
 * Return an array of all core module names and their current versions.
 *
 * @return array Associative array of module_name => version
 */
function voipdomain_get_core_module_versions ()
{
  $versions = array ();
  $constants = get_defined_constants ( true);
  if ( array_key_exists ( "user", $constants))
  {
    foreach ( $constants["user"] as $name => $value)
    {
      if ( preg_match ( '/^MODULE_(.+)_VERSION$/', $name, $matches))
      {
        $versions[strtolower ( $matches[1])] = $value;
      }
    }
  }
  return $versions;
}
?>
