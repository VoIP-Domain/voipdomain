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
 * VoIP Domain extensions module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Extensions database
 */
framework_add_hook ( "install_db", "extensions_install_db");

/**
 * Function to create extensions database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_install_db ( $buffer, $parameters)
{
  /**
   * Add basic system tables
   */
  install_add_db_table ( "Extensions", "CREATE TABLE `Extensions` (\n" .
                                       "  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,\n" .
                                       "  `Number` smallint(2) unsigned NOT NULL,\n" .
                                       "  `Description` varchar(50) NOT NULL,\n" .
                                       "  `Range` bigint(20) unsigned NOT NULL,\n" .
                                       "  `Type` varchar(50) NOT NULL,\n" .
                                       "  PRIMARY KEY (`ID`),\n" .
                                       "  UNIQUE KEY `Number` (`Number`),\n" .
                                       "  KEY `Type` (`Type`),\n" .
                                       "  KEY `Extensions_ibfk_1` (`Range`),\n" .
                                       "  CONSTRAINT `Extensions_ibfk_1` FOREIGN KEY (`Range`) REFERENCES `Ranges` (`ID`) ON UPDATE CASCADE\n" .
                                       ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Extensions';\n", array ( "Ranges"));
  install_add_db_table ( "ExtensionActivity", "CREATE TABLE `ExtensionActivity` (\n" .
                                              "  `UID` bigint(20) unsigned NOT NULL,\n" .
                                              "  `LastDialed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',\n" .
                                              "  `LastReceived` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',\n" .
                                              "  PRIMARY KEY (`UID`),\n" .
                                              "  KEY `ExtensionActivity_ibfk_1` (`UID`),\n" . 
                                              "  CONSTRAINT `ExtensionActivity_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `Extensions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                              ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Extension activity';\n", array ( "Extensions"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "ExtensionsInsert", "CREATE TRIGGER `ExtensionsInsert` AFTER INSERT ON `Extensions` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionsUpdate", "CREATE TRIGGER `ExtensionsUpdate` AFTER UPDATE ON `Extensions` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionsDelete", "CREATE TRIGGER `ExtensionsDelete` AFTER DELETE ON `Extensions` FOR EACH ROW CALL UpdateCache('Extensions')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
