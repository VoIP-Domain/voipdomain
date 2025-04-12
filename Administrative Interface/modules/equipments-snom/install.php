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
 * VoIP Domain Snom equipments module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Snom
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Equipments database
 */
framework_add_hook ( "install_db", "equipments_snom_install_db");

/**
 * Function to create Snom equipments database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_snom_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system data
   */
  install_add_db_data ( "Equipments", array (
    array ( "UID" => "snom710", "Vendor" => "Snom", "Model" => "710", "Type" => "IPP", "SupportLevel" => "None", "Description" => "A business phone designed for HD audio, performance and affordability.", "Image" => "snom-710.png", "VendorLink" => "https://snom.com/", "ModelLink" => "https://www.snomamericas.com/en/pd/ip-phones/desk-phones/snom7xx-series/snom710", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 1, "Shortcuts" => 5, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"G722\",\"ULAW\",\"ALAW\",\"GSM\",\"G726\",\"G726AAL2\",\"G723\",\"G729\"]", "AudioCodecs" => "[\"G722\",\"ULAW\",\"ALAW\",\"GSM\",\"G726\",\"G726AAL2\",\"G723\",\"G729\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"Username\":\"admin\",\"Password\":\"vdadmin\",\"AdminModePassword\":\"vdadmin\"}", "SupportedFirmwares" => "[{\"Version\":\"8.9.3.80\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"snom710-8.9.3.80-SIP-r.bin\",\"SHA256\":\"7cb53f172382e2a85223d7462d3bc9a5cb9f6a2f78312f382135c6cd60326259\",\"Size\":13490384,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "snom720", "Vendor" => "Snom", "Model" => "720", "Type" => "IPP", "SupportLevel" => "None", "Description" => "A business phone designed for HD audio, performance and affordability.", "Image" => "snom-720.png", "VendorLink" => "https://snom.com/", "ModelLink" => "https://www.snomamericas.com/en/pd/ip-phones/desk-phones/snom7xx-series/snom710", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 1, "Shortcuts" => 18, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"G722\",\"ULAW\",\"ALAW\",\"GSM\",\"G726\",\"G726AAL2\",\"G723\",\"G729\"]", "AudioCodecs" => "[\"G722\",\"ULAW\",\"ALAW\",\"GSM\",\"G726\",\"G726AAL2\",\"G723\",\"G729\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"Username\":\"admin\",\"Password\":\"vdadmin\",\"AdminModePassword\":\"vdadmin\"}", "SupportedFirmwares" => "[{\"Version\":\"8.9.3.80\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"snom720-8.9.3.80-SIP-r.bin\",\"SHA256\":\"1ed573baacb57dc606257f3fb716ecf1895173d694110191eb3946db57c8228b\",\"Size\":22092256,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "snom870", "Vendor" => "Snom", "Model" => "870", "Type" => "IPP", "SupportLevel" => "None", "Description" => "A business phone designed for HD audio, performance and affordability.", "Image" => "snom-870.png", "VendorLink" => "https://snom.com/", "ModelLink" => "https://www.snomamericas.com/en/pd/ip-phones/desk-phones/snom8xx-series/snom870", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 1, "Shortcuts" => 15, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"G722\",\"ULAW\",\"ALAW\",\"GSM\",\"G726\",\"G726AAL2\",\"G723\",\"G729\"]", "AudioCodecs" => "[\"G722\",\"ULAW\",\"ALAW\",\"GSM\",\"G726\",\"G726AAL2\",\"G723\",\"G729\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"Username\":\"admin\",\"Password\":\"vdadmin\",\"AdminModePassword\":\"vdadmin\"}", "SupportedFirmwares" => "[{\"Version\":\"8.7.5.35\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"snom870-8.7.5.35-SIP-r.bin\",\"SHA256\":\"5a53b59058c346b6052d88f27e94e10d3fae550d361ec25835112150ba8f90fb\",\"Size\":25167056,\"Available\":false}]}]", "Active" => 0)
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
