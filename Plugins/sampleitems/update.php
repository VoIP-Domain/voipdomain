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
 * VoIP Domain sample items module upgrade script. Demonstrates the version-aware
 * schema migration pattern for plugins.
 *
 * Version checks are cumulative: upgrading from 1.0.0 to 1.2.0 applies both
 * the 1.1.0 and 1.2.0 migration blocks in order.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Sample Items
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to upgrade sample items database
 */
framework_add_hook ( "upgrade_db", "sampleitems_upgrade_db");

/**
 * Function to upgrade sample items database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Version context:
 *                          - version_from: currently installed version
 *                          - version_to: target version
 *                          - type: "upgrade"
 * @return string Output of the generated page
 */
function sampleitems_upgrade_db ( $buffer, $parameters)
{
  /**
   * Upgrade to 1.1.0: add Priority column
   */
  if ( version_compare ( $parameters["version_from"], "1.1.0", "<"))
  {
    install_add_db_alter ( "SampleItems_add_priority",
      "ALTER TABLE `SampleItems` ADD COLUMN `Priority` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `Description`");
  }

  /**
   * Upgrade to 1.2.0: add index on Priority column
   */
  if ( version_compare ( $parameters["version_from"], "1.2.0", "<"))
  {
    install_add_db_alter ( "SampleItems_idx_priority",
      "ALTER TABLE `SampleItems` ADD INDEX `idx_priority` (`Priority`)",
      array ( "SampleItems_add_priority"));
  }

  return $buffer;
}
?>
