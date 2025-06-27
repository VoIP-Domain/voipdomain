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
 * VoIP Domain extensions queues module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Extensions Queues database
 */
framework_add_hook ( "install_db", "extensions_queues_install_db");

/**
 * Function to create extensions queues database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_queues_install_db ( $buffer, $parameters)
{
  /**
   * Add basic system tables
   */
  install_add_db_table ( "ExtensionQueue", "CREATE TABLE `ExtensionQueue` (\n" .
                                           "  `Extension` bigint unsigned NOT NULL,\n" .
                                           "  `Queue` bigint unsigned NOT NULL,\n" .
                                           "  UNIQUE KEY `Extension` (`Extension`),\n" .
                                           "  KEY `Queue` (`Queue`),\n" .
                                           "  CONSTRAINT `ExtensionQueue_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE,\n" .
                                           "  CONSTRAINT `ExtensionQueue_ibfk_2` FOREIGN KEY (`Queue`) REFERENCES `Queues` (`ID`) ON UPDATE CASCADE\n" .
                                           ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Extension queue link';\n", array ( "Extensions", "Queues"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "ExtensionQueueInsert", "CREATE TRIGGER `ExtensionQueueInsert` AFTER INSERT ON `ExtensionQueue` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionQueueUpdate", "CREATE TRIGGER `ExtensionQueueUpdate` AFTER UPDATE ON `ExtensionQueue` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionQueueDelete", "CREATE TRIGGER `ExtensionQueueDelete` AFTER DELETE ON `ExtensionQueue` FOR EACH ROW CALL UpdateCache('Extensions')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
