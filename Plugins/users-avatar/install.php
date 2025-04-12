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
 * VoIP Domain user login avatar module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage User Avatar
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create User Avatar database
 */
framework_add_hook ( "install_db", "useravatar_install_db");

/**
 * Function to create user avatar database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function useravatar_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "UserAvatar", "CREATE TABLE `UserAvatar` (\n" .
                                       "  `User` bigint unsigned NOT NULL,\n" .
                                       "  `Avatar` char(23) NOT NULL,\n" .
                                       "  UNIQUE KEY `User` (`User`),\n" .
                                       "  KEY `UserAvatar_ibfk_1` (`User`),\n" .
                                       "  CONSTRAINT `UserAvatar_ibfk_1` FOREIGN KEY (`User`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                       ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Users"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "UserAvatarInsert", "CREATE TRIGGER `UserAvatarInsert` AFTER INSERT ON `UserAvatar` FOR EACH ROW CALL UpdateCache('Users')");
  install_add_db_trigger ( "UserAvatarUpdate", "CREATE TRIGGER `UserAvatarUpdate` AFTER UPDATE ON `UserAvatar` FOR EACH ROW CALL UpdateCache('Users')");
  install_add_db_trigger ( "UserAvatarDelete", "CREATE TRIGGER `UserAvatarDelete` AFTER DELETE ON `UserAvatar` FOR EACH ROW CALL UpdateCache('Users')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
