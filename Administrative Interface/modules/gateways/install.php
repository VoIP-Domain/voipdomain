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
 * VoIP Domain gateways module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Gateways
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Gateways database
 */
framework_add_hook ( "install_db", "gateways_install_db");

/**
 * Function to create gateways database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Gateways", "CREATE TABLE `Gateways` (\n" .
                                     "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                     "  `Description` varchar(255) NOT NULL,\n" .
                                     "  `Active` boolean NOT NULL DEFAULT false,\n" .
                                     "  `Config` varchar(255) NOT NULL,\n" .
                                     "  `Number` varchar(255) DEFAULT '',\n" .
                                     "  `Type` enum('Digital','Analog','Mobile','VoIP') NOT NULL,\n" .
                                     "  `Priority` smallint(2) unsigned NOT NULL DEFAULT '0',\n" .
                                     "  `Currency` char(3) NOT NULL,\n" .
                                     "  `Address` varchar(255) NOT NULL,\n" .
                                     "  `Port` smallint(2) unsigned NOT NULL,\n" .
                                     "  `Username` varchar(255) NOT NULL,\n" .
                                     "  `Password` varchar(255) NOT NULL,\n" .
                                     "  `Routes` longblob NOT NULL,\n" .
                                     "  `Translations` longblob NOT NULL,\n" .
                                     "  `Discard` smallint(2) unsigned NOT NULL,\n" .
                                     "  `Minimum` smallint(2) unsigned NOT NULL,\n" .
                                     "  `Fraction` smallint(2) unsigned NOT NULL,\n" .
                                     "  `NAT` boolean NOT NULL DEFAULT false,\n" .
                                     "  `RPID` boolean NOT NULL DEFAULT false,\n" .
                                     "  `Qualify` boolean NOT NULL DEFAULT false,\n" .
                                     "  PRIMARY KEY (`ID`),\n" .
                                     "  KEY `Description` (`Description`),\n" .
                                     "  KEY `Type` (`Type`),\n" .
                                     "  CONSTRAINT `Gateways_ibfk_1` FOREIGN KEY (`Currency`) REFERENCES `Currencies` (`Code`) ON UPDATE CASCADE\n" .
                                     ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "GatewaysInsert", "CREATE TRIGGER `GatewaysInsert` AFTER INSERT ON `Gateways` FOR EACH ROW CALL UpdateCache('Gateways')");
  install_add_db_trigger ( "GatewaysUpdate", "CREATE TRIGGER `GatewaysUpdate` AFTER UPDATE ON `Gateways` FOR EACH ROW CALL UpdateCache('Gateways')");
  install_add_db_trigger ( "GatewaysDelete", "CREATE TRIGGER `GatewaysDelete` AFTER DELETE ON `Gateways` FOR EACH ROW CALL UpdateCache('Gateways')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
