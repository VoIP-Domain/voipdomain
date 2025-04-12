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
 * VoIP Domain ivrs module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage IVRs
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create IVRs database
 */
framework_add_hook ( "install_db", "ivrs_install_db");

/**
 * Function to create ivrs database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "IVRs", "CREATE TABLE `IVRs` (\n" .
                                 "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                 "  `Name` varchar(255) NOT NULL,\n" .
                                 "  `Description` varchar(255) NOT NULL,\n" .
                                 "  PRIMARY KEY (`ID`),\n" .
                                 "  UNIQUE KEY `Name` (`Name`)\n" .
                                 ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "IVRWorkflows", "CREATE TABLE `IVRWorkflows` (\n" .
                                         "  `IVR` bigint unsigned NOT NULL,\n" .
                                         "  `Name` varchar(255) NOT NULL,\n" .
                                         "  `Description` varchar(255) NOT NULL,\n" .
                                         "  `Revision` int unsigned NOT NULL,\n" .
                                         "  `Workflow` blob NOT NULL,\n" .
                                         "  UNIQUE KEY `IVRRevision` (`IVR`, `Revision`),\n" .
                                         "  CONSTRAINT `IVRWorkflows_ibfk_1` FOREIGN KEY (`IVR`) REFERENCES `IVRs` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                         ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "IVRs"));
  install_add_db_table ( "ExtensionIVR", "CREATE TABLE `ExtensionIVR` (\n" .
                                         "  `Extension` bigint unsigned NOT NULL,\n" .
                                         "  `IVR` bigint unsigned NOT NULL,\n" .
                                         "  UNIQUE KEY `Extension` (`Extension`),\n" .
                                         "  KEY `IVR` (`IVR`),\n" .
                                         "  CONSTRAINT `ExtensionIVR_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE,\n" .
                                         "  CONSTRAINT `ExtensionIVR_ibfk_2` FOREIGN KEY (`IVR`) REFERENCES `IVRs` (`ID`) ON UPDATE CASCADE\n" .
                                         ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Extensions", "IVRs"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "IVRsInsert", "CREATE TRIGGER `IVRsInsert` AFTER INSERT ON `IVRs` FOR EACH ROW CALL UpdateCache('IVRs')");
  install_add_db_trigger ( "IVRsUpdate", "CREATE TRIGGER `IVRsUpdate` AFTER UPDATE ON `IVRs` FOR EACH ROW CALL UpdateCache('IVRs')");
  install_add_db_trigger ( "IVRsDelete", "CREATE TRIGGER `IVRsDelete` AFTER DELETE ON `IVRs` FOR EACH ROW CALL UpdateCache('IVRs')");
  install_add_db_trigger ( "IVRWorkflowsInsert", "CREATE TRIGGER `IVRWorkflowsInsert` AFTER INSERT ON `IVRWorkflows` FOR EACH ROW CALL UpdateCache('IVRs')");
  install_add_db_trigger ( "IVRWorkflowsUpdate", "CREATE TRIGGER `IVRWorkflowsUpdate` AFTER UPDATE ON `IVRWorkflows` FOR EACH ROW CALL UpdateCache('IVRs')");
  install_add_db_trigger ( "IVRWorkflowsDelete", "CREATE TRIGGER `IVRWorkflowsDelete` AFTER DELETE ON `IVRWorkflows` FOR EACH ROW CALL UpdateCache('IVRs')");
  install_add_db_trigger ( "ExtensionIVRInsert", "CREATE TRIGGER `ExtensionIVRInsert` AFTER INSERT ON `ExtensionIVR` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionIVRUpdate", "CREATE TRIGGER `ExtensionIVRUpdate` AFTER UPDATE ON `ExtensionIVR` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionIVRDelete", "CREATE TRIGGER `ExtensionIVRDelete` AFTER DELETE ON `ExtensionIVR` FOR EACH ROW CALL UpdateCache('Extensions')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
