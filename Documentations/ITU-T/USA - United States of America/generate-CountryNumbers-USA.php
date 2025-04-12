#!/usr/bin/php -q
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
 * United States of America telephony system standard has many data that can be
 * processed from many places. This script fetch the main database that contains
 * the NDC and NSN as thousand number with location and network operator name,
 * process it and generate the final filter to the United States of America.
 * The main database was fetched from
 * https://www.nationalpooling.com/reports/region/AllBlocksAugmentedReport.zip
 * extract from zip and processed as CSV file.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR & E_USER_ERROR);
ini_set ( "display_errors", "false");

/**
 * Check if script is running from CLI
 */
if ( ! defined ( "STDIN"))
{
  echo "This script must be executed into CLI!\n";
  exit ( 1);
}

/**
 * Show software version header
 */
echo chr ( 27) . "[1;37mVoIP Domain NANPA Thousands Database Extract Tool" . chr ( 27) . "[1;0m v1.0\n";
echo "\n";

/**
 * Data of United States of America
 */
$countrycode = 840;
$prefixes = array ();

/**
 * Fetch thousand blocks database
 */
echo "Downloading thousands blocks database... ";
$socket = curl_init ();
curl_setopt ( $socket, CURLOPT_URL, "https://www.nationalpooling.com/reports/region/AllBlocksAugmentedReport.zip");
curl_setopt ( $socket, CURLOPT_USERAGENT, "VoIP Domain Client v1.0 (Linux; U)");
curl_setopt ( $socket, CURLOPT_RETURNTRANSFER, true);
curl_setopt ( $socket, CURLOPT_TIMEOUT, 10);
curl_setopt ( $socket, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt ( $socket, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec ( $socket);
$status = curl_getinfo ( $socket, CURLINFO_HTTP_CODE);
curl_close ( $socket);
if ( $status != 200)
{
  if ( $status == 0)
  {
    echo "Timed out!\n";
  } else {
    echo "Returned HTTP code " . $status . "!\n";
  }
  exit ( 1);
}
echo "Done!\n";
echo "Writting database to disk... ";
if ( ! file_put_contents ( "AllBlocksAugmentedReport.zip", $result))
{
  echo "Error writing file!\n";
  exit ( 2);
}
unset ( $status);
unset ( $socket);
unset ( $result);
echo "Done!\n";

/**
 * Expand zip file
 */
echo "Expanding zip file... ";
$zip = new ZipArchive;
if ( $zip->open ( "AllBlocksAugmentedReport.zip") === true)
{
  $zip->extractTo ( "./", array ( "AllBlocksAugmentedReport.txt"));
  $zip->close ();
} else {
  echo "Invalid zip file!\n";
  exit ( 3);
}
unset ( $zip);
echo "Done!\n";

/**
 * Open database file
 */
echo "Openning extracted database file... ";
if ( ! $fp = fopen ( "AllBlocksAugmentedReport.txt", "r"))
{
  echo "Cannot open file!\n";
  exit ( 4);
}
echo "Done!\n";

/**
 * Import data files
 */
require_once ( "db-locations.inc.php");
require_once ( "db-ratecenters.inc.php");

/**
 * Process database file
 */
echo "Parsing database file... ";
$db = array ();
$ocn = array ();
while ( $entry = fgetcsv ( $fp))
{
  if ( $entry[0] == "Region")
  {
    continue;
  }
  if ( $entry[5] == "AS")
  {
    if ( ! array_key_exists ( $entry[13], $ocn))
    {
      $ocn[$entry[13]] = $entry[12];
    }
    $db[$entry[2] . $entry[3] . $entry[4]] = array ( "Area" => $entry[1], "City" => $entry[9], "OCN" => $entry[13]);
  }
}
ksort ( $db);
echo "Done!\n";

/**
 * Reduce database for NPA-NXX allocated to one OCN only to optimize system
 */
echo "Computing redundancy entries... ";
$toreduce = array ();
foreach ( $db as $npanxx => $data)
{
  if ( substr ( $npanxx, $level) == "0")
  {
    $current = substr ( $npanxx, 0, $level);
    $unique = true;
    for ( $x = 0; $x <= 9; $x++)
    {
      if ( ! array_key_exists ( $current . $x, $db) || array_diff ( $db[$current . $x], $data))
      {
        $unique = false;
      }
    }
    if ( $unique)
    {
      $toreduce[] = $current;
    }
  }
}
foreach ( $toreduce as $index)
{
  $db[$index] = $db[$index . "0"];
  unset ( $db[$index . "0"]);
  unset ( $db[$index . "1"]);
  unset ( $db[$index . "2"]);
  unset ( $db[$index . "3"]);
  unset ( $db[$index . "4"]);
  unset ( $db[$index . "5"]);
  unset ( $db[$index . "6"]);
  unset ( $db[$index . "7"]);
  unset ( $db[$index . "8"]);
  unset ( $db[$index . "9"]);
}
ksort ( $db, SORT_STRING);
unset ( $toreduce);
echo "Done!\n";

/**
 * Write SQL file
 */
echo "Writing script file \"CountryNumbers-USA.sql\"... ";
if ( ! $fp = fopen ( "CountryNumbers-USA.sql", "w"))
{
  echo "Error: Cannot write to file!\n";
  exit ( 1);
}

/**
 * Create SQL database file header
 */
$output = "--    ___ ___       ___ _______     ______                        __\n";
$output .= "--   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.\n";
$output .= "--   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |\n";
$output .= "--   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|\n";
$output .= "--   |:  1   |     |:  |:  |       |:  1    /\n";
$output .= "--    \:.. ./      |::.|::.|       |::.. . /\n";
$output .= "--     `---'       `---`---'       `------'\n";
$output .= "--\n";
$output .= "-- Copyright (C) 2016-2025 Ernani José Camargo Azevedo\n";
$output .= "--\n";
$output .= "-- This program is free software: you can redistribute it and/or modify\n";
$output .= "-- it under the terms of the GNU General Public License as published by\n";
$output .= "-- the Free Software Foundation, either version 3 of the License, or\n";
$output .= "-- (at your option) any later version.\n";
$output .= "--\n";
$output .= "-- This program is distributed in the hope that it will be useful,\n";
$output .= "-- but WITHOUT ANY WARRANTY; without even the implied warranty of\n";
$output .= "-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n";
$output .= "-- GNU General Public License for more details.\n";
$output .= "--\n";
$output .= "-- You should have received a copy of the GNU General Public License\n";
$output .= "-- along with this program.  If not, see <https://www.gnu.org/licenses/>.\n";
$output .= "--\n";
$output .= "\n";
$output .= "--\n";
$output .= "-- VoIP Domain country database filters. This module add the filter calls\n";
$output .= "-- related to country database of United States of America..\n";
$output .= "--\n";
$output .= "-- Reference: https://www.itu.int/oth/T02020000DE/en (2006-11-22)\n";
$output .= "--            https://www.nationalpooling.com/reports/region/AllBlocksAugmentedReport.zip (2020-06-09)\n";
$output .= "--\n";
$output .= "-- @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>\n";
$output .= "-- @version    1.0\n";
$output .= "-- @package    VoIP Domain\n";
$output .= "-- @subpackage CountryDB\n";
$output .= "-- @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.\n";
$output .= "-- @license    https://www.gnu.org/licenses/gpl-3.0.en.html\n";
$output .= "--\n";
$output .= "\n";
fwrite ( $fp, $output);

/**
 * Write each database entry
 */
fwrite ( $fp, "INSERT INTO `CountryNumbers` (`Country`, `NumberRegex`, `Data`) VALUES ");
$records = 0;
foreach ( $db as $npanxx => $data)
{
  $records++;
  $prefixes[] = "1" . $npanxx;
  fwrite ( $fp, "(" . $countrycode . ", '^\\\\+1" . $npanxx . str_repeat ( "[0-9]", 10 - strlen ( $npanxx)) . "\$', '{\"CC\":1,\"NDC\":\"{2,3}\",\"SN\":\"{5,}\",\"Area\":\"" . addslashes ( array_key_exists ( $data["Area"], $locations) ? $locations[$data["Area"]] : $data["Area"]) . "\",\"City\":\"" . addslashes ( array_key_exists ( $data["City"], $rates[$data["Area"]]) ? $rates[$data["Area"]][$data["City"]] : $data["City"]) . "\",\"Operator\":\"" . addslashes ( $ocn[$data["OCN"]]) . "\",\"Type\":3,\"CallFormats\":{\"Local\":\"({2,3}) {5,3}-{8,}\",\"International\":\"+1 {2,3} {5,}\"}}')" . ( $records % 100 != 0 ? ", " : ";\nINSERT INTO `CountryNumbers` (`Country`, `NumberRegex`, `Data`) VALUES "));
}

/**
 * Write toll-free numbers
 */
$prefixes[] = "18";
fwrite ( $fp, "(" . $countrycode . ", '^\\\\+18(00|22|33|44|55|66|77|8[0-9])[0-9][0-9][0-9][0-9][0-9][0-9]\$', '{\"CC\":1,\"NDC\":\"{2,3}\",\"SN\":\"{5,}\",\"Area\":\"\",\"City\":\"\",\"Operator\":\"\",\"Type\":64,\"CallFormats\":{\"Local\":\"({2,3}) {5,3}-{8,}\",\"International\":\"+1 {2,3} {5,}\"}}'), ");

/**
 * Write premium rate numbers
 */
$prefixes[] = "1900";
fwrite ( $fp, "(" . $countrycode . ", '^\\\\+1900[0-9][0-9][0-9][0-9][0-9][0-9]\$', '{\"CC\":1,\"NDC\":\"{2,3}\",\"SN\":\"{5,}\",\"Area\":\"\",\"City\":\"\",\"Operator\":\"\",\"Type\":16,\"CallFormats\":{\"Local\":\"({2,3}) {5,3}-{8,}\",\"International\":\"+1 {2,3} {5,}\"}}');\n");
echo "Done\n";

/**
 * Write prefixes
 */
echo "Writting prefixes... ";
$output = "INSERT INTO `CountryCodes` (`Country`, `Code`, `Prefix`) VALUES ";
$records = 0;
$prefixes = array_unique ( $prefixes);
$last = sizeof ( $prefixes);
foreach ( $prefixes as $prefix)
{
  if ( $prefix != "1")
  {
    $output .= "(" . $countrycode . ", '1', '" . addslashes ( $prefix) . "')";
    if ( $records == $last)
    {
      $output .= ";\n";
    } else {
      $output .= $records % 100 != 0 ? ", " : ";\nINSERT INTO `CountryCodes` (`Country`, `Code`, `Prefix`) VALUES ";
    }
  }
}
fwrite ( $fp, $output);
fclose ( $fp);
echo "Done!\n";

/**
 * Finished!
 */
echo "\n";
echo "Finished!\n";
?>
