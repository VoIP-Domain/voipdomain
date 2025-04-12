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
 * VoIP Domain servers module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Servers
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Servers database
 */
framework_add_hook ( "install_db", "servers_install_db");

/**
 * Function to create servers database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Servers", "CREATE TABLE `Servers` (\n" .
                                    "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                    "  `Description` varchar(255) NOT NULL,\n" .
                                    "  `Address` varchar(255) NOT NULL,\n" .
                                    "  `Port` int(2) unsigned NOT NULL,\n" .
                                    "  `NTP` varchar(255),\n" .
                                    "  `TransfStart` time DEFAULT NULL,\n" .
                                    "  `TransfEnd` time DEFAULT NULL,\n" .
                                    "  `PublicKey` blob NOT NULL,\n" .
                                    "  `PrivateKey` blob NOT NULL,\n" .
                                    "  PRIMARY KEY (`ID`)\n" .
                                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "ServerBackup", "CREATE TABLE `ServerBackup` (\n" .
                                         "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                         "  `Server` bigint unsigned NOT NULL,\n" .
                                         "  `Description` varchar(255) NOT NULL,\n" .
                                         "  `Address` varchar(255) NOT NULL,\n" .
                                         "  `Port` int(2) unsigned NOT NULL,\n" .
                                         "  `PublicKey` blob NOT NULL,\n" .
                                         "  `PrivateKey` blob NOT NULL,\n" .
                                         "  PRIMARY KEY (`ID`),\n" .
                                         "  KEY `ServerBackup_ibfk_1` (`Server`),\n" .
                                         "  CONSTRAINT `ServerBackup_ibfk_1` FOREIGN KEY (`Server`) REFERENCES `Servers` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                         ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Servers"));
  install_add_db_table ( "Commands", "CREATE TABLE `Commands` (\n" .
                                     "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                     "  `Server` bigint unsigned NOT NULL,\n" .
                                     "  `Event` varchar(255) NOT NULL,\n" .
                                     "  `Data` longblob,\n" .
                                     "  PRIMARY KEY (`ID`),\n" .
                                     "  KEY `Server` (`Server`),\n" .
                                     "  CONSTRAINT `Commands_ibfk_1` FOREIGN KEY (`Server`) REFERENCES `Servers` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                     ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Servers"));
  install_add_db_table ( "GroupedCommands", "CREATE TABLE `GroupedCommands` (\n" .
                                            "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                            "  `Server` bigint unsigned NOT NULL,\n" .
                                            "  `Total` smallint(2) UNSIGNED NOT NULL,\n" .
                                            "  `Left` smallint(2) UNSIGNED NOT NULL,\n" .
                                            "  PRIMARY KEY (`ID`),\n" .
                                            "  CONSTRAINT `GroupedCommands_ibfk_1` FOREIGN KEY (`Server`) REFERENCES `Servers` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                            ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Servers"));
  install_add_db_table ( "GroupCommand", "CREATE TABLE `GroupCommand` (\n" .
                                         "  `Group` bigint unsigned NOT NULL,\n" .
                                         "  `Command` bigint unsigned NOT NULL,\n" .
                                         "  KEY `Group` (`Group`),\n" .
                                         "  KEY `Command` (`Command`),\n" .
                                         "  CONSTRAINT `GroupCommand_ibfk_1` FOREIGN KEY (`Group`) REFERENCES `GroupedCommands` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,\n" .
                                         "  CONSTRAINT `GroupCommand_ibfk_2` FOREIGN KEY (`Command`) REFERENCES `Commands` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                         ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "GroupedCommands", "Commands"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "ServersInsert", "CREATE TRIGGER `ServersInsert` AFTER INSERT ON `Servers` FOR EACH ROW CALL UpdateCache('Servers')");
  install_add_db_trigger ( "ServersUpdate", "CREATE TRIGGER `ServersUpdate` AFTER UPDATE ON `Servers` FOR EACH ROW CALL UpdateCache('Servers')");
  install_add_db_trigger ( "ServersDelete", "CREATE TRIGGER `ServersDelete` AFTER DELETE ON `Servers` FOR EACH ROW CALL UpdateCache('Servers')");
  install_add_db_trigger ( "ServerBackupInsert", "CREATE TRIGGER `ServerBackupInsert` AFTER INSERT ON `ServerBackup` FOR EACH ROW CALL UpdateCache('Servers')");
  install_add_db_trigger ( "ServerBackupUpdate", "CREATE TRIGGER `ServerBackupUpdate` AFTER UPDATE ON `ServerBackup` FOR EACH ROW CALL UpdateCache('Servers')");
  install_add_db_trigger ( "ServerBackupDelete", "CREATE TRIGGER `ServerBackupDelete` AFTER DELETE ON `ServerBackup` FOR EACH ROW CALL UpdateCache('Servers')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
