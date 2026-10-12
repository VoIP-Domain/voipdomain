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
 * VoIP Domain Polycom equipments module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Polycom
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Equipments database
 */
framework_add_hook ( "install_db", "equipments_polycom_install_db");

/**
 * Function to create Polycom equipments database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_polycom_install_db ( $buffer, $parameters)
{
  /**
   * Add basic system data
   */
  install_add_db_data ( "Equipments", array (
    array ( "UID" => "spip301", "Vendor" => "Polycom", "Model" => "SoundPoint IP 301", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Entry-level single-line IP phone. Note: Product reached EOL.", "Image" => "polycom-spip301.png", "VendorLink" => "https://www.poly.com/", "ModelLink" => "https://www.poly.com/", "VideoSupport" => False, "AutoProvision" => True, "BLFSupport" => False, "Accounts" => 1, "Shortcuts" => 0, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G729\",\"G722\"]", "SupportedVideoCodecs" => "[]", "SupportedFirmwares" => "[]"),
    array ( "UID" => "spip430", "Vendor" => "Polycom", "Model" => "SoundPoint IP 430", "Type" => "IPP", "SupportLevel" => "Basic", "Description" => "Two-line IP phone with display. Note: Product reached EOL.", "Image" => "polycom-spip430.png", "VendorLink" => "https://www.poly.com/", "ModelLink" => "https://www.poly.com/", "VideoSupport" => False, "AutoProvision" => True, "BLFSupport" => True, "Accounts" => 2, "Shortcuts" => 2, "Extensions" => 0, "ShortcutsPerExtension" => 0, "SupportedAudioCodecs" => "[\"ULAW\",\"ALAW\",\"G729\",\"G722\",\"G726\"]", "SupportedVideoCodecs" => "[]", "SupportedFirmwares" => "[]")
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
