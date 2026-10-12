<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain administrators module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Administrators
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create administrators database
 */
framework_add_hook ( "install_db", "admins_install_db");

/**
 * Function to create administrators database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_install_db ( $buffer, $parameters)
{
  /**
   * Add administrators tables
   */
  install_add_db_table ( "Admins", "CREATE TABLE `Admins` (\n" .
                                   "  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,\n" .
                                   "  `Name` varchar(255) NOT NULL,\n" .
                                   "  `Username` varchar(50) NOT NULL,\n" .
                                   "  `Password` char(64) NOT NULL,\n" .
                                   "  `Salt` char(64) NOT NULL,\n" .
                                   "  `Iterations` int(2) unsigned NOT NULL DEFAULT '40000',\n" .
                                   "  `Permissions` mediumblob,\n" .
                                   "  `Email` varchar(255) NOT NULL,\n" .
                                   "  `Since` datetime NOT NULL,\n" .
                                   "  `Language` varchar(255) DEFAULT '',\n" .
                                   "  PRIMARY KEY (`ID`),\n" .
                                   "  KEY `Username` (`Username`)\n" .
                                   ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tenants administration users';\n");
  install_add_db_table ( "AdminSFA", "CREATE TABLE `AdminSFA` (\n" .
                                     "  `UID` bigint(20) unsigned NOT NULL,\n" .
                                     "  `Key` char(32) NOT NULL,\n" .
                                     "  `Status` enum('Pending', 'Active') NOT NULL,\n" .
                                     "  UNIQUE KEY `UID` (`UID`),\n" .
                                     "  KEY `AdminSFA_ibfk_1` (`UID`),\n" .
                                     "  CONSTRAINT `AdminSFA_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `Admins` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                     ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tenant administration users SFA';\n", array ( "Admins"));
  install_add_db_table ( "AdminSessions", "CREATE TABLE `AdminSessions` (\n" .
                                          "  `SID` char(64) NOT NULL,\n" .
                                          "  `Admin` bigint(20) unsigned NOT NULL,\n" .
                                          "  `LastSeen` bigint(20) unsigned NOT NULL,\n" .
                                          "  PRIMARY KEY (`SID`),\n" .
                                          "  UNIQUE KEY `SID` (`SID`),\n" .
                                          "  KEY `AdminSessions_ibfk_1` (`Admin`),\n" .
                                          "  CONSTRAINT `AdminSessions_ibfk_1` FOREIGN KEY (`Admin`) REFERENCES `Admins` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                          ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='System tenant administrators sessions';\n", array ( "Admins"));

  /**
   * Add administrators triggers
   */
  install_add_db_trigger ( "AdminsInsert", "CREATE TRIGGER `AdminsInsert` AFTER INSERT ON `Admins` FOR EACH ROW CALL UpdateCache('Admins')");
  install_add_db_trigger ( "AdminsUpdate", "CREATE TRIGGER `AdminsUpdate` AFTER UPDATE ON `Admins` FOR EACH ROW CALL UpdateCache('Admins')");
  install_add_db_trigger ( "AdminsDelete", "CREATE TRIGGER `AdminsDelete` AFTER DELETE ON `Admins` FOR EACH ROW CALL UpdateCache('Admins')");
  install_add_db_trigger ( "AdminSFAInsert", "CREATE TRIGGER `AdminSFAInsert` AFTER INSERT ON `AdminSFA` FOR EACH ROW CALL UpdateCache('Admins')");
  install_add_db_trigger ( "AdminSFAUpdate", "CREATE TRIGGER `AdminSFAUpdate` AFTER UPDATE ON `AdminSFA` FOR EACH ROW CALL UpdateCache('Admins')");
  install_add_db_trigger ( "AdminSFADelete", "CREATE TRIGGER `AdminSFADelete` AFTER DELETE ON `AdminSFA` FOR EACH ROW CALL UpdateCache('Admins')");

  /**
   * Add default administrator
   */
  install_add_db_data ( "Admins", array (
    array ( "Name" => "Administrator", "Username" => "admin", "Password" => "6eedcf62914377ce6c04d1cd39ed10bfcc41affd264da255715dbbbc9ee8dfdf", "Salt" => "d90171577077c7a5333a4032798343683b65190432d4dfb30edd8993e00b9411", "Iterations" => 40000, "Permissions" => "[]", "Email" => "admin@voipdomain.io", "Since" => date ( "Y-m-d H:i:s"), "Language" => "en_US")
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
