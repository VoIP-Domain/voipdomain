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
 * VoIP Domain equipments configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Audiocodes
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Function to check if equipment model exists.
 *
 * @global array $_in Framework global configuration variable
 * @param string $model The model to be checked
 * @param string[optional] $vendor Limit to this vendor only. If not specified,
 *                                 search in all vendors.
 * @return boolean If the model exists
 */
function equipment_model_exists ( $model, $vendor = "")
{
  global $_in;

  if ( $vendor == "")
  {
    foreach ( $_in["equipments"]["vendors"] as $vendor)
    {
      foreach ( $_in["equipments"]["vendors"][$vendor] as $temp)
      {
        if ( $model == $temp)
        {
          return true;
        }
      }
    }
  } else {
    foreach ( $_in["equipments"]["vendors"][$vendor] as $temp)
    {
      if ( $model == $temp)
      {
        return true;
      }
    }
  }

  // If reach here, model was not found
  return false;
}

/**
 * Function to check if equipment vendor exists.
 *
 * @global array $_in Framework global configuration variable
 * @param string $vendor The vendor to be checked
 * @return boolean If the vendor exists
 */
function equipment_vendor_exists ( $vendor)
{
  global $_in;

  foreach ( $_in["equipments"]["vendors"] as $temp)
  {
    if ( $vendor == $temp)
    {
      return true;
    }
  }

  // If reach here, vendor was not found
  return false;
}
?>
