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
 * VoIP Domain conferences module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Conferences
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Conferences database
 */
framework_add_hook ( "install_db", "conferences_install_db");

/**
 * Function to create conferences database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_install_db ( $buffer, $parameters)
{
  /**
   * Add basic system tables
   */
  install_add_db_table ( "Conferences", "CREATE TABLE `Conferences` (\n" .
                                        "  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,\n" .
                                        "  `Tenant` bigint(20) unsigned NOT NULL,\n" .
                                        "  `Name` varchar(255) NOT NULL,\n" .
                                        "  `Description` varchar(255) NOT NULL,\n" .
                                        "  `PIN` varchar(20) NOT NULL DEFAULT '',\n" .
                                        "  `AdminPIN` varchar(20) NOT NULL DEFAULT '',\n" .
                                        "  `MaxMembers` int(11) NOT NULL DEFAULT 0,\n" .
                                        "  `MOHClass` varchar(255) NOT NULL DEFAULT '',\n" .
                                        "  `RecordConference` enum('Y','N') NOT NULL DEFAULT 'N',\n" .
                                        "  `AnnounceJoinLeave` enum('Y','N') NOT NULL DEFAULT 'Y',\n" .
                                        "  `WaitForAdmin` enum('Y','N') NOT NULL DEFAULT 'N',\n" .
                                        "  PRIMARY KEY (`ID`),\n" .
                                        "  UNIQUE KEY `Name` (`Tenant`,`Name`),\n" .
                                        "  KEY `Conferences_ibfk_1` (`Tenant`),\n" .
                                        "  CONSTRAINT `Conferences_ibfk_1` FOREIGN KEY (`Tenant`) REFERENCES `Tenants` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                        ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Conference rooms';\n", array ( "Tenants"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "ConferencesInsert", "CREATE TRIGGER `ConferencesInsert` AFTER INSERT ON `Conferences` FOR EACH ROW CALL UpdateCache('Conferences')");
  install_add_db_trigger ( "ConferencesUpdate", "CREATE TRIGGER `ConferencesUpdate` AFTER UPDATE ON `Conferences` FOR EACH ROW CALL UpdateCache('Conferences')");
  install_add_db_trigger ( "ConferencesDelete", "CREATE TRIGGER `ConferencesDelete` AFTER DELETE ON `Conferences` FOR EACH ROW CALL UpdateCache('Conferences')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
