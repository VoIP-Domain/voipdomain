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
 * VoIP Domain extensions phones module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Extensions database
 */
framework_add_hook ( "install_db", "extensionsphones_install_db");

/**
 * Function to create extensions phones database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensionsphones_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "PhoneAccounts", "CREATE TABLE `PhoneAccounts` (\n" .
                                          "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                          "  `Extension` bigint unsigned NOT NULL,\n" .
                                          "  `Username` varchar(50) NOT NULL,\n" .
                                          "  `Password` varchar(50) NOT NULL,\n" .
                                          "  `Equipment` bigint unsigned NOT NULL,\n" .
                                          "  `MAC` char(12) DEFAULT NULL,\n" .
                                          "  `Variables` mediumblob DEFAULT NULL,\n" .
                                          "  PRIMARY KEY (`ID`),\n" .
                                          "  KEY `Extension` (`Extension`),\n" .
                                          "  KEY `Equipment` (`Equipment`),\n" .
                                          "  CONSTRAINT `PhoneAccounts_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,\n" .
                                          "  CONSTRAINT `PhoneAccounts_ibfk_2` FOREIGN KEY (`Equipment`) REFERENCES `Equipments` (`ID`) ON UPDATE CASCADE\n" .
                                          ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Extensions", "Equipments"));
  install_add_db_table ( "PhoneCapture", "CREATE TABLE `PhoneCapture` (\n" .
                                         "  `Extension` bigint unsigned NOT NULL,\n" .
                                         "  `Group` bigint unsigned NOT NULL,\n" .
                                         "  CONSTRAINT `PhoneCapture` UNIQUE (`Extension`,`Group`),\n" .
                                         "  KEY `PhoneCapture_ibfk_2` (`Group`),\n" .
                                         "  CONSTRAINT `PhoneCapture_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE,\n" .
                                         "  CONSTRAINT `PhoneCapture_ibfk_2` FOREIGN KEY (`Group`) REFERENCES `Groups` (`ID`) ON UPDATE CASCADE\n" .
                                         ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Extensions", "Groups"));
  install_add_db_table ( "PhoneHint", "CREATE TABLE `PhoneHint` (\n" .
                                      "  `Extension` bigint unsigned NOT NULL,\n" .
                                      "  `Hint` bigint unsigned NOT NULL,\n" .
                                      "  CONSTRAINT `PhoneHint` UNIQUE (`Extension`,`Hint`),\n" .
                                      "  KEY `PhoneHint_ibfk_2` (`Hint`),\n" .
                                      "  CONSTRAINT `PhoneHint_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,\n" .
                                      "  CONSTRAINT `PhoneHint_ibfk_2` FOREIGN KEY (`Hint`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE\n" .
                                      ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Extensions"));
  install_add_db_table ( "PhoneTranshipment", "CREATE TABLE `PhoneTranshipment` (\n" .
                                              "  `Extension` bigint unsigned NOT NULL,\n" .
                                              "  `Transhipment` bigint unsigned NOT NULL,\n" .
                                              "  CONSTRAINT `PhoneTranshipment` UNIQUE (`Extension`,`Transhipment`),\n" .
                                              "  KEY `PhoneTranshipment_ibfk_2` (`Transhipment`),\n" .
                                              "  CONSTRAINT `PhoneTranshipment_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE,\n" .
                                              "  CONSTRAINT `PhoneTranshipment_ibfk_2` FOREIGN KEY (`Transhipment`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE\n" .
                                              ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Extensions"));
  install_add_db_table ( "ExtensionPhone", "CREATE TABLE `ExtensionPhone` (\n" .
                                           "  `Extension` bigint unsigned NOT NULL,\n" .
                                           "  `Email` varchar(255) DEFAULT NULL,\n" .
                                           "  `Group` bigint unsigned NOT NULL,\n" .
                                           "  `Password` char(6) DEFAULT NULL,\n" .
                                           "  `Permissions` mediumblob,\n" .
                                           "  `Options` mediumblob,\n" .
                                           "  `CostCenter` int(10) unsigned DEFAULT NULL,\n" .
                                           "  UNIQUE KEY `Extension` (`Extension`),\n" .
                                           "  KEY `ExtensionPhone_ibfk_1` (`Extension`),\n" .
                                           "  KEY `ExtensionPhone_ibfk_2` (`Group`),\n" .
                                           "  CONSTRAINT `ExtensionPhone_ibfk_1` FOREIGN KEY (`Extension`) REFERENCES `Extensions` (`ID`) ON UPDATE CASCADE,\n" .
                                           "  CONSTRAINT `ExtensionPhone_ibfk_2` FOREIGN KEY (`Group`) REFERENCES `Groups` (`ID`) ON UPDATE CASCADE\n" .
                                           ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Extensions", "Groups"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "PhoneAccountsInsert", "CREATE TRIGGER `PhoneAccountsInsert` AFTER INSERT ON `PhoneAccounts` FOR EACH ROW CALL UpdateCache('PhoneAccounts')");
  install_add_db_trigger ( "PhoneAccountsUpdate", "CREATE TRIGGER `PhoneAccountsUpdate` AFTER UPDATE ON `PhoneAccounts` FOR EACH ROW CALL UpdateCache('PhoneAccounts')");
  install_add_db_trigger ( "PhoneAccountsDelete", "CREATE TRIGGER `PhoneAccountsDelete` AFTER DELETE ON `PhoneAccounts` FOR EACH ROW CALL UpdateCache('PhoneAccounts')");
  install_add_db_trigger ( "PhoneCaptureInsert", "CREATE TRIGGER `PhoneCaptureInsert` AFTER INSERT ON `PhoneCapture` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "PhoneCaptureUpdate", "CREATE TRIGGER `PhoneCaptureUpdate` AFTER UPDATE ON `PhoneCapture` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "PhoneCaptureDelete", "CREATE TRIGGER `PhoneCaptureDelete` AFTER DELETE ON `PhoneCapture` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "PhoneHintInsert", "CREATE TRIGGER `PhoneHintInsert` AFTER INSERT ON `PhoneHint` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "PhoneHintUpdate", "CREATE TRIGGER `PhoneHintUpdate` AFTER UPDATE ON `PhoneHint` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "PhoneHintDelete", "CREATE TRIGGER `PhoneHintDelete` AFTER DELETE ON `PhoneHint` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "PhoneTranshipmentInsert", "CREATE TRIGGER `PhoneTranshipmentInsert` AFTER INSERT ON `PhoneTranshipment` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "PhoneTranshipmentUpdate", "CREATE TRIGGER `PhoneTranshipmentUpdate` AFTER UPDATE ON `PhoneTranshipment` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "PhoneTranshipmentDelete", "CREATE TRIGGER `PhoneTranshipmentDelete` AFTER DELETE ON `PhoneTranshipment` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionPhoneInsert", "CREATE TRIGGER `ExtensionPhoneInsert` AFTER INSERT ON `ExtensionPhone` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionPhoneUpdate", "CREATE TRIGGER `ExtensionPhoneUpdate` AFTER UPDATE ON `ExtensionPhone` FOR EACH ROW CALL UpdateCache('Extensions')");
  install_add_db_trigger ( "ExtensionPhoneDelete", "CREATE TRIGGER `ExtensionPhoneDelete` AFTER DELETE ON `ExtensionPhone` FOR EACH ROW CALL UpdateCache('Extensions')");

  /**
   * Return structured data
   */
  return $buffer;
}
?>
