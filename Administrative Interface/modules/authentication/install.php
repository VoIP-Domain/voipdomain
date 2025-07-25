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
 * VoIP Domain authentication module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Authentication
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create authentication database
 */
framework_add_hook ( "install_db", "authentication_install_db");

/**
 * Function to create authentication database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_install_db ( $buffer, $parameters)
{
  /**
   * Add basic system tables
   */
  install_add_db_table ( "AuthenticationCache", "CREATE TABLE `AuthenticationCache` (\n" .
                                                "  `Tenant` bigint(20) unsigned NOT NULL,\n" .
                                                "  `Plugin` varchar(255) NOT NULL,\n" .
                                                "  `Cookie` char(16) NOT NULL,\n" .
                                                "  `State` char(32) NOT NULL,\n" .
                                                "  `Expire` int(11) UNSIGNED NOT NULL,\n" .
                                                "  `Callback` varchar(255),\n" .
                                                "  UNIQUE KEY `AuthenticationPair` (`Tenant`,`Cookie`, `State`),\n" .
                                                "  KEY `AuthencationCache_ibfk_1` (`Tenant`),\n" . 
                                                "  CONSTRAINT `AuthenticationCache_ibfk_1` FOREIGN KEY (`Tenant`) REFERENCES `Tenants` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" . 
                                                ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Authentication proccess cache';\n", array ( "Tenants"));
  install_add_db_table ( "AuthenticationToken", "CREATE TABLE `AuthenticationToken` (\n" .
                                                "  `Token` char(32) NOT NULL,\n" .
                                                "  `Tenant` bigint(20) unsigned NOT NULL,\n" .
                                                "  `Plugin` varchar(255) NOT NULL,\n" .
                                                "  `Email` varchar(255) DEFAULT NULL,\n" .
                                                "  `IssueDate` datetime NOT NULL,\n" .
                                                "  `LastSeen` datetime NOT NULL,\n" .
                                                "  `Expires` datetime NOT NULL,\n" .
                                                "  `TokenData` text NOT NULL,\n" .
                                                "  `UserData` text NOT NULL,\n" .
                                                "  PRIMARY KEY (`Token`),\n" .
                                                "  KEY `Email` (`Email`),\n" .
                                                "  KEY `AuthencationToken_ibfk_1` (`Tenant`),\n" . 
                                                "  CONSTRAINT `AuthenticationToken_ibfk_1` FOREIGN KEY (`Tenant`) REFERENCES `Tenants` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" . 
                                                ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Authentication tokens';\n", array ( "Tenants"));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
