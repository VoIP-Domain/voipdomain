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
 * processed from many places. The location of a central office exchange station
 * uses an abbreviated name. This script get the database from
 * https://www.nationalpooling.com/reports/NPA-RateCenterReports/index.htm
 * converted to CSV and extract the translated abbreviations to complete
 * location name.
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
echo chr ( 27) . "[1;37mVoIP Domain NANPA Rate Center Extract Tool" . chr ( 27) . "[1;0m v1.0\n";
echo "\n";

/**
 * Check for database csv file
 */
echo "Parsing database file... ";
if ( ! is_readable ( "NpaRateCenterReport.csv"))
{
  echo "Error: Cannot read file \"NpaRateCenterReport.csv\"!\n";
  exit ( 1);
}
if ( ! $fp = @fopen ( "NpaRateCenterReport.csv", "r"))
{
  echo "Error: Cannot open file \"NpaRateCenterReport.csv\" for read!\n";
  exit ( 2);
}

/**
 * Create output file
 */
$output = "<?php\n";
$output .= "/**   ___ ___       ___ _______     ______                        __\n";
$output .= " *   |   Y   .-----|   |   _   |   |   _  \\ .-----.--------.---.-|__.-----.\n";
$output .= " *   |.  |   |  _  |.  |.  1   |   |.  |   \\|  _  |        |  _  |  |     |\n";
$output .= " *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|\n";
$output .= " *   |:  1   |     |:  |:  |       |:  1    /\n";
$output .= " *    \\:.. ./      |::.|::.|       |::.. . /\n";
$output .= " *     `---'       `---`---'       `------'\n";
$output .= " *\n";
$output .= " * Copyright (C) 2016-2025 Ernani José Camargo Azevedo\n";
$output .= " *\n";
$output .= " * This program is free software: you can redistribute it and/or modify\n";
$output .= " * it under the terms of the GNU General Public License as published by\n";
$output .= " * the Free Software Foundation, either version 3 of the License, or\n";
$output .= " * (at your option) any later version.\n";
$output .= " *\n";
$output .= " * This program is distributed in the hope that it will be useful,\n";
$output .= " * but WITHOUT ANY WARRANTY; without even the implied warranty of\n";
$output .= " * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n";
$output .= " * GNU General Public License for more details.\n";
$output .= " *\n";
$output .= " * You should have received a copy of the GNU General Public License\n";
$output .= " * along with this program.  If not, see <https://www.gnu.org/licenses/>.\n";
$output .= " */\n";
$output .= "\n";
$output .= "\$rates = array ();\n";
$states = array ();
while ( $entry = fgetcsv ( $fp))
{
  if ( $entry[0] == "State")
  {
    continue;
  }
  if ( ! in_array ( $entry[0], $states))
  {
    $states[] = $entry[0];
    $output .= "\$rates[\"" . $entry[0] . "\"] = array ();\n";
  }
  $location = ucwords ( strtolower ( $entry[3]));
  $location = preg_replace ( "/( |^)St /", "$1Saint ", $location);
  $location = preg_replace ( "/( |^)U S /", "$1U. S. ", $location);
  $location = preg_replace ( "/( |^)U\.s\.( |$)/", "$1U. S.$2", $location);
  $location = preg_replace ( "/( |^)Afs( |$)/", "$1Air Force Station$2", $location);
  $location = preg_replace ( "/ \(a/", " (A", $location);
  $location = preg_replace ( "/ \(b/", " (B", $location);
  $location = preg_replace ( "/ \(c/", " (C", $location);
  $location = preg_replace ( "/ \(d/", " (D", $location);
  $location = preg_replace ( "/ \(e/", " (E", $location);
  $location = preg_replace ( "/ \(f/", " (F", $location);
  $location = preg_replace ( "/ \(g/", " (G", $location);
  $location = preg_replace ( "/ \(h/", " (H", $location);
  $location = preg_replace ( "/ \(i/", " (I", $location);
  $location = preg_replace ( "/ \(j/", " (J", $location);
  $location = preg_replace ( "/ \(k/", " (K", $location);
  $location = preg_replace ( "/ \(l/", " (L", $location);
  $location = preg_replace ( "/ \(m/", " (M", $location);
  $location = preg_replace ( "/ \(n/", " (N", $location);
  $location = preg_replace ( "/ \(o/", " (O", $location);
  $location = preg_replace ( "/ \(p/", " (P", $location);
  $location = preg_replace ( "/ \(q/", " (Q", $location);
  $location = preg_replace ( "/ \(r/", " (R", $location);
  $location = preg_replace ( "/ \(s/", " (S", $location);
  $location = preg_replace ( "/ \(t/", " (T", $location);
  $location = preg_replace ( "/ \(u/", " (U", $location);
  $location = preg_replace ( "/ \(v/", " (V", $location);
  $location = preg_replace ( "/ \(w/", " (W", $location);
  $location = preg_replace ( "/ \(x/", " (X", $location);
  $location = preg_replace ( "/ \(y/", " (Y", $location);
  $location = preg_replace ( "/ \(z/", " (Z", $location);
  $output .= "\$rates[\"" . $entry[0] . "\"][\"" . addslashes ( $entry[2]) . "\"] = \"" . str_replace ( "\"", "\\\"", str_replace ( "\\", "\\\\", $location)) . "\";\n";
}
$output .= "?>\n";
fclose ( $fp);
echo "Done!\n";

/**
 * Save rate translation file
 */
echo "Writing \"db-ratecenters.inc.php\" file... ";
file_put_contents ( "db-ratecenters.inc.php", $output);
echo "Done!\n";
echo "\n";
echo "Finished.\n";
?>
