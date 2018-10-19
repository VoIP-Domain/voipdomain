<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * VoIP Domain user login avatar plugin.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage User Avatar
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add avatar's filters
 */
framework_add_filter ( "authentication_success", "avatar_authentication_success");

/**
 * Function to add cookie that allow user to view their avatar at next
 * authentication. This is a security measure that avoid anyone to see user's
 * avatar (that maybe contain sensitive informations).
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Received data
 */
function avatar_authentication_success ( $buffer, $parameters)
{
  global $_in;

  /**
   * Verify if avatar is enabled to user browser, otherwise, add/update the cookie
   */
  if ( ! array_key_exists ( "vca", $_COOKIE) || ! preg_match ( "/(^|\|)" . $_in["session"]["AvatarID"] . "($|\|)/", $_COOKIE["vca"]))
  {
    setcookie ( "vca", ( array_key_exists ( "vca", $_COOKIE) && ! empty ( $_COOKIE["vca"]) ? $_COOKIE["vca"] . "|" : "") . $_in["session"]["AvatarID"], time () + 31536000, "/");
  }

  /**
   * Return incoming buffer
   */
  return $buffer;
}
?>
