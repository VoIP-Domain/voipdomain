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
 * VoIP Domain Khomp equipments models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Khomp
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_model_ips40cc_configure", "equipment_model_khomp_basic_configure");
framework_add_hook ( "equipment_model_ips40cc_firmware_add", "equipment_model_khomp_add_firmware");
framework_add_hook ( "equipment_model_ips40cc_firmware_remove", "equipment_model_khomp_remove_firmware");
framework_add_hook ( "ap_type_ips40cc", "ap_type_khomp");
framework_add_hook ( "ap_type_ips40cc_remove", "ap_type_khomp_remove");
framework_add_hook ( "account_type_ips40cc", "account_type_khomp_audio");

framework_add_hook ( "equipment_model_ips100_configure", "equipment_model_khomp_basic_configure");
framework_add_hook ( "equipment_model_ips100_firmware_add", "equipment_model_khomp_add_firmware");
framework_add_hook ( "equipment_model_ips100_firmware_remove", "equipment_model_khomp_remove_firmware");
framework_add_hook ( "ap_type_ips100", "ap_type_khomp");
framework_add_hook ( "ap_type_ips100_remove", "ap_type_khomp_remove");
framework_add_hook ( "account_type_ips100", "account_type_khomp_audio");

framework_add_hook ( "equipment_model_ips102_configure", "equipment_model_khomp_basic_configure");
framework_add_hook ( "equipment_model_ips102_firmware_add", "equipment_model_khomp_add_firmware");
framework_add_hook ( "equipment_model_ips102_firmware_remove", "equipment_model_khomp_remove_firmware");
framework_add_hook ( "ap_type_ips102", "ap_type_khomp");
framework_add_hook ( "ap_type_ips102_remove", "ap_type_khomp_remove");
framework_add_hook ( "account_type_ips102", "account_type_khomp_audio");

framework_add_hook ( "equipment_model_ips108_configure", "equipment_model_khomp_basic_configure");
framework_add_hook ( "equipment_model_ips108_firmware_add", "equipment_model_khomp_add_firmware");
framework_add_hook ( "equipment_model_ips108_firmware_remove", "equipment_model_khomp_remove_firmware");
framework_add_hook ( "ap_type_ips108", "ap_type_khomp");
framework_add_hook ( "ap_type_ips108_remove", "ap_type_khomp_remove");
framework_add_hook ( "account_type_ips108", "account_type_khomp_audio");

framework_add_hook ( "equipment_model_ips200_configure", "equipment_model_khomp_basic_configure");
framework_add_hook ( "equipment_model_ips200_firmware_add", "equipment_model_khomp_add_firmware");
framework_add_hook ( "equipment_model_ips200_firmware_remove", "equipment_model_khomp_remove_firmware");
framework_add_hook ( "ap_type_ips200", "ap_type_khomp");
framework_add_hook ( "ap_type_ips200_remove", "ap_type_khomp_remove");
framework_add_hook ( "account_type_ips200", "account_type_khomp_audio");

framework_add_hook ( "equipment_model_ips212_configure", "equipment_model_khomp_basic_configure");
framework_add_hook ( "equipment_model_ips212_firmware_add", "equipment_model_khomp_add_firmware");
framework_add_hook ( "equipment_model_ips212_firmware_remove", "equipment_model_khomp_remove_firmware");
framework_add_hook ( "ap_type_ips212", "ap_type_khomp");
framework_add_hook ( "ap_type_ips212_remove", "ap_type_khomp_remove");
framework_add_hook ( "account_type_ips212", "account_type_khomp_audio");

framework_add_hook ( "equipment_model_ips300_configure", "equipment_model_khomp_basic_configure");
framework_add_hook ( "equipment_model_ips300_firmware_add", "equipment_model_khomp_add_firmware");
framework_add_hook ( "equipment_model_ips300_firmware_remove", "equipment_model_khomp_remove_firmware");
framework_add_hook ( "ap_type_ips300", "ap_type_khomp");
framework_add_hook ( "ap_type_ips300_remove", "ap_type_khomp_remove");
framework_add_hook ( "account_type_ips300", "account_type_khomp_audio");

/**
 * Cleanup functions
 */
framework_add_hook ( "ap_khomp_wipe", "ap_khomp_wipe");
cleanup_register ( "Accounts-Khomp", "ap_khomp_wipe", array ( "Before" => array ( "Accounts")));

framework_add_hook ( "firmware_khomp_wipe", "firmware_khomp_wipe");
cleanup_register ( "Firmwares-Khomp", "firmware_khomp_wipe", array ( "Before" => array ( "Equipments")));

/**
 * Function to wipe the Khomp equipment models auto provisioning files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function ap_khomp_wipe ( $buffer, $parameters)
{
  // Remove auto provisioning configuration files:
  foreach ( list_config ( "datafile", "ap") as $ap)
  {
    $data = read_config ( "datafile", $ap);
    if ( equipment_model_exists ( $ap["Type"], "Khomp"))
    {
      unlink_config ( "ap", strtolower ( $data["MAC"]) . ".cfg");
      unlink_config ( "datafile", $ap);
    }
  }

  return $buffer;
}

/**
 * Function to wipe the Khomp equipment models firmware files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function firmware_khomp_wipe ( $buffer, $parameters)
{
  // Khomp generic kernel
  unlink_config ( "ap", "Khomp/Kernel/K2_uImage_100N_version2.7.4");

  // Khomp IPS 200
  unlink_config ( "ap", "Khomp/IPS200/S2_IPS_290_KHOMP_290_MD5_version4.5.0.7-11810");
  unlink_config ( "ap", "FD000290.cfg");
  unlink_config ( "ap", "KD000290.cfg");

  // Khomp IPS 212
  unlink_config ( "ap", "Khomp/IPS212S2_IPS_212_KHOMP_320V3_MD5_version4.6.0.0-12027");
  unlink_config ( "ap", "FD000320.cfg");
  unlink_config ( "ap", "KD000320.cfg");

  // Khomp vendor directory
  unlink_config ( "apdir", "Khomp");

  return $buffer;
}

/**
 * Function to configure the basic Khomp equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_khomp_basic_configure ( $buffer, $parameters)
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
      case "G722":
        $codecs[] = "g722";
        break;
      case "G723":
        $codecs[] = "g723";
        break;
      case "G729":
        $codecs[] = "g729";
        break;
      case "ILBC":
        $codecs[] = "ilbc";
        break;
      case "G726":
        $codecs[] = "g726";
        break;
      case "GSM":
        $codecs[] = "gsm";
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
 * Function to add a Khomp firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_khomp_add_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "K2_uImage_100N_version2.7.4":				// Khomp generic kernel firmware file version 2.7.4
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Khomp/Kernel") && ! mkdir ( $_in["general"]["tftpdir"] . "/Khomp/Kernel", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Khomp/Kernel/K2_uImage_100N_version2.7.4", $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      // Create Kernel auto provisioning file to each available model:
      if ( is_file ( $_in["general"]["tftpdir"] . "/Khomp/IPS200/S2_IPS_290_KHOMP_290_MD5_version4.5.0.7-11810"))	// Khomp IPS 200
      {
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/KD000290.cfg", "Khomp/Kernel/K2_uImage_100N_version2.7.4") === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }
      if ( is_file ( $_in["general"]["tftpdir"] . "/Khomp/IPS212/S2_IPS_212_KHOMP_320V3_MD5_version4.6.0.0-12027"))	// Khomp IPS 212
      {
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/KD000320.cfg", "Khomp/Kernel/K2_uImage_100N_version2.7.4") === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "S2_IPS_290_KHOMP_290_MD5_version4.5.0.7-11810":	// Khomp IPS 200 firmware file version 4.5.0.7
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Khomp/IPS200") && ! mkdir ( $_in["general"]["tftpdir"] . "/Khomp/IPS200", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Khomp/IPS200/S2_IPS_290_KHOMP_290_MD5_version4.5.0.7-11810", $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/FD000290.cfg", "Khomp/IPS200/S2_IPS_290_KHOMP_290_MD5_version4.5.0.7-11810") === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      // Add Kernel auto provisioning file if available:
      if ( is_file ( $_in["general"]["tftpdir"] . "/Khomp/Kernel/K2_uImage_100N_version2.7.4"))
      {
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/KD000290.cfg", "Khomp/Kernel/K2_uImage_100N_version2.7.4") === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "S2_IPS_212_KHOMP_320V3_MD5_version4.6.0.0-12027":	// Khomp IPS 212 firmware file version 4.6.0.0
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Khomp/IPS212") && ! mkdir ( $_in["general"]["tftpdir"] . "/Khomp/IPS212", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Khomp/IPS212/S2_IPS_212_KHOMP_320V3_MD5_version4.6.0.0-12027", $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/FD000320.cfg", "Khomp/IPS212/S2_IPS_212_KHOMP_320V3_MD5_version4.6.0.0-12027") === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      // Add Kernel auto provisioning file if available:
      if ( is_file ( $_in["general"]["tftpdir"] . "/Khomp/Kernel/K2_uImage_100N_version2.7.4"))
      {
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/KD000320.cfg", "Khomp/Kernel/K2_uImage_100N_version2.7.4") === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to remove a Khomp firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_khomp_remove_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "K2_uImage_100N_version2.7.4":                         // Khomp generic kernel firmware file version 2.7.4
      @unlink ( $_in["general"]["tftpdir"] . "/Khomp/Kernel/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Khomp/Kernel");
      @unlink ( $_in["general"]["tftpdir"] . "/KD000290.cfg");
      @unlink ( $_in["general"]["tftpdir"] . "/KD000320.cfg");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "S2_IPS_290_KHOMP_290_MD5_version4.5.0.7-11810":       // Khomp IPS 200 firmware file version 4.5.0.7
      @unlink ( $_in["general"]["tftpdir"] . "/Khomp/IPS200/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Khomp/IPS200");
      @unlink ( $_in["general"]["tftpdir"] . "/FD000290.cfg");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "S2_IPS_212_KHOMP_320V3_MD5_version4.6.0.0-12027":     // Khomp IPS 212 firmware file version 4.6.0.0
      @unlink ( $_in["general"]["tftpdir"] . "/Khomp/IPS212/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Khomp/IPS212");
      @unlink ( $_in["general"]["tftpdir"] . "/FD000320.cfg");

      return array ( "code" => 212, "message" => "Firmware file removed.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to create the Khomp phones auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_khomp ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "ips200":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-khomp-ips200.xml");
      break;
    case "ips212":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-khomp-ips212.xml");
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

  // Configure equipment CODEC's:
  $codecs = array (
    "ALAW" => array ( "Type" => "G711A", "Code" => 0),
    "ULAW" => array ( "Type" => "G711U", "Code" => 1),
    "G723" => array ( "Type" => "G723", "Code" => 2),
    "G729" => array ( "Type" => "G729", "Code" => 3),
    "G722" => array ( "Type" => "G722", "Code" => 4),
    "ILBC" => array ( "Type" => "iLBC", "Code" => 5),
    "G726" => array ( "Type" => "G726_32", "Code" => 6)
  );
  $codectype = "";
  $enablecode = "";
  foreach ( $equipmentconfig["AudioCodecs"] as $id => $codec)
  {
    $codectype .= "CodecType" . ( $id == 0 ? "" : $id) . "=\"" . $codecs[$codec]["Code"] . "\" ";
    $enablecode .= "enableCode" . $id . "=\"" . $codecs[$codec]["Type"] . "\" ";
    unset ( $codecs[$codec]);
  }
  for ( $id++; $id <= 8; $id++)
  {
    $codectype .= "CodecType" . ( $id == 0 ? "" : $id) . "=\"255\" ";
    $enablecode .= "enableCode" . $id . "=\"\" ";
  }
  $id = 0;
  $disablecode = "";
  foreach ( $codecs as $codec)
  {
    $disablecode .= "disableCode" . $id . "=\"" . $codec["Type"] . "\" ";
    $id++;
  }
  for ( $id++; $id <= 9; $id++)
  {
    $disablecode .= "disableCode" . $id . "=\"\" ";
  }
  $content = str_replace ( "{{{codectype}}}", $codectype, $content);
  $content = str_replace ( "{{{enablecode}}}", $enablecode, $content);
  $content = str_replace ( "{{{disablecode}}}", $disablecode, $content);

  // If provided NTP servers, configure them:
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{sntpaddr1}}}", $ntpaddr1, $content);
  $content = str_replace ( "{{{sntpaddr2}}}", $ntpaddr2, $content);

  // Set phone language (1 = English, 4 = Spanish and 6 = Portuguese):
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
      $languagecode = 6;
      $i18n = array (
        "History" => "Histórico",
        "Directory" => "Diretório",
        "PickUp" => "Captura",
        "Menu" => "Menu"
      );
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
      $languagecode = 4;
      $i18n = array (
        "History" => "Histórico",
        "Directory" => "Directorio",
        "PickUp" => "Atrapar",
        "Menu" => "Menú"
      );
      break;

    // English (default):
    default:
      $languagecode = 1;
      $i18n = array (
        "History" => "History",
        "Directory" => "Directory",
        "PickUp" => "PickUp",
        "Menu" => "Menu"
      );
      break;
  }
  $content = str_replace ( "{{{languagecode}}}", $languagecode, $content);
  $content = str_replace ( "{{{i18nhistory}}}", $i18n["History"], $content);
  $content = str_replace ( "{{{i18ndirectory}}}", $i18n["Directory"], $content);
  $content = str_replace ( "{{{i18npickup}}}", $i18n["PickUp"], $content);
  $content = str_replace ( "{{{i18nmenu}}}", $i18n["Menu"], $content);

  // Set phone country tone:
  switch ( $parameters["Country"])
  {
    case "CH": // China
      $tonecountry = 0;
      break;
    case "US": // United States of America
      $tonecountry = 1;
      break;
    case "IN": // India
      $tonecountry = 2;
      break;
    case "BG": // United Kingdom of Great Britain and Northern Ireland
      $tonecountry = 3;
      break;
    case "AU": // Australia
      $tonecountry = 4;
      break;
    case "NZ": // New Zealand
      $tonecountry = 5;
      break;
    case "RU": // Russian Federation
      $tonecountry = 6;
      break;
    case "HK": // Hong Kong
      $tonecountry = 7;
      break;
    case "ID": // Indonesia
      $tonecountry = 8;
      break;
    case "DE": // Germany
      $tonecountry = 9;
      break;
    case "NL": // Netherlands
      $tonecountry = 10;
      break;
    case "FR": // France
      $tonecountry = 11;
      break;
    case "RS": // Serbia
      $tonecountry = 13;
      break;
    case "BR": // Brazil
      $tonecountry = 14;
      break;
    default: // United States of America
      $tonecountry = 1;
      break;
  }
  $content = str_replace ( "{{{tonecountry}}}", $tonecountry, $content);

  // Create dialplan:
  $dialplans = array ();
  $emergency = "";
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( $dialplanentry["Emergency"])
    {
      $emergency .= "," . $dialplanentry["Pattern"];
    }
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    $dialplans[] = array ( "Prefix" => ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "?", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))), "Descriptor" => $dialplan["Label"]);
  }
  $routes = "";
  $routeid = 0;
  foreach ( $dialplans as $data)
  {
    $routes .= "    <RouteTab id=\"" . $routeid . "\" DelNumbLen=\"0\" AddNumbString=\"\" CallerAddNumbString=\"\" Descriptor=\"" . str_replace ( "\"", "\\\"", $data["Descriptor"]) . "\" PrefixNum=\"" . str_replace ( "\"", "\\\"", $data["Prefix"]) . "\" NumbFlag=\"0\" NumbPostion=\"0\" CallerAddNumbFlag=\"0\" CallerAddNumbPostion=\"0\" CallerDelNumbFlag=\"0\" CallerDelNumbLen=\"0\" CallerDelNumbPostion=\"0\" DelNumbFlag=\"0\" DelNumbPostion=\"0\" RingMode=\"0\" RouteID=\"" . $routeid . "\" AddNumbFlag=\"0\" AddNumbPostion=\"0\" IP=\"" . $_in["general"]["address"] . "\" ext_IP=\"\" PortNo=\"" . $_in["general"]["port"] . "\" Account=\"0\" />\n";
    $routeid++;
  }
  $content = str_replace ( "{{{dialplan}}}", $routes, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 0; $x <= 1; $x++)
  {
    $linekeys[$x] = array ( "Num" => "", "SipAccounts" => "0", "Type" => "0", "Name" => "", "DialogFunCode" => "", "FunCodeEnable" => "0");
  }
  $x = 0;
  foreach ( $parameters["Hints"] as $number)
  {
    if ( ! $extension = read_config ( "datafile", "extension-" . $number))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
    }
    if ( $x > 1)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLD limit. Khomp " . strtoupper ( $parameters["Type"]) . " support up to 2 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Num" => $number, "SipAccounts" => "127", "Type" => "1", "Name" => $extension["Name"], "DialogFunCode" => "", "FunCodeEnable" => "0");
    $x++;
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "    <Programbutton id=\"" . $id . "\" Num=\"" . $entry["Num"] . "\" SipAccounts=\"" . $entry["SipAccounts"] . "\" Type=\"" . $entry["Type"] . "\" Name=\"" . $entry["Name"] . "\" DialogFunCode=\"" . $entry["DialogFunCode"] . "\" FunCodeEnable=\"" . $entry["FunCodeEnable"] . "\" />\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set timezone:
  switch ( $parameters["Offset"])
  {
    case -12:
      $timezone = 0;
      break;
    case -11:
      $timezone = 1;
      break;
    case -10:
      switch ( $parameters["TimeZone"])
      {
        case "Pacific/Honolulu":
          $timezone = 33;
          break;
        default:
          $timezone = 2;
          break;
      }
      break;
    case -9:
      switch ( $parameters["TimeZone"])
      {
        case "America/Anchorage":
        case "America/Juneau":
        case "America/Nome":
        case "America/Sitka":
        case "America/Yakutat":
          $timezone = 30;
          break;
        default:
          $timezone = 3;
          break;
      }
      break;
    case -8:
      switch ( $parameters["TimeZone"])
      {
        case "America/Los_Angeles":
        case "America/Metlakatla":
          $timezone = 4;
          break;
        case "America/Dawson":
        case "America/Vancouver":
        case "America/Whitehorse":
          $timezone = 49;
          break;
        case "America/Tijuana":
          $timezone = 50;
          break;
        default:
          $timezone = 35;
          break;
      }
      break;
    case -7:
      switch ( $parameters["TimeZone"])
      {
        case "America/Cambridge_Bay":
        case "America/Creston":
        case "America/Dawson_Creek":
        case "America/Edmonton":
        case "America/Inuvik":
        case "America/Yellowknife":
          $timezone = 51;
          break;
        case "America/Chihuahua":
        case "America/Hermosillo":
        case "America/Mazatlan":
        case "America/Ojinaga":
          $timezone = 52;
          break;
        case "America/Phoenix":
          $timezone = 31;
          break;
        default:
          $timezone = 40;
          break;
      }
      break;
    case -6:
      switch ( $parameters["TimeZone"])
      {
        case "America/Bahia_Banderas":
        case "America/Cancun":
        case "America/Matamoros":
        case "America/Merida":
        case "America/Mexico_City":
        case "America/Monterrey":
          $timezone = 6;
          break;
        case "America/Indiana/Tell_City":
        case "America/Indiana/Knox":
          $timezone = 39;
          break;
        case "America/Rainy_River":
        case "America/Rankin_Inlet":
        case "America/Regina":
        case "America/Resolute":
        case "America/Swift_Current":
        case "America/Winnipeg":
          $timezone = 53;
          break;
        default:
          $timezone = 37;
          break;
      }
      break;
    case -5:
      switch ( $parameters["TimeZone"])
      {
        case "America/Lima":
          $timezone = 7;
          break;
        case "America/Indiana/Petersburg":
        case "America/Indiana/Vevay":
        case "America/Indiana/Indianapolis":
        case "America/Indiana/Marengo":
        case "America/Indiana/Vincennes":
        case "America/Indiana/Winamac":
          $timezone = 38;
          break;
        case "America/Detroit":
          $timezone = 34;
          break;
        case "America/Nassau":
          $timezone = 55;
          break;
        case "America/Atikokan":
        case "America/Coral_Harbour":
        case "America/Iqaluit":
        case "America/Montreal":
        case "America/Nipigon":
        case "America/Pangnirtung":
        case "America/Thunder_Bay":
        case "America/Toronto":
          $timezone = 56;
          break;
        case "America/Havana":
          $timezone = 57;
          break;
        default:
          $timezone = 32;
          break;
      }
      break;
    case -4.5:
      $timezone = 58;
      break;
    case -4:
      switch ( $parameters["TimeZone"])
      {
        case "America/Blanc-Sablon":
        case "America/Glace_Bay":
        case "America/Goose_Bay":
        case "America/Halifax":
        case "America/Moncton":
          $timezone = 42;
          break;
        case "Atlantic/Bermuda":
          $timezone = 60;
          break;
        case "America/Port_of_Spain":
          $timezone = 62;
          break;
        default:
          $timezone = 42;
          break;
      }
      break;
    case -3.5:
      $timezone = 43;
      break;
    case -3:
      switch ( $parameters["TimeZone"])
      {
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
          $timezone = 64;
          break;
        default:
          $timezone = 9;
          break;
      }
      break;
    case -2:
      $timezone = 10;
      break;
    case -1:
      switch ( $parameters["TimeZone"])
      {
        case "Atlantic/Azores":
          $timezone = 65;
          break;
        default:
          $timezone = 11;
          break;
      }
      break;
    case 0:
      switch ( $parameters["TimeZone"])
      {
        case "America/Danmarkshavn":
          $timezone = 66;
          break;
        case "Atlantic/Faroe":
          $timezone = 67;
          break;
        case "Europe/Dublin":
          $timezone = 68;
          break;
        case "Atlantic/Madeira":
        case "Europe/Lisbon":
          $timezone = 69;
          break;
        case "Atlantic/Canary":
          $timezone = 70;
          break;
        case "Europe/London":
          $timezone = 71;
          break;
        case "Africa/Casablanca":
          $timezone = 72;
          break;
        default:
          $timezone = 12;
          break;
      }
      break;
    case 1:
      switch ( $parameters["TimeZone"])
      {
        case "Europe/Tirane":
          $timezone = 73;
          break;
        case "Europe/Vienna":
          $timezone = 74;
          break;
        case "Europe/Brussels":
          $timezone = 75;
          break;
        case "Africa/Ndjamena":
          $timezone = 77;
          break;
        case "Africa/Ceuta":
        case "Europe/Madrid":
          $timezone = 78;
          break;
        case "Europe/Zagreb":
          $timezone = 79;
          break;
        case "Europe/Prague":
          $timezone = 80;
          break;
        case "Europe/Copenhagen":
          $timezone = 81;
          break;
        case "Europe/Berlin":
          $timezone = 13;
          break;
        case "Europe/Paris":
          $timezone = 82;
          break;
        case "Europe/Budapest":
          $timezone = 83;
          break;
        case "Europe/Rome":
          $timezone = 84;
          break;
        case "Europe/Luxembourg":
          $timezone = 85;
          break;
        case "Europe/Skopje":
          $timezone = 86;
          break;
        case "Europe/Amsterdam":
          $timezone = 87;
          break;
        default:
          $timezone = 81;
          break;
      }
      break;
    case 2:
      switch ( $parameters["TimeZone"])
      {
        case "Europe/Tallinn":
          $timezone = 89;
          break;
        case "Europe/Helsinki":
          $timezone = 90;
          break;
        case "Asia/Gaza":
        case "Asia/Hebron":
          $timezone = 91;
          break;
        case "Europe/Athens":
          $timezone = 92;
          break;
        case "Asia/Amman":
          $timezone = 93;
          break;
        case "Europe/Riga":
          $timezone = 94;
          break;
        case "Asia/Beirut":
          $timezone = 95;
          break;
        case "Europe/Chisinau":
          $timezone = 96;
          break;
        case "Europe/Simferopol":
          $timezone = 97;
          break;
        case "Africa/Cairo":
          $timezone = 14;
          break;
        case "Asia/Jerusalem":
          $timezone = 45;
          break;
        case "Europe/Bucharest":
          $timezone = 48;
          break;
        case "Asia/Damascus":
          $timezone = 98;
          break;
        case "Europe/Istanbul":
          $timezone = 99;
          break;
        case "Europe/Kiev":
        case "Europe/Uzhgorod":
        case "Europe/Zaporozhye":
          $timezone = 100;
          break;
        default:
          $timezone = 92;
          break;
      }
      break;
    case 3:
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Baghdad":
          $timezone = 102;
          break;
        case "Europe/Kaliningrad":
          $timezone = 15;
          break;
        default:
          $timezone = 101;
          break;
      }
      break;
    case 3.5:
      $timezone = 47;
      break;
    case 4:
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Yerevan":
          $timezone = 103;
          break;
        case "Asia/Baku":
          $timezone = 104;
          break;
        case "Asia/Tbilisi":
          $timezone = 105;
          break;
        case "Europe/Moscow":
        case "Europe/Samara":
        case "Europe/Volgograd":
          $timezone = 107;
          break;
        case "Asia/Muscat":
          $timezone = 16;
          break;
        default:
          $timezone = 105;
          break;
      }
      break;
    case 4.5:
      $timezone = 108;
      break;
    case 5:
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Aqtau":
        case "Asia/Aqtobe":
        case "Asia/Oral":
          $timezone = 109;
          break;
        case "Asia/Yekaterinburg":
          $timezone = 111;
          break;
        default:
          $timezone = 17;
          break;
      }
      break;
    case 5.5:
      $timezone = 26;
      break;
    case 6:
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Yekaterinburg":
          $timezone = 18;
          break;
        default:
          $timezone = 112;
          break;
      }
      break;
    case 6.5:
      $timezone = 46;
      break;
    case 7:
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Novokuznetsk":
        case "Asia/Novosibirsk":
        case "Asia/Omsk":
          $timezone = 113;
          break;
        case "Indian/Christmas":
          $timezone = 123;
          break;
        default:
          $timezone = 19;
          break;
      }
      break;
    case 8:
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Chongqing":
        case "Asia/Harbin":
        case "Asia/Shanghai":
          $timezone = 20;
          break;
        case "Australia/Perth":
          $timezone = 115;
          break;
        default:
          $timezone = 114;
          break;
      }
      break;
    case 8.8:
      $timezone = 122;
      break;
    case 9:
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Seoul":
          $timezone = 116;
          break;
        default:
          $timezone = 21;
          break;
      }
      break;
    case 9.5:
      switch ( $parameters["TimeZone"])
      {
        case "Australia/Darwin":
          $timezone = 117;
          break;
        default:
          $timezone = 44;
          break;
      }
      break;
    case 10:
      switch ( $parameters["TimeZone"])
      {
        case "Australia/Brisbane":
        case "Australia/Lindeman":
          $timezone = 125;
          break;
        case "Asia/Yakutsk":
          $timezone = 118;
          break;
        default:
          $timezone = 22;
          break;
      }
      break;
    case 10.5:
      $timezone = 119;
      break;
    case 11:
      switch ( $parameters["TimeZone"])
      {
        case "Pacific/Guadalcanal":
          $timezone = 23;
          break;
        default:
          $timezone = 120;
          break;
      }
      break;
    case 11.5:
      $timezone = 124;
      break;
    case 12:
      $timezone = 24;
      break;
    case 12.8:
      $timezone = 121;
      break;
    case 13:
      $timezone = 25;
      break;
    default:
      $timezone = 12;
      break;
  }
  $content = str_replace ( "{{{timezone}}}", $timezone, $content);

  // Set date and time format:
  switch ( $parameters["Country"])
  {
    case "BR":
      $dateformat = 5; // DD/MM/YYYY
      $timeformat = 0; // 24h
      break;
    default:
      $dateformat = 1; // MM DD WWW
      $timeformat = 1; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".xml", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify khomp-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to remove the Khomp auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_khomp_remove ( $buffer, $parameters)
{
  global $_in;

  // Remove AP file
  if ( ! unlink ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".xml"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP removed.");
}

/**
 * Function to create the Khomp audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_khomp_audio ( $buffer, $parameters)
{
  // Return Khomp audio only SIP account template
  return "[" . $parameters["Username"] . "](vd_extension_" . $parameters["Number"] . ",vd_equipment_" . $parameters["Type"] . "))\n" .
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
