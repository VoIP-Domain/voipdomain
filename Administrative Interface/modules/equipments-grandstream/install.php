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
 * VoIP Domain Grandstream equipments module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Grandstream
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Equipments database
 */
framework_add_hook ( "install_db", "equipments_grandstream_install_db");

/**
 * Function to create Grandstream equipments database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_grandstream_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system data
   */
  install_add_db_data ( "Equipments", array (
    array ( "UID" => "gxp1160", "Vendor" => "Grandstream", "Model" => "GXP1160", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Entry level IP business phone with 1 line.", "Image" => "grandstream-gxp1160.png", "VendorLink" => "https://grandstream.com/", "ModelLink" => "https://www.grandstream.com/products/ip-voice-telephony-gxp-series-ip-phones/gxp-series-high-end-ip-phones/product/gxp1160/1160", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 2, "Shortcuts" => 2, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}", "SupportedFirmwares" => "[{\"Version\":\"1.0.8.9\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"gxp1160fw.bin\",\"SHA256\":\"a21b5f354b6d36d06d67e12223f36cca846c5df55fd9b338a7e927fb807d73e1\",\"Size\":7015956,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "gxp1165", "Vendor" => "Grandstream", "Model" => "GXP1165", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Entry level IP business phone with 1 line.", "Image" => "grandstream-gxp1165.png", "VendorLink" => "https://grandstream.com/", "ModelLink" => "https://www.grandstream.com/products/ip-voice-telephony-gxp-series-ip-phones/gxp-series-high-end-ip-phones/product/gxp1160/1165", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 2, "Shortcuts" => 2, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}", "SupportedFirmwares" => "[{\"Version\":\"1.0.8.9\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"gxp1160fw.bin\",\"SHA256\":\"a21b5f354b6d36d06d67e12223f36cca846c5df55fd9b338a7e927fb807d73e1\",\"Size\":7015956,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "gxp1610", "Vendor" => "Grandstream", "Model" => "GXP1610", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "grandstream-gxp1610.png", "VendorLink" => "https://grandstream.com/", "ModelLink" => "https://www.grandstream.com/products/ip-voice-telephony-gxp-series-ip-phones/gxp-series-basic-ip-phones/product/gxp1610/gxp1610", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}", "SupportedFirmwares" => "[{\"Version\":\"1.0.7.64\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"gxp1600fw.bin\",\"SHA256\":\"3782efc4eb5ec1716f9ad67b9da651bdeb294e76972d612a323b11457ee397b2\",\"Size\":13734608,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "gxp1615", "Vendor" => "Grandstream", "Model" => "GXP1615", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "grandstream-gxp1615.png", "VendorLink" => "https://grandstream.com/", "ModelLink" => "https://www.grandstream.com/products/ip-voice-telephony-gxp-series-ip-phones/gxp-series-basic-ip-phones/product/gxp1610/gxp1615", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}", "SupportedFirmwares" => "[{\"Version\":\"1.0.7.64\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"gxp1600fw.bin\",\"SHA256\":\"3782efc4eb5ec1716f9ad67b9da651bdeb294e76972d612a323b11457ee397b2\",\"Size\":13734608,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "gxp1620", "Vendor" => "Grandstream", "Model" => "GXP1620", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "grandstream-gxp1620.png", "VendorLink" => "https://grandstream.com/", "ModelLink" => "https://www.grandstream.com/products/ip-voice-telephony-gxp-series-ip-phones/gxp-series-basic-ip-phones/product/gxp1620/gxp1620", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}", "SupportedFirmwares" => "[{\"Version\":\"1.0.7.64\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"gxp1600fw.bin\",\"SHA256\":\"3782efc4eb5ec1716f9ad67b9da651bdeb294e76972d612a323b11457ee397b2\",\"Size\":13734608,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "gxp1625", "Vendor" => "Grandstream", "Model" => "GXP1625", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "grandstream-gxp1625.png", "VendorLink" => "https://grandstream.com/", "ModelLink" => "https://www.grandstream.com/products/ip-voice-telephony-gxp-series-ip-phones/gxp-series-basic-ip-phones/product/gxp1620/gxp1625", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}", "SupportedFirmwares" => "[{\"Version\":\"1.0.7.64\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"gxp1600fw.bin\",\"SHA256\":\"3782efc4eb5ec1716f9ad67b9da651bdeb294e76972d612a323b11457ee397b2\",\"Size\":13734608,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "gxp1628", "Vendor" => "Grandstream", "Model" => "GXP1628", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "grandstream-gxp1628.png", "VendorLink" => "https://grandstream.com/", "ModelLink" => "https://www.grandstream.com/products/ip-voice-telephony-gxp-series-ip-phones/gxp-series-basic-ip-phones/product/gxp1628", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 2, "Shortcuts" => 8, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[]", "Active" => 0),
    array ( "UID" => "gxp1630", "Vendor" => "Grandstream", "Model" => "GXP1630", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "grandstream-gxp1630.png", "VendorLink" => "https://grandstream.com/", "ModelLink" => "https://www.grandstream.com/products/ip-voice-telephony-gxp-series-ip-phones/gxp-series-basic-ip-phones/product/gxp1630", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 2, "Shortcuts" => 8, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[]", "Active" => 0)
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
