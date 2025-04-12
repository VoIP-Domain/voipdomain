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
 * VoIP Domain audios module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Audios
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Audios database
 */
framework_add_hook ( "install_db", "audios_install_db");

/**
 * Function to create audios database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Audios", "CREATE TABLE `Audios` (\n" .
                                   "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                   "  `Filename` varchar(255) NOT NULL,\n" .
                                   "  `Description` varchar(255) NOT NULL,\n" .
                                   "  PRIMARY KEY (`ID`)\n" .
                                   ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "AudiosInsert", "CREATE TRIGGER `AudiosInsert` AFTER INSERT ON `Audios` FOR EACH ROW CALL UpdateCache('Audios')");
  install_add_db_trigger ( "AudiosUpdate", "CREATE TRIGGER `AudiosUpdate` AFTER UPDATE ON `Audios` FOR EACH ROW CALL UpdateCache('Audios')");
  install_add_db_trigger ( "AudiosDelete", "CREATE TRIGGER `AudiosDelete` AFTER DELETE ON `Audios` FOR EACH ROW CALL UpdateCache('Audios')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
