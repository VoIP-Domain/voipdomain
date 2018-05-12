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
 * VoIP Domain extensions country actions module. This module add the Asterisk
 * dialplan for a specific country.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Phones
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "dialplan_country_BR", "dialplan_country_BR");

/**
 * Function to return the Brazilian telephony valid dialing numbers.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialplan_country_BR ( $buffer, $parameters)
{
  // Brazil dialing structure
  $data = array ();

  // Emergency numbers:
  $data[] = array ( "Pattern" => "100", "Label" => "Services: Emergency", "Kind" => "Services", "Emergency" => true);
  $data[] = array ( "Pattern" => "128", "Label" => "Services: Emergency", "Kind" => "Services", "Emergency" => true);
  $data[] = array ( "Pattern" => "153", "Label" => "Services: Emergency", "Kind" => "Services", "Emergency" => true);
  $data[] = array ( "Pattern" => "19X", "Label" => "Services: Emergency", "Kind" => "Services", "Emergency" => true);

  // Service numbers:
  $data[] = array ( "Pattern" => "10[1247-9]", "Label" => "Services", "Kind" => "Services", "Emergency" => false);
  $data[] = array ( "Pattern" => "1[124-9]X", "Label" => "Services", "Kind" => "Services", "Emergency" => false);
  $data[] = array ( "Pattern" => "13[0-24-689]", "Label" => "Services", "Kind" => "Services", "Emergency" => false);
  $data[] = array ( "Pattern" => "17[0-35-9]", "Label" => "Services", "Kind" => "Services", "Emergency" => false);
  $data[] = array ( "Pattern" => "105X", "Label" => "Services", "Kind" => "Services", "Emergency" => false);
  $data[] = array ( "Pattern" => "133X", "Label" => "Services", "Kind" => "Services", "Emergency" => false);
  $data[] = array ( "Pattern" => "174X", "Label" => "Services", "Kind" => "Services", "Emergency" => false);
  $data[] = array ( "Pattern" => "10[36]XX", "Label" => "Services", "Kind" => "Services", "Emergency" => false);

  // Landline numbers:
  $data[] = array ( "Pattern" => "[2-5]XXXXXXX", "Label" => "Local landline", "Kind" => "Local Landline", "Emergency" => false);
  $data[] = array ( "Pattern" => "0ZZ[2-5]XXXXXXX", "Label" => "Interstate landline", "Kind" => "Interstate Landline", "Emergency" => false);

  // Mobile numbers:
  $data[] = array ( "Pattern" => "[6-9]XXXXXXXX", "Label" => "Local mobile", "Kind" => "Local Mobile", "Emergency" => false);
  $data[] = array ( "Pattern" => "0ZZ[6-9]XXXXXXXX", "Label" => "Interstate mobile", "Kind" => "Interstate Mobile", "Emergency" => false);

  // Tollfree:
  $data[] = array ( "Pattern" => "0800XXXXXXX", "Label" => "Tollfree", "Kind" => "Tollfree", "Emergency" => false);

  // Premium rate numbers:
  $data[] = array ( "Pattern" => "0[359]00XXXXXXX", "Label" => "Premium Rate Number", "Kind" => "PRN", "Emergency" => false);

  // International:
  $data[] = array ( "Pattern" => "00.", "Label" => "International", "Kind" => "International", "Emergency" => false);

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
