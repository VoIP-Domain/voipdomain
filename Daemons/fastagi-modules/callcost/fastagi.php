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
 * FastAGI call cost application.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk FastAGI call cost application
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * FastAGI application call cost
 */
framework_add_hook ( "callcost", "callcost");

/**
 * Function to process call cost.
 *
 * Expected parameters:
 * Arg 1: Total bid time
 * Arg 2: Discard time
 * Arg 3: Minimum time
 * Arg 4: Fraction time
 * Arg 5: Cost ($/m)
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function callcost ( $buffer, $parameters)
{
  if ( (int) $parameters["arg_1"] >= (int) $parameters["arg_3"])
  {
    $cost = ( (float) $parameters["arg_5"] / 60) * ceil ( (int) $parameters["arg_1"] / (int) $parameters["arg_4"]) * (int) $parameters["arg_4"];
  } else {
    if ( (int) $parameters["arg_1"] > (int) $parameters["arg_2"])
    {
      $cost = ( (float) $parameters["arg_5"] / 60) * (int) $parameters["arg_3"];
    } else {
      $cost = 0;
    }
  }
  $data = array ();
  $data[] = "SET VARIABLE \"CDR(value)\" \"" . $cost . "\"";

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
