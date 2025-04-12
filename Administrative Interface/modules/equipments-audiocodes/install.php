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
 * VoIP Domain Audiocodes equipments module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Audiocodes
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Equipments database
 */
framework_add_hook ( "install_db", "equipments_audiocodes_install_db");

/**
 * Function to create Audiocodes equipments database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_audiocodes_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system data
   */
  install_add_db_data ( "Equipments", array (
    array ( "UID" => "310hd", "Vendor" => "AudioCodes", "Model" => "310HD", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Entry-level IP Phone with 1 line. Note: Product reached EOL.", "Image" => "audiocodes-310hd.png", "VendorLink" => "https://audiocodes.com/", "ModelLink" => "https://audiocodes.com/", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"G722\",\"ULAW\",\"ALAW\",\"G729\",\"ILBC\",\"G726\",\"OPUS\"]", "AudioCodecs" => "[\"G729\",\"ALAW\",\"ULAW\",\"G722\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"User\":{\"Name\":\"user\",\"Password\":\"vduser\"},\"Admin\":{\"Name\":\"admin\",\"Password\":\"vdadmin\"}}", "SupportedFirmwares" => "[{\"Version\":\"53.84.0.160\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"53.84.0.160.rom\",\"SHA256\":\"3cc074a609817601df1d7e2e186958fddb101b60957d95df6ddeb51c36108d7f\",\"Size\":8189344,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "405hd", "Vendor" => "AudioCodes", "Model" => "405HD", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Cost-effective phone with GbE.", "Image" => "audiocodes-405hd.png", "VendorLink" => "https://audiocodes.com/", "ModelLink" => "https://audiocodes.com/solutions-products/products/ip-phones/405hd-ip-phone", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"G722\",\"ULAW\",\"ALAW\",\"G729\",\"ILBC\",\"G726\",\"OPUS\"]", "AudioCodecs" => "[\"G729\",\"ALAW\",\"ULAW\",\"G722\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"User\":{\"Name\":\"user\",\"Password\":\"vduser\"},\"Admin\":{\"Name\":\"admin\",\"Password\":\"vdadmin\"}}", "SupportedFirmwares" => "[{\"Version\":\"2.2.16.694\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"405HD_2.2.16.694.img\",\"SHA256\":\"1c0d214f0f3fa663b0083918b0f9526d661d0e6c8dc201e4ef396be8a86f2398\",\"Size\":7584647,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "440hd", "Vendor" => "AudioCodes", "Model" => "440HD", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Entry-level IP Phone with 1 line. Note: Product reached EOL.", "Image" => "audiocodes-440hd.png", "VendorLink" => "https://audiocodes.com/", "ModelLink" => "https://audiocodes.com/", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"G722\",\"ULAW\",\"ALAW\",\"G729\",\"ILBC\",\"G726\",\"OPUS\"]", "AudioCodecs" => "[\"G729\",\"ALAW\",\"ULAW\",\"G722\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"User\":{\"Name\":\"user\",\"Password\":\"vduser\"},\"Admin\":{\"Name\":\"admin\",\"Password\":\"vdadmin\"}}", "SupportedFirmwares" => "[{\"Version\":\"53.84.0.160\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"53.84.0.160.rom\",\"SHA256\":\"3cc074a609817601df1d7e2e186958fddb101b60957d95df6ddeb51c36108d7f\",\"Size\":8189344,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "450hd", "Vendor" => "AudioCodes", "Model" => "450HD", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Executive high-end business phone with GbE. Note: Product reached EOL.", "Image" => "audiocodes-450hd.png", "VendorLink" => "https://audiocodes.com/", "ModelLink" => "https://audiocodes.com/", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 0, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ALAW\",\"ULAW\",\"G722\",\"SILK8\",\"SILK16\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G722\",\"SILK8\",\"SILK16\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"User\":{\"Name\":\"user\",\"Password\":\"vduser\"},\"Admin\":{\"Name\":\"admin\",\"Password\":\"vdadmin\"}}", "SupportedFirmwares" => "[{\"Version\":\"3.4.8.808\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"450HD_3_4_8_808.img\",\"SHA256\":\"e6a2879dfcff59125be4a4f2a72c44efc1a8e3883de393d203710f65b7363c03\",\"Size\":47535544,\"Available\":false}]}]", "Active" => 0)
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
