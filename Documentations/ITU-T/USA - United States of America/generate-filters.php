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
$level = 6;
do
{
  echo ( $level + 1) . " ";
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
  $level--;
  ksort ( $db, SORT_STRING);
  $continue == sizeof ( $toreduce) > 0;
  unset ( $toreduce);
} while ( $continue);
krsort ( $db, SORT_NUMERIC);
echo "Done!\n";

/**
 * Generate VoIP Domain PHP file header
 */
$header = "<?php\n";
$header .= "/**   ___ ___       ___ _______     ______                        __\n";
$header .= " *   |   Y   .-----|   |   _   |   |   _  \\ .-----.--------.---.-|__.-----.\n";
$header .= " *   |.  |   |  _  |.  |.  1   |   |.  |   \\|  _  |        |  _  |  |     |\n";
$header .= " *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|\n";
$header .= " *   |:  1   |     |:  |:  |       |:  1    /\n";
$header .= " *    \\:.. ./      |::.|::.|       |::.. . /\n";
$header .= " *     `---'       `---`---'       `------'\n";
$header .= " *\n";
$header .= " * Copyright (C) 2016-2025 Ernani José Camargo Azevedo\n";
$header .= " *\n";
$header .= " * This program is free software: you can redistribute it and/or modify\n";
$header .= " * it under the terms of the GNU General Public License as published by\n";
$header .= " * the Free Software Foundation, either version 3 of the License, or\n";
$header .= " * (at your option) any later version.\n";
$header .= " *\n";
$header .= " * This program is distributed in the hope that it will be useful,\n";
$header .= " * but WITHOUT ANY WARRANTY; without even the implied warranty of\n";
$header .= " * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n";
$header .= " * GNU General Public License for more details.\n";
$header .= " *\n";
$header .= " * You should have received a copy of the GNU General Public License\n";
$header .= " * along with this program.  If not, see <https://www.gnu.org/licenses/>.\n";
$header .= " */\n";
$header .= "\n";
$header .= "/**\n";
$header .= " * VoIP Domain country database filter module. This module add the filter calls\n";
$header .= " * related to country database of United States of America.\n";
$header .= " *\n";
$header .= " * Reference: https://www.itu.int/oth/T02020000DE/en (2006-11-22)\n";
$header .= " *            https://www.nationalpooling.com/reports/region/AllBlocksAugmentedReport.zip (" . date ( "Y-m-d") . ")\n";
$header .= " *\n";
$header .= " * Glossary:\n";
$header .= " *  CC - Country Code\n";
$header .= " *  NDC - National Destination Code (also known as area code)\n";
$header .= " *  N(S)N - National (Significant) Number\n";
$header .= " *  SN - Subscriber Number\n";
$header .= " *\n";
$header .= " * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>\n";
$header .= " * @version    1.0\n";
$header .= " * @package    VoIP Domain\n";
$header .= " * @subpackage CountryDB\n";
$header .= " * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.\n";
$header .= " * @license    https://www.gnu.org/licenses/gpl-3.0.en.html\n";
$header .= " */\n";
$header .= "\n";

/**
 * Function to write NANPA NDC file.
 *
 * @globa string $header VoIP Domain PHP file header
 * @param string $ndc NANPA 3 digits NDC code
 * @param array $ndcdb NANPA NDC database array
 * @return boolean If output file was sucessfully written
 */
function write_nanpa_ndc ( $ndc, $ndcdb)
{
  global $header;

  /**
   * Check NDC parameter
   */
  if ( ! preg_match ( "/^\d\d\d$/", $ndc))
  {
    return false;
  }

  /**
   * Create NANPA NDC file code
   */
  $output = $header;
  $output .= "/**\n";
  $output .= " * E.164 United States of America NDC " . $ndc . " country hook\n";
  $output .= " */\n";
  $output .= "framework_add_filter ( \"e164_identify_NANPA_" . $ndc . "\", \"e164_identify_NANPA_" . $ndc . "\");\n";
  $output .= "\n";
  $output .= "/**\n";
  $output .= " * E.164 North American area number identification hook. This hook is an\n";
  $output .= " * e164_identify sub hook, called when the ISO3166 Alpha3 are \"USA\" (code for\n";
  $output .= " * United States of America). This hook will verify if phone number is valid,\n";
  $output .= " * returning the area code, area name, phone number, others number related\n";
  $output .= " * information and if possible, the number type (mobile, landline, Premium Rate\n";
  $output .= " * Number, etc).\n";
  $output .= " *\n";
  $output .= " * @param string \$buffer Buffer from plugin system if processed by other function\n";
  $output .= " *                       before\n";
  $output .= " * @param array \$parameters Parameters to the function. Number provided as\n";
  $output .= " *                          \$parameters[\"Number\"]\n";
  $output .= " * @return array Array contaning many information about the requested number\n";
  $output .= " */\n";
  $output .= "function e164_identify_NANPA_" . $ndc . " ( \$buffer, \$parameters)\n";
  $output .= "{\n";
  $output .= "  /**\n";
  $output .= "   * Check if number country code is from United States of America at NDC " . $ndc . " area\n";
  $output .= "   */\n";
  $output .= "  if ( substr ( \$parameters[\"Number\"], 0, 5) != \"+1" . $ndc . "\")\n";
  $output .= "  {\n";
  $output .= "    return ( is_array ( \$buffer) ? \$buffer : false);\n";
  $output .= "  }\n";
  $output .= "\n";
  $output .= "  \$prefixes = array (\n";
  $output .= substr ( $ndcdb, 0, strlen ( $ndcdb) - 1);
  $output .= "  );\n";
  $output .= "  foreach ( \$prefixes as \$prefix => \$data)\n";
  $output .= "  {\n";
  $output .= "    if ( (int) substr ( \$parameters[\"Number\"], 5, strlen ( \$prefix)) == \$prefix)\n";
  $output .= "    {\n";
  $output .= "      return array_merge_recursive ( is_array ( \$buffer) ? \$buffer : array (), \$data);\n";
  $output .= "    }\n";
  $output .= "  }\n";
  $output .= "  return array_merge_recursive ( is_array ( \$buffer) ? \$buffer : array (), array ( \"Area\" => \$data[\"Area\"]));\n";
  $output .= "}\n";

  /**
   * Write NDC file
   */
  return file_put_contents ( "filter-NANPA-" . $ndc . ".php", $output);
}

/**
 * Generate each NDC for United States of America
 */
echo "Generating and writing filter script for each NDC...";
$current = "";
$ndcdb = "";
foreach ( $db as $npanxx => $data)
{
  if ( $current != substr ( $npanxx, 0, 3))
  {
    if ( $current != "")
    {
      write_nanpa_ndc ( $current, $ndcdb);
      $ndcdb = "";
    }
    $current = substr ( $npanxx, 0, 3);
    echo " " . $current;
  }
  $ndcdb .= "    \"" . substr ( $npanxx, 3) . "\" => array ( \"Area\" => \"" . addslashes ( array_key_exists ( $data["Area"], $locations) ? $locations[$data["Area"]] : $data["Area"]) . "\", \"City\" => \"" . addslashes ( array_key_exists ( $data["City"], $rates[$data["Area"]]) ? $rates[$data["Area"]][$data["City"]] : $data["City"]) . "\", \"Operator\" => \"" . addslashes ( $ocn[$data["OCN"]]) . "\"),\n";
}
write_nanpa_ndc ( $current, $ndcdb);
echo " Done!\n";

/**
 * Generate countrydb for United States of America
 */
echo "Generating filter script... ";
$output = $header;
$output .= "/**\n";
$output .= " * E.164 United States of America country hook\n";
$output .= " */\n";
$output .= "framework_add_filter ( \"e164_identify_country_USA\", \"e164_identify_country_USA\");\n";
$output .= "\n";
$output .= "/**\n";
$output .= " * E.164 North American area number identification hook. This hook is an\n";
$output .= " * e164_identify sub hook, called when the ISO3166 Alpha3 are \"USA\" (code for\n";
$output .= " * United States of America). This hook will verify if phone number is valid,\n";
$output .= " * returning the area code, area name, phone number, others number related\n";
$output .= " * information and if possible, the number type (mobile, landline, Premium Rate\n";
$output .= " * Number, etc).\n";
$output .= " *\n";
$output .= " * @param string \$buffer Buffer from plugin system if processed by other function\n";
$output .= " *                       before\n";
$output .= " * @param array \$parameters Parameters to the function. Number provided as\n";
$output .= " *                          \$parameters[\"Number\"]\n";
$output .= " * @return array Array contaning many information about the requested number\n";
$output .= " */\n";
$output .= "function e164_identify_country_USA ( \$buffer, \$parameters)\n";
$output .= "{\n";
$output .= "  /**\n";
$output .= "   * Check if number country code is from United States of America\n";
$output .= "   */\n";
$output .= "  if ( substr ( \$parameters[\"Number\"], 0, 2) != \"+1\")\n";
$output .= "  {\n";
$output .= "    return ( is_array ( \$buffer) ? \$buffer : false);\n";
$output .= "  }\n";
$output .= "\n";
$output .= "  /**\n";
$output .= "   * Our check are splitted into NDC due to large number of entries\n";
$output .= "   */\n";
$output .= "  if ( ! framework_has_hook ( \"e164_identify_NANPA_\" . substr ( \$parameters[\"Number\"], 2, 3)) && is_readable ( \"filter-NANPA-\" . substr ( \$parameters[\"Number\"], 2, 3) . \".php\"))\n";
$output .= "  {\n";
$output .= "    require_once ( \"filter-NANPA-\" . substr ( \$parameters[\"Number\"], 2, 3) . \".php\");\n";
$output .= "    \$data = filters_call ( \"e164_identify_NANPA_\" . substr ( \$parameters[\"Number\"], 2, 3), \$parameters);\n";
$output .= "    if ( is_array ( \$data) && sizeof ( \$data) != 0)\n";
$output .= "    {\n";
$output .= "      return array_merge_recursive ( is_array ( \$buffer) ? \$buffer : array (), array ( \"CC\" => \"1\", \"NDC\" => substr ( \$parameters[\"Number\"], 2, 3), \"Country\" => \"United States of America\", \"Area\" => \$data[\"Area\"], \"City\" => \$data[\"City\"], \"Operator\" => \$data[\"Operator\"], \"SN\" => substr ( \$parameters[\"Number\"], 5), \"Type\" => VD_PHONETYPE_LANDLINE + VD_PHONETYPE_MOBILE, \"CallFormats\" => array ( \"Local\" => \"(\" . substr ( \$parameters[\"Number\"], 2, 3) . \") \" . substr ( \$parameters[\"Number\"], 5, 3) . \"-\" . substr ( \$parameters[\"Number\"], 8), \"International\" => \"+1 \" . substr ( \$parameters[\"Number\"], 2, 3) . \" \" . substr ( \$parameters[\"Number\"], 5))));\n";
$output .= "    }\n";
$output .= "  }\n";
$output .= "\n";
$output .= "  /**\n";
$output .= "   * Check for toll-free numbers\n";
$output .= "   */\n";
$output .= "  switch ( substr ( \$parameters[\"Number\"], 2, 3))\n";
$output .= "  {\n";
$output .= "    case \"800\":\n";
$output .= "    case \"822\":\n";
$output .= "    case \"833\":\n";
$output .= "    case \"844\":\n";
$output .= "    case \"855\":\n";
$output .= "    case \"866\":\n";
$output .= "    case \"877\":\n";
$output .= "    case \"880\":\n";
$output .= "    case \"881\":\n";
$output .= "    case \"882\":\n";
$output .= "    case \"883\":\n";
$output .= "    case \"884\":\n";
$output .= "    case \"885\":\n";
$output .= "    case \"886\":\n";
$output .= "    case \"887\":\n";
$output .= "    case \"888\":\n";
$output .= "    case \"889\":\n";
$output .= "      return array_merge_recursive ( is_array ( \$buffer) ? \$buffer : array (), array ( \"CC\" => \"1\", \"NDC\" => substr ( \$parameters[\"Number\"], 2, 3), \"Country\" => \"United States of America\", \"Area\" => \"\", \"City\" => \"\", \"Operator\" => \"\", \"SN\" => substr ( \$parameters[\"Number\"], 5), \"Type\" => VD_PHONETYPE_TOLLFREE, \"CallFormats\" => array ( \"Local\" => \"(\" . substr ( \$parameters[\"Number\"], 2, 3) . \") \" . substr ( \$parameters[\"Number\"], 5, 3) . \"-\" . substr ( \$parameters[\"Number\"], 8), \"International\" => \"+1 \" . substr ( \$parameters[\"Number\"], 2, 3) . \" \" . substr ( \$parameters[\"Number\"], 5))));\n";
$output .= "      break;\n";
$output .= "  }\n";
$output .= "\n";
$output .= "  /**\n";
$output .= "   * Check for premium rate numbers\n";
$output .= "   */\n";
$output .= "  if ( substr ( \$parameters[\"Number\"], 2, 3) == \"900\")\n";
$output .= "  {\n";
$output .= "    return array_merge_recursive ( is_array ( \$buffer) ? \$buffer : array (), array ( \"CC\" => \"1\", \"NDC\" => substr ( \$parameters[\"Number\"], 2, 3), \"Country\" => \"United States of America\", \"Area\" => \"\", \"City\" => \"\", \"Operator\" => \"\", \"SN\" => substr ( \$parameters[\"Number\"], 5), \"Type\" => VD_PHONETYPE_PRN, \"CallFormats\" => array ( \"Local\" => \"(\" . substr ( \$parameters[\"Number\"], 2, 3) . \") \" . substr ( \$parameters[\"Number\"], 5, 3) . \"-\" . substr ( \$parameters[\"Number\"], 8), \"International\" => \"+1 \" . substr ( \$parameters[\"Number\"], 2, 3) . \" \" . substr ( \$parameters[\"Number\"], 5))));\n";
$output .= "  }\n";
$output .= "\n";
$output .= "  /**\n";
$output .= "   * If reached here, number wasn't identified as a valid Honduras phone number\n";
$output .= "   */\n";
$output .= "  return ( is_array ( \$buffer) ? \$buffer : false);\n";
$output .= "}\n";
$output .= "?>\n";
echo "Done!\n";

/**
 * Write script file
 */
echo "Writing script file \"filter-USA.php\"... ";
file_put_contents ( "filter-USA.php", $output);
unset ( $output);
echo "Done!\n";

/**
 * Finished!
 */
echo "\n";
echo "Finished!\n";
?>
