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
 * VoIP Domain notifications module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Notifications
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Notifications database
 */
framework_add_hook ( "install_db", "notifications_install_db");

/**
 * Function to create notifications database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Notifications", "CREATE TABLE `Notifications` (\n" .
                                          "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                          "  `Description` varchar(255) NOT NULL,\n" .
                                          "  `Event` varchar(255) NOT NULL,\n" .
                                          "  `Filters` text NOT NULL,\n" .
                                          "  `URL` varchar(255) NOT NULL,\n" .
                                          "  `Method` enum('GET','POST','PUT','DELETE','HEAD','OPTIONS','TRACE','CONNECT') NOT NULL DEFAULT 'POST',\n" .
                                          "  `Type` enum('JSON','FORM-DATA','PHP') NOT NULL DEFAULT 'JSON',\n" .
                                          "  `Variables` text NOT NULL,\n" .
                                          "  `Headers` text NOT NULL,\n" .
                                          "  `RelaxSSL` enum('Y','N') NOT NULL DEFAULT 'N',\n" .
                                          "  `Expire` datetime NOT NULL,\n" .
                                          "  PRIMARY KEY (`ID`),\n" .
                                          "  KEY `Event` (`Event`)\n" .
                                          ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "NotificationsInsert", "CREATE TRIGGER `NotificationsInsert` AFTER INSERT ON `Notifications` FOR EACH ROW CALL UpdateCache('Notifications')");
  install_add_db_trigger ( "NotificationsUpdate", "CREATE TRIGGER `NotificationsUpdate` AFTER UPDATE ON `Notifications` FOR EACH ROW CALL UpdateCache('Notifications')");
  install_add_db_trigger ( "NotificationsDelete", "CREATE TRIGGER `NotificationsDelete` AFTER DELETE ON `Notifications` FOR EACH ROW CALL UpdateCache('Notifications')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
