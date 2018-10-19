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
 * VoIP Domain auditory api module. This module add the api calls related to
 * auditory.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Auditory
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to list auditory authors
 */
framework_add_hook ( "auditory_authors", "auditory_authors");
framework_add_api_call ( "/auditory/authors", "Read", "auditory_authors", array ( "permissions" => array ( "auditor")));

/**
 * Function to generate users and tokens list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function auditory_authors ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Users", "Tokens"));

  /**
   * Search users
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users` " . ( ! empty ( $parameters["q"]) ? "WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' OR `User` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "' " : "") . "ORDER BY `Name`, `User`"))
  {
    while ( $user = $result->fetch_assoc ())
    {
      $data[] = array ( "u" . $user["ID"], $user["Name"] . " (" . __ ( "User") . ")");
    }
  }

  /**
   * Search tokens
   */
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` " . ( ! empty ( $parameters["q"]) ? "WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' " : "") . "ORDER BY `Description`, `Token`"))
  {
    while ( $token = $result->fetch_assoc ())
    {
      $data[] = array ( "t" . $token["ID"], $token["Description"] . " (" . __ ( "Token") . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate auditory records report
 */
framework_add_hook ( "auditory_report", "auditory_report");
framework_add_api_call ( "/auditory/report", "Read", "auditory_report", array ( "permissions" => array ( "auditor")));

/**
 * Function to generate auditory report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function auditory_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for basic parameters
   */
  if ( ( ! empty ( $parameters["author"]) && ! preg_match ( "/^(u|t|s)[0-9]+$/", $parameters["author"])) || empty ( $parameters["start"]) || empty ( $parameters["end"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanityze input data
   */
  $data = array ();
  $data["start"] = format_form_datetime ( $parameters["start"]);
  $data["end"] = format_form_datetime ( $parameters["end"]);
  $data["author"] = trim ( strip_tags ( $parameters["author"]));

  /**
   * Get auditory informations
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Auditory`.*, `Users`.`Name`, `Tokens`.`Description` FROM `Auditory` LEFT JOIN `Users` ON `Auditory`.`Author` = `Users`.`ID` AND `Auditory`.`Type` = 'U' LEFT JOIN `Tokens` ON `Auditory`.`Author` = `Tokens`.`ID` AND `Auditory`.`Type` = 'T' WHERE `Auditory`.`Date` >= '" . $_in["mysql"]["id"]->real_escape_string ( $data["start"]) . "' AND `Auditory`.`Date` <= '" . $_in["mysql"]["id"]->real_escape_string ( $data["end"]) . "'" . ( ! empty ( $data["author"]) ? " AND `Auditory`.`Author` = " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $data["author"], 1)) . " AND `Auditory`.`Type` = '" . $_in["mysql"]["id"]->real_escape_string ( strtoupper ( substr ( $data["author"], 0, 1))) . "'" : "")))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $result->fetch_assoc ())
  {
    $output[] = array ( format_db_timestamp ( $data["Date"]), format_db_datetime ( $data["Date"]), ( $data["Name"] != NULL ? $data["Name"] . " (" . __ ( "User") . ")" : $data["Description"] . " (" . __ ( "Token") . ")"), $data["Module"], $data["Function"], json_encode ( unserialize ( $data["Data"])));
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}
?>
