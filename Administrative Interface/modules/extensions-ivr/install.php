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
 * VoIP Domain extensions IVRs module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions IVRs
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Extensions IVRs database
 */
framework_add_hook ( "install_db", "extensions_ivrs_install_db");

/**
 * Function to create extensions ivrs database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_ivrs_install_db ( $buffer, $parameters)
{
  /**
   * Add basic system tables
   */
  install_add_db_table ( "ExtensionIVR", "CREATE TABLE `ExtensionIVR` (\n" .
                                         "  `Extension` bigint(20) unsigned NOT NULL,\n" .
                                         "  `IVR` bigint(20) unsigned NOT NULL,\n" .
                                         "  UNIQUE KEY `Extension` (`Extension`),\n" .
                                         "  KEY `IVR` (`IVR`),\n" .
                                         "  CONSTRAINT `ExtensionIVR_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE,\n" .
                                         "  CONSTRAINT `ExtensionIVR_ibfk_2` FOREIGN KEY (`IVR`) REFERENCES `IVRs` (`ID`) ON UPDATE CASCADE\n" .
                                         ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Extension to IVRs link';\n", array ( "Extensions", "IVRs"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "ExtensionIVRInsert", "CREATE TRIGGER `ExtensionIVRInsert` AFTER INSERT ON `ExtensionIVR` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionIVRUpdate", "CREATE TRIGGER `ExtensionIVRUpdate` AFTER UPDATE ON `ExtensionIVR` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionIVRDelete", "CREATE TRIGGER `ExtensionIVRDelete` AFTER DELETE ON `ExtensionIVR` FOR EACH ROW CALL UpdateCache('Extensions')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
