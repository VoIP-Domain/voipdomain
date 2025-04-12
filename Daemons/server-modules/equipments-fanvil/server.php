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
 * VoIP Domain Fanvil equipments models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Fanvil
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_model_x1sp_configure", "equipment_model_fanvil_x1sp_configure");
framework_add_hook ( "equipment_model_x1sp_firmware_add", "equipment_model_fanvil_add_firmware");
framework_add_hook ( "equipment_model_x1sp_firmware_remove", "equipment_model_fanvil_remove_firmware");
framework_add_hook ( "ap_type_x1sp", "ap_type_fanvil_x1sp");
framework_add_hook ( "ap_type_x1sp_remove", "ap_type_fanvil_remove");
framework_add_hook ( "account_type_x1sp", "account_type_fanvil_audio");

framework_add_hook ( "equipment_model_x3sp_configure", "equipment_model_fanvil_x3sp_configure");
framework_add_hook ( "equipment_model_x3sp_firmware_add", "equipment_model_fanvil_add_firmware");
framework_add_hook ( "equipment_model_x3sp_firmware_remove", "equipment_model_fanvil_remove_firmware");
framework_add_hook ( "ap_type_x3sp", "ap_type_fanvil_x3sp");
framework_add_hook ( "ap_type_x3sp_remove", "ap_type_fanvil_remove");
framework_add_hook ( "account_type_x3sp", "account_type_fanvil_audio");

/**
 * Cleanup functions
 */
framework_add_hook ( "ap_fanvil_wipe", "ap_fanvil_wipe");
cleanup_register ( "Accounts-Fanvil", "ap_fanvil_wipe", array ( "Before" => array ( "Accounts")));

framework_add_hook ( "firmware_fanvil_wipe", "firmware_fanvil_wipe");
cleanup_register ( "Firmwares-Fanvil", "firmware_fanvil_wipe", array ( "Before" => array ( "Equipments")));

/**
 * Function to wipe the Fanvil equipment models auto provisioning files.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function ap_fanvil_wipe ( $buffer, $parameters)
{
  global $_in;

  // Remove auto provisioning configuration files:
  foreach ( list_config ( "datafile", "ap") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( equipment_model_exists ( $data["Type"], "Fanvil"))
    {
      unlink_config ( "ap", strtolower ( $data["MAC"]) . ".cfg");
      unlink_config ( "datafile", $filename);
    }
  }

  return $buffer;
}

/**
 * Function to wipe the Fanvil equipment models firmware files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function firmware_fanvil_wipe ( $buffer, $parameters)
{
  // Fanvil X1S(P)
  unlink_config ( "ap", "Fanvil/X1SP/fanvil_x1s_x1sp_hwv1_0.txt");
  unlink_config ( "ap", "Fanvil/X1SP/x1sp-5914-RECOVERY-P0.18.23.1.23-2.4.12.2-1179T2022-08-05-21.56.57.z");
  unlink_config ( "apdir", "Fanvil/X1SP");

  // Fanvil X3S(P)
  unlink_config ( "ap", "Fanvil/X3SP/fanvil_x3s_hw3_1_1.txt");
  unlink_config ( "ap", "Fanvil/X3SP/x3s2.14.0.7387T20220824101038.z");
  unlink_config ( "ap", "Fanvil/X3SP/VoIP Domain.bmp");
  unlink_config ( "apdir", "Fanvil/X3SP");

  // Fanvil vendor directory
  unlink_config ( "apdir", "Fanvil");

  return $buffer;
}

/**
 * Function to configure the Fanvil X1S(P) audio equipment model.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_fanvil_x1sp_configure ( $buffer, $parameters)
{
  // Process audio codecs array
  $codecs = array ();
  foreach ( $parameters["AudioCodecs"] as $codec)
  {
    switch ( $codec)
    {
      case "ALAW":
        $codecs[] = "alaw";
        break;
      case "ULAW":
        $codecs[] = "ulaw";
        break;
      case "G729":
      case "G729A":
        $codecs[] = "g729";
        break;
      case "G722":
        $codecs[] = "g722";
        break;
      case "ILBC":
        $codecs[] = "ilbc";
        break;
      case "G726":
        $codecs[] = "g726";
        break;
      case "OPUS":
        $codecs[] = "opus";
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
 * Function to configure the Fanvil X3S(P) audio equipment model.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_fanvil_x3sp_configure ( $buffer, $parameters)
{
  // Process audio codecs array
  $codecs = array ();
  foreach ( $parameters["AudioCodecs"] as $codec)
  {
    switch ( $codec)
    {
      case "ALAW":
        $codecs[] = "alaw";
        break;
      case "ULAW":
        $codecs[] = "ulaw";
        break;
      case "G729":
      case "G729A":
        $codecs[] = "g729";
        break;
      case "G722":
        $codecs[] = "g722";
        break;
      case "G723":
        $codecs[] = "g723";
        break;
      case "G726":
        $codecs[] = "g726";
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
 * Function to add a Fanvil firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_fanvil_add_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "x1sp-5914-RECOVERY-P0.18.23.1.23-2.4.12.2-1179T2022-08-05-21.56.57.z":	// Fanvil X1S(P)
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Fanvil/X1SP") && ! mkdir ( $_in["general"]["tftpdir"] . "/Fanvil/X1SP", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Fanvil/X1SP/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      $content = "Version=2.4.12.2\n" .
                 "Firmware=x1sp-5914-RECOVERY-P0.18.23.1.23-2.4.12.2-1179T2022-08-05-21.56.57.z\n" .
                 "BuildTime=2022.09.11 21:56\n" .
                 "Info=TXT\n";
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Fanvil/X1SP/fanvil_x1s_x1sp_hwv1_0.txt", $content) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      break;
    case "x3s2.14.0.7387T20220824101038.z":						// Fanvil X3S(P)
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Fanvil/X3SP") && ! mkdir ( $_in["general"]["tftpdir"] . "/Fanvil/X3SP", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Fanvil/X3SP/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      $content = "Version=2.14.0.7387\n" .
                 "Firmware=x3s2.14.0.7387T20220824101038.z\n" .
                 "BuildTime=2022.08.24 10:10\n" .
                 "Info=TXT\n";
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Fanvil/X3SP/fanvil_x3s_hw3_1_1.txt", $content) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      if ( ! copy ( dirname ( __FILE__) . "/wallpaper%20-%20320x240.bmp", $_in["general"]["tftpdir"] . "/Fanvil/X3SP/VoIP%20Domain.bmp") === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to remove a Fanvil firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_fanvil_remove_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "x1sp-5914-RECOVERY-P0.18.23.1.23-2.4.12.2-1179T2022-08-05-21.56.57.z":	// Fanvil X1S(P)
      if ( ! check_config ( "datafile", "x1sp"))
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Fanvil/X1SP/" . $parameters["Filename"]);
        @unlink ( $_in["general"]["tftpdir"] . "/Fanvil/X1SP/fanvil_x1s_x1sp_hwv1_0.txt");
        @rmdir ( $_in["general"]["tftpdir"] . "/Fanvil/X1SP");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "x3s2.14.0.7387T20220824101038.z":						// Fanvil X3S(P)
      if ( ! check_config ( "datafile", "x3sp"))
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Fanvil/X3SP/" . $parameters["Filename"]);
        @unlink ( $_in["general"]["tftpdir"] . "/Fanvil/X3SP/fanvil_x3s_hw3_1_1.txt");
        @unlink ( $_in["general"]["tftpdir"] . "/Fanvil/X3SP/VoIP Domain.bmp");
        @rmdir ( $_in["general"]["tftpdir"] . "/Fanvil/X3SP");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to create the Fanvil X1S(P) auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_fanvil_x1sp ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "x1sp":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-fanvil-" . $parameters["Type"] . ".xml");
      break;
    default:
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
  }

  // Get equipment system configuration:
  $equipmentconfig = read_config ( "datafile", "equipment-" . $parameters["Type"]);

  // Account configuration:
  $content = str_replace ( "{{{username}}}", $parameters["Username"], $content);
  $content = str_replace ( "{{{password}}}", $parameters["Password"], $content);
  $content = str_replace ( "{{{displayname}}}", $parameters["Name"], $content);
  $content = str_replace ( "{{{domain}}}", $parameters["Domain"], $content);
  $content = str_replace ( "{{{server1addr}}}", $_in["general"]["address"], $content);
  $content = str_replace ( "{{{server1port}}}", $_in["general"]["port"], $content);

  // Configure equipment CODEC's:
  $codecconfig = array ();
  $codecs = array (
    "ULAW" => "PCMU",
    "ALAW" => "PCMA",
    "G726" => "G726-32",
    "G722" => "G722",
    "G729" => "G729",
    "ILBC" => "iLBC",
    "OPUS" => "opus"
  );
  foreach ( $equipmentconfig["AudioCodecs"] as $codec)
  {
    $codecconfig[] = $codecs[$codec];
  }
  $content = str_replace ( "{{{codecconfig}}}", implode ( ",", $codecconfig), $content);

  // Set phone language:
  $language = "";
  switch ( $parameters["Country"])
  {
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
      $language = "pt";
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "tc";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = "cn";
      break;

    // Croatian:
    case "HR": // Croatia
    case "BA": // Bosnia and Herzegovina
    case "ME": // Montenegro
    case "RO": // Romania
    case "RS": // Serbia
      $language = "hr";
      break;

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = "ru";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "it";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "de";
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
      $language = "fr";
      break;

    // Hebrew:
    case "IL": // Israel
      $language = "he";
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
      $language = "es";
      break;

    // Turkish:
    case "TR": // Turkey
      $language = "tr";
      break;

    // Slovenian:
    case "SI": // Slovenia
      $language = "slo";
      break;

    // Hungarian:
    case "HU": // Hungary
    case "AT": // Austria
    case "SK": // Slovakia
      $language = "hu";
      break;

    // Japanese:
    case "JP": // Japan
    case "PW": // Palau
      $language = "jp";
      break;

    // English (default):
    default:
      $language = "en";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $dialingtone = 15;
      break;
    case "AT": // Austria
      $dialingtone = 22;
      break;
    case "BE": // Belgium
      $dialingtone = 0;
      break;
    case "BR": // Brazil
      $dialingtone = 16;
      break;
    case "CA": // Canada
      $dialingtone = 18;
      break;
    case "CL": // Chile
      $dialingtone = 20;
      break;
    case "CN": // China
      $dialingtone = 1;
      break;
    case "TW": // Taiwan
      $dialingtone = 10;
      break;
    case "HR": // Croatia
      $dialingtone = 17;
      break;
    case "CZ": // Czech
      $dialingtone = 12;
      break;
    case "DK": // Denmark
      $dialingtone = 23;
      break;
    case "FI": // Finland
      $dialingtone = 24;
      break;
    case "FR": // France
      $dialingtone = 25;
      break;
    case "DE": // Germany
      $dialingtone = 2;
      break;
    case "GR": // Greece
      $dialingtone = 26;
      break;
    case "HU": // Hungary
      $dialingtone = 27;
      break;
    case "LT": // Lithuania
      $dialingtone = 28;
      break;
    case "IN": // India
      $dialingtone = 29;
      break;
    case "IL": // Israel
      $dialingtone = 3;
      break;
    case "IT": // Italy
      $dialingtone = 21;
      break;
    case "JP": // Japan
      $dialingtone = 4;
      break;
    case "MX": // Mexico
      $dialingtone = 30;
      break;
    case "NZ": // New Zealand
      $dialingtone = 31;
      break;
    case "NL": // Netherlands
      $dialingtone = 5;
      break;
    case "NO": // Norway
      $dialingtone = 6;
      break;
    case "PT": // Portugal
      $dialingtone = 32;
      break;
    case "RU": // Russia
      $dialingtone = 19;
      break;
    case "ZA": // South Africa
      $dialingtone = 14;
      break;
    case "KR": // Korea (Republic of)
      $dialingtone = 7;
      break;
    case "ES": // Spain
      $dialingtone = 33;
      break;
    case "SE": // Sweden
      $dialingtone = 8;
      break;
    case "CH": // Switzerland
      $dialingtone = 9;
      break;
    case "GB": // Great Britain
      $dialingtone = 13;
      break;
    default: // United States of America
      $dialingtone = 11;
      break;
  }
  $content = str_replace ( "{{{dialingtone}}}", $dialingtone, $content);

  // Create dialplan:
  $dialplan = array ();
  $emergency = "";
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( $dialplanentry["Emergency"] && empty ( $emergency))
    {
      $emergency = $dialplanentry["Pattern"];
    }
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    $dialplan[] = ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] . "," : "") . str_replace ( "X", "x", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"])));
  }
  $dialcode = "";
  foreach ( $dialplan as $id => $dialpattern)
  {
    $dialcode .= "    <dialplan index=\"" . ( $id + 1) . "\">\n";
    $dialcode .= "      <Number>" . $dialpattern . "</Number>\n";
    $dialcode .= "      <ApplyToCall>0</ApplyToCall>\n";
    $dialcode .= "      <MatchToSend>0</MatchToSend>\n";
    $dialcode .= "      <Media>0</Media>\n";
    $dialcode .= "      <Line>-1</Line>\n";
    $dialcode .= "      <Address></Address>\n";
    $dialcode .= "      <Port>0</Port>\n";
    $dialcode .= "      <Alias></Alias>\n";
    $dialcode .= "      <DelLen>0</DelLen>\n";
    $dialcode .= "      <Suffix></Suffix>\n";
    $dialcode .= "    </dialplan>\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialcode, $content);
  $content = str_replace ( "{{{emergencynumber}}}", substr ( $emergency, 1), $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 2; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Value" => "", "Title" => "", "Icon" => "Green");
  }
  $x = 0;
  foreach ( $parameters["Hints"] as $number)
  {
    $x++;
    if ( ! $extension = read_config ( "datafile", "extension-" . $number))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
    }
    if ( $x > 2)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Fanvil X1S(P) supports up to 2 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x]["Type"] = 1;
    $linekeys[$x]["Value"] = $number . "@1/bc*8" . $number;
    $linekeys[$x]["Title"] = $number;
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "      <Fkey index=\"" . ( $id + 1) . "\">\n";
    $linekey .= "        <Type>" . $entry["Type"] . "</Type>\n";
    $linekey .= "        <Value>" . $entry["Value"] . "</Value>\n";
    $linekey .= "        <Title>" . $entry["Title"] . "</Title>\n";
    $linekey .= "        <ICON>" . $entry["Icon"] . "</ICON>\n";
    $linekey .= "      </Fkey>\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Niue":
    case "Pacific/Pago_Pago":
      $timezone = -44;
      $timezonename = "UTC-11";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = -40;
      $timezonename = "UTC-10";
      break;
    case "Pacific/Marquesas":
      $timezone = -38;
      $timezonename = "UTC-9:30";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = -36;
      $timezonename = "UTC-9";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "America/Tijuana":
    case "Pacific/Pitcairn":
      $timezone = -32;
      $timezonename = "UTC-8";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
    case "America/Boise":
    case "America/Denver":
    case "America/Phoenix":
      $timezone = -28;
      $timezonename = "UTC-7";
      break;
    case "America/Bahia_Banderas":
    case "America/Belize":
    case "America/Cancun":
    case "America/Chicago":
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Managua":
    case "America/Matamoros":
    case "America/Menominee":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Tegucigalpa":
    case "America/Winnipeg":
    case "Pacific/Galapagos":
      $timezone = -24;
      $timezonename = "UTC-6";
      break;
    case "America/Atikokan":
    case "America/Bogota":
    case "America/Cayman":
    case "America/Coral_Harbour":
    case "America/Detroit":
    case "America/Grand_Turk":
    case "America/Guayaquil":
    case "America/Havana":
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
    case "America/Iqaluit":
    case "America/Jamaica":
    case "America/Kentucky/Louisville":
    case "America/Kentucky/Monticello":
    case "America/Lima":
    case "America/Montreal":
    case "America/Nassau":
    case "America/New_York":
    case "America/Nipigon":
    case "America/Panama":
    case "America/Pangnirtung":
    case "America/Port-au-Prince":
    case "America/Thunder_Bay":
    case "America/Toronto":
    case "Pacific/Easter":
      $timezone = -20;
      $timezonename = "UTC-5";
      break;
    case "America/Caracas":
      $timezone = -18;
      $timezonename = "UTC-4:30";
      break;
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Blanc-Sablon":
    case "America/Boa_Vista":
    case "America/Curacao":
    case "America/Dominica":
    case "America/Eirunepe":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Halifax":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Manaus":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Moncton":
    case "America/Montserrat":
    case "America/Port_of_Spain":
    case "America/Porto_Velho":
    case "America/Puerto_Rico":
    case "America/Rio_Branco":
    case "America/Santo_Domingo":
    case "America/St_Barthelemy":
    case "America/St_Kitts":
    case "America/St_Lucia":
    case "America/St_Thomas":
    case "America/St_Vincent":
    case "America/Thule":
    case "America/Tortola":
    case "Atlantic/Bermuda":
      $timezone = -16;
      $timezonename = "UTC-4";
      break;
    case "America/St_Johns":
      $timezone = -14;
      $timezonename = "UTC-3:30";
      break;
    case "America/Araguaina":
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
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Godthab":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = -12;
      $timezonename = "UTC-3";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = -8;
      $timezonename = "UTC-2";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = -4;
      $timezonename = "UTC-1";
      break;
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
    case "America/Danmarkshavn":
    case "Atlantic/Canary":
    case "Atlantic/Faroe":
    case "Atlantic/Madeira":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Europe/Dublin":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
    case "Europe/Lisbon":
    case "Europe/London":
      $timezone = 0;
      $timezonename = "UTC";
      break;
    case "Africa/Algiers":
    case "Africa/Bangui":
    case "Africa/Brazzaville":
    case "Africa/Ceuta":
    case "Africa/Douala":
    case "Africa/Kinshasa":
    case "Africa/Lagos":
    case "Africa/Libreville":
    case "Africa/Luanda":
    case "Africa/Malabo":
    case "Africa/Ndjamena":
    case "Africa/Niamey":
    case "Africa/Porto-Novo":
    case "Africa/Tunis":
    case "Arctic/Longyearbyen":
    case "Europe/Amsterdam":
    case "Europe/Andorra":
    case "Europe/Belgrade":
    case "Europe/Berlin":
    case "Europe/Bratislava":
    case "Europe/Brussels":
    case "Europe/Budapest":
    case "Europe/Copenhagen":
    case "Europe/Gibraltar":
    case "Europe/Ljubljana":
    case "Europe/Luxembourg":
    case "Europe/Madrid":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Paris":
    case "Europe/Podgorica":
    case "Europe/Prague":
    case "Europe/Rome":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Skopje":
    case "Europe/Stockholm":
    case "Europe/Tirane":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Vienna":
    case "Europe/Warsaw":
    case "Europe/Zagreb":
    case "Europe/Zurich":
      $timezone = 4;
      $timezonename = "UTC+1";
      break;
    case "Africa/Blantyre":
    case "Africa/Bujumbura":
    case "Africa/Cairo":
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
    case "Africa/Windhoek":
    case "Asia/Amman":
    case "Asia/Beirut":
    case "Asia/Damascus":
    case "Asia/Gaza":
    case "Asia/Hebron":
    case "Asia/Jerusalem":
    case "Asia/Nicosia":
    case "Europe/Athens":
    case "Europe/Bucharest":
    case "Europe/Chisinau":
    case "Europe/Helsinki":
    case "Europe/Istanbul":
    case "Europe/Kiev":
    case "Europe/Mariehamn":
    case "Europe/Riga":
    case "Europe/Simferopol":
    case "Europe/Sofia":
    case "Europe/Tallinn":
    case "Europe/Uzhgorod":
    case "Europe/Vilnius":
    case "Europe/Zaporozhye":
      $timezone = 8;
      $timezonename = "UTC+2";
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
    case "Asia/Aden":
    case "Asia/Baghdad":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Europe/Kaliningrad":
    case "Europe/Minsk":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
      $timezone = 12;
      $timezonename = "UTC+3";
      break;
    case "Asia/Tehran":
      $timezone = 14;
      $timezonename = "UTC+3:30";
      break;
    case "Asia/Baku":
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Asia/Tbilisi":
    case "Asia/Yerevan":
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = 16;
      $timezonename = "UTC+4";
      break;
    case "Asia/Kabul":
      $timezone = 18;
      $timezonename = "UTC+4:30";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Karachi":
    case "Asia/Oral":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = 20;
      $timezonename = "UTC+5";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = 22;
      $timezonename = "UTC+5:30";
      break;
    case "Asia/Kathmandu":
      $timezone = 23;
      $timezonename = "UTC+5:45";
      break;
    case "Asia/Almaty":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Qyzylorda":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Asia/Yekaterinburg":
    case "Indian/Chagos":
      $timezone = 24;
      $timezonename = "UTC+6";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = 26;
      $timezonename = "UTC+6:30";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = 28;
      $timezonename = "UTC+7";
      break;
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Hong_Kong":
    case "Asia/Krasnoyarsk":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Shanghai":
    case "Asia/Singapore":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
    case "Australia/Perth":
      $timezone = 32;
      $timezonename = "UTC+8";
      break;
    case "Australia/Eucla":
      $timezone = 35;
      $timezonename = "UTC+8:45";
      break;
    case "Asia/Dili":
    case "Asia/Irkutsk":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Asia/Seoul":
    case "Asia/Tokyo":
    case "Pacific/Palau":
      $timezone = 36;
      $timezonename = "UTC+9";
      break;
    case "Australia/Darwin":
      $timezone = 38;
      $timezonename = "UTC+9:30";
      break;
    case "Asia/Yakutsk":
    case "Australia/Brisbane":
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = 40;
      $timezonename = "UTC+10";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = 42;
      $timezonename = "UTC+10:30";
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
      $timezone = 44;
      $timezonename = "UTC+11";
      break;
    case "Pacific/Norfolk":
      $timezone = 46;
      $timezonename = "UTC+11:30";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = 48;
      $timezonename = "UTC+12";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = 52;
      $timezonename = "UTC+13";
      break;
    case "Pacific/Chatham":
      $timezone = 55;
      $timezonename = "UTC+13:45";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = 56;
      $timezonename = "UTC+14";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timezone}}}", $timezone, $content);
  $content = str_replace ( "{{{timezonename}}}", $timezonename, $content);
  $content = str_replace ( "{{{ntpserver1}}}", $ntpaddr1, $content);
  $content = str_replace ( "{{{ntpserver2}}}", $ntpaddr2, $content);

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
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 5; // DD MM YYYY
      $dateseparator = 0; // (space)
      $timeformat = 0; // 24h
      break;
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
      $dateformat = 3; // WW MMM DD
      $dateseparator = 0; // (space)
      $timeformat = 0; // 24h
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
      $dateformat = 9; // YYYY MM DD
      $dateseparator = 0; // (space)
      $timeformat = 0; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 7; // MM DD YYYY
      $dateseparator = 0; // (space)
      $timeformat = 1; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 7; // MM DD YYYY
      $dateseparator = 0; // (space)
      $timeformat = 0; // 24h
      break;
    default:
      $dateformat = 9; // YYYY MM DD
      $dateseparator = 0; // (space)
      $timeformat = 1; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);
  $content = str_replace ( "{{{dateseparator}}}", $dateseparator, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", $_in["general"]["address"], $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify fanvil-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Fanvil X3S(P) auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_fanvil_x3sp ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "x3sp":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-fanvil-" . $parameters["Type"] . ".xml");
      break;
    default:
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
  }

  // Get equipment system configuration:
  $equipmentconfig = read_config ( "datafile", "equipment-" . $parameters["Type"]);

  // Account configuration:
  $content = str_replace ( "{{{username}}}", $parameters["Username"], $content);
  $content = str_replace ( "{{{password}}}", $parameters["Password"], $content);
  $content = str_replace ( "{{{displayname}}}", $parameters["Name"], $content);
  $content = str_replace ( "{{{domain}}}", $parameters["Domain"], $content);
  $content = str_replace ( "{{{server1addr}}}", $_in["general"]["address"], $content);
  $content = str_replace ( "{{{server1port}}}", $_in["general"]["port"], $content);

  // Configure equipment CODEC's:
  $codecconfig = array ();
  $codecs = array (
    "ULAW" => "G711U",
    "ALAW" => "G711A",
    "G726" => "G726-32",
    "G722" => "G722",
    "G729" => "G729",
    "G723" => "G723"
  );
  foreach ( $equipmentconfig["AudioCodecs"] as $codec)
  {
    $codecconfig[] = $codecs[$codec];
  }
  $content = str_replace ( "{{{codecconfig}}}", implode ( ",", $codecconfig), $content);
  $globalcodecs = array (
    "ULAW" => "PCMU",
    "ALAW" => "PCMA",
    "G726" => "G726-32",
    "G722" => "G722",
    "G729" => "G729",
    "G723" => "G723"
  );
  $globalcodec = "";
  for ( $x = 1; $x <= 6; $x++)
  {
    if ( $equipmentconfig["AudioCodecs"][$x - 1] != "")
    {
      $globalcodec .= "        <Voice_Codec" . $x . ">" . $globalcodecs[$equipmentconfig["AudioCodecs"][$x - 1]] . "/8000</Voice_Codec" . $x . ">\n";
    } else {
      $globalcodec .= "        <Voice_Codec" . $x . "/>\n";
    }
  }
  $content = str_replace ( "{{{globalcodecconfig}}}", $globalcodec, $content);

  // Set phone language:
  $language = 0;
  switch ( $parameters["Country"])
  {
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
      $language = 17;
      break;

    // Polish:
    case "PL": // Poland
      $language = 5;
      break;

    // Indonesian:
    case "ID": // Indonesia
      $language = 20;
      break;

    // Malay:
    case "MY": // Malaysia
    case "BN": // Brunei Darussalam
    case "SG": // Singapore
      $language = 21;
      break;

    // Catalan:
    case "AD": // Andorra
      $language = 14;
      break;

    // Czech:
    case "CZ": // Czech Republic
      $language = 18;
      break;

    // Ukrainian:
    case "UA": // Ukraine
      $language = 24;
      break;

    // Bulgarian:
    case "BG": // Bulgaria
      $language = 12;
      break;

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
    case "MR": // Mauritania
    case "MA": // Morocco
    case "OM": // Oman
    case "PS": // Palestine, State of
    case "QA": // Qatar
    case "SA": // Saudi Arabia
    case "SO": // Somalia
    case "SD": // Sudan
    case "SY": // Syrian Arab Republic
    case "TN": // Tunisia
    case "AE": // United Arab Emirates
    case "YE": // Yemen
      $language = 23;
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = 2;
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = 1;
      break;

    // Croatian:
    case "HR": // Croatia
    case "BA": // Bosnia and Herzegovina
    case "ME": // Montenegro
    case "RO": // Romania
    case "RS": // Serbia
      $language = "hr";
      break;

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = 6;
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = 7;
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = 16;
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
      $language = 4;
      break;

    // Hebrew:
    case "IL": // Israel
      $language = 8;
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
      $language = 10;
      break;

    // Turkish:
    case "TR": // Turkey
      $language = 9;
      break;

    // Slovenian:
    case "SI": // Slovenia
      $language = 13;
      break;

    // Hungarian:
    case "HU": // Hungary
    case "AT": // Austria
    case "SK": // Slovakia
      $language = 22;
      break;

    // Japanese:
    case "JP": // Japan
    case "PW": // Palau
      $language = 11;
      break;

    // English (default):
    default:
      $language = 0;
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $dialingtone = 15;
      break;
    case "AT": // Austria
      $dialingtone = 22;
      break;
    case "BE": // Belgium
      $dialingtone = 0;
      break;
    case "BR": // Brazil
      $dialingtone = 16;
      break;
    case "CA": // Canada
      $dialingtone = 18;
      break;
    case "CL": // Chile
      $dialingtone = 20;
      break;
    case "CN": // China
      $dialingtone = 1;
      break;
    case "TW": // Taiwan
      $dialingtone = 10;
      break;
    case "HR": // Croatia
      $dialingtone = 17;
      break;
    case "CZ": // Czech
      $dialingtone = 12;
      break;
    case "DK": // Denmark
      $dialingtone = 23;
      break;
    case "FI": // Finland
      $dialingtone = 24;
      break;
    case "FR": // France
      $dialingtone = 25;
      break;
    case "DE": // Germany
      $dialingtone = 2;
      break;
    case "GR": // Greece
      $dialingtone = 26;
      break;
    case "HU": // Hungary
      $dialingtone = 27;
      break;
    case "LT": // Lithuania
      $dialingtone = 28;
      break;
    case "IN": // India
      $dialingtone = 29;
      break;
    case "IL": // Israel
      $dialingtone = 3;
      break;
    case "IT": // Italy
      $dialingtone = 21;
      break;
    case "JP": // Japan
      $dialingtone = 4;
      break;
    case "MX": // Mexico
      $dialingtone = 30;
      break;
    case "NZ": // New Zealand
      $dialingtone = 31;
      break;
    case "NL": // Netherlands
      $dialingtone = 5;
      break;
    case "NO": // Norway
      $dialingtone = 6;
      break;
    case "PT": // Portugal
      $dialingtone = 32;
      break;
    case "RU": // Russia
      $dialingtone = 19;
      break;
    case "ZA": // South Africa
      $dialingtone = 14;
      break;
    case "KR": // Korea (Republic of)
      $dialingtone = 7;
      break;
    case "ES": // Spain
      $dialingtone = 33;
      break;
    case "SE": // Sweden
      $dialingtone = 8;
      break;
    case "CH": // Switzerland
      $dialingtone = 9;
      break;
    case "GB": // Great Britain
      $dialingtone = 13;
      break;
    default: // United States of America
      $dialingtone = 11;
      break;
  }
  $content = str_replace ( "{{{dialingtone}}}", $dialingtone, $content);

  // Create dialplan:
  $dialplan = array ();
  $emergency = "";
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( $dialplanentry["Emergency"] && empty ( $emergency))
    {
      $emergency = $dialplanentry["Pattern"];
    }
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    $dialplan[] = ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] . "," : "") . str_replace ( "X", "x", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"])));
  }
  $dialcode = "";
  foreach ( $dialplan as $id => $dialpattern)
  {
    $dialcode .= "      <Dial_Peer_List_Entry>\n";
    $dialcode .= "        <ID>Item" . ( $id + 1) . "</ID>\n";
    $dialcode .= "        <Number>" . $dialpattern . "</Number>\n";
    $dialcode .= "        <protocol>2</protocol>\n";
    $dialcode .= "        <address>0.0.0.0</address>\n";
    $dialcode .= "        <port>5060</port>\n";
    $dialcode .= "        <SipDomain></SipDomain>\n";
    $dialcode .= "        <Alias></Alias>\n";
    $dialcode .= "        <DelLen>0</DelLen>\n";
    $dialcode .= "        <Suffix></Suffix>\n";
    $dialcode .= "        <type>1</type>\n";
    $dialcode .= "      </Dial_Peer_List_Entry>\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialcode, $content);
  $content = str_replace ( "{{{emergencynumber}}}", substr ( $emergency, 1), $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 2; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Value" => "", "Title" => "", "Icon" => "Green");
  }
  $x = 0;
  foreach ( $parameters["Hints"] as $number)
  {
    $x++;
    if ( ! $extension = read_config ( "datafile", "extension-" . $number))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
    }
    if ( $x > 2)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Fanvil X3S(P) supports up to 2 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x]["Type"] = 1;
    $linekeys[$x]["Value"] = $number . "@1/bc*8" . $number;
    $linekeys[$x]["Title"] = $number;
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "      <Function_Key_Entry>\n";
    $linekey .= "        <ID>Fkey" . ( $id + 1) . ">\n";
    $linekey .= "        <Type>" . $entry["Type"] . "</Type>\n";
    $linekey .= "        <Value>" . $entry["Value"] . "</Value>\n";
    $linekey .= "        <Title>" . $entry["Title"] . "</Title>\n";
    $linekey .= "        <ICON>" . $entry["Icon"] . "</ICON>\n";
    $linekey .= "      </Function_Key_Entry>\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Niue":
    case "Pacific/Pago_Pago":
      $timezone = -44;
      $timezonename = "UTC-11";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = -40;
      $timezonename = "UTC-10";
      break;
    case "Pacific/Marquesas":
      $timezone = -38;
      $timezonename = "UTC-9:30";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = -36;
      $timezonename = "UTC-9";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "America/Tijuana":
    case "Pacific/Pitcairn":
      $timezone = -32;
      $timezonename = "UTC-8";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
    case "America/Boise":
    case "America/Denver":
    case "America/Phoenix":
      $timezone = -28;
      $timezonename = "UTC-7";
      break;
    case "America/Bahia_Banderas":
    case "America/Belize":
    case "America/Cancun":
    case "America/Chicago":
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Managua":
    case "America/Matamoros":
    case "America/Menominee":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Tegucigalpa":
    case "America/Winnipeg":
    case "Pacific/Galapagos":
      $timezone = -24;
      $timezonename = "UTC-6";
      break;
    case "America/Atikokan":
    case "America/Bogota":
    case "America/Cayman":
    case "America/Coral_Harbour":
    case "America/Detroit":
    case "America/Grand_Turk":
    case "America/Guayaquil":
    case "America/Havana":
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
    case "America/Iqaluit":
    case "America/Jamaica":
    case "America/Kentucky/Louisville":
    case "America/Kentucky/Monticello":
    case "America/Lima":
    case "America/Montreal":
    case "America/Nassau":
    case "America/New_York":
    case "America/Nipigon":
    case "America/Panama":
    case "America/Pangnirtung":
    case "America/Port-au-Prince":
    case "America/Thunder_Bay":
    case "America/Toronto":
    case "Pacific/Easter":
      $timezone = -20;
      $timezonename = "UTC-5";
      break;
    case "America/Caracas":
      $timezone = -18;
      $timezonename = "UTC-4:30";
      break;
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Blanc-Sablon":
    case "America/Boa_Vista":
    case "America/Curacao":
    case "America/Dominica":
    case "America/Eirunepe":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Halifax":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Manaus":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Moncton":
    case "America/Montserrat":
    case "America/Port_of_Spain":
    case "America/Porto_Velho":
    case "America/Puerto_Rico":
    case "America/Rio_Branco":
    case "America/Santo_Domingo":
    case "America/St_Barthelemy":
    case "America/St_Kitts":
    case "America/St_Lucia":
    case "America/St_Thomas":
    case "America/St_Vincent":
    case "America/Thule":
    case "America/Tortola":
    case "Atlantic/Bermuda":
      $timezone = -16;
      $timezonename = "UTC-4";
      break;
    case "America/St_Johns":
      $timezone = -14;
      $timezonename = "UTC-3:30";
      break;
    case "America/Araguaina":
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
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Godthab":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = -12;
      $timezonename = "UTC-3";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = -8;
      $timezonename = "UTC-2";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = -4;
      $timezonename = "UTC-1";
      break;
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
    case "America/Danmarkshavn":
    case "Atlantic/Canary":
    case "Atlantic/Faroe":
    case "Atlantic/Madeira":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Europe/Dublin":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
    case "Europe/Lisbon":
    case "Europe/London":
      $timezone = 0;
      $timezonename = "UTC";
      break;
    case "Africa/Algiers":
    case "Africa/Bangui":
    case "Africa/Brazzaville":
    case "Africa/Ceuta":
    case "Africa/Douala":
    case "Africa/Kinshasa":
    case "Africa/Lagos":
    case "Africa/Libreville":
    case "Africa/Luanda":
    case "Africa/Malabo":
    case "Africa/Ndjamena":
    case "Africa/Niamey":
    case "Africa/Porto-Novo":
    case "Africa/Tunis":
    case "Arctic/Longyearbyen":
    case "Europe/Amsterdam":
    case "Europe/Andorra":
    case "Europe/Belgrade":
    case "Europe/Berlin":
    case "Europe/Bratislava":
    case "Europe/Brussels":
    case "Europe/Budapest":
    case "Europe/Copenhagen":
    case "Europe/Gibraltar":
    case "Europe/Ljubljana":
    case "Europe/Luxembourg":
    case "Europe/Madrid":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Paris":
    case "Europe/Podgorica":
    case "Europe/Prague":
    case "Europe/Rome":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Skopje":
    case "Europe/Stockholm":
    case "Europe/Tirane":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Vienna":
    case "Europe/Warsaw":
    case "Europe/Zagreb":
    case "Europe/Zurich":
      $timezone = 4;
      $timezonename = "UTC+1";
      break;
    case "Africa/Blantyre":
    case "Africa/Bujumbura":
    case "Africa/Cairo":
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
    case "Africa/Windhoek":
    case "Asia/Amman":
    case "Asia/Beirut":
    case "Asia/Damascus":
    case "Asia/Gaza":
    case "Asia/Hebron":
    case "Asia/Jerusalem":
    case "Asia/Nicosia":
    case "Europe/Athens":
    case "Europe/Bucharest":
    case "Europe/Chisinau":
    case "Europe/Helsinki":
    case "Europe/Istanbul":
    case "Europe/Kiev":
    case "Europe/Mariehamn":
    case "Europe/Riga":
    case "Europe/Simferopol":
    case "Europe/Sofia":
    case "Europe/Tallinn":
    case "Europe/Uzhgorod":
    case "Europe/Vilnius":
    case "Europe/Zaporozhye":
      $timezone = 8;
      $timezonename = "UTC+2";
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
    case "Asia/Aden":
    case "Asia/Baghdad":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Europe/Kaliningrad":
    case "Europe/Minsk":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
      $timezone = 12;
      $timezonename = "UTC+3";
      break;
    case "Asia/Tehran":
      $timezone = 14;
      $timezonename = "UTC+3:30";
      break;
    case "Asia/Baku":
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Asia/Tbilisi":
    case "Asia/Yerevan":
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = 16;
      $timezonename = "UTC+4";
      break;
    case "Asia/Kabul":
      $timezone = 18;
      $timezonename = "UTC+4:30";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Karachi":
    case "Asia/Oral":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = 20;
      $timezonename = "UTC+5";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = 22;
      $timezonename = "UTC+5:30";
      break;
    case "Asia/Kathmandu":
      $timezone = 23;
      $timezonename = "UTC+5:45";
      break;
    case "Asia/Almaty":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Qyzylorda":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Asia/Yekaterinburg":
    case "Indian/Chagos":
      $timezone = 24;
      $timezonename = "UTC+6";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = 26;
      $timezonename = "UTC+6:30";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = 28;
      $timezonename = "UTC+7";
      break;
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Hong_Kong":
    case "Asia/Krasnoyarsk":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Shanghai":
    case "Asia/Singapore":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
    case "Australia/Perth":
      $timezone = 32;
      $timezonename = "UTC+8";
      break;
    case "Australia/Eucla":
      $timezone = 35;
      $timezonename = "UTC+8:45";
      break;
    case "Asia/Dili":
    case "Asia/Irkutsk":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Asia/Seoul":
    case "Asia/Tokyo":
    case "Pacific/Palau":
      $timezone = 36;
      $timezonename = "UTC+9";
      break;
    case "Australia/Darwin":
      $timezone = 38;
      $timezonename = "UTC+9:30";
      break;
    case "Asia/Yakutsk":
    case "Australia/Brisbane":
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = 40;
      $timezonename = "UTC+10";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = 42;
      $timezonename = "UTC+10:30";
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
      $timezone = 44;
      $timezonename = "UTC+11";
      break;
    case "Pacific/Norfolk":
      $timezone = 46;
      $timezonename = "UTC+11:30";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = 48;
      $timezonename = "UTC+12";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = 52;
      $timezonename = "UTC+13";
      break;
    case "Pacific/Chatham":
      $timezone = 55;
      $timezonename = "UTC+13:45";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = 56;
      $timezonename = "UTC+14";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timezone}}}", $timezone, $content);
  $content = str_replace ( "{{{timezonename}}}", $timezonename, $content);
  $content = str_replace ( "{{{ntpserver1}}}", $ntpaddr1, $content);
  $content = str_replace ( "{{{ntpserver2}}}", $ntpaddr2, $content);

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
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 5; // DD MM YYYY
      $dateseparator = 0; // (space)
      $timeformat = 0; // 24h
      break;
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
      $dateformat = 3; // WW MMM DD
      $dateseparator = 0; // (space)
      $timeformat = 0; // 24h
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
      $dateformat = 9; // YYYY MM DD
      $dateseparator = 0; // (space)
      $timeformat = 0; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 7; // MM DD YYYY
      $dateseparator = 0; // (space)
      $timeformat = 1; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 7; // MM DD YYYY
      $dateseparator = 0; // (space)
      $timeformat = 0; // 24h
      break;
    default:
      $dateformat = 9; // YYYY MM DD
      $dateseparator = 0; // (space)
      $timeformat = 1; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);
  $content = str_replace ( "{{{dateseparator}}}", $dateseparator, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", $_in["general"]["address"], $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify fanvil-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to remove the Fanvil auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_fanvil_remove ( $buffer, $parameters)
{
  global $_in;

  // Remove AP file
  if ( ! unlink ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP removed.");
}

/**
 * Function to create the Fanvil audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_fanvil_audio ( $buffer, $parameters)
{
  // Return Fanvil audio only SIP account template
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
