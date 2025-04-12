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
 * VoIP Domain profiles module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Profiles
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Profiles database
 */
framework_add_hook ( "install_db", "profiles_install_db");

/**
 * Function to create profiles database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Profiles", "CREATE TABLE `Profiles` (\n" .
                                     "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                     "  `Description` varchar(255) NOT NULL,\n" .
                                     "  `Domain` varchar(255) NOT NULL,\n" .
                                     "  `Country` int(2) unsigned NOT NULL,\n" .
                                     "  `TimeZone` varchar(255) NOT NULL,\n" .
                                     "  `Offset` int(2) NOT NULL,\n" .
                                     "  `AreaCode` smallint(2) unsigned NOT NULL,\n" .
                                     "  `Prefix` smallint(1) unsigned NOT NULL,\n" .
                                     "  `NGGW` bigint unsigned NOT NULL,\n" .
                                     "  `DefaultGW` text DEFAULT '',\n" .
                                     "  `BlockedGW` text DEFAULT '',\n" .
                                     "  `Language` varchar(5) NOT NULL DEFAULT 'en_US',\n" .
                                     "  `MOH` int(10) unsigned DEFAULT NULL,\n" .
                                     "  `EmergencyShortcut` boolean NOT NULL DEFAULT false,\n" .
                                     "  PRIMARY KEY (`ID`),\n" .
                                     "  KEY `Profiles_ibfk_1` (`Country`),\n" .
                                     "  KEY `Profiles_ibfk_2` (`NGGW`),\n" .
                                     "  CONSTRAINT `Profiles_ibfk_1` FOREIGN KEY (`Country`) REFERENCES `Countries` (`Code`) ON UPDATE CASCADE,\n" .
                                     "  CONSTRAINT `Profiles_ibfk_2` FOREIGN KEY (`NGGW`) REFERENCES `Gateways` (`ID`) ON UPDATE CASCADE\n" .
                                     ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Countries", "Gateways"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "ProfilesInsert", "CREATE TRIGGER `ProfilesInsert` AFTER INSERT ON `Profiles` FOR EACH ROW CALL UpdateCache('Profiles')");
  install_add_db_trigger ( "ProfilesUpdate", "CREATE TRIGGER `ProfilesUpdate` AFTER UPDATE ON `Profiles` FOR EACH ROW CALL UpdateCache('Profiles')");
  install_add_db_trigger ( "ProfilesDelete", "CREATE TRIGGER `ProfilesDelete` AFTER DELETE ON `Profiles` FOR EACH ROW CALL UpdateCache('Profiles')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
