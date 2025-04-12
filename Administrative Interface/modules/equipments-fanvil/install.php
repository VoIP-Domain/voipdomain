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
 * VoIP Domain Fanvil equipments module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Fanvil
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Equipments database
 */
framework_add_hook ( "install_db", "equipments_fanvil_install_db");

/**
 * Function to create Fanvil equipments database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_fanvil_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system data
   */
  install_add_db_data ( "Equipments", array (
    array ( "UID" => "x1sp", "Vendor" => "Fanvil", "Model" => "X1S(P)", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Entry-level IP Phone with 2 lines.", "Image" => "fanvil-x1sp.png", "VendorLink" => "https://fanvil.com/", "ModelLink" => "https://fanvil.com/products/p1/x/20220413/7344.html", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 2, "Shortcuts" => 2, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"G726\",\"OPUS\",\"ULAW\",\"ALAW\",\"G729\",\"ILBC\",\"G722\"]", "AudioCodecs" => "[\"ALAW\",\"ULAW\",\"G722\",\"G726\",\"G729\",\"OPUS\",\"ILBC\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"User\":{\"Name\":\"user\",\"Password\":\"vduser\"},\"Admin\":{\"Name\":\"admin\",\"Password\":\"vdadmin\"}}", "SupportedFirmwares" => "[{\"Version\":\"2.4.12.2\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"x1sp-5914-RECOVERY-P0.18.23.1.23-2.4.12.2-1179T2022-08-05-21.56.57.z\",\"SHA256\":\"6c4f8e73aa3f9388997331b1057737285e4973dfe2533a1d1bbdd8cfbaaa5671\",\"Size\":10861354,\"Available\":false}]}]", "Active" => 0),
    array ( "UID" => "x3sp", "Vendor" => "Fanvil", "Model" => "X3S(P)", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Entry-level IP Phone with 2 lines.", "Image" => "fanvil-x3sp.png", "VendorLink" => "https://fanvil.com/", "ModelLink" => "https://fanvil.com/products/p1/x/20220413/7342.html", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 2, "Shortcuts" => 2, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"G726\",\"ULAW\",\"ALAW\",\"G729\",\"G722\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G729\",\"G722\",\"G726\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"User\":{\"Name\":\"user\",\"Password\":\"vduser\"},\"Admin\":{\"Name\":\"admin\",\"Password\":\"vdadmin\"}}", "SupportedFirmwares" => "[{\"Version\":\"2.4.12.2\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"x3s2.14.0.7387T20220824101038.z\",\"SHA256\":\"da255a2b27a95adfa845f01796c8a0a7203bd78d8a1323faf8e66ce5e25c175b\",\"Size\":3922923,\"Available\":false}]}]", "Active" => 0)
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
