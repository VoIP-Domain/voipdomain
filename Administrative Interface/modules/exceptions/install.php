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
 * VoIP Domain exceptions module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Exceptions
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Exceptions database
 */
framework_add_hook ( "install_db", "exceptions_install_db");

/**
 * Function to create exceptions database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Exceptions", "CREATE TABLE `Exceptions` (\n" .
                                       "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                       "  `Description` varchar(255) NOT NULL,\n" .
                                       "  `Number` varchar(80) NOT NULL,\n" .
                                       "  PRIMARY KEY (`ID`),\n" .
                                       "  UNIQUE KEY `Number` (`Number`)\n" .
                                       ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "ExceptionsInsert", "CREATE TRIGGER `ExceptionsInsert` AFTER INSERT ON `Exceptions` FOR EACH ROW CALL UpdateCache('Exceptions')");
  install_add_db_trigger ( "ExceptionsUpdate", "CREATE TRIGGER `ExceptionsUpdate` AFTER UPDATE ON `Exceptions` FOR EACH ROW CALL UpdateCache('Exceptions')");
  install_add_db_trigger ( "ExceptionsDelete", "CREATE TRIGGER `ExceptionsDelete` AFTER DELETE ON `Exceptions` FOR EACH ROW CALL UpdateCache('Exceptions')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
