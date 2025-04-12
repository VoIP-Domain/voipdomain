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
 * FastAGI Tellows anti spam score check module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk FastAGI Tellows Score Application
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * FastAGI application Tellows Score
 */
register_app ( "tellows_score", "tellows_score", array ( "title" => "Tellows Score Checker"));
framework_add_hook ( "tellows_score", "tellows_score");

/**
 * Function to check Tellows API the score of a number to detect spammers.
 *
 * Expected parameters:
 * Arg 1: Number (E.164 standard number)
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tellows_score ( $buffer, $parameters)
{
  // Update Tellows API key from data files:
  update_datafiles ( "configs-tellows");

  // Filter basic parameters:
  $num = preg_replace ( "/[^0-9+]/", "", $parameters["arg_1"]);

  // Check score only if it's an E.164 number
  if ( substr ( $num, 0, 1) != "+")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "SET VARIABLE \"TELLOWS_SCORE\" \"0\""));
  }

  // Check Tellows API:
  $ch = curl_init ();
  curl_setopt_array ( $ch, array (
    CURLOPT_URL => "https://www.tellows.de/basic/num/" . urlencode ( $num) . "?json=1&lang=en",
    CURLOPT_HTTPHEADER => array (
      "X-Auth-Token: " . fetch_config ( "db", "tellows")[0]["Key"]
    ),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 2,
    CURLOPT_TIMEOUT => 3
  ));
  $result = json_decode ( @curl_exec ( $ch), true);
  curl_close ( $ch);
  $data = array ( "SET VARIABLE \"TELLOWS_SCORE\" \"" . ( $result["tellows"]["score"] ? (int) $result["tellows"]["score"] : 0) . "\"");

  // Merge buffer and return data:
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
