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
 * VoIP Domain multi-tenant module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Multi-Tenant
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create multi-tenant database
 */
framework_add_hook ( "install_db", "multitenant_install_db");

/**
 * Function to create multi-tenant database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function multitenant_install_db ( $buffer, $parameters)
{
  /**
   * Add basic system tables
   */
  install_add_db_table ( "Tenants", "CREATE TABLE `Tenants` (\n" .
                                    "  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,\n" .
                                    "  `Name` varchar(255) NOT NULL,\n" .
                                    "  `Domain` varchar(255) DEFAULT NULL,\n" .
                                    "  `Status` enum('Active','Suspended') NOT NULL DEFAULT 'Active',\n" .
                                    "  `Settings` text NOT NULL,\n" .
                                    "  `CreatedAt` datetime NOT NULL,\n" .
                                    "  `UpdatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n" .
                                    "  `Country` int(2) unsigned NOT NULL,\n" .
                                    "  `TimeZone` varchar(50) NOT NULL DEFAULT 'UTC',\n" .
                                    "  `Offset` int(2) NOT NULL,\n" .
                                    "  `Currency` int(2) UNSIGNED NOT NULL DEFAULT 840,\n" .
                                    "  `Language` varchar(5) NOT NULL DEFAULT 'en_US',\n" .
                                    "  `PublicKey` blob NOT NULL,\n" .
                                    "  `PrivateKey` blob NOT NULL,\n" .
                                    "  PRIMARY KEY (`ID`),\n" .
                                    "  UNIQUE KEY `Domain` (`Domain`),\n" .
                                    "  KEY `Status` (`Status`),\n" .
                                    "  CONSTRAINT `Tentants_ibfk_1` FOREIGN KEY (`Country`) REFERENCES `Countries` (`Code`) ON DELETE CASCADE ON UPDATE CASCADE,\n" .
                                    "  CONSTRAINT `Tentants_ibfk_2` FOREIGN KEY (`Currency`) REFERENCES `Currencies` (`ISO4217`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Multi-tenant organizations';\n", array ( "Currencies", "Countries"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "TenantsChange", "CREATE TRIGGER `TenantsChange` AFTER UPDATE ON `Tenants` FOR EACH ROW CALL UpdateCache('Tenants')");
  install_add_db_trigger ( "TenantsInsert", "CREATE TRIGGER `TenantsInsert` AFTER INSERT ON `Tenants` FOR EACH ROW CALL UpdateCache('Tenants')");
  install_add_db_trigger ( "TenantsDelete", "CREATE TRIGGER `TenantsDelete` AFTER DELETE ON `Tenants` FOR EACH ROW CALL UpdateCache('Tenants')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
