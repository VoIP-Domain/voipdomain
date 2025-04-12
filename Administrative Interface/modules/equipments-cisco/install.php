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
 * VoIP Domain Cisco equipments module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Cisco
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Equipments database
 */
framework_add_hook ( "install_db", "equipments_cisco_install_db");

/**
 * Function to create Cisco equipments database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_cisco_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system data
   */
  install_add_db_data ( "Equipments", array (
    array ( "UID" => 3905, "Vendor" => "Cisco", "Model" => "3905", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Cost-effective entry-level Enterprise IP Phone.", "Image" => "cisco-3905.png", "VendorLink" => "https://cisco.com/", "ModelLink" => "https://www.cisco.com/c/en/us/support/collaboration-endpoints/unified-sip-phone-3905/model.html", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G729\",\"G722\"]", "AudioCodecs" => "[\"G729\",\"ULAW\",\"ALAW\",\"G722\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[{\"Version\":\"9.4(1)SR3\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"cmterm-3905.9-4-1SR3.zip\",\"SHA256\":\"bd73a08882905d4b272f076fbca4296c968877a3fd6989e0edc6ba54ff3e1028\",\"Size\":2845500,\"Available\":false},{\"Filename\":\"po-locale-en_GB-14.2.1.4001-1.tar\",\"SHA256\":\"0f2b15e2f18c38da621729734d8332bb26b08112474b2e753c5bb79a5b38a02d\",\"Size\":5847040,\"Available\":false},{\"Filename\":\"po-locale-pt_BR-14.2.1.4001-1.tar\",\"SHA256\":\"3b6b03e033316ca3428df37574f8ac5d44237cd3755c3a91ff32a4b7f39e239d\",\"Size\":5826560,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => 7811, "Vendor" => "Cisco", "Model" => "7811", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Easy-to-Use, Cost-Effective Voice VoIP Collaboration.", "Image" => "cisco-7811.png", "VendorLink" => "https://cisco.com/", "ModelLink" => "https://www.cisco.com/c/en/us/products/collaboration-endpoints/ip-phone-7811/index.html", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G729\",\"G722\"]", "AudioCodecs" => "[\"G729\",\"ULAW\",\"ALAW\",\"G722\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[{\"Version\":\"14.2(1)SR1\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"cmterm-78xx.11-7-1-17.zip\",\"SHA256\":\"ec6f0bd3dfc83340e6c04de402e0141590b2e5f7bc2ad5586fe3efe3c1ebfcb4\",\"Size\":72187435,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => 8961, "Vendor" => "Cisco", "Model" => "8961", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Easy-to-Use, Cost-Effective Voice VoIP Collaboration. Note: Product reached EOL.", "Image" => "cisco-8961.png", "VendorLink" => "https://cisco.com/", "ModelLink" => "https://www.cisco.com/c/en/us/obsolete/collaboration-endpoints/cisco-unified-ip-phone-8961.html", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G729\",\"G722\",\"G723\",\"ILBC\",\"G726\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G729\",\"G722\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "[]", "SupportedFirmwares" => "[{\"Version\":\"9.4(1)SR9\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"cmterm-8961.9-4-1-9_REL.zip\",\"SHA256\":\"bbc39ca7cd3667d83f1d6bc02ca64234729c511d52fe988b480e66e9312e9303\",\"Size\":36917542,\"Available\":false}]}]", "Active" => 0)
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
