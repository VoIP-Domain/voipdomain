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
 * VoIP Domain Khomp equipments module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Khomp
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Equipments database
 */
framework_add_hook ( "install_db", "equipments_khomp_install_db");

/**
 * Function to create Khomp equipments database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_khomp_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system data
   */
  install_add_db_data ( "Equipments", array (
    array ( "UID" => "ips40cc", "Vendor" => "Khomp", "Model" => "IPS 40 CC", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "khomp-ips40cc.png", "VendorLink" => "https://khomp.com/", "ModelLink" => "", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[]", "Active" => 0),
    array ( "UID" => "ips100", "Vendor" => "Khomp", "Model" => "IPS 100", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "", "VendorLink" => "https://khomp.com/", "ModelLink" => "", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[]", "Active" => 0),
    array ( "UID" => "ips102", "Vendor" => "Khomp", "Model" => "IPS 102", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "khomp-ips102.png", "VendorLink" => "https://khomp.com/", "ModelLink" => "", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[]", "Active" => 0),
    array ( "UID" => "ips108", "Vendor" => "Khomp", "Model" => "IPS 108", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "khomp-ips108.png", "VendorLink" => "https://khomp.com/", "ModelLink" => "", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[]", "Active" => 0),
    array ( "UID" => "ips200", "Vendor" => "Khomp", "Model" => "IPS 200", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Entry-level IP Phone with 2 lines. Note: Product reached EOL.", "Image" => "khomp-ips200.png", "VendorLink" => "https://khomp.com/", "ModelLink" => "", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 2, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"G722\",\"G723\",\"G729\",\"G726\",\"ILBC\",\"ALAW\",\"ULAW\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G722\",\"G729\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"User\":{\"Name\":\"user\",\"Password\":\"SWHc@h2FRSWj!\"},\"Admin\":{\"Name\":\"admin\",\"Password\":\"UIt$l)d*KQrZQ\"}}", "SupportedFirmwares" => "[{\"Version\":\"4.5.0.7\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"S2_IPS_290_KHOMP_290_MD5_version4.5.0.7-11810\",\"SHA256\":\"a638d36028eeedacb54278fddc61f0b79b48a90388d0d5d2b35a3f9806cf2175\",\"Size\":4345899,\"Available\":false},{\"Filename\":\"K2_uImage_100N_version2.7.4\",\"SHA256\":\"9e80e879289629861451378968d499d7da27f8716c3e30948dcd67253522a63b\",\"Size\":3179149,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "ips212", "Vendor" => "Khomp", "Model" => "IPS 212", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Entry-level IP Phone with 2 lines. Note: Product reached EOL.", "Image" => "khomp-ips212.png", "VendorLink" => "https://khomp.com/", "ModelLink" => "", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 2, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"User\":{\"Name\":\"user\",\"Password\":\"z5Aa^UN#9PrID\"},\"Admin\":{\"Name\":\"admin\",\"Password\":\"BeqHSMzE^nNp6\"}}", "SupportedFirmwares" => "[{\"Version\":\"4.6.0.0\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"S2_IPS_212_KHOMP_320V3_MD5_version4.6.0.0-12027\",\"SHA256\":\"ce90e6ae8dfb643f68df1de651d008a52ed15f4e4f287bfd93b035d3bbb238d8\",\"Size\":4276267,\"Available\":false},{\"Filename\":\"K2_uImage_100N_version2.7.4\",\"SHA256\":\"9e80e879289629861451378968d499d7da27f8716c3e30948dcd67253522a63b\",\"Size\":3179149,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "ips300", "Vendor" => "Khomp", "Model" => "IPS 300", "Type" => "IPP", "SupportLevel" => "None", "Description" => "", "Image" => "khomp-ips300.png", "VendorLink" => "https://khomp.com/", "ModelLink" => "", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[]", "Active" => 0)
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
