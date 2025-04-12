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
 * VoIP Domain users module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Users
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Users database
 */
framework_add_hook ( "install_db", "users_install_db");

/**
 * Function to create users database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Users", "CREATE TABLE `Users` (\n" .
                                  "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                  "  `Name` varchar(255) NOT NULL,\n" .
                                  "  `Username` varchar(50) NOT NULL,\n" .
                                  "  `Password` char(64) NOT NULL,\n" .
                                  "  `Salt` char(64) NOT NULL,\n" .
                                  "  `Iterations` int(2) unsigned NOT NULL DEFAULT '40000',\n" .
                                  "  `Permissions` mediumblob,\n" .
                                  "  `Email` varchar(255) NOT NULL,\n" .
                                  "  `Since` datetime NOT NULL,\n" .
                                  "  `Language` varchar(255) DEFAULT '',\n" .
                                  "  `OTPKey` char(32) DEFAULT '',\n" .
                                  "  PRIMARY KEY (`ID`),\n" .
                                  "  KEY `Username` (`Username`)\n" .
                                  ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");
  install_add_db_table ( "UserSFA", "CREATE TABLE `UserSFA` (\n" .
                                    "  `UID` bigint unsigned NOT NULL,\n" .
                                    "  `Key` char(32) NOT NULL,\n" .
                                    "  `Status` enum('Pending', 'Active') NOT NULL,\n" .
                                    "  UNIQUE KEY `UID` (`UID`),\n" .
                                    "  KEY `UserSFA_ibfk_1` (`UID`),\n" .
                                    "  CONSTRAINT `UserSFA_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Users"));
  install_add_db_table ( "SFACache", "CREATE TABLE `SFACache` (\n" .
                                     "  `UID` bigint unsigned NOT NULL,\n" .
                                     "  `Key` char(32) NOT NULL,\n" .
                                     "  KEY `UID` (`UID`),\n" .
                                     "  CONSTRAINT `SFACache_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                     ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Users"));
  install_add_db_table ( "Sessions", "CREATE TABLE `Sessions` (\n" .
                                     "  `SID` char(64) NOT NULL,\n" .
                                     "  `User` bigint unsigned NOT NULL,\n" .
                                     "  `LastSeen` bigint unsigned NOT NULL,\n" .
                                     "  PRIMARY KEY (`SID`),\n" .
                                     "  UNIQUE KEY `SID` (`SID`),\n" .
                                     "  KEY `Sessions_ibfk_1` (`User`),\n" .
                                     "  CONSTRAINT `Sessions_ibfk_1` FOREIGN KEY (`User`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                     ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n", array ( "Users"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "UsersInsert", "CREATE TRIGGER `UsersInsert` AFTER INSERT ON `Users` FOR EACH ROW CALL UpdateCache('Users')");
  install_add_db_trigger ( "UsersUpdate", "CREATE TRIGGER `UsersUpdate` AFTER UPDATE ON `Users` FOR EACH ROW CALL UpdateCache('Users')");
  install_add_db_trigger ( "UsersDelete", "CREATE TRIGGER `UsersDelete` AFTER DELETE ON `Users` FOR EACH ROW CALL UpdateCache('Users')");
  install_add_db_trigger ( "UserSFAInsert", "CREATE TRIGGER `UserSFAInsert` AFTER INSERT ON `UserSFA` FOR EACH ROW CALL UpdateCache('Users')");
  install_add_db_trigger ( "UserSFAUpdate", "CREATE TRIGGER `UserSFAUpdate` AFTER UPDATE ON `UserSFA` FOR EACH ROW CALL UpdateCache('Users')");
  install_add_db_trigger ( "UserSFADelete", "CREATE TRIGGER `UserSFADelete` AFTER DELETE ON `UserSFA` FOR EACH ROW CALL UpdateCache('Users')");

  /**
   * Add basic system data
   */
  install_add_db_data ( "Users", array (
    array ( "Name" => "Administrator", "Username" => "admin", "Password" => "6eedcf62914377ce6c04d1cd39ed10bfcc41affd264da255715dbbbc9ee8dfdf", "Salt" => "d90171577077c7a5333a4032798343683b65190432d4dfb30edd8993e00b9411", "Iterations" => 40000, "Permissions" => "{\"administrator\":true,\"auditor\":true}", "Email" => "admin@voipdomain.io", "Since" => date ( "Y-m-d H:i:s"), "Language" => "en_US")
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
