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
 * VoIP Domain Snom equipments models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Snom
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_model_snom710_configure", "equipment_model_snom_basic_configure");
framework_add_hook ( "equipment_model_snom710_firmware_add", "equipment_model_snom_add_firmware");
framework_add_hook ( "equipment_model_snom710_firmware_remove", "equipment_model_snom_remove_firmware");
framework_add_hook ( "ap_type_snom710", "ap_type_snom_7X0");
framework_add_hook ( "ap_type_snom710_remove", "ap_type_snom_remove");
framework_add_hook ( "account_type_snom710", "account_type_snom_audio");

framework_add_hook ( "equipment_model_snom720_configure", "equipment_model_snom_basic_configure");
framework_add_hook ( "equipment_model_snom720_firmware_add", "equipment_model_snom_add_firmware");
framework_add_hook ( "equipment_model_snom720_firmware_remove", "equipment_model_snom_remove_firmware");
framework_add_hook ( "ap_type_snom720", "ap_type_snom_7X0");
framework_add_hook ( "ap_type_snom720_remove", "ap_type_snom_remove");
framework_add_hook ( "account_type_snom720", "account_type_snom_audio");

framework_add_hook ( "equipment_model_snom870_configure", "equipment_model_snom_basic_configure");
framework_add_hook ( "equipment_model_snom870_firmware_add", "equipment_model_snom_add_firmware");
framework_add_hook ( "equipment_model_snom870_firmware_remove", "equipment_model_snom_remove_firmware");
framework_add_hook ( "ap_type_snom870", "ap_type_snom_870");
framework_add_hook ( "ap_type_snom870_remove", "ap_type_snom_remove");
framework_add_hook ( "account_type_snom870", "account_type_snom_audio");

/**
 * Cleanup functions
 */
framework_add_hook ( "ap_snom_wipe", "ap_snom_wipe");
cleanup_register ( "Accounts-Snom", "ap_snom_wipe", array ( "Before" => array ( "Accounts")));

framework_add_hook ( "firmware_snom_wipe", "firmware_snom_wipe");
cleanup_register ( "Firmwares-Snom", "firmware_snom_wipe", array ( "Before" => array ( "Equipments")));

/**
 * Function to wipe the Snom equipment models auto provisioning files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function ap_snom_wipe ( $buffer, $parameters)
{
  // Remove auto provisioning configuration files:
  foreach ( list_config ( "datafile", "ap") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( equipment_model_exists ( $data["Type"], "Snom"))
    {
      unlink_config ( "ap", strtolower ( $data["Type"] . "-" . $data["MAC"]) . ".htm");
      unlink_config ( "datafile", $filename);
    }
  }

  return $buffer;
}

/**
 * Function to wipe the Snom equipment models firmware files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function firmware_snom_wipe ( $buffer, $parameters)
{
  // Snom 710
  unlink_config ( "ap", "Snom/710/snom710-8.9.3.80-SIP-r.bin");
  unlink_config ( "ap", "Snom/710/firmware.xml");
  unlink_config ( "apdir", "Snom/710");

  // Snom 720
  unlink_config ( "ap", "Snom/720/snom720-8.9.3.80-SIP-r.bin");
  unlink_config ( "ap", "Snom/720/firmware.xml");
  unlink_config ( "apdir", "Snom/720");

  // Snom 870
  unlink_config ( "ap", "Snom/870/snom870-8.7.5.35-SIP-r.bin");
  unlink_config ( "ap", "Snom/870/firmware.xml");
  unlink_config ( "apdir", "Snom/870");

  // Snom vendor directory
  unlink_config ( "apdir", "Snom");

  return $buffer;
}

/**
 * Function to configure the basic Snom equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_snom_basic_configure ( $buffer, $parameters)
{
  // Process audio codecs array
  $codecs = array ();
  foreach ( $parameters["AudioCodecs"] as $codec)
  {
    switch ( $codec)
    {
      case "G722":
        $codecs[] = "g722";
        break;
      case "ULAW":
        $codecs[] = "ulaw";
        break;
      case "ALAW":
        $codecs[] = "alaw";
        break;
      case "GSM":
        $codecs[] = "gsm";
        break;
      case "G726":
        $codecs[] = "g726";
        break;
      case "G726AAL2":
        $codecs[] = "g726aal2";
        break;
      case "G723":
        $codecs[] = "g723";
        break;
      case "G729":
        $codecs[] = "g729";
        break;
      default:
        writeLog ( "Invalid audio codec \"" . $codec . "\" to model \"" . $parameters["UID"] . "\"!", VoIP_LOG_WARNING);
        break;
    }
  }

  // Process video codecs array
  foreach ( $parameters["VideoCodecs"] as $codec)
  {
    writeLog ( "Invalid video codec \"" . $codec . "\" to model \"" . $parameters["UID"] . "\"!", VoIP_LOG_WARNING);
  }

  // Return softphone SIP model template
  return "[vd_equipment_" . $parameters["UID"] . "]\n" .
         "allow=!all," . implode ( ",", $codecs) . "\n" . $buffer;
}

/**
 * Function to add a Snom firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_snom_add_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "snom710-8.9.3.80-SIP-r.bin":	// Snom 710
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Snom/710") && ! mkdir ( $_in["general"]["tftpdir"] . "/Snom/710", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      $firmwarefile = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
      $firmwarefile .= "<firmware-settings>";
      $firmwarefile .= "  <firmware perm=\"\">tftp://" . $_in["general"]["address"] . "/Snom/710/snom710-8.9.3.80-SIP-r.bin</firmware>";
      $firmwarefile .= "</firmware-settings>";
      if ( file_put_contents ( $_in["general"]["tftpdir"] . "/Snom/710/" . $parameters["Filename"], $parameters["Data"]) === false || file_put_contents ( $_in["general"]["tftpdir"] . "/Snom/710/firmware.xml", $firmwarefile) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "snom720-8.9.3.80-SIP-r.bin":	// Snom 720
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Snom/720") && ! mkdir ( $_in["general"]["tftpdir"] . "/Snom/720", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      $firmwarefile = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
      $firmwarefile .= "<firmware-settings>";
      $firmwarefile .= "  <firmware perm=\"\">tftp://" . $_in["general"]["address"] . "/Snom/720/snom720-8.9.3.80-SIP-r.bin</firmware>";
      $firmwarefile .= "</firmware-settings>";
      if ( file_put_contents ( $_in["general"]["tftpdir"] . "/Snom/720/" . $parameters["Filename"], $parameters["Data"]) === false || file_put_contents ( $_in["general"]["tftpdir"] . "/Snom/720/firmware.xml", $firmwarefile) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "snom870-8.7.5.35-SIP-r.bin":	// Snom 870
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Snom/870") && ! mkdir ( $_in["general"]["tftpdir"] . "/Snom/870", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      $firmwarefile = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
      $firmwarefile .= "<firmware-settings>";
      $firmwarefile .= "  <firmware perm=\"\">tftp://" . $_in["general"]["address"] . "/Snom/870/snom870-8.7.5.35-SIP-r.bin</firmware>";
      $firmwarefile .= "</firmware-settings>";
      if ( file_put_contents ( $_in["general"]["tftpdir"] . "/Snom/870/" . $parameters["Filename"], $parameters["Data"]) === false || file_put_contents ( $_in["general"]["tftpdir"] . "/Snom/870/firmware.xml", $firmwarefile) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to remove a Snom firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_snom_remove_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "snom710-8.9.3.80-SIP-r.bin":	// Snom 710
      if ( ! check_config ( "datafile", "snom710"))
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Snom/710/" . $parameters["Filename"]);
        @unlink ( $_in["general"]["tftpdir"] . "/Snom/710/firmware.xml");
        @rmdir ( $_in["general"]["tftpdir"] . "/Snom/710");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "snom720-8.9.3.80-SIP-r.bin":	// Snom 720
      if ( ! check_config ( "datafile", "snom720"))
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Snom/720/" . $parameters["Filename"]);
        @unlink ( $_in["general"]["tftpdir"] . "/Snom/720/firmware.xml");
        @rmdir ( $_in["general"]["tftpdir"] . "/Snom/720");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "snom870-8.7.5.35-SIP-r.bin":	// Snom 870
      if ( ! check_config ( "datafile", "snom870"))
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Snom/870/" . $parameters["Filename"]);
        @unlink ( $_in["general"]["tftpdir"] . "/Snom/870/firmware.xml");
        @rmdir ( $_in["general"]["tftpdir"] . "/Snom/870");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to create the Snom 710/720 auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_snom_7X0 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "snom710":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-snom-710.xml");
      break;
    case "snom720":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-snom-720.xml");
      break;
    default:
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
  }

  // Get equipment system configuration:
  $equipmentconfig = read_config ( "datafile", "equipment-" . $parameters["Type"]);

  // Equipment MAC:
  $content = str_replace ( "{{{mac}}}", strtolower ( $parameters["MAC"]), $content);

  // Account configuration:
  $content = str_replace ( "{{{username}}}", $parameters["Username"], $content);
  $content = str_replace ( "{{{password}}}", $parameters["Password"], $content);
  $content = str_replace ( "{{{displayname}}}", $parameters["Name"], $content);
  $content = str_replace ( "{{{server1addr}}}", $_in["general"]["address"], $content);
  $content = str_replace ( "{{{server1port}}}", $_in["general"]["port"], $content);
  $content = str_replace ( "{{{domain}}}", $parameters["Domain"], $content);

  // Configure equipment CODEC's:
  $codecconfig = "";
  $codecs = array (
    "G722" => "g722",
    "ULAW" => "pcma",
    "ALAW" => "pcmu",
    "GSM" => "gsm",
    "G726" => "g726-32",
    "G726AAL2" => "all2-g726-32",
    "G723" => "g723",
    "G729" => "g729"
  );
  foreach ( $equipmentconfig["AudioCodecs"] as $codec)
  {
    $codecconfig .= $codecs[$codec] . ",";
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig . ",telephone-event", $content);

  // Set phone language:
  $language = "";
  switch ( $parameters["Country"])
  {
    // Catalan:
    case "AD": // Andorra
      $language = "Catalan";
      break;

    // Croatian:
    case "HR": // Croatia
    case "BA": // Bosnia and Herzegovina
    case "ME": // Montenegro
    case "MK": // Macedonia (the former Yugoslav Republic of)
    case "RO": // Romania
    case "RS": // Serbia
    case "SI": // Slovenia
      $language = "Bosanski";
      break;

    // Danish
    case "DK": // Denmark
    case "FO": // Faroe Islands
      $language = "Dansk";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "Deutsch";
      break;

    // Czech:
    case "CZ": // Czech Republic
      $language = "Čeština";
      break;

    // Spanish:
    case "AR": // Argentina
    case "CO": // Colombia
    case "ES": // Spain
    case "PE": // Peru
    case "VE": // Venezuela
    case "CL": // Chile
    case "GT": // Guatemala
    case "EC": // Ecuador
    case "BO": // Bolivia
    case "CU": // Cuba
    case "DO": // Dominican Republic
    case "HN": // Honduras
    case "PY": // Paraguay
    case "SV": // El Salvador
    case "NI": // Nicaragua
    case "CR": // Costa Rica
    case "PA": // Panama
    case "UY": // Uruguay
    case "GQ": // Equatorial Guinea
    case "PR": // Puerto Rico
    case "MX": // Mexico
      $language = "Español";
      break;

    // Finnish:
    case "FI": // Finland
      $language = "Suomi";
      break;

    // Estonian:
    case "EE": // Estonia
      $language = "Estonian";
      break;

    // French:
    case "BJ": // Benin
    case "BF": // Burkina Faso
    case "CG": // Congo
    case "CD": // Congo (Democratic Republic of the)
    case "GA": // Gabon
    case "GN": // Guinea
    case "MC": // Monaco
    case "NE": // Niger
    case "SN": // Senegal
    case "TG": // Togo
    case "FR": // France
      $language = "Francais";
      break;

    // Hebrew:
    case "IL": // Israel
      $language = "Hebrew";
      break;

    // Hungarian:
    case "HU": // Hungary
    case "AT": // Austria
    case "SK": // Slovakia
      $language = "Hungarian";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "Italiano";
      break;

    // Dutch:
    case "BE": // Belgium
    case "NL": // Netherlands
    case "AW": // Aruba
    case "CW": // Curaçao
    case "SX": // Sint Maarten (Dutch part)
      $language = "Dutch";
      break;

    // Norwegian:
    case "NO": // Norway
      $language = "Norsk";
      break;

    // Polish:
    case "PL": // Poland
      $language = "Polski";
      break;

    // Portuguese:
    case "AO": // Angola
    case "BR": // Brazil
    case "CV": // Cabo Verde
    case "GW": // Guinea-Bissau
    case "TL": // Timor-Leste
    case "MO": // Macao
    case "MZ": // Mozambique
    case "PT": // Portugal
    case "ST": // Sao Tome and Principe
      $language = "Português";
      break;

    // Slovenian:
    case "SI": // Slovenia
      $language = "Slovenščina";
      break;

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = "Russian";
      break;

    // Swedish:
    case "SE": // Sweden
    case "AX": // Åland Islands
      $language = "Svenska";
      break;

    // Turkish:
    case "TR": // Turkey
      $language = "Turkce";
      break;

    // British English:
    case "GB": // Great Britain
      $language = "English(UK)";
      break;

    // English (default):
    default:
      $language = "English";
      break;
  }
  $content = str_replace ( "{{{language}}}", string2isonumericentities ( $language), $content);

  // Set phone country tone:
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $phonetones = "AUS";
      break;
    case "AT": // Austria
      $phonetones = "AUT";
      break;
    case "CN": // China
      $phonetones = "CHN";
      break;
    case "CZ": // Czech
      $phonetones = "CZE";
      break;
    case "DK": // Denmark
      $phonetones = "DNK";
      break;
    case "FR": // France
      $phonetones = "FRA";
      break;
    case "DE": // Germany
      $phonetones = "GER";
      break;
    case "GB": // Great Britain
      $phonetones = "GBR";
      break;
    case "IN": // India
      $phonetones = "IND";
      break;
    case "IT": // Italy
      $phonetones = "ITA";
      break;
    case "JP": // Japan
      $phonetones = "JPN";
      break;
    case "MX": // Mexico
      $phonetones = "MEX";
      break;
    case "NL": // Netherlands
      $phonetones = "NLD";
      break;
    case "NO": // Norway
      $phonetones = "NOR";
      break;
    case "NZ": // New Zealand
      $phonetones = "NZL";
      break;
    case "ES": // Spain
      $phonetones = "ESP";
      break;
    case "SE": // Sweden
      $phonetones = "SWE";
      break;
    case "CH": // Switzerland
      $phonetones = "SWI";
      break;
    default: // United States of America
      $phonetones = "USA";
      break;
  }
  $content = str_replace ( "{{{phonetones}}}", $phonetones, $content);

  // Create dialplan:
  $dialplan = "";
  $emergency = "";
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( $dialplanentry["Emergency"])
    {
      $emergency .= " " . $dialplanentry["Pattern"];
    }
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    $dialplan .= "|^" . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\$";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan . "|d", $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);

  // Configure BLF's:
  $functionkeys = array ();
  for ( $x = 0; $x <= ( $parameters["Type"] == "snom710" ? 4 : 17); $x++)
  {
    $functionkeys[$x] = array ( "Context" => "inactive", "Label" => "", "LP" => "", "Number" => "");
  }
  $x = 0;
  foreach ( $parameters["Hints"] as $number)
  {
    if ( ! $extension = read_config ( "datafile", "extension-" . $number))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
    }
    if ( $x > ( $parameters["Type"] == "snom710" ? 4 : 17))
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. SNOM " . ( $parameters["Type"] == "snom710" ? "710" : "720") . " supports up to " . ( $parameters["Type"] == "snom710" ? "5" : "18") . " BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $functionkeys[$x] = array ( "Context" => "active", "Label" => $extension["Name"], "LP" => "on", "Number" => $number);
    $x++;
  }
  $functionkey = "";
  foreach ( $functionkeys as $id => $entry)
  {
    $functionkey .= "    <fkey idx=\"" . $id . "\" context=\"" . $entry["Context"] . "\" label=\"" . addslashes ( $entry["Label"]) . "\" lp=\"" . $entry["LP"] . "\" perm=\"\">" . ( ! empty ( $entry["Number"]) ? "blf " . $entry["Number"] : "") . "</fkey>\n";
  }
  $content = str_replace ( "{{{functionkey}}}", $functionkey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Niue":
    case "Pacific/Pago_Pago":
    case "Pacific/Honolulu":
    case "America/Adak":
    case "Pacific/Tahiti":
      $timezone = "USA-10";
      break;
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
      $timezone = "USA2-10";
      break;
    case "Pacific/Marquesas":
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "USA-9";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "USA-8";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "CAN-8";
      break;
    case "America/Tijuana":
      $timezone = "MEX-8";
      break;
    case "America/Boise":
    case "America/Denver":
    case "America/Phoenix":
      $timezone = "USA-7";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Yellowknife":
    case "America/Edmonton":
    case "America/Inuvik":
      $timezone = "CAN-7";
      break;
    case "America/Hermosillo":
    case "America/Chihuahua":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "MEX-7";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "CAN-6";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
      $timezone = "MEX-6";
      break;
    case "Pacific/Easter":
      $timezone = "CHL-6";
      break;
    case "America/Belize":
    case "America/Chicago":
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Managua":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "America/Tegucigalpa":
    case "Pacific/Galapagos":
      $timezone = "USA-6";
      break;
    case "America/Nassau":
      $timezone = "BHS-5";
      break;
    case "America/Havana":
      $timezone = "CUB-5";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "CAN-5";
      break;
    case "America/Bogota":
    case "America/Cayman":
    case "America/Detroit":
    case "America/Grand_Turk":
    case "America/Guayaquil":
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
    case "America/Jamaica":
    case "America/Kentucky/Louisville":
    case "America/Kentucky/Monticello":
    case "America/Lima":
    case "America/New_York":
    case "America/Panama":
    case "America/Port-au-Prince":
      $timezone = "USA-5";
      break;
    case "America/Caracas":
      $timezone = "VEN-4.5";
      break;
    case "America/Santiago":
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "CHL-4";
      break;
    case "America/Asuncion":
      $timezone = "PRY-4";
      break;
    case "America/Port_of_Spain":
      $timezone = "TTB-4";
      break;
    case "Atlantic/Stanley":
      $timezone = "FLK-4";
      break;
    case "Atlantic/Bermuda":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
    case "America/Puerto_Rico":
    case "America/Santo_Domingo":
    case "America/St_Barthelemy":
    case "America/St_Kitts":
    case "America/St_Lucia":
    case "America/St_Thomas":
    case "America/St_Vincent":
    case "America/Thule":
    case "America/Tortola":
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Curacao":
    case "America/Dominica":
      $timezone = "BMU-4";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "CAN-4";
      break;
    case "America/St_Johns":
      $timezone = "CAN-3.5";
      break;
    case "America/Argentina/Buenos_Aires":
    case "America/Argentina/Catamarca":
    case "America/Argentina/Cordoba":
    case "America/Argentina/Jujuy":
    case "America/Argentina/La_Rioja":
    case "America/Argentina/Mendoza":
    case "America/Argentina/Rio_Gallegos":
    case "America/Argentina/Salta":
    case "America/Argentina/San_Juan":
    case "America/Argentina/San_Luis":
    case "America/Argentina/Tucuman":
    case "America/Argentina/Ushuaia":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Montevideo":
      $timezone = "ARG-3";
      break;
    case "America/Araguaina":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Maceio":
    case "America/Recife":
    case "America/Santarem":
    case "America/Sao_Paulo":
    case "America/Bahia":
      $timezone = "BRA2-3";
      break;
    case "America/Godthab":
      $timezone = "GRL-3";
      break;
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "BRA-2";
      break;
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
    case "America/Scoresbysund":
      $timezone = "PRT-1";
      break;
    case "Atlantic/Faroe":
      $timezone = "FRO-0";
      break;
    case "Europe/Dublin":
      $timezone = "IRL-0";
      break;
    case "Atlantic/Madeira":
    case "Europe/Lisbon":
      $timezone = "PRT-0";
      break;
    case "Atlantic/Canary":
      $timezone = "ESP-0";
      break;
    case "Europe/Guernsey":
    case "Europe/London":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "America/Danmarkshavn":
    case "Africa/Abidjan":
    case "Africa/Accra":
    case "Africa/Bamako":
    case "Africa/Banjul":
    case "Africa/Bissau":
    case "Africa/Casablanca":
    case "Africa/Conakry":
    case "Africa/Dakar":
    case "Africa/El_Aaiun":
    case "Africa/Freetown":
    case "Africa/Lome":
    case "Africa/Monrovia":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
      $timezone = "GRB-0";
      break;
    case "Europe/Tirane":
      $timezone = "ALB+1";
      break;
    case "Europe/Vienna":
      $timezone = "AUT+1";
      break;
    case "Europe/Brussels":
      $timezone = "BEL+1";
      break;
    case "Africa/Luanda":
      $timezone = "CAI+1";
      break;
    case "Europe/Zagreb":
      $timezone = "HRV+1";
      break;
    case "Europe/Prague":
      $timezone = "CZE+1";
      break;
    case "Europe/Copenhagen":
      $timezone = "DNK+1";
      break;
    case "Europe/Paris":
      $timezone = "FRA+1";
      break;
    case "Europe/Berlin":
      $timezone = "GER+1";
      break;
    case "Europe/Budapest":
      $timezone = "HUN+1";
      break;
    case "Europe/Rome":
      $timezone = "ITA+1";
      break;
    case "Europe/Luxembourg":
      $timezone = "LUX+1";
      break;
    case "Europe/Skopje":
      $timezone = "MAK+1";
      break;
    case "Europe/Amsterdam":
      $timezone = "NLD+1";
      break;
    case "Africa/Windhoek":
      $timezone = "NAM+1";
      break;
    case "Europe/Oslo":
    case "Europe/Andorra":
    case "Europe/Ljubljana":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Arctic/Longyearbyen":
      $timezone = "NOR+1";
      break;
    case "Europe/Warsaw":
      $timezone = "POL+1";
      break;
    case "Europe/Bratislava":
      $timezone = "SVK+1";
      break;
    case "Europe/Madrid,ES,1":
      $timezone = "ESP+1";
      break;
    case "Europe/Stockholm":
      $timezone = "SWE+1";
      break;
    case "Europe/Zurich":
      $timezone = "CHE+1";
      break;
    case "Europe/Gibraltar":
      $timezone = "GIB+1";
      break;
    case "Europe/Belgrade":
      $timezone = "YUG+1";
      break;
    case "Africa/Algiers":
    case "Africa/Bangui":
    case "Africa/Brazzaville":
    case "Africa/Ceuta":
    case "Africa/Douala":
    case "Africa/Kinshasa":
    case "Africa/Lagos":
    case "Africa/Libreville":
    case "Africa/Malabo":
    case "Africa/Ndjamena":
    case "Africa/Niamey":
    case "Africa/Porto-Novo":
    case "Africa/Tunis":
      $timezone = "WAT+1";
      break;
    case "Europe/Minsk":
      $timezone = "BLR+2";
      break;
    case "Europe/Sofia":
      $timezone = "BGR+2";
      break;
    case "Asia/Nicosia":
      $timezone = "CYP+2";
      break;
    case "Africa/Blantyre":
    case "Africa/Bujumbura":
    case "Africa/Gaborone":
    case "Africa/Harare":
    case "Africa/Johannesburg":
    case "Africa/Kigali":
    case "Africa/Lubumbashi":
    case "Africa/Lusaka":
    case "Africa/Maputo":
    case "Africa/Maseru":
    case "Africa/Mbabane":
    case "Africa/Tripoli":
      $timezone = "CAT+2";
      break;
    case "Africa/Cairo":
      $timezone = "EGY+2";
      break;
    case "Europe/Tallinn":
      $timezone = "EST+2";
      break;
    case "Europe/Helsinki":
      $timezone = "FIN+2";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "GAZ+2";
      break;
    case "Europe/Athens":
      $timezone = "GRC+2";
      break;
    case "Asia/Jerusalem":
      $timezone = "ISR+2";
      break;
    case "Asia/Amman":
      $timezone = "JOR+2";
      break;
    case "Europe/Riga":
      $timezone = "LVA+2";
      break;
    case "Asia/Beirut":
      $timezone = "LBN+2";
      break;
    case "Europe/Chisinau":
      $timezone = "MDA+2";
      break;
    case "Europe/Simferopol":
      $timezone = "RUS+2";
      break;
    case "Europe/Bucharest":
      $timezone = "ROU+2";
      break;
    case "Asia/Damascus":
      $timezone = "SYR+2";
      break;
    case "Europe/Istanbul":
      $timezone = "TUR+2";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
    case "Europe/Mariehamn":
    case "Europe/Vilnius":
      $timezone = "UKR+2";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
    case "Africa/Nairobi":
      $timezone = "EAT+3";
      break;
    case "Asia/Aden":
    case "Asia/Baghdad":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
      $timezone = "IRQ+3";
      break;
    case "Europe/Kaliningrad":
      $timezone = "RUS+3";
      break;
    case "Asia/Tehran":
      $timezone = "IRN+3.5";
      break;
    case "Asia/Yerevan":
      $timezone = "ARM+4";
      break;
    case "Asia/Baku":
      $timezone = "AZE+4";
      break;
    case "Asia/Tbilisi":
      $timezone = "GEO+4";
      break;
    case "Asia/Aqtau":
      $timezone = "KAZ+4";
      break;
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
      $timezone = "RUS+4";
      break;
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Asia/Kabul":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "UAE+4";
      break;
    case "Asia/Aqtobe":
      $timezone = "KAZ+5";
      break;
    case "Asia/Karachi":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Oral":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "PAK+5";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
    case "Asia/Kathmandu":
      $timezone = "IND+5.5";
      break;
    case "Asia/Almaty":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Qyzylorda":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Asia/Rangoon":
    case "Indian/Cocos":
    case "Indian/Chagos":
      $timezone = "KAZ+6";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "RUS+6";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Omsk":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "THA+7";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
      $timezone = "RUS+7";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "CHN+8";
      break;
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Hong_Kong":
    case "Asia/Krasnoyarsk":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Singapore":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
      $timezone = "SGP+8";
      break;
    case "Australia/Perth":
    case "Australia/Eucla":
      $timezone = "AUS+8";
      break;
    case "Asia/Pyongyang":
    case "Asia/Seoul":
      $timezone = "KOR+9";
      break;
    case "Asia/Tokyo":
    case "Asia/Dili":
    case "Asia/Irkutsk":
    case "Asia/Jayapura":
    case "Pacific/Palau":
      $timezone = "JPN+9";
      break;
    case "Australia/Darwin":
      $timezone = "AUS2+9.5";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = "AUS+10";
      break;
    case "Australia/Brisbane":
      $timezone = "AUS2+10";
      break;
    case "Asia/Yakutsk":
      $timezone = "RUS+10";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "AUS+10.5";
      break;
    case "Antarctica/Macquarie":
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
    case "Australia/Currie":
    case "Australia/Hobart":
    case "Australia/Lord_Howe":
    case "Australia/Melbourne":
    case "Australia/Sydney":
    case "Pacific/Efate":
    case "Pacific/Guadalcanal":
    case "Pacific/Kosrae":
    case "Pacific/Noumea":
    case "Pacific/Pohnpei":
      $timezone = "NCL+11";
      break;
    case "Pacific/Norfolk":
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "NZL+12";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "RUS+12";
      break;
    case "Pacific/Chatham":
      $timezone = "NZL+12.75";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "TON+13";
      break;
  }
  $content = str_replace ( "{{{timezone}}}", $timezone, $content);
  $utcoffset = strpos ( $timezone, "-") ? "-" : "+";
  $hour = substr ( $timezone, strpos ( $timezone, $utcoffset) + 1);
  switch ( strpos ( $hour, ".") ? substr ( $hour, strpos ( $hour, ".") + 1) : "")
  {
    case "25":
      $minutes = 15;
      break;
    case "5":
      $minutes = 30;
      break;
    case "75":
      $minutes = 45;
      break;
    default:
      $minutes = 0;
      break;
  }
  if ( $minutes > 0)
  {
    $hour = substr ( $hour, 0, strpos ( $hour, "."));
  }
  $content = str_replace ( "{{{utcoffset}}}", $utcoffset . ((( (int) $hour * 60) + $minutes) * 60), $content);
  $ntpaddr = "";
  foreach ( $parameters["NTP"] as $ntp)
  {
    if ( empty ( $ntpaddr) && $hosts = gethostbynamel ( $ntp))
    {
      $ntpaddr = $hosts[0];
    }
  }
  $content = str_replace ( "{{{ntpserver}}}", $ntpaddr, $content);

  // Set date and time format:
  switch ( $parameters["Country"])
  {
    case "BR": // Brazil
    case "IT": // Italy
    case "MX": // Mexico
    case "CU": // Cuba
    case "DO": // Dominican Republic
    case "AG": // Antigua and Barbuda
    case "BS": // Bahamas
    case "BB": // Barbados
    case "BZ": // Belize
    case "CO": // Colombia
    case "CR": // Costa Rica
    case "CU": // Cuba
    case "DM": // Dominica
    case "DO": // Dominican Republic
    case "MQ": // Martinique
    case "BL": // Saint Barthélemy
    case "MF": // Saint Martin (French part)
    case "GD": // Grenada
    case "HT": // Haiti
    case "HN": // Honduras
    case "JM": // Jamaica
    case "AW": // Aruba
    case "CW": // Curaçao
    case "SX": // Sint Maarten (Dutch part)
    case "KN": // Saint Kitts and Nevis
    case "LC": // Saint Lucia
    case "VC": // Saint Vincent and the Grenadines
    case "TT": // Trinidad and Tobago
    case "AI": // Anguilla
    case "VG": // Virgin Islands (British)
    case "KY": // Cayman Islands
    case "MS": // Montserrat
    case "TC": // Turks and Caicos Islands
    case "GT": // Guatemala
    case "HN": // Honduras
    case "AR": // Argentina
    case "CL": // Chile
    case "PE": // Peru
    case "VE": // Venezuela (Bolivarian Republic of)
    case "EG": // Egypt
    case "DZ": // Algeria
    case "MA": // Morocco
    case "TN": // Tunisia
    case "SO": // Somalia
    case "NG": // Nigeria
    case "ET": // Ethiopia
    case "CD": // Congo (Democratic Republic of the)
    case "TZ": // Tanzania, United Republic of
    case "SD": // Sudan
    case "UG": // Uganda
    case "IQ": // Iraq
    case "SA": // Saudi Arabia
    case "YE": // Yemen
    case "TH": // Thailand
    case "KH": // Cambodia
    case "PK": // Pakistan
    case "BD": // Bangladesh
    case "PG": // Papua New Guinea
    case "NZ": // New Zealand
    case "VN": // Viet Nam
    case "GB": // United Kingdom of Great Britain and Northern Ireland
    case "FR": // France
    case "ES": // Spain
    case "AF": // Afghanistan
    case "NP": // Nepal
    case "CM": // Cameroon
    case "TG": // Togo
    case "PA": // Panama
    case "PT": // Portugal
    case "AL": // Albania
    case "AT": // Austria
    case "AZ": // Azerbaijan
    case "BE": // Belgium
    case "BW": // Botswana
    case "KH": // Cambodia
    case "CY": // Cyprus
    case "GQ": // Equatorial Guinea
    case "ER": // Eritrea
    case "GN": // Guinea
    case "NA": // Namibia
    case "NU": // Niue
    case "PS": // Palestine, State of
    case "AX": // Åland Islands
    case "AM": // Armenia
    case "BY": // Belarus
    case "BA": // Bosnia and Herzegovina
    case "BG": // Bulgaria
    case "HR": // Croatia
    case "CZ": // Czech Republic
    case "DK": // Denmark
    case "EE": // Estonia
    case "FI": // Finland
    case "GE": // Georgia
    case "DE": // Germany
    case "GL": // Greenland
    case "IS": // Iceland
    case "IL": // Israel
    case "KG": // Kyrgyzstan
    case "LV": // Latvia
    case "LI": // Liechtenstein
    case "LU": // Luxembourg
    case "ME": // Montenegro
    case "MK": // Macedonia (the former Yugoslav Republic of)
    case "NO": // Norway
    case "PL": // Poland
    case "RO": // Romania
    case "RS": // Serbia
    case "SK": // Slovakia
    case "SI": // Slovenia
    case "CH": // Switzerland
    case "TJ": // Tajikistan
    case "TR": // Turkey
    case "TM": // Turkmenistan
    case "UA": // Ukraine
    case "UZ": // Uzbekistan
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = "off"; // DD-MM
      $timeformat = "on"; // 24h
      break;
    case "CH": // China
    case "KP": // Korea (Democratic People's Republic of)
    case "KR": // Korea (Republic of)
    case "LS": // Lesotho
    case "LT": // Lithuania
    case "MN": // Mongolia
    case "MM": // Myanmar
    case "RU": // Russian Federation
    case "SG": // Singapore
    case "LK": // Sri Lanka
    case "SE": // Sweden
    case "DJ": // Djibouti
    case "JP": // Japan
    case "TW": // Taiwan
    case "BT": // Bhutan
    case "GH": // Ghana
    case "HK": // Hong Kong
    case "IR": // Iran (Islamic Republic of)
    case "KE": // Kenya
    case "MO": // Macao
    case "MV": // Maldives
    case "PW": // Palau
    case "RW": // Rwanda
    case "ZA": // South Africa
    case "HU": // Hungary
    case "KZ": // Kazakhstan
      $dateformat = "on"; // MM-DD
      $timeformat = "on"; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = "on"; // MM-DD
      $timeformat = "off"; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = "on"; // MM-DD
      $timeformat = "on"; // 24h
      break;
    default:
      $dateformat = "on"; // MM-DD
      $timeformat = "off"; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["Username"], $content);
  $content = str_replace ( "{{{webpassword}}}", $equipmentconfig["ExtraSettings"]["Password"], $content);
  $content = str_replace ( "{{{adminmodepassword}}}", $equipmentconfig["ExtraSettings"]["AdminModePassword"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", $_in["general"]["address"], $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . $parameters["Type"] . "-" . strtolower ( $parameters["MAC"]) . ".htm", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify snom-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Snom 870 auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_snom_870 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "snom870":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-snom-870.xml");
      break;
    default:
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
  }

  // Get equipment system configuration:
  $equipmentconfig = read_config ( "datafile", "equipment-" . $parameters["Type"]);

  // Equipment MAC:
  $content = str_replace ( "{{{mac}}}", strtolower ( $parameters["MAC"]), $content);

  // Account configuration:
  $content = str_replace ( "{{{username}}}", $parameters["Username"], $content);
  $content = str_replace ( "{{{password}}}", $parameters["Password"], $content);
  $content = str_replace ( "{{{displayname}}}", $parameters["Name"], $content);
  $content = str_replace ( "{{{server1addr}}}", $_in["general"]["address"], $content);
  $content = str_replace ( "{{{server1port}}}", $_in["general"]["port"], $content);
  $content = str_replace ( "{{{domain}}}", $parameters["Domain"], $content);

  // Configure equipment CODEC's:
  $codecconfig = "";
  $codecs = array (
    "G722" => "g722",
    "ULAW" => "pcma",
    "ALAW" => "pcmu",
    "G726" => "g726-32",
    "G726AAL2" => "all2-g726-32",
    "G729" => "g729"
  );
  foreach ( $equipmentconfig["AudioCodecs"] as $codec)
  {
    $codecconfig .= $codecs[$codec] . ",";
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig . ",telephone-event", $content);

  // Set phone language:
  $language = "";
  switch ( $parameters["Country"])
  {
    // Arabic:
    case "DZ": // Algeria
    case "BH": // Bahrain
    case "TD": // Chad
    case "KM": // Comoros
    case "DJ": // Djibouti
    case "EG": // Egypt
    case "IQ": // Iraq
    case "JO": // Jordan
    case "KW": // Kuwait
    case "LB": // Lebanon
    case "LY": // Libya
    case "ML": // Mali
    case "MR": // Mauritania
    case "MO": // Morocco
    case "OM": // Oman
    case "PS": // Palestine, State of
    case "QA": // Qatar
    case "SA": // Saudi Arabia
    case "SO": // Somalia
    case "SD": // Sudan
    case "SY": // Syrian Arab Republic
    case "AE": // United Arab Emirates
    case "YE": // Yemen
      $language = "Arabic";
      break;

    // Catalan:
    case "AD": // Andorra
      $language = "Catalan";
      break;

    // Croatian:
    case "HR": // Croatia
    case "BA": // Bosnia and Herzegovina
    case "ME": // Montenegro
    case "MK": // Macedonia (the former Yugoslav Republic of)
    case "RO": // Romania
    case "RS": // Serbia
    case "SI": // Slovenia
      $language = "Bosanski";
      break;

    // Danish
    case "DK": // Denmark
    case "FO": // Faroe Islands
      $language = "Dansk";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "Deutsch";
      break;

    // Czech:
    case "CZ": // Czech Republic
      $language = "Čeština";
      break;

    // Greek:
    case "GR": // Greece
      $language = "Ελληνικά";
      break;

    // Spanish:
    case "AR": // Argentina
    case "CO": // Colombia
    case "ES": // Spain
    case "PE": // Peru
    case "VE": // Venezuela
    case "CL": // Chile
    case "GT": // Guatemala
    case "EC": // Ecuador
    case "BO": // Bolivia
    case "CU": // Cuba
    case "DO": // Dominican Republic
    case "HN": // Honduras
    case "PY": // Paraguay
    case "SV": // El Salvador
    case "NI": // Nicaragua
    case "CR": // Costa Rica
    case "PA": // Panama
    case "UY": // Uruguay
    case "GQ": // Equatorial Guinea
    case "PR": // Puerto Rico
    case "MX": // Mexico
      $language = "Español";
      break;

    // Finnish:
    case "FI": // Finland
      $language = "Suomi";
      break;

    // Estonian:
    case "EE": // Estonia
      $language = "Estonian";
      break;

    // French:
    case "BJ": // Benin
    case "BF": // Burkina Faso
    case "CG": // Congo
    case "CD": // Congo (Democratic Republic of the)
    case "GA": // Gabon
    case "GN": // Guinea
    case "MC": // Monaco
    case "NE": // Niger
    case "SN": // Senegal
    case "TG": // Togo
    case "FR": // France
      $language = "Francais";
      break;

    // Hebrew:
    case "IL": // Israel
      $language = "Hebrew";
      break;

    // Hungarian:
    case "HU": // Hungary
    case "AT": // Austria
    case "SK": // Slovakia
      $language = "Hungarian";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "Italiano";
      break;

    // Lithuanian
    case "LT": // Lithuania 
      $language = "Lithuanian";
      break;

    // Latvian:
    case "LV": // Latvia
      $language = "Latvian";
      break;

    // Dutch:
    case "BE": // Belgium
    case "NL": // Netherlands
    case "AW": // Aruba
    case "CW": // Curaçao
    case "SX": // Sint Maarten (Dutch part)
      $language = "Netherlands";
      break;

    // Norwegian:
    case "NO": // Norway
      $language = "Norsk";
      break;

    // Polish:
    case "PL": // Poland
      $language = "Polski";
      break;

    // Portuguese:
    case "AO": // Angola
    case "BR": // Brazil
    case "CV": // Cabo Verde
    case "GW": // Guinea-Bissau
    case "TL": // Timor-Leste
    case "MO": // Macao
    case "MZ": // Mozambique
    case "PT": // Portugal
    case "ST": // Sao Tome and Principe
      $language = "Português";
      break;

    // Slovenian:
    case "SI": // Slovenia
      $language = "Slovenian";
      break;

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = "Russian";
      break;

    // Swedish:
    case "SE": // Sweden
    case "AX": // Åland Islands
      $language = "Svenska";
      break;

    // Turkish:
    case "TR": // Turkey
      $language = "Turkce";
      break;

    // British English:
    case "GB": // Great Britain
      $language = "English(UK)";
      break;

    // English (default):
    default:
      $language = "English";
      break;
  }
  $content = str_replace ( "{{{language}}}", string2isonumericentities ( $language), $content);

  // Set phone country tone:
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $phonetones = "AUS";
      break;
    case "AT": // Austria
      $phonetones = "AUT";
      break;
    case "CN": // China
      $phonetones = "CHN";
      break;
    case "CZ": // Czech
      $phonetones = "CZE";
      break;
    case "DK": // Denmark
      $phonetones = "DNK";
      break;
    case "FR": // France
      $phonetones = "FRA";
      break;
    case "DE": // Germany
      $phonetones = "GER";
      break;
    case "GB": // Great Britain
      $phonetones = "GBR";
      break;
    case "GR": // Greece
      $phonetones = "GRE";
      break;
    case "IN": // India
      $phonetones = "IND";
      break;
    case "IT": // Italy
      $phonetones = "ITA";
      break;
    case "JP": // Japan
      $phonetones = "JPN";
      break;
    case "MX": // Mexico
      $phonetones = "MEX";
      break;
    case "NL": // Netherlands
      $phonetones = "NLD";
      break;
    case "NO": // Norway
      $phonetones = "NOR";
      break;
    case "NZ": // New Zealand
      $phonetones = "NZL";
      break;
    case "ES": // Spain
      $phonetones = "ESP";
      break;
    case "SE": // Sweden
      $phonetones = "SWE";
      break;
    case "CH": // Switzerland
      $phonetones = "SWI";
      break;
    default: // United States of America
      $phonetones = "USA";
      break;
  }
  $content = str_replace ( "{{{phonetones}}}", $phonetones, $content);

  // Create dialplan:
  $dialplan = "";
  $emergency = "";
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( $dialplanentry["Emergency"])
    {
      $emergency .= " " . $dialplanentry["Pattern"];
    }
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    $dialplan .= "|^" . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\$";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan . "|d", $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);

  // Configure BLF's:
  $functionkeys = array ();
  for ( $x = 0; $x <= 14; $x++)
  {
    $functionkeys[$x] = array ( "Context" => "inactive", "Label" => "", "LP" => "", "Number" => "");
  }
  $x = 0;
  foreach ( $parameters["Hints"] as $number)
  {
    if ( ! $extension = read_config ( "datafile", "extension-" . $number))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
    }
    if ( $x > 14)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. SNOM 870 supports up to 15 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $functionkeys[$x] = array ( "Context" => "active", "Label" => $extension["Name"], "LP" => "on", "Number" => $number);
    $x++;
  }
  $functionkey = "";
  foreach ( $functionkeys as $id => $entry)
  {
    $functionkey .= "    <fkey idx=\"" . $id . "\" context=\"" . $entry["Context"] . "\" label=\"" . addslashes ( $entry["Label"]) . "\" lp=\"" . $entry["LP"] . "\" perm=\"\">" . ( ! empty ( $entry["Number"]) ? "blf " . $entry["Number"] : "") . "</fkey>\n";
  }
  $content = str_replace ( "{{{functionkey}}}", $functionkey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Niue":
    case "Pacific/Pago_Pago":
    case "Pacific/Honolulu":
    case "America/Adak":
    case "Pacific/Tahiti":
      $timezone = "USA-10";
      break;
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
      $timezone = "USA2-10";
      break;
    case "Pacific/Marquesas":
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "USA-9";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "USA-8";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "CAN-8";
      break;
    case "America/Tijuana":
      $timezone = "MEX-8";
      break;
    case "America/Boise":
    case "America/Denver":
    case "America/Phoenix":
      $timezone = "USA-7";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Yellowknife":
    case "America/Edmonton":
    case "America/Inuvik":
      $timezone = "CAN-7";
      break;
    case "America/Hermosillo":
    case "America/Chihuahua":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "MEX-7";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "CAN-6";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
      $timezone = "MEX-6";
      break;
    case "Pacific/Easter":
      $timezone = "CHL-6";
      break;
    case "America/Belize":
    case "America/Chicago":
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Managua":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "America/Tegucigalpa":
    case "Pacific/Galapagos":
      $timezone = "USA-6";
      break;
    case "America/Nassau":
      $timezone = "BHS-5";
      break;
    case "America/Havana":
      $timezone = "CUB-5";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "CAN-5";
      break;
    case "America/Bogota":
    case "America/Cayman":
    case "America/Detroit":
    case "America/Grand_Turk":
    case "America/Guayaquil":
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
    case "America/Jamaica":
    case "America/Kentucky/Louisville":
    case "America/Kentucky/Monticello":
    case "America/Lima":
    case "America/New_York":
    case "America/Panama":
    case "America/Port-au-Prince":
      $timezone = "USA-5";
      break;
    case "America/Caracas":
      $timezone = "VEN-4.5";
      break;
    case "America/Santiago":
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "CHL-4";
      break;
    case "America/Asuncion":
      $timezone = "PRY-4";
      break;
    case "America/Port_of_Spain":
      $timezone = "TTB-4";
      break;
    case "Atlantic/Stanley":
      $timezone = "FLK-4";
      break;
    case "Atlantic/Bermuda":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
    case "America/Puerto_Rico":
    case "America/Santo_Domingo":
    case "America/St_Barthelemy":
    case "America/St_Kitts":
    case "America/St_Lucia":
    case "America/St_Thomas":
    case "America/St_Vincent":
    case "America/Thule":
    case "America/Tortola":
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Curacao":
    case "America/Dominica":
      $timezone = "BMU-4";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "CAN-4";
      break;
    case "America/St_Johns":
      $timezone = "CAN-3.5";
      break;
    case "America/Argentina/Buenos_Aires":
    case "America/Argentina/Catamarca":
    case "America/Argentina/Cordoba":
    case "America/Argentina/Jujuy":
    case "America/Argentina/La_Rioja":
    case "America/Argentina/Mendoza":
    case "America/Argentina/Rio_Gallegos":
    case "America/Argentina/Salta":
    case "America/Argentina/San_Juan":
    case "America/Argentina/San_Luis":
    case "America/Argentina/Tucuman":
    case "America/Argentina/Ushuaia":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Montevideo":
      $timezone = "ARG-3";
      break;
    case "America/Araguaina":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Maceio":
    case "America/Recife":
    case "America/Santarem":
    case "America/Sao_Paulo":
    case "America/Bahia":
      $timezone = "BRA2-3";
      break;
    case "America/Godthab":
      $timezone = "GRL-3";
      break;
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "BRA-2";
      break;
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
    case "America/Scoresbysund":
      $timezone = "PRT-1";
      break;
    case "Atlantic/Faroe":
      $timezone = "FRO-0";
      break;
    case "Europe/Dublin":
      $timezone = "IRL-0";
      break;
    case "Atlantic/Madeira":
    case "Europe/Lisbon":
      $timezone = "PRT-0";
      break;
    case "Atlantic/Canary":
      $timezone = "ESP-0";
      break;
    case "Europe/Guernsey":
    case "Europe/London":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "America/Danmarkshavn":
    case "Africa/Abidjan":
    case "Africa/Accra":
    case "Africa/Bamako":
    case "Africa/Banjul":
    case "Africa/Bissau":
    case "Africa/Casablanca":
    case "Africa/Conakry":
    case "Africa/Dakar":
    case "Africa/El_Aaiun":
    case "Africa/Freetown":
    case "Africa/Lome":
    case "Africa/Monrovia":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
      $timezone = "GRB-0";
      break;
    case "Europe/Tirane":
      $timezone = "ALB+1";
      break;
    case "Europe/Vienna":
      $timezone = "AUT+1";
      break;
    case "Europe/Brussels":
      $timezone = "BEL+1";
      break;
    case "Africa/Luanda":
      $timezone = "CAI+1";
      break;
    case "Europe/Zagreb":
      $timezone = "HRV+1";
      break;
    case "Europe/Prague":
      $timezone = "CZE+1";
      break;
    case "Europe/Copenhagen":
      $timezone = "DNK+1";
      break;
    case "Europe/Paris":
      $timezone = "FRA+1";
      break;
    case "Europe/Berlin":
      $timezone = "GER+1";
      break;
    case "Europe/Budapest":
      $timezone = "HUN+1";
      break;
    case "Europe/Rome":
      $timezone = "ITA+1";
      break;
    case "Europe/Luxembourg":
      $timezone = "LUX+1";
      break;
    case "Europe/Skopje":
      $timezone = "MAK+1";
      break;
    case "Europe/Amsterdam":
      $timezone = "NLD+1";
      break;
    case "Africa/Windhoek":
      $timezone = "NAM+1";
      break;
    case "Europe/Oslo":
    case "Europe/Andorra":
    case "Europe/Ljubljana":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Arctic/Longyearbyen":
      $timezone = "NOR+1";
      break;
    case "Europe/Warsaw":
      $timezone = "POL+1";
      break;
    case "Europe/Bratislava":
      $timezone = "SVK+1";
      break;
    case "Europe/Madrid,ES,1":
      $timezone = "ESP+1";
      break;
    case "Europe/Stockholm":
      $timezone = "SWE+1";
      break;
    case "Europe/Zurich":
      $timezone = "CHE+1";
      break;
    case "Europe/Gibraltar":
      $timezone = "GIB+1";
      break;
    case "Europe/Belgrade":
      $timezone = "YUG+1";
      break;
    case "Africa/Algiers":
    case "Africa/Bangui":
    case "Africa/Brazzaville":
    case "Africa/Ceuta":
    case "Africa/Douala":
    case "Africa/Kinshasa":
    case "Africa/Lagos":
    case "Africa/Libreville":
    case "Africa/Malabo":
    case "Africa/Ndjamena":
    case "Africa/Niamey":
    case "Africa/Porto-Novo":
    case "Africa/Tunis":
      $timezone = "WAT+1";
      break;
    case "Europe/Minsk":
      $timezone = "BLR+2";
      break;
    case "Europe/Sofia":
      $timezone = "BGR+2";
      break;
    case "Asia/Nicosia":
      $timezone = "CYP+2";
      break;
    case "Africa/Blantyre":
    case "Africa/Bujumbura":
    case "Africa/Gaborone":
    case "Africa/Harare":
    case "Africa/Johannesburg":
    case "Africa/Kigali":
    case "Africa/Lubumbashi":
    case "Africa/Lusaka":
    case "Africa/Maputo":
    case "Africa/Maseru":
    case "Africa/Mbabane":
    case "Africa/Tripoli":
      $timezone = "CAT+2";
      break;
    case "Africa/Cairo":
      $timezone = "EGY+2";
      break;
    case "Europe/Tallinn":
      $timezone = "EST+2";
      break;
    case "Europe/Helsinki":
      $timezone = "FIN+2";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "GAZ+2";
      break;
    case "Europe/Athens":
      $timezone = "GRC+2";
      break;
    case "Asia/Jerusalem":
      $timezone = "ISR+2";
      break;
    case "Asia/Amman":
      $timezone = "JOR+2";
      break;
    case "Europe/Riga":
      $timezone = "LVA+2";
      break;
    case "Asia/Beirut":
      $timezone = "LBN+2";
      break;
    case "Europe/Chisinau":
      $timezone = "MDA+2";
      break;
    case "Europe/Simferopol":
      $timezone = "RUS+2";
      break;
    case "Europe/Bucharest":
      $timezone = "ROU+2";
      break;
    case "Asia/Damascus":
      $timezone = "SYR+2";
      break;
    case "Europe/Istanbul":
      $timezone = "TUR+2";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
    case "Europe/Mariehamn":
    case "Europe/Vilnius":
      $timezone = "UKR+2";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
    case "Africa/Nairobi":
      $timezone = "EAT+3";
      break;
    case "Asia/Aden":
    case "Asia/Baghdad":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
      $timezone = "IRQ+3";
      break;
    case "Europe/Kaliningrad":
      $timezone = "RUS+3";
      break;
    case "Asia/Tehran":
      $timezone = "IRN+3.5";
      break;
    case "Asia/Yerevan":
      $timezone = "ARM+4";
      break;
    case "Asia/Baku":
      $timezone = "AZE+4";
      break;
    case "Asia/Tbilisi":
      $timezone = "GEO+4";
      break;
    case "Asia/Aqtau":
      $timezone = "KAZ+4";
      break;
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
      $timezone = "RUS+4";
      break;
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Asia/Kabul":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "UAE+4";
      break;
    case "Asia/Aqtobe":
      $timezone = "KAZ+5";
      break;
    case "Asia/Karachi":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Oral":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "PAK+5";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
    case "Asia/Kathmandu":
      $timezone = "IND+5.5";
      break;
    case "Asia/Almaty":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Qyzylorda":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Asia/Rangoon":
    case "Indian/Cocos":
    case "Indian/Chagos":
      $timezone = "KAZ+6";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "RUS+6";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Omsk":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "THA+7";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
      $timezone = "RUS+7";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "CHN+8";
      break;
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Hong_Kong":
    case "Asia/Krasnoyarsk":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Singapore":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
      $timezone = "SGP+8";
      break;
    case "Australia/Perth":
    case "Australia/Eucla":
      $timezone = "AUS+8";
      break;
    case "Asia/Pyongyang":
    case "Asia/Seoul":
      $timezone = "KOR+9";
      break;
    case "Asia/Tokyo":
    case "Asia/Dili":
    case "Asia/Irkutsk":
    case "Asia/Jayapura":
    case "Pacific/Palau":
      $timezone = "JPN+9";
      break;
    case "Australia/Darwin":
      $timezone = "AUS2+9.5";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = "AUS+10";
      break;
    case "Australia/Brisbane":
      $timezone = "AUS2+10";
      break;
    case "Asia/Yakutsk":
      $timezone = "RUS+10";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "AUS+10.5";
      break;
    case "Antarctica/Macquarie":
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
    case "Australia/Currie":
    case "Australia/Hobart":
    case "Australia/Lord_Howe":
    case "Australia/Melbourne":
    case "Australia/Sydney":
    case "Pacific/Efate":
    case "Pacific/Guadalcanal":
    case "Pacific/Kosrae":
    case "Pacific/Noumea":
    case "Pacific/Pohnpei":
      $timezone = "NCL+11";
      break;
    case "Pacific/Norfolk":
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "NZL+12";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "RUS+12";
      break;
    case "Pacific/Chatham":
      $timezone = "NZL+12.75";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "TON+13";
      break;
  }
  $content = str_replace ( "{{{timezone}}}", $timezone, $content);
  $utcoffset = strpos ( $timezone, "-") ? "-" : "+";
  $hour = substr ( $timezone, strpos ( $timezone, $utcoffset) + 1);
  switch ( strpos ( $hour, ".") ? substr ( $hour, strpos ( $hour, ".") + 1) : "")
  {
    case "25":
      $minutes = 15;
      break;
    case "5":
      $minutes = 30;
      break;
    case "75":
      $minutes = 45;
      break;
    default:
      $minutes = 0;
      break;
  }
  if ( $minutes > 0)
  {
    $hour = substr ( $hour, 0, strpos ( $hour, "."));
  }
  $content = str_replace ( "{{{utcoffset}}}", $utcoffset . ((( (int) $hour * 60) + $minutes) * 60), $content);
  $ntpaddr = "";
  foreach ( $parameters["NTP"] as $ntp)
  {
    if ( empty ( $ntpaddr) && $hosts = gethostbynamel ( $ntp))
    {
      $ntpaddr = $hosts[0];
    }
  }
  $content = str_replace ( "{{{ntpserver}}}", $ntpaddr, $content);

  // Set date and time format:
  switch ( $parameters["Country"])
  {
    case "BR": // Brazil
    case "IT": // Italy
    case "MX": // Mexico
    case "CU": // Cuba
    case "DO": // Dominican Republic
    case "AG": // Antigua and Barbuda
    case "BS": // Bahamas
    case "BB": // Barbados
    case "BZ": // Belize
    case "CO": // Colombia
    case "CR": // Costa Rica
    case "CU": // Cuba
    case "DM": // Dominica
    case "DO": // Dominican Republic
    case "MQ": // Martinique
    case "BL": // Saint Barthélemy
    case "MF": // Saint Martin (French part)
    case "GD": // Grenada
    case "HT": // Haiti
    case "HN": // Honduras
    case "JM": // Jamaica
    case "AW": // Aruba
    case "CW": // Curaçao
    case "SX": // Sint Maarten (Dutch part)
    case "KN": // Saint Kitts and Nevis
    case "LC": // Saint Lucia
    case "VC": // Saint Vincent and the Grenadines
    case "TT": // Trinidad and Tobago
    case "AI": // Anguilla
    case "VG": // Virgin Islands (British)
    case "KY": // Cayman Islands
    case "MS": // Montserrat
    case "TC": // Turks and Caicos Islands
    case "GT": // Guatemala
    case "HN": // Honduras
    case "AR": // Argentina
    case "CL": // Chile
    case "PE": // Peru
    case "VE": // Venezuela (Bolivarian Republic of)
    case "EG": // Egypt
    case "DZ": // Algeria
    case "MA": // Morocco
    case "TN": // Tunisia
    case "SO": // Somalia
    case "NG": // Nigeria
    case "ET": // Ethiopia
    case "CD": // Congo (Democratic Republic of the)
    case "TZ": // Tanzania, United Republic of
    case "SD": // Sudan
    case "UG": // Uganda
    case "IQ": // Iraq
    case "SA": // Saudi Arabia
    case "YE": // Yemen
    case "TH": // Thailand
    case "KH": // Cambodia
    case "PK": // Pakistan
    case "BD": // Bangladesh
    case "PG": // Papua New Guinea
    case "NZ": // New Zealand
    case "VN": // Viet Nam
    case "GB": // United Kingdom of Great Britain and Northern Ireland
    case "FR": // France
    case "ES": // Spain
    case "AF": // Afghanistan
    case "NP": // Nepal
    case "CM": // Cameroon
    case "TG": // Togo
    case "PA": // Panama
    case "PT": // Portugal
    case "AL": // Albania
    case "AT": // Austria
    case "AZ": // Azerbaijan
    case "BE": // Belgium
    case "BW": // Botswana
    case "KH": // Cambodia
    case "CY": // Cyprus
    case "GQ": // Equatorial Guinea
    case "ER": // Eritrea
    case "GN": // Guinea
    case "NA": // Namibia
    case "NU": // Niue
    case "PS": // Palestine, State of
    case "AX": // Åland Islands
    case "AM": // Armenia
    case "BY": // Belarus
    case "BA": // Bosnia and Herzegovina
    case "BG": // Bulgaria
    case "HR": // Croatia
    case "CZ": // Czech Republic
    case "DK": // Denmark
    case "EE": // Estonia
    case "FI": // Finland
    case "GE": // Georgia
    case "DE": // Germany
    case "GL": // Greenland
    case "IS": // Iceland
    case "IL": // Israel
    case "KG": // Kyrgyzstan
    case "LV": // Latvia
    case "LI": // Liechtenstein
    case "LU": // Luxembourg
    case "ME": // Montenegro
    case "MK": // Macedonia (the former Yugoslav Republic of)
    case "NO": // Norway
    case "PL": // Poland
    case "RO": // Romania
    case "RS": // Serbia
    case "SK": // Slovakia
    case "SI": // Slovenia
    case "CH": // Switzerland
    case "TJ": // Tajikistan
    case "TR": // Turkey
    case "TM": // Turkmenistan
    case "UA": // Ukraine
    case "UZ": // Uzbekistan
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = "off"; // DD-MM
      $timeformat = "on"; // 24h
      break;
    case "CH": // China
    case "KP": // Korea (Democratic People's Republic of)
    case "KR": // Korea (Republic of)
    case "LS": // Lesotho
    case "LT": // Lithuania
    case "MN": // Mongolia
    case "MM": // Myanmar
    case "RU": // Russian Federation
    case "SG": // Singapore
    case "LK": // Sri Lanka
    case "SE": // Sweden
    case "DJ": // Djibouti
    case "JP": // Japan
    case "TW": // Taiwan
    case "BT": // Bhutan
    case "GH": // Ghana
    case "HK": // Hong Kong
    case "IR": // Iran (Islamic Republic of)
    case "KE": // Kenya
    case "MO": // Macao
    case "MV": // Maldives
    case "PW": // Palau
    case "RW": // Rwanda
    case "ZA": // South Africa
    case "HU": // Hungary
    case "KZ": // Kazakhstan
      $dateformat = "on"; // MM-DD
      $timeformat = "on"; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = "on"; // MM-DD
      $timeformat = "off"; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = "on"; // MM-DD
      $timeformat = "on"; // 24h
      break;
    default:
      $dateformat = "on"; // MM-DD
      $timeformat = "off"; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["Username"], $content);
  $content = str_replace ( "{{{webpassword}}}", $equipmentconfig["ExtraSettings"]["Password"], $content);
  $content = str_replace ( "{{{adminmodepassword}}}", $equipmentconfig["ExtraSettings"]["AdminModePassword"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", $_in["general"]["address"], $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . $parameters["Type"] . "-" . strtoupper ( $parameters["MAC"]) . ".htm", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify snom-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to remove the Snom auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_snom_remove ( $buffer, $parameters)
{
  global $_in;

  // Remove AP file
  if ( ! unlink ( $_in["general"]["tftpdir"] . "/" . $parameters["Type"] . "-" . strtolower ( $parameters["MAC"]) . ".htm"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP removed.");
}

/**
 * Function to create the Snom audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_snom_audio ( $buffer, $parameters)
{
  // Return Snom audio only SIP account template
  return "[" . $parameters["Username"] . "](vd_extension_" . $parameters["Number"] . ",vd_equipment_" . $parameters["Type"] . ")\n" .
         "aors=" . $parameters["Username"] . "\n" .
         "auth=" . $parameters["Username"] . "\n" .
         "\n" .
         "[" . $parameters["Username"] . "]\n" .
         "type=aor\n" .
         "max_contacts=1\n" .
         "\n" .
         "[" . $parameters["Username"] . "]\n" .
         "type=auth\n" .
         "auth_type=userpass\n" .
         "password=" . $parameters["Password"] . "\n" .
         "username=" . $parameters["Username"] . "\n";
}
?>
