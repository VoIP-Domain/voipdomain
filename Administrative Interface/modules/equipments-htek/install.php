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
 * VoIP Domain Htek equipments module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Htek
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Equipments database
 */
framework_add_hook ( "install_db", "equipments_htek_install_db");

/**
 * Function to create Htek equipments database structure.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_htek_install_db ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add basic system data
   */
  install_add_db_data ( "Equipments", array (
    array ( "UID" => "uc902sp", "Vendor" => "Htek", "Model" => "UC902SP", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Eco-entry Level Phone.", "Image" => "htek-uc902sp.png", "VendorLink" => "https://htek.com/", "ModelLink" => "https://www.htek.com/products/clientsfavourites/uc902sp/details/", "VideoSupport" => 0, "AutoProvision" => 1, "BLFSupport" => 1, "Accounts" => 2, "Shortcuts" => 2, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G722\",\"ILBC\",\"OPUS\",\"G726\",\"G723\",\"G729\"]", "AudioCodecs" => "[\"ULAW\",\"ALAW\",\"G722\",\"ILBC\",\"OPUS\",\"G726\"]", "SupportedVideoCodecs" => "[]", "VideoCodecs" => "[]", "ExtraSettings" => "{\"User\":{\"Name\":\"vduser\",\"Password\":\"vduser\"},\"Admin\":{\"Name\":\"vdadmin\",\"Password\":\"vdadmin\"}}", "SupportedFirmwares" => "[{\"Version\":\"2.42.6.5.45\",\"Priority\":0,\"Available\":false,\"Files\":[{\"Filename\":\"fw910M.rom\",\"SHA256\":\"8eb3405fd22ac9ee11d9cd73594af29a6590b15e31a5ae6949c93802ca8eecde\",\"Size\":10199606,\"Available\":false}]}]", "Active" => 0)
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
