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
 * VoIP Domain ranges module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Ranges
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Ranges database
 */
framework_add_hook ( "install_db", "ranges_install_db");

/**
 * Function to create ranges database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Ranges", "CREATE TABLE `Ranges` (\n" .
                                   "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                   "  `Description` varchar(255) NOT NULL,\n" .
                                   "  `Server` bigint unsigned NOT NULL,\n" .
                                   "  `Start` smallint(2) unsigned NOT NULL,\n" .
                                   "  `Finish` smallint(2) unsigned NOT NULL,\n" .
                                   "  PRIMARY KEY (`ID`),\n" .
                                   "  KEY `Range` (`Start`,`Finish`),\n" .
                                   "  KEY `Ranges_ibfk_1` (`Server`),\n" .
                                   "  CONSTRAINT `Ranges_ibfk_1` FOREIGN KEY (`Server`) REFERENCES `Servers` (`ID`) ON UPDATE CASCADE\n" .
                                   ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Servers"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "RangesInsert", "CREATE TRIGGER `RangesInsert` AFTER INSERT ON `Ranges` FOR EACH ROW CALL UpdateCache('Ranges')");
  install_add_db_trigger ( "RangesUpdate", "CREATE TRIGGER `RangesUpdate` AFTER UPDATE ON `Ranges` FOR EACH ROW CALL UpdateCache('Ranges')");
  install_add_db_trigger ( "RangesDelete", "CREATE TRIGGER `RangesDelete` AFTER DELETE ON `Ranges` FOR EACH ROW CALL UpdateCache('Ranges')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
