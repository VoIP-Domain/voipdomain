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
 * VoIP Domain extensions hunts module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Hunts
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Extensions database
 */
framework_add_hook ( "install_db", "extensionshunts_install_db");

/**
 * Function to create extensions hunts database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensionshunts_install_db ( $buffer, $parameters)
{
  /**
   * Add basic system tables
   */
  install_add_db_table ( "ExtensionHunt", "CREATE TABLE `ExtensionHunt` (\n" .
                                          "  `Extension` bigint(20) unsigned NOT NULL,\n" .
                                          "  `Hunt` bigint(20) unsigned NOT NULL,\n" .
                                          "  UNIQUE KEY `ExtensionHunt` (`Extension`, `Hunt`),\n" .
                                          "  KEY `ExtensionHunt_ibfk_1` (`Extension`),\n" .
                                          "  KEY `ExtensionHunt_ibfk_2` (`Hunt`),\n" .
                                          "  CONSTRAINT `ExtensionHunt_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE,\n" .
                                          "  CONSTRAINT `ExtensionHunt_ibfk_2` FOREIGN KEY (`Hunt`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE\n" .
                                          ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Extension hunt link';\n", array ( "Extensions"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "ExtensionHuntInsert", "CREATE TRIGGER `ExtensionHuntInsert` AFTER INSERT ON `ExtensionHunt` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionHuntUpdate", "CREATE TRIGGER `ExtensionHuntUpdate` AFTER UPDATE ON `ExtensionHunt` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionHuntDelete", "CREATE TRIGGER `ExtensionHuntDelete` AFTER DELETE ON `ExtensionHunt` FOR EACH ROW CALL UpdateCache('Extensions')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
