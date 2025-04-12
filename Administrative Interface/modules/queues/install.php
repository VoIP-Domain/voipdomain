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
 * VoIP Domain queues module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Queues database
 */
framework_add_hook ( "install_db", "queues_install_db");

/**
 * Function to create queues database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Queues", "CREATE TABLE `Queues` (\n" .
                                   "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                   "  `Description` varchar(255) NOT NULL,\n" .
                                   "  `Name` varchar(255) NOT NULL,\n" .
                                   "  `Strategy` enum('ringall','roundrobin','leastrecent','fewestcalls','random','rrmemory') NOT NULL DEFAULT 'ringall',\n" .
                                   "  PRIMARY KEY (`ID`),\n" .
                                   "  UNIQUE KEY `Name` (`Name`)\n" .
                                   ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "QueueMembers", "CREATE TABLE `QueueMembers` (\n" .
                                         "  `Queue` bigint unsigned NOT NULL,\n" .
                                         "  `Member` varchar(255) NOT NULL,\n" .
                                         "  KEY `Queue` (`Queue`),\n" .
                                         "  CONSTRAINT `QueueMembers_ibfk_1` FOREIGN KEY (`Queue`) REFERENCES `Queues` (`ID`) ON UPDATE CASCADE ON DELETE CASCADE\n" .
                                         ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Queues"));
  install_add_db_table ( "ExtensionQueue", "CREATE TABLE `ExtensionQueue` (\n" .
                                           "  `Extension` bigint unsigned NOT NULL,\n" .
                                           "  `Queue` bigint unsigned NOT NULL,\n" .
                                           "  UNIQUE KEY `Extension` (`Extension`),\n" .
                                           "  KEY `Queue` (`Queue`),\n" .
                                           "  CONSTRAINT `ExtensionQueue_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE,\n" .
                                           "  CONSTRAINT `ExtensionQueue_ibfk_2` FOREIGN KEY (`Queue`) REFERENCES `Queues` (`ID`) ON UPDATE CASCADE\n" .
                                           ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Extensions", "Queues"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "QueuesInsert", "CREATE TRIGGER `QueuesInsert` AFTER INSERT ON `Queues` FOR EACH ROW CALL UpdateCache('Queues')");
  install_add_db_trigger ( "QueuesUpdate", "CREATE TRIGGER `QueuesUpdate` AFTER UPDATE ON `Queues` FOR EACH ROW CALL UpdateCache('Queues')");
  install_add_db_trigger ( "QueuesDelete", "CREATE TRIGGER `QueuesDelete` AFTER DELETE ON `Queues` FOR EACH ROW CALL UpdateCache('Queues')");
  install_add_db_trigger ( "QueueMembersInsert", "CREATE TRIGGER `QueueMembersInsert` AFTER INSERT ON `QueueMembers` FOR EACH ROW CALL UpdateCache('QueueMembers')");
  install_add_db_trigger ( "QueueMembersUpdate", "CREATE TRIGGER `QueueMembersUpdate` AFTER UPDATE ON `QueueMembers` FOR EACH ROW CALL UpdateCache('QueueMembers')");
  install_add_db_trigger ( "QueueMembersDelete", "CREATE TRIGGER `QueueMembersDelete` AFTER DELETE ON `QueueMembers` FOR EACH ROW CALL UpdateCache('QueueMembers')");
  install_add_db_trigger ( "ExtensionQueueInsert", "CREATE TRIGGER `ExtensionQueueInsert` AFTER INSERT ON `ExtensionQueue` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionQueueUpdate", "CREATE TRIGGER `ExtensionQueueUpdate` AFTER UPDATE ON `ExtensionQueue` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionQueueDelete", "CREATE TRIGGER `ExtensionQueueDelete` AFTER DELETE ON `ExtensionQueue` FOR EACH ROW CALL UpdateCache('Extensions')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
