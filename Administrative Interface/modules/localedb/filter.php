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
 * VoIP Domain locale filter module. This module add the filter calls related
 * to locales database.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage LocaleDB
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add locale's filters
 */
framework_add_filter ( "get_locale", "get_locale");

/**
 * Function to check if locale exist and return all information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function get_locale ( $buffer, $parameters)
{
  global $_in;

  /**
   * Search locale
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Locales` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "'"))
  {
    return $buffer;
  }
  if ( $result->num_rows != 1)
  {
    return $buffer;
  }
  $locale = $result->fetch_assoc ();
  $locale["NameEN"] = $locale["Name"];
  $locale["Name"] = ( array_key_exists ( $locale["Code"], $_in["locales"][$_in["general"]["language"]]) ? $_in["locales"][$_in["general"]["language"]][$locale["Code"]] : $locale["NameEN"]);

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $locale);
}
?>
