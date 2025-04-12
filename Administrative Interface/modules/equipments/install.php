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
 * VoIP Domain equipments module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Equipments database
 */
framework_add_hook ( "install_db", "equipments_install_db");

/**
 * Function to create equipments database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system tables
   */
  install_add_db_table ( "Equipments", "CREATE TABLE `Equipments` (\n" .
                                       "  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,\n" .
                                       "  `UID` varchar(255) NOT NULL,\n" .
                                       "  `Vendor` varchar(255) NOT NULL,\n" .
                                       "  `Model` varchar(255) NOT NULL,\n" .
                                       "  `Type` enum('IPP', 'IPVP', 'IPCP', 'IPM', 'ATA', 'SOFTPHONE', 'WEBPHONE') NOT NULL,\n" .
                                       "  `SupportLevel` enum('None', 'Basic', 'Complete', 'Premium') NOT NULL,\n" .
                                       "  `Description` mediumblob NOT NULL,\n" .
                                       "  `Image` varchar(255) NOT NULL,\n" .
                                       "  `VendorLink` varchar(255) NOT NULL,\n" .
                                       "  `ModelLink` varchar(255) NOT NULL,\n" .
                                       "  `VideoSupport` boolean NOT NULL,\n" .
                                       "  `AutoProvision` boolean NOT NULL,\n" .
                                       "  `BLFSupport` boolean NOT NULL,\n" .
                                       "  `Accounts` int(1) unsigned NOT NULL,\n" .
                                       "  `Shortcuts` int(2) unsigned NOT NULL,\n" .
                                       "  `Extensions` int(2) unsigned NOT NULL,\n" .
                                       "  `ShortcutsPerExtension` int(2) unsigned NOT NULL,\n" .
                                       "  `SupportedAudioCodecs` mediumblob NOT NULL,\n" .
                                       "  `AudioCodecs` mediumblob NOT NULL,\n" .
                                       "  `SupportedVideoCodecs` mediumblob NOT NULL,\n" .
                                       "  `VideoCodecs` mediumblob NOT NULL,\n" .
                                       "  `SupportedFirmwares` mediumblob NOT NULL,\n" .
                                       "  `Active` boolean NOT NULL DEFAULT false,\n" .
                                       "  `ExtraSettings` mediumblob NOT NULL,\n" .
                                       "  PRIMARY KEY (`ID`),\n" .
                                       "  UNIQUE KEY `UID` (`UID`),\n" .
                                       "  KEY `Vendor` (`Vendor`),\n" .
                                       "  KEY `Model` (`Model`)\n" .
                                       ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n");

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "EquipmentsInsert", "CREATE TRIGGER `EquipmentsInsert` AFTER INSERT ON `Equipments` FOR EACH ROW CALL UpdateCache('Equipments')");
  install_add_db_trigger ( "EquipmentsUpdate", "CREATE TRIGGER `EquipmentsUpdate` AFTER UPDATE ON `Equipments` FOR EACH ROW CALL UpdateCache('Equipments')");
  install_add_db_trigger ( "EquipmentsDelete", "CREATE TRIGGER `EquipmentsDelete` AFTER DELETE ON `Equipments` FOR EACH ROW CALL UpdateCache('Equipments')");

  /**
   * Add basic system data
   */
  install_add_db_data ( "Equipments", array (
    array ( "UID" => "webphone", "Vendor" => "VoIP Domain", "Model" => "Web Phone", "Type" => "WEBPHONE", "SupportLevel" => "Premium", "Description" => "Web based IP phone.", "Image" => "", "VendorLink" => "https://voipdomain.io/", "ModelLink" => "", "VideoSupport" => true, "AutoProvision" => false, "BLFSupport" => false, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"OPUS\",\"ALAW\",\"ULAW\"]", "AudioCodecs" => "[\"OPUS\",\"ALAW\",\"ULAW\"]", "SupportedVideoCodecs" => "[\"VP8\",\"H264\"]", "VideoCodecs" => "[\"VP8\",\"H264\"]", "SupportedFirmwares" => "[]", "Active" => true, "ExtraSettings" => "[]"),
    array ( "UID" => "softphone", "Vendor" => "VoIP Domain", "Model" => "SIP Phone", "Type" => "SOFTPHONE", "SupportLevel" => "Basic", "Description" => "Generic SIP phone.", "Image" => "", "VendorLink" => "https://voipdomain.io/", "ModelLink" => "", "VideoSupport" => true, "AutoProvision" => false, "BLFSupport" => false, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"OPUS\",\"ALAW\",\"ULAW\",\"G722\",\"G729\",\"GSM\",\"ILBC\",\"G723\",\"G726\",\"G726AAL2\",\"EVS\",\"SPEEX\",\"SPEEX16\",\"SPEEX32\",\"SIREN7\",\"SIREN14\",\"ADPCM\",\"SILK8\",\"SILK12\",\"SILK16\",\"SILK24\",\"G729A\",\"G719\",\"SLIN\",\"SLIN12\",\"SLIN16\",\"SLIN24\",\"SLIN32\",\"SLIN44\",\"SLIN48\",\"SLIN96\",\"SLIN192\",\"LPC10\",\"AMR\",\"AMRWB\"]", "AudioCodecs" => "[\"OPUS\",\"ALAW\",\"ULAW\",\"G722\",\"G729\",\"GSM\",\"ILBC\",\"G723\",\"G726\",\"G726AAL2\"]", "SupportedVideoCodecs" => "[\"VP8\",\"H264\",\"H261\",\"H263\",\"H263P\",\"H264P\",\"H265\",\"VP9\",\"MPEG4\"]", "VideoCodecs" => "[\"VP8\",\"H263\",\"H264\",\"MPEG4\"]", "SupportedFirmwares" => "[]", "Active" => true, "ExtraSettings" => "[]")
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
