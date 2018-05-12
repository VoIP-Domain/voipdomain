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
 * VoIP Domain Cisco equipments models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Cisco
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_model_3905_configure", "equipment_model_cisco_basic_configure");
framework_add_hook ( "equipment_model_3905_firmware_add", "equipment_model_cisco_add_firmare");
framework_add_hook ( "equipment_model_3905_firmware_remove", "equipment_model_cisco_remove_firmware");
framework_add_hook ( "ap_type_3905", "ap_type_cisco_3905");
framework_add_hook ( "ap_type_3905_remove", "ap_type_cisco_remove");
framework_add_hook ( "account_type_3905", "account_type_cisco_3905");

framework_add_hook ( "equipment_model_7811_configure", "equipment_model_cisco_basic_configure");
framework_add_hook ( "equipment_model_7811_firmware_add", "equipment_model_cisco_add_firmare");
framework_add_hook ( "equipment_model_7811_firmware_remove", "equipment_model_cisco_remove_firmware");
framework_add_hook ( "ap_type_7811", "ap_type_cisco_7811");
framework_add_hook ( "ap_type_7811_remove", "ap_type_cisco_remove");
framework_add_hook ( "account_type_7811", "account_type_cisco_7811");

framework_add_hook ( "equipment_model_7942_configure", "equipment_model_cisco_basic_configure");
framework_add_hook ( "equipment_model_7942_firmware_add", "equipment_model_cisco_add_firmare");
framework_add_hook ( "equipment_model_7942_firmware_remove", "equipment_model_cisco_remove_firmware");
framework_add_hook ( "ap_type_7942", "ap_type_cisco_79xx");
framework_add_hook ( "ap_type_7942_remove", "ap_type_cisco_remove");
framework_add_hook ( "account_type_7942", "account_type_cisco_7942");

framework_add_hook ( "equipment_model_8961_configure", "equipment_model_cisco_basic_configure");
framework_add_hook ( "equipment_model_8961_firmware_add", "equipment_model_cisco_add_firmare");
framework_add_hook ( "equipment_model_8961_firmware_remove", "equipment_model_cisco_remove_firmware");
framework_add_hook ( "ap_type_8961", "ap_type_cisco_8961");
framework_add_hook ( "ap_type_8961_remove", "ap_type_cisco_remove");
framework_add_hook ( "account_type_8961", "account_type_cisco_8961");

/**
 * Cleanup functions
 */
framework_add_hook ( "ap_cisco_wipe", "ap_cisco_wipe");
cleanup_register ( "Accounts-Cisco", "ap_cisco_wipe", array ( "Before" => array ( "Accounts")));

framework_add_hook ( "firmware_cisco_wipe", "firmware_cisco_wipe");
cleanup_register ( "Firmwares-Cisco", "firmware_cisco_wipe", array ( "Before" => array ( "Equipments")));

/**
 * Function to wipe the Cisco equipment models auto provisioning files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function ap_cisco_wipe ( $buffer, $parameters)
{
  // Remove auto provisioning configuration files:
  foreach ( list_config ( "datafile", "ap") as $ap)
  {
    $data = read_config ( "datafile", $ap);
    if ( equipment_model_exists ( $ap["Type"], "Cisco"))
    {
      unlink_config ( "ap", "SEP" . strtoupper ( $data["MAC"]) . ".cnf.xml");
      unlink_config ( "ap", "SEP" . strtoupper ( $data["MAC"]) . ".dpt.xml.gz");
      unlink_config ( "datafile", $ap);
    }
  }

  return $buffer;
}

/**
 * Function to wipe the Cisco equipment models firmware files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function firmware_cisco_wipe ( $buffer, $parameters)
{
  // Cisco 3905
  unlink_config ( "ap", "Cisco/3905/APP3905.9-4-1SR3.zz");
  unlink_config ( "ap", "Cisco/3905/CP3905.9-4-1SR3.loads");
  unlink_config ( "apdir", "Cisco/3905");

  // Cisco 7811
  unlink_config ( "ap", "Cisco/7811/kern2.78xx.14-2-1-0101-26.sbn");
  unlink_config ( "ap", "Cisco/7811/kern78xx.14-2-1-0101-26.sbn");
  unlink_config ( "ap", "Cisco/7811/rootfs2.78xx.14-2-1-0101-26.sbn");
  unlink_config ( "ap", "Cisco/7811/rootfs78xx.14-2-1-0101-26.sbn");
  unlink_config ( "ap", "Cisco/7811/sboot2.78xx.14-2-1-0101-26.sbn");
  unlink_config ( "ap", "Cisco/7811/sboot78xx.14-2-1-0101-26.sbn");
  unlink_config ( "ap", "Cisco/7811/sip78xx.14-2-1-0101-26.loads");
  unlink_config ( "apdir", "Cisco/7811");

  // Cisco 8961
  unlink_config ( "ap", "Cisco/8961/dkern8961.100609R2-9-4-1-9.sebn");
  unlink_config ( "ap", "Cisco/8961/kern8961.9-4-1-9.sebn");
  unlink_config ( "ap", "Cisco/8961/rootfs8961.9-4-1-9.sebn");
  unlink_config ( "ap", "Cisco/8961/sboot8961.031610R1-9-4-1-9.sebn");
  unlink_config ( "ap", "Cisco/8961/sip8961.9-4-1-9.loads");
  unlink_config ( "ap", "Cisco/8961/skern8961.022809R2-9-4-1-9.sebn");
  unlink_config ( "ap", "Cisco/8961/softkeys.xml");
  unlink_config ( "apdir", "Cisco/8961");

  // Cisco vendor directory
  unlink_config ( "apdir", "Cisco");

  // **TODO**: Remover diretório Locale da Cisco!!!

  return $buffer;
}

/**
 * Function to configure the basic Cisco equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_cisco_basic_configure ( $buffer, $parameters)
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
        $codecs[] = "g729";
        break;
      case "G722":
        $codecs[] = "g722";
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
 * Function to add a Cisco firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_cisco_add_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "cmterm-3905.9-4-1SR3.zip":		// Cisco 3905
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Cisco/3905") && ! mkdir ( $_in["general"]["tftpdir"] . "/Cisco/3905", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Cisco/3905/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      // **TODO**: Need to explode file, extracting firmware files (and keeping zip file after)

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "cmterm-78xx.11-7-1-17.zip":		// Cisco 7811
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Cisco/7811") && ! mkdir ( $_in["general"]["tftpdir"] . "/Cisco/7811", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Cisco/7811/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      // **TODO**: Need to explode file, extracting firmware files (and keeping zip file after)

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "cmterm-8961.9-4-1-9_REL.zip":		// Cisco 8961
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Cisco/8961") && ! mkdir ( $_in["general"]["tftpdir"] . "/Cisco/8961", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Cisco/8961/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      // **TODO**: Need to explode file, extracting firmware files (and keeping zip file after)

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "po-locale-pt_BR-14.2.1.4001-1.tar":	// Cisco locale language files to Brazilian Portuguese
    case "po-locale-en_GB-14.2.1.4001-1.tar":	// Cisco locale language files to British English
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Cisco/Locale") && ! mkdir ( $_in["general"]["tftpdir"] . "/Cisco/Locale", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Cisco/Locale/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      // **TODO**: Need to explode file, extracting firmware files (and keeping tar file after)

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to remove a Cisco firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_cisco_remove_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "cmterm-3905.9-4-1SR3.zip":		// Cisco 3905
      @unlink ( $_in["general"]["tftpdir"] . "/Cisco/3905/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Cisco/3905");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "cmterm-78xx.11-7-1-17.zip":		// Cisco 7811
      @unlink ( $_in["general"]["tftpdir"] . "/Cisco/7811/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Cisco/7811");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "cmterm-8961.9-4-1-9_REL.zip":		// Cisco 8961
      @unlink ( $_in["general"]["tftpdir"] . "/Cisco/8961/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Cisco/8961");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "po-locale-pt_BR-14.2.1.4001-1.tar":	// Cisco locale language files to Brazilian Portuguese
    case "po-locale-en_GB-14.2.1.4001-1.tar":	// Cisco locale language files to British English
      @unlink ( $_in["general"]["tftpdir"] . "/Cisco/Locale/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Cisco/Locale");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}


/**
 * Function to create the Cisco 79XX phones auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_cisco_79xx ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content
  $content = file_get_contents ( dirname ( __FILE__) . "/template-cisco-79xx.xml");

// * Required parameters are: (int) Number, (string) Username, (string) Password, (string) Name, (string) MAC, (string) Type, (string) Domain
// $notify = array ( "Number" => $parameters["Number"], "Username" => $account["Username"], "Password" => $account["Password"], "Name" => $parameters["Description"], "MAC" => $account["MAC"], "Type" => $account["Template"], "Domain" => $parameters["GroupReg"]["Domain"], "NTP" => $parameters["Range"]["NTP"]);
// * country, external call prefix (to build dialplan string)
// * country (to set the comfort tones and phone interface language)
  $content = str_replace ( "{{{username}}}", $parameters["Username"], $content);
  $content = str_replace ( "{{{password}}}", $parameters["Password"], $content);
  $content = str_replace ( "{{{displayname}}}", $parameters["Name"], $content);
  $content = str_replace ( "{{{domain}}}", $parameters["Domain"], $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/SEP" . strtoupper ( $parameters["MAC"]) . ".cnf.xml", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify cisco-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Cisco 3905 phones auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_cisco_3905 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "3905":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-cisco-" . $parameters["Type"] . ".xml");
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
  $content = str_replace ( "{{{g722support}}}", in_array ( "G722", $equipmentconfig["AudioCodecs"]) ? "2" : "1", $content);
  $content = str_replace ( "{{{advertiseg722}}}", in_array ( "G722", $equipmentconfig["AudioCodecs"]) ? "1" : "0", $content);

  // Set phone language:
  $language = "";
  $langcode = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "portuguese_brazil";
      $langcode = "pt_BR";
      break;
    case "en_US":
      $language = "english_united_states";
      $langcode = "en_US";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Arabic:
      case "DZ": // Algeria
        $language = "arabic_algeria";
        $langcode = "ar_DZ";
        break;
      case "BH": // Bahrain
        $language = "arabic_bahrain";
        $langcode = "ar_BH";
        break;
      case "EG": // Egypt
        $language = "arabic_egypt";
        $langcode = "ar_EG";
        break;
      case "IQ": // Iraq
        $language = "arabic_iraq";
        $langcode = "ar_IQ";
        break;
      case "JO": // Jordan
        $language = "arabic_jordan";
        $langcode = "ar_JO";
        break;
      case "KW": // Kuwait
        $language = "arabic_kuwait";
        $langcode = "ar_KW";
        break;
      case "LB": // Lebanon
        $language = "arabic_lebanon";
        $langcode = "ar_LB";
        break;
      case "MO": // Morocco
        $language = "arabic_morocco";
        $langcode = "ar_MA";
        break;
      case "OM": // Oman
        $language = "arabic_oman";
        $langcode = "ar_OM";
        break;
      case "QA": // Qatar
        $language = "arabic_qatar";
        $langcode = "ar_QA";
        break;
      case "SA": // Saudi Arabia
        $language = "arabic_saudi_arabia";
        $langcode = "ar_SA";
        break;
      case "TN": // Tunisia
        $language = "arabic_tunisia";
        $langcode = "ar_TN";
        break;
      case "AE": // United Arab Emirates
      case "TD": // Chad
      case "KM": // Comoros
      case "DJ": // Djibouti
      case "LY": // Libya
      case "ML": // Mali
      case "MR": // Mauritania
      case "PS": // Palestine, State of
      case "SO": // Somalia
      case "SD": // Sudan
      case "SY": // Syrian Arab Republic
        $language = "arabic_united_arab_emirates";
        $langcode = "ar_AE";
        break;
      case "YE": // Yemen
        $language = "arabic_yemen";
        $langcode = "ar_YE";
        break;

      // Bulgarian:
      case "BG": // Bulgaria
        $language = "bulgarian_bulgaria";
        $langcode = "bg_BG";
        break;

      // Chinese:
      case "CN": // China
        $language = "chinese_china";
        $langcode = "zh_CN";
        break;
      case "HK": // Hong Kong
        $language = "chinese_hong_kong";
        $langcode = "zh_HK";
        break;
      case "TW": // Taiwan
        $language = "chinese_taiwan";
        $langcode = "zh_TW";
        break;

      // Croatian:
      case "HR": // Croatia
        $language = "croatian_croatia";
        $langcode = "hr_HR";
        break;

      // Czech:
      case "CZ": // Czech Republic
        $language = "czech_czech_republic";
        $langcode = "cs_CZ";
        break;

      // Danish:
      case "DK": // Denmark
        $language = "danish_denmark";
        $langcode = "da_DK";
        break;

      // Dutch:
      case "NL": // Netherlands
        $language = "dutch_netherlands";
        $langcode = "nl_NL";
        break;

      // English (United Kingdom):
      case "GB": // United Kingdom of Great Britain and Northern Ireland
      case "IE": // Ireland
        $language = "english_united_kingdom";
        $langcode = "en_GB";
        break;

      // Estonian:
      case "EE": // Estonia
        $language = "estonian_estonia";
        $langcode = "et_EE";
        break;

      // Finnish:
      case "FI": // Finland
        $language = "finnish_finland";
        $langcode = "fi_FI";
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
        $language = "french_france";
        $langcode = "fr_FR";
        break;

      // German:
      case "AT": // Austria
        $language = "german_austria";
        $langcode = "de_AT";
        break;
      case "CH": // Switzerland
        $language = "german_switzerland";
        $langcode = "de_CH";
        break;
      case "DE": // Germany
      case "BE": // Belgium
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "german_germany";
        $langcode = "de_DE";
        break;

      // Greek:
      case "GR": // Greece
        $language = "greek_greece";
        $langcode = "el_GR";
        break;

      // Hebrew:
      case "IL": // Israel
        $language = "hebrew_israel";
        $langcode = "he_IL";
        break;

      // Hungarian:
      case "HU": // Hungary
        $language = "hungarian_hungary";
        $langcode = "hu_HU";
        break;

      // Italian:
      case "IT": // Italy
        $language = "italian_italy";
        $langcode = "it_IT";
        break;

      // Japanese:
      case "JP": // Japan
        $language = "japanese_japan";
        $langcode = "ja_JP";
        break;

      // Korean:
      case "KP": // Korea (Democratic People's Republic of)
      case "KR": // Korea (Republic of)
        $language = "korean_korea_republic";
        $langcode = "ko_KR";
        break;

      // Latvian:
      case "LV": // Latvia
        $language = "latvian_latvia";
        $langcode = "lv_LV";
        break;

      // Lithuanian
      case "LT": // Lithuania 
        $language = "lithuanian_lithuania";
        $langcode = "lt_LT";
        break;

      // Norwegian
      case "NO": // Norway
        $language = "norwegian_norway";
        $langcode = "no_NO";
        break;

      // Polish:
      case "PL": // Poland
        $language = "polish_poland";
        $langcode = "pl_PL";
        break;

      // Portuguese:
      case "BR": // Brazil
        $language = "portuguese_brazil";
        $langcode = "pt_BR";
        break;
      case "AO": // Angola
      case "CV": // Cabo Verde
      case "GW": // Guinea-Bissau
      case "TL": // Timor-Leste
      case "MO": // Macao
      case "MZ": // Mozambique
      case "PT": // Portugal
      case "ST": // Sao Tome and Principe
        $language = "portuguese_portugal";
        $langcode = "pt_PT";
        break;

      // Romanian:
      case "RO": // Romania
        $language = "romanian_romania";
        $langcode = "ro_RO";
        break;

      // Russian:
      case "RU": // Russian Federation
        $language = "russian_russian_federation";
        $langcode = "ru_RU";
        break;

      // Serbian:
      case "ME": // Montenegro
        $language = "serbian_republic_of_montenegro";
        $langcode = "sr_ME";
        break;
      case "RS": // Serbia
        $language = "serbian_republic_of_serbia";
        $langcode = "sr_RS";
        break;

      // Slovak:
      case "SK": // Slovakia
        $language = "slovak_slovakia";
        $langcode = "sk_SK";
        break;

      // Slovenian:
      case "SI": // Slovenia
        $language = "slovenian_slovenia";
        $langcode = "sl_SI";
        break;

      // Spanish:
      case "AR": // Argentina
      case "CL": // Chile
      case "PY": // Paraguay
      case "UY": // Uruguay
        $language = "spanish_argentina";
        $langcode = "es_AR";
        break;
      case "CO": // Colombia
      case "PE": // Peru
      case "VE": // Venezuela
      case "GT": // Guatemala
      case "EC": // Ecuador
      case "BO": // Bolivia
      case "CU": // Cuba
      case "DO": // Dominican Republic
      case "HN": // Honduras
      case "SV": // El Salvador
      case "NI": // Nicaragua
      case "PA": // Panama
      case "GQ": // Equatorial Guinea
        $language = "spanish_colombia";
        $langcode = "es_CO";
        break;
      case "ES": // Spain
      case "CR": // Costa Rica
      case "PR": // Puerto Rico
      case "MX": // Mexico
        $language = "spanish_spain";
        $langcode = "es_ES";
        break;

      // Swedish:
      case "SE": // Sweden
        $language = "swedish_sweden";
        $langcode = "sv_SE";
        break;

      // Thai:
      case "TH": // Thailand
        $language = "thai_thailand";
        $langcode = "th_TH";
        break;

      // Turkish:
      case "TR": // Turkey
        $language = "turkish_turkiye";
        $langcode = "tr_TR";
        break;

      // Ukranian:
      case "UA": // Ukraine
        $language = "ukrainian_ukraine";
        $langcode = "uk_UA";
        break;

      // Default (English United States):
      default:
        $language = "english_united_states";
        $langcode = "en_US";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $content = str_replace ( "{{{langcode}}}", $langcode, $content);

  // Set phone country tone:
  $locale = "";
  switch ( $parameters["Country"])
  {
    case "DZ": // Algeria
      $locale = "algeria";
      break;
    case "AR": // Argentina
      $locale = "argentina";
      break;
    case "AU": // Australia
      $locale = "australia";
      break;
    case "AT": // Austria
      $locale = "austria";
      break;
    case "BH": // Bahrain
      $locale = "bahrain";
      break;
    case "BE": // Belgium
      $locale = "belgium";
      break;
    case "BR": // Brazil
      $locale = "brazil";
      break;
    case "BG": // Bulgaria
      $locale = "bulgaria";
      break;
    case "CA": // Canada
      $locale = "canada";
      break;
    case "CL": // Chile
      $locale = "chile";
      break;
    case "CH": // China
      $locale = "china";
      break;
    case "CO": // Colombia
      $locale = "colombia";
      break;
    case "HR": // Croatia
      $locale = "croatia";
      break;
    case "CY": // Cyprus
      $locale = "cyprus";
      break;
    case "CZ": // Czech Republic
      $locale = "czech_republic";
      break;
    case "DK": // Denmark
      $locale = "denmark";
      break;
    case "EG": // Egypt
      $locale = "egypt";
      break;
    case "EE": // Estonia
      $locale = "estonia";
      break;
    case "FI": // Finland
      $locale = "finland";
      break;
    case "FR": // France
      $locale = "france";
      break;
    case "DE": // Germany
      $locale = "germany";
      break;
    case "GH": // Ghana
      $locale = "ghana";
      break;
    case "GR": // Greece
      $locale = "greece";
      break;
    case "HK": // Hong Kong
      $locale = "hong_kong";
      break;
    case "HU": // Hungary
      $locale = "hungary";
      break;
    case "IS": // Iceland
      $locale = "iceland";
      break;
    case "IN": // India
      $locale = "india";
      break;
    case "ID": // Indonesia
      $locale = "indonesia";
      break;
    case "IQ": // Iraq
      $locale = "iraq";
      break;
    case "IE": // Ireland
      $locale = "ireland";
      break;
    case "IL": // Israel
      $locale = "israel";
      break;
    case "IT": // Italy
      $locale = "italy";
      break;
    case "JP": // Japan
      $locale = "japan";
      break;
    case "JO": // Jordan
      $locale = "jordan";
      break;
    case "KE": // Kenya
      $locale = "kenya";
      break;
    case "KP": // Korea (Democratic People's Republic of)
    case "KR": // Korea (Republic of)
      $locale = "korea_republic";
      break;
    case "KW": // Kuwait
      $locale = "kuwait";
      break;
    case "LV": // Latvia
      $locale = "latvia";
      break;
    case "LB": // Lebanon
      $locale = "lebanon";
      break;
    case "LT": // Lithuania
      $locale = "lithuania";
      break;
    case "LU": // Luxembourg
      $locale = "luxembourg";
      break;
    case "MY": // Malaysia
      $locale = "malaysia";
      break;
    case "MR": // Mauritania
      $locale = "mauritania";
      break;
    case "MX": // Mexico
      $locale = "mexico";
      break;
    case "MA": // Morocco
      $locale = "morocco";
      break;
    case "NP": // Nepal
      $locale = "nepal";
      break;
    case "NL": // Netherlands
      $locale = "netherlands";
      break;
    case "NZ": // New Zealand
      $locale = "new_zealand";
      break;
    case "NG": // Nigeria
      $locale = "nigeria";
      break;
    case "NO": // Norway
      $locale = "norway";
      break;
    case "OM": // Oman
      $locale = "oman";
      break;
    case "PK": // Pakistan
      $locale = "pakistan";
      break;
    case "PA": // Panama
      $locale = "panama";
      break;
    case "PE": // Peru
      $locale = "peru";
      break;
    case "PH": // Philippines
      $locale = "philippines";
      break;
    case "PL": // Poland
      $locale = "poland";
      break;
    case "PT": // Portugal
      $locale = "portugal";
      break;
    case "QA": // Qatar
      $locale = "qatar";
      break;
    case "ME": // Montenegro
      $locale = "republic_of_montenegro";
      break;
    case "RS": // Serbia
      $locale = "republic_of_serbia";
      break;
    case "RO": // Romania
      $locale = "romania";
      break;
    case "RU": // Russian Federation
      $locale = "russian_federation";
      break;
    case "SA": // Saudi Arabia
      $locale = "saudi_arabia";
      break;
    case "SG": // Singapore
      $locale = "singapore";
      break;
    case "SK": // Slovakia
      $locale = "slovakia";
      break;
    case "SI": // Slovenia
      $locale = "slovenia";
      break;
    case "ZA": // South Africa
      $locale = "south_africa";
      break;
    case "ES": // Spain
      $locale = "spain";
      break;
    case "SD": // Sudan
      $locale = "sudan";
      break;
    case "SE": // Sweden
      $locale = "sweden";
      break;
    case "CH": // Switzerland
      $locale = "switzerland";
      break;
    case "TW": // Taiwan
      $locale = "taiwan";
      break;
    case "TH": // Thailand
      $locale = "thailand";
      break;
    case "TN": // Tunisia
      $locale = "tunisia";
      break;
    case "TR": // Turkey
      $locale = "turkiye";
      break;
    case "UA": // Ukraine
      $locale = "ukraine";
      break;
    case "AE": // United Arab Emirates
      $locale = "united_arab_emirates";
      break;
    case "GB": // United Kingdom of Great Britain and Northern Ireland
      $locale = "united_kingdom";
      break;
    case "VE": // Venezuela (Bolivarian Republic of)
      $locale = "venezuela";
      break;
    case "VN": // Viet Nam
      $locale = "vietnam";
      break;
    case "YE": // Yemen
      $locale = "yemen";
      break;
    case "ZW": // Zimbabwe
      $locale = "zimbabwe";
      break;

    default: // United States of America
      $locale = "united_states";
      break;
  }
  $content = str_replace ( "{{{locale}}}", $locale, $content);

  // Create dialplan:
  $dialplan = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  $dialplan .= "<DIALTEMPLATE>\n";
  $dialpattern = array ();
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    $dialentry = ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] . "," : "") . $dialplanentry["Pattern"];
    $dialentry = str_replace ( "*", "\\*", $dialentry);
    $dialentry = str_replace ( ".", "*", $dialentry);
    $dialentry = str_replace ( "[0-9]", "X", $dialentry);
    $dialentry = str_replace ( "X", ".", $dialentry);	// We know that X means 0-9 (and not ., that means 0-9, # or *), but Cisco dialpattern are very restrictive and will generate a very big dialtemplate that will fail to load.
    $dialentry = str_replace ( "Z", ".", $dialentry);	// We know that Z means 1-9 (and not ., that means 0-9, # or *), but Cisco dialpattern are very restrictive and will generate a very big dialtemplate that will fail to load.
    $dialentry = str_replace ( "N", ".", $dialentry);	// We know that N means 2-9 (and not ., that means 0-9, # or *), but Cisco dialpattern are very restrictive and will generate a very big dialtemplate that will fail to load.
    $dialpattern[] = $dialentry;
  }
  while ( array_search_substring ( "[", $dialpattern))
  {
    foreach ( $dialpattern as $index => $dialentry)
    {
      if ( strpos ( $dialentry, "[") !== false)
      {
        unset ( $dialpattern[$index]);
        break;
      }
    }
    $start = substr ( $dialentry, 0, strpos ( $dialentry, "["));
    $end = substr ( $dialentry, strpos ( $dialentry, "]") + 1);
    $toexpand = substr ( $dialentry, strlen ( $start) + 1, strlen ( $dialentry) - strlen ( $start) - strlen ( $end) - 2);

    $processed = "";
    for ( $x = 0; $x < strlen ( $toexpand); $x++)
    {
      if ( substr ( $toexpand, $x + 1, 1) == "-")
      {
        foreach ( range ( substr ( $toexpand, $x, 1), substr ( $toexpand, $x + 2, 1)) as $tmp)
        {
          $processed .= $tmp;
        }
        $x += 2;
      } else {
        $processed .= substr ( $toexpand, $x, 1);
      }
    }

    for ( $x = 0; $x < strlen ( $processed); $x++)
    {
      $dialpattern[] = $start . substr ( $processed, $x, 1) . $end;
    }
  }
  foreach ( $dialpattern as $dialentry)
  {
    $dialplan .= "  <TEMPLATE MATCH=\"" . $dialentry . "\" Timeout=\"" . ( strpos ( $dialentry, "*") !== false && substr ( $dialentry, strpos ( $dialentry, "*") - 1, 1) != "\\" ? "3" : "0") . "\" />\n";
  }
  $dialplan .= "  <TEMPLATE MATCH=\"*\" Timeout=\"3\" />\n";
  $dialplan .= "</DIALTEMPLATE>\n";
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/SEP" . strtoupper ( $parameters["MAC"]) . ".dpt.xml.gz", gzencode ( $dialplan, 9)) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }
  $content = str_replace ( "{{{dialtemplate}}}", "SEP" . strtoupper ( $parameters["MAC"]) . ".dpt.xml.gz", $content);

  // Set NTP servers and time offset:
  $ntpservers = "      <ntps>\n";
  $ntpips = array ();
  foreach ( $parameters["NTP"] as $ntp)
  {
    if ( $hosts = gethostbynamel ( $ntp))
    {
      $ntpips = array_merge_recursive ( $ntpips, $hosts);
    }
  }
  foreach ( $ntpips as $ntp)
  {
    $ntpservers .= "        <ntp>\n";
    $ntpservers .= "          <name>" . $ntp . "</name>\n";
    $ntpservers .= "          <ntpMode>unicast</ntpMode>\n";
    $ntpservers .= "        </ntp>\n";
  }
  $ntpservers .= "      </ntps>\n";
  switch ( $parameters["Offset"] * 60)
  {
    case -720:	// GMT-12:00
      $timezone = "Dateline Standard Time";
      break;
    case -660:	// GMT-11:00
      $timezone = "Samoa Standard Time";
      break;
    case -600:	// GMT-10:00
      $timezone = "Hawaiian Standard Time";
      break;
    case -540:	// GMT-09:00
      $timezone = "Alaskan Standard/Daylight Time";
      break;
    case -480:	// GMT-08:00
      $timezone = "Pacific Standard/Daylight Time";
      break;
    case -420:	// GMT-07:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Boise":
        case "America/Denver":
        case "America/Phoenix":
          $timezone = "Mountain Standard/Daylight Time";
          break;
        default:
          $timezone = "US Mountain Standard Time";
          break;
      }
      break;
    case -360:	// GMT-06:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Bahia_Banderas":
        case "America/Cancun":
        case "America/Matamoros":
        case "America/Merida":
        case "America/Mexico_City":
        case "America/Monterrey":
          $timezone = "Mexico Standard/Daylight Time";
          break;
        case "America/Chicago":
        case "America/Indiana/Knox":
        case "America/Indiana/Tell_City":
        case "America/Menominee":
        case "America/North_Dakota/Beulah":
        case "America/North_Dakota/Center":
        case "America/North_Dakota/New_Salem":
          $timezone = "Central Standard/Daylight Time";
          break;
        default:
          $timezone = "Canada Central Standard Time";
          break;
      }
      break;
    case -300:	// GMT-05:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Detroit":
        case "America/Indiana/Indianapolis":
        case "America/Indiana/Marengo":
        case "America/Indiana/Petersburg":
        case "America/Indiana/Vevay":
        case "America/Indiana/Vincennes":
        case "America/Indiana/Winamac":
        case "America/Kentucky/Louisville":
        case "America/Kentucky/Monticello":
        case "America/New_York":
          $timezone = "Eastern Standard/Daylight Time";
          break;
        case "America/Bogota":
        case "America/Cayman":
        case "America/Grand_Turk":
        case "America/Guayaquil":
        case "America/Havana":
        case "America/Jamaica":
        case "America/Lima":
        case "America/Nassau":
        case "America/Panama":
        case "America/Port-au-Prince":
        case "Pacific/Easter":
          $timezone = "SA Pacific Standard Time";
          break;
        default:
          $timezone = "US Eastern Standard Time";
          break;
      }
      break;
    case -240:	// GMT-04:00
      switch ( $parameters["TimeZone"])
      {
        case "Atlantic/Bermuda":
          $timezone = "Atlantic Standard/Daylight Time";
          break;
        case "America/Anguilla":
        case "America/Antigua":
        case "America/Aruba":
        case "America/Barbados":
        case "America/Curacao":
        case "America/Dominica":
        case "America/Grenada":
        case "America/Guadeloupe":
        case "America/Guyana":
        case "America/Kralendijk":
        case "America/Lower_Princes":
        case "America/Marigot":
        case "America/Martinique":
        case "America/Montserrat":
        case "America/Port_of_Spain":
        case "America/Puerto_Rico":
        case "America/Santo_Domingo":
        case "America/St_Barthelemy":
        case "America/St_Kitts":
        case "America/St_Lucia":
        case "America/St_Thomas":
        case "America/St_Vincent":
        case "America/Thule":
        case "America/Tortola":
          $timezone = "Pacific SA Standard Time";
          break;
        default:
          $timezone = "SA Western Standard Time";
          break;
      }
      break;
    case -210:	// GMT-03:30
      $timezone = "Newfoundland Standard/Daylight Time";
      break;
    case -180:	// GMT-03:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Araguaina":
        case "America/Belem":
        case "America/Campo_Grande":
        case "America/Cuiaba":
        case "America/Fortaleza":
        case "America/Maceio":
        case "America/Recife":
        case "America/Santarem":
        case "America/Sao_Paulo":
          $timezone = "SA Eastern Standard Time";
          break;
        case "Atlantic/Stanley":
          $timezone = "Pacific SA Daylight Time";
          break;
        default:
          $timezone = "E. South America Standard/Daylight Time";
          break;
      }
      break;
    case -120:	// GMT-02:00
      $timezone = "Mid-Atlantic Standard/Daylight Time";
      break;
    case -60:	// GMT-01:00
      $timezone = "Azores Standard/Daylight Time";
      break;
    case 0:	// GMT+00:00
      $timezone = "Greenwich Standard Time";
      break;
    case 60:	// GMT+01:00
      switch ( $parameters["TimeZone"])
      {
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
          $timezone = "GTB Standard/Daylight Time";
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
          $timezone = "Egypt Standard/Daylight Time";
          break;
        default:
          $timezone = "W. Europe Standard/Daylight Time";
          break;
      }
      break;
    case 120:	// GMT+02:00
      switch ( $parameters["TimeZone"])
      {
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
          $timezone = "South Africa Standard Time";
          break;
        case "Asia/Jerusalem":
          $timezone = "Jerusalem Standard/Daylight Time";
          break;
        default:
          $timezone = "Central Europe Standard/Daylight Time";
          break;
      }
      break;
    case 180:	// GMT+03:00
      switch ( $parameters["TimeZone"])
      {
        case "Europe/Kaliningrad":
        case "Europe/Minsk":
          $timezone = "Russian Standard/Daylight Time";
          break;
        default:
          $timezone = "Saudi Arabia Standard Time";
          break;
      }
      break;
    case 210:	// GMT+03:30
      $timezone = "Iran Standard/Daylight Time";
      break;
    case 240:	// GMT+04:00
      switch ( $parameters["TimeZone"])
      {
        case "Europe/Moscow":
        case "Europe/Samara":
        case "Europe/Volgograd":
          $timezone = "Caucasus Standard/Daylight Time";
          break;
        default:
          $timezone = "Arabian Standard Time";
          break;
      }
      break;
    case 270:	// GMT+04:30
      $timezone = "Afghanistan Standard Time";
      break;
    case 300:	// GMT+05:00
      $timezone = "West Asia Standard Time";
      break;
    case 330:	// GMT+05:30
      $timezone = "India Standard Time";
      break;
    case 360:	// GMT+06:00
      $timezone = "Central Asia Standard Time";
      break;
    case 420:	// GMT+07:00
      $timezone = "SE Asia Standard Time";
      break;
    case 480:	// GMT+08:00
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Taipei":
          $timezone = "Taipei Standard Time";
          break;
        default:
          $timezone = "China Standard/Daylight Time";
          break;
      }
      break;
    case 540:	// GMT+09:00
      $timezone = "Tokyo Standard Time";
      break;
    case 570:	// GMT+09:30
      $timezone = "Cen. Australia Standard/Daylight Time";
      break;
    case 600:	// GMT+10:00
      switch ( $parameters["TimeZone"])
      {
        case "Australia/Brisbane":
        case "Australia/Lindeman":
          $timezone = "AUS Eastern Standard/Daylight Time";
          break;
        case "Pacific/Chuuk":
        case "Pacific/Guam":
        case "Pacific/Port_Moresby":
        case "Pacific/Saipan":
          $timezone = "West Pacific Standard Time";
          break;
        default:
          $timezone = "Tasmania Standard/Daylight Time";
          break;
      }
      break;
    case 660:	// GMT+11:00
      $timezone = "Central Pacific Standard Time";
      break;
    case 720:	// GMT+12:00
      $timezone = "New Zealand Standard/Daylight Time";
      break;
    default:	// GMT+00:00
      $timezone = "Greenwich Standard Time";
      break;
  }
  $content = str_replace ( "{{{ntpservers}}}", $ntpservers, $content);
  $content = str_replace ( "{{{timezone}}}", $timezone, $content);

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
      $dateformat = "D/M/YY";
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
      $dateformat = "D.M.YY";
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = "D-M-YY";
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
      $dateformat = "YY-M-D";
      break;
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
      $dateformat = "YY/M/D";
      break;
    case "HU": // Hungary
    case "KZ": // Kazakhstan
      $dateformat = "YY.M.D";
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = "M/D/YYA";
      break;
    case "ID": // Indonesia
      $dateformat = "M-D-YY";
      break;
    default: // Most used format (M/D/YYA)
      $dateformat = "M/D/YYA";
      break;
  }
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/SEP" . strtoupper ( $parameters["MAC"]) . ".cnf.xml", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify cisco-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Cisco 7811 phones auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_cisco_7811 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "7811":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-cisco-" . $parameters["Type"] . ".xml");
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
  $content = str_replace ( "{{{g722support}}}", in_array ( "G722", $equipmentconfig["AudioCodecs"]) ? "2" : "1", $content);
  $content = str_replace ( "{{{advertiseg722}}}", in_array ( "G722", $equipmentconfig["AudioCodecs"]) ? "1" : "0", $content);

  // Set phone language:
  $language = "";
  $langcode = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "portuguese_brazil";
      $langcode = "pt_BR";
      break;
    case "en_US":
      $language = "english_united_states";
      $langcode = "en_US";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Arabic:
      case "DZ": // Algeria
        $language = "arabic_algeria";
        $langcode = "ar_DZ";
        break;
      case "BH": // Bahrain
        $language = "arabic_bahrain";
        $langcode = "ar_BH";
        break;
      case "EG": // Egypt
        $language = "arabic_egypt";
        $langcode = "ar_EG";
        break;
      case "IQ": // Iraq
        $language = "arabic_iraq";
        $langcode = "ar_IQ";
        break;
      case "JO": // Jordan
        $language = "arabic_jordan";
        $langcode = "ar_JO";
        break;
      case "KW": // Kuwait
        $language = "arabic_kuwait";
        $langcode = "ar_KW";
        break;
      case "LB": // Lebanon
        $language = "arabic_lebanon";
        $langcode = "ar_LB";
        break;
      case "MO": // Morocco
        $language = "arabic_morocco";
        $langcode = "ar_MA";
        break;
      case "OM": // Oman
        $language = "arabic_oman";
        $langcode = "ar_OM";
        break;
      case "QA": // Qatar
        $language = "arabic_qatar";
        $langcode = "ar_QA";
        break;
      case "SA": // Saudi Arabia
        $language = "arabic_saudi_arabia";
        $langcode = "ar_SA";
        break;
      case "TN": // Tunisia
        $language = "arabic_tunisia";
        $langcode = "ar_TN";
        break;
      case "AE": // United Arab Emirates
      case "TD": // Chad
      case "KM": // Comoros
      case "DJ": // Djibouti
      case "LY": // Libya
      case "ML": // Mali
      case "MR": // Mauritania
      case "PS": // Palestine, State of
      case "SO": // Somalia
      case "SD": // Sudan
      case "SY": // Syrian Arab Republic
        $language = "arabic_united_arab_emirates";
        $langcode = "ar_AE";
        break;
      case "YE": // Yemen
        $language = "arabic_yemen";
        $langcode = "ar_YE";
        break;

      // Bulgarian:
      case "BG": // Bulgaria
        $language = "bulgarian_bulgaria";
        $langcode = "bg_BG";
        break;

      // Chinese:
      case "CN": // China
        $language = "chinese_china";
        $langcode = "zh_CN";
        break;
      case "HK": // Hong Kong
        $language = "chinese_hong_kong";
        $langcode = "zh_HK";
        break;
      case "TW": // Taiwan
        $language = "chinese_taiwan";
        $langcode = "zh_TW";
        break;

      // Croatian:
      case "HR": // Croatia
        $language = "croatian_croatia";
        $langcode = "hr_HR";
        break;

      // Czech:
      case "CZ": // Czech Republic
        $language = "czech_czech_republic";
        $langcode = "cs_CZ";
        break;

      // Danish:
      case "DK": // Denmark
        $language = "danish_denmark";
        $langcode = "da_DK";
        break;

      // Dutch:
      case "NL": // Netherlands
        $language = "dutch_netherlands";
        $langcode = "nl_NL";
        break;

      // English (United Kingdom):
      case "GB": // United Kingdom of Great Britain and Northern Ireland
      case "IE": // Ireland
        $language = "english_united_kingdom";
        $langcode = "en_GB";
        break;

      // Estonian:
      case "EE": // Estonia
        $language = "estonian_estonia";
        $langcode = "et_EE";
        break;

      // Finnish:
      case "FI": // Finland
        $language = "finnish_finland";
        $langcode = "fi_FI";
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
        $language = "french_france";
        $langcode = "fr_FR";
        break;

      // German:
      case "AT": // Austria
        $language = "german_austria";
        $langcode = "de_AT";
        break;
      case "CH": // Switzerland
        $language = "german_switzerland";
        $langcode = "de_CH";
        break;
      case "DE": // Germany
      case "BE": // Belgium
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "german_germany";
        $langcode = "de_DE";
        break;

      // Greek:
      case "GR": // Greece
        $language = "greek_greece";
        $langcode = "el_GR";
        break;

      // Hebrew:
      case "IL": // Israel
        $language = "hebrew_israel";
        $langcode = "he_IL";
        break;

      // Hungarian:
      case "HU": // Hungary
        $language = "hungarian_hungary";
        $langcode = "hu_HU";
        break;

      // Italian:
      case "IT": // Italy
        $language = "italian_italy";
        $langcode = "it_IT";
        break;

      // Japanese:
      case "JP": // Japan
        $language = "japanese_japan";
        $langcode = "ja_JP";
        break;

      // Korean:
      case "KP": // Korea (Democratic People's Republic of)
      case "KR": // Korea (Republic of)
        $language = "korean_korea_republic";
        $langcode = "ko_KR";
        break;

      // Latvian:
      case "LV": // Latvia
        $language = "latvian_latvia";
        $langcode = "lv_LV";
        break;

      // Lithuanian
      case "LT": // Lithuania 
        $language = "lithuanian_lithuania";
        $langcode = "lt_LT";
        break;

      // Norwegian
      case "NO": // Norway
        $language = "norwegian_norway";
        $langcode = "no_NO";
        break;

      // Polish:
      case "PL": // Poland
        $language = "polish_poland";
        $langcode = "pl_PL";
        break;

      // Portuguese:
      case "BR": // Brazil
        $language = "portuguese_brazil";
        $langcode = "pt_BR";
        break;
      case "AO": // Angola
      case "CV": // Cabo Verde
      case "GW": // Guinea-Bissau
      case "TL": // Timor-Leste
      case "MO": // Macao
      case "MZ": // Mozambique
      case "PT": // Portugal
      case "ST": // Sao Tome and Principe
        $language = "portuguese_portugal";
        $langcode = "pt_PT";
        break;

      // Romanian:
      case "RO": // Romania
        $language = "romanian_romania";
        $langcode = "ro_RO";
        break;

      // Russian:
      case "RU": // Russian Federation
        $language = "russian_russian_federation";
        $langcode = "ru_RU";
        break;

      // Serbian:
      case "ME": // Montenegro
        $language = "serbian_republic_of_montenegro";
        $langcode = "sr_ME";
        break;
      case "RS": // Serbia
        $language = "serbian_republic_of_serbia";
        $langcode = "sr_RS";
        break;

      // Slovak:
      case "SK": // Slovakia
        $language = "slovak_slovakia";
        $langcode = "sk_SK";
        break;

      // Slovenian:
      case "SI": // Slovenia
        $language = "slovenian_slovenia";
        $langcode = "sl_SI";
        break;

      // Spanish:
      case "AR": // Argentina
      case "CL": // Chile
      case "PY": // Paraguay
      case "UY": // Uruguay
        $language = "spanish_argentina";
        $langcode = "es_AR";
        break;
      case "CO": // Colombia
      case "PE": // Peru
      case "VE": // Venezuela
      case "GT": // Guatemala
      case "EC": // Ecuador
      case "BO": // Bolivia
      case "CU": // Cuba
      case "DO": // Dominican Republic
      case "HN": // Honduras
      case "SV": // El Salvador
      case "NI": // Nicaragua
      case "PA": // Panama
      case "GQ": // Equatorial Guinea
        $language = "spanish_colombia";
        $langcode = "es_CO";
        break;
      case "ES": // Spain
      case "CR": // Costa Rica
      case "PR": // Puerto Rico
      case "MX": // Mexico
        $language = "spanish_spain";
        $langcode = "es_ES";
        break;

      // Swedish:
      case "SE": // Sweden
        $language = "swedish_sweden";
        $langcode = "sv_SE";
        break;

      // Thai:
      case "TH": // Thailand
        $language = "thai_thailand";
        $langcode = "th_TH";
        break;

      // Turkish:
      case "TR": // Turkey
        $language = "turkish_turkiye";
        $langcode = "tr_TR";
        break;

      // Ukranian:
      case "UA": // Ukraine
        $language = "ukrainian_ukraine";
        $langcode = "uk_UA";
        break;

      // Default (English United States):
      default:
        $language = "english_united_states";
        $langcode = "en_US";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $content = str_replace ( "{{{langcode}}}", $langcode, $content);

  // Set phone country tone:
  $locale = "";
  switch ( $parameters["Country"])
  {
    case "DZ": // Algeria
      $locale = "algeria";
      break;
    case "AR": // Argentina
      $locale = "argentina";
      break;
    case "AU": // Australia
      $locale = "australia";
      break;
    case "AT": // Austria
      $locale = "austria";
      break;
    case "BH": // Bahrain
      $locale = "bahrain";
      break;
    case "BE": // Belgium
      $locale = "belgium";
      break;
    case "BR": // Brazil
      $locale = "brazil";
      break;
    case "BG": // Bulgaria
      $locale = "bulgaria";
      break;
    case "CA": // Canada
      $locale = "canada";
      break;
    case "CL": // Chile
      $locale = "chile";
      break;
    case "CH": // China
      $locale = "china";
      break;
    case "CO": // Colombia
      $locale = "colombia";
      break;
    case "HR": // Croatia
      $locale = "croatia";
      break;
    case "CY": // Cyprus
      $locale = "cyprus";
      break;
    case "CZ": // Czech Republic
      $locale = "czech_republic";
      break;
    case "DK": // Denmark
      $locale = "denmark";
      break;
    case "EG": // Egypt
      $locale = "egypt";
      break;
    case "EE": // Estonia
      $locale = "estonia";
      break;
    case "FI": // Finland
      $locale = "finland";
      break;
    case "FR": // France
      $locale = "france";
      break;
    case "DE": // Germany
      $locale = "germany";
      break;
    case "GH": // Ghana
      $locale = "ghana";
      break;
    case "GR": // Greece
      $locale = "greece";
      break;
    case "HK": // Hong Kong
      $locale = "hong_kong";
      break;
    case "HU": // Hungary
      $locale = "hungary";
      break;
    case "IS": // Iceland
      $locale = "iceland";
      break;
    case "IN": // India
      $locale = "india";
      break;
    case "ID": // Indonesia
      $locale = "indonesia";
      break;
    case "IQ": // Iraq
      $locale = "iraq";
      break;
    case "IE": // Ireland
      $locale = "ireland";
      break;
    case "IL": // Israel
      $locale = "israel";
      break;
    case "IT": // Italy
      $locale = "italy";
      break;
    case "JP": // Japan
      $locale = "japan";
      break;
    case "JO": // Jordan
      $locale = "jordan";
      break;
    case "KE": // Kenya
      $locale = "kenya";
      break;
    case "KP": // Korea (Democratic People's Republic of)
    case "KR": // Korea (Republic of)
      $locale = "korea_republic";
      break;
    case "KW": // Kuwait
      $locale = "kuwait";
      break;
    case "LV": // Latvia
      $locale = "latvia";
      break;
    case "LB": // Lebanon
      $locale = "lebanon";
      break;
    case "LT": // Lithuania
      $locale = "lithuania";
      break;
    case "LU": // Luxembourg
      $locale = "luxembourg";
      break;
    case "MY": // Malaysia
      $locale = "malaysia";
      break;
    case "MR": // Mauritania
      $locale = "mauritania";
      break;
    case "MX": // Mexico
      $locale = "mexico";
      break;
    case "MA": // Morocco
      $locale = "morocco";
      break;
    case "NP": // Nepal
      $locale = "nepal";
      break;
    case "NL": // Netherlands
      $locale = "netherlands";
      break;
    case "NZ": // New Zealand
      $locale = "new_zealand";
      break;
    case "NG": // Nigeria
      $locale = "nigeria";
      break;
    case "NO": // Norway
      $locale = "norway";
      break;
    case "OM": // Oman
      $locale = "oman";
      break;
    case "PK": // Pakistan
      $locale = "pakistan";
      break;
    case "PA": // Panama
      $locale = "panama";
      break;
    case "PE": // Peru
      $locale = "peru";
      break;
    case "PH": // Philippines
      $locale = "philippines";
      break;
    case "PL": // Poland
      $locale = "poland";
      break;
    case "PT": // Portugal
      $locale = "portugal";
      break;
    case "QA": // Qatar
      $locale = "qatar";
      break;
    case "ME": // Montenegro
      $locale = "republic_of_montenegro";
      break;
    case "RS": // Serbia
      $locale = "republic_of_serbia";
      break;
    case "RO": // Romania
      $locale = "romania";
      break;
    case "RU": // Russian Federation
      $locale = "russian_federation";
      break;
    case "SA": // Saudi Arabia
      $locale = "saudi_arabia";
      break;
    case "SG": // Singapore
      $locale = "singapore";
      break;
    case "SK": // Slovakia
      $locale = "slovakia";
      break;
    case "SI": // Slovenia
      $locale = "slovenia";
      break;
    case "ZA": // South Africa
      $locale = "south_africa";
      break;
    case "ES": // Spain
      $locale = "spain";
      break;
    case "SD": // Sudan
      $locale = "sudan";
      break;
    case "SE": // Sweden
      $locale = "sweden";
      break;
    case "CH": // Switzerland
      $locale = "switzerland";
      break;
    case "TW": // Taiwan
      $locale = "taiwan";
      break;
    case "TH": // Thailand
      $locale = "thailand";
      break;
    case "TN": // Tunisia
      $locale = "tunisia";
      break;
    case "TR": // Turkey
      $locale = "turkiye";
      break;
    case "UA": // Ukraine
      $locale = "ukraine";
      break;
    case "AE": // United Arab Emirates
      $locale = "united_arab_emirates";
      break;
    case "GB": // United Kingdom of Great Britain and Northern Ireland
      $locale = "united_kingdom";
      break;
    case "VE": // Venezuela (Bolivarian Republic of)
      $locale = "venezuela";
      break;
    case "VN": // Viet Nam
      $locale = "vietnam";
      break;
    case "YE": // Yemen
      $locale = "yemen";
      break;
    case "ZW": // Zimbabwe
      $locale = "zimbabwe";
      break;

    default: // United States of America
      $locale = "united_states";
      break;
  }
  $content = str_replace ( "{{{locale}}}", $locale, $content);

  // Create dialplan:
  $dialplan = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  $dialplan .= "<DIALTEMPLATE>\n";
  $dialpattern = array ();
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    $dialentry = ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] . "," : "") . $dialplanentry["Pattern"];
    $dialentry = str_replace ( "*", "\\*", $dialentry);
    $dialentry = str_replace ( ".", "*", $dialentry);
    $dialentry = str_replace ( "[0-9]", "X", $dialentry);
    $dialentry = str_replace ( "X", ".", $dialentry);	// We know that X means 0-9 (and not ., that means 0-9, # or *), but Cisco dialpattern are very restrictive and will generate a very big dialtemplate that will fail to load.
    $dialentry = str_replace ( "Z", ".", $dialentry);	// We know that Z means 1-9 (and not ., that means 0-9, # or *), but Cisco dialpattern are very restrictive and will generate a very big dialtemplate that will fail to load.
    $dialentry = str_replace ( "N", ".", $dialentry);	// We know that N means 2-9 (and not ., that means 0-9, # or *), but Cisco dialpattern are very restrictive and will generate a very big dialtemplate that will fail to load.
    $dialpattern[] = $dialentry;
  }
  while ( array_search_substring ( "[", $dialpattern))
  {
    foreach ( $dialpattern as $index => $dialentry)
    {
      if ( strpos ( $dialentry, "[") !== false)
      {
        unset ( $dialpattern[$index]);
        break;
      }
    }
    $start = substr ( $dialentry, 0, strpos ( $dialentry, "["));
    $end = substr ( $dialentry, strpos ( $dialentry, "]") + 1);
    $toexpand = substr ( $dialentry, strlen ( $start) + 1, strlen ( $dialentry) - strlen ( $start) - strlen ( $end) - 2);

    $processed = "";
    for ( $x = 0; $x < strlen ( $toexpand); $x++)
    {
      if ( substr ( $toexpand, $x + 1, 1) == "-")
      {
        foreach ( range ( substr ( $toexpand, $x, 1), substr ( $toexpand, $x + 2, 1)) as $tmp)
        {
          $processed .= $tmp;
        }
        $x += 2;
      } else {
        $processed .= substr ( $toexpand, $x, 1);
      }
    }

    for ( $x = 0; $x < strlen ( $processed); $x++)
    {
      $dialpattern[] = $start . substr ( $processed, $x, 1) . $end;
    }
  }
  foreach ( $dialpattern as $dialentry)
  {
    $dialplan .= "  <TEMPLATE MATCH=\"" . $dialentry . "\" Timeout=\"" . ( strpos ( $dialentry, "*") !== false && substr ( $dialentry, strpos ( $dialentry, "*") - 1, 1) != "\\" ? "3" : "0") . "\" />\n";
  }
  $dialplan .= "  <TEMPLATE MATCH=\"*\" Timeout=\"3\" />\n";
  $dialplan .= "</DIALTEMPLATE>\n";
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/SEP" . strtoupper ( $parameters["MAC"]) . ".dpt.xml.gz", gzencode ( $dialplan, 9)) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }
  $content = str_replace ( "{{{dialtemplate}}}", "SEP" . strtoupper ( $parameters["MAC"]) . ".dpt.xml.gz", $content);

  // Set NTP servers and time offset:
  $ntpservers = "      <ntps>\n";
  $ntpips = array ();
  foreach ( $parameters["NTP"] as $ntp)
  {
    if ( $hosts = gethostbynamel ( $ntp))
    {
      $ntpips = array_merge_recursive ( $ntpips, $hosts);
    }
  }
  foreach ( $ntpips as $ntp)
  {
    $ntpservers .= "        <ntp>\n";
    $ntpservers .= "          <name>" . $ntp . "</name>\n";
    $ntpservers .= "          <ntpMode>unicast</ntpMode>\n";
    $ntpservers .= "        </ntp>\n";
  }
  $ntpservers .= "      </ntps>\n";
  switch ( $parameters["Offset"] * 60)
  {
    case -720:	// GMT-12:00
      $timezone = "Dateline Standard Time";
      break;
    case -660:	// GMT-11:00
      $timezone = "Samoa Standard Time";
      break;
    case -600:	// GMT-10:00
      $timezone = "Hawaiian Standard Time";
      break;
    case -540:	// GMT-09:00
      $timezone = "Alaskan Standard/Daylight Time";
      break;
    case -480:	// GMT-08:00
      $timezone = "Pacific Standard/Daylight Time";
      break;
    case -420:	// GMT-07:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Boise":
        case "America/Denver":
        case "America/Phoenix":
          $timezone = "Mountain Standard/Daylight Time";
          break;
        default:
          $timezone = "US Mountain Standard Time";
          break;
      }
      break;
    case -360:	// GMT-06:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Bahia_Banderas":
        case "America/Cancun":
        case "America/Matamoros":
        case "America/Merida":
        case "America/Mexico_City":
        case "America/Monterrey":
          $timezone = "Mexico Standard/Daylight Time";
          break;
        case "America/Chicago":
        case "America/Indiana/Knox":
        case "America/Indiana/Tell_City":
        case "America/Menominee":
        case "America/North_Dakota/Beulah":
        case "America/North_Dakota/Center":
        case "America/North_Dakota/New_Salem":
          $timezone = "Central Standard/Daylight Time";
          break;
        default:
          $timezone = "Canada Central Standard Time";
          break;
      }
      break;
    case -300:	// GMT-05:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Detroit":
        case "America/Indiana/Indianapolis":
        case "America/Indiana/Marengo":
        case "America/Indiana/Petersburg":
        case "America/Indiana/Vevay":
        case "America/Indiana/Vincennes":
        case "America/Indiana/Winamac":
        case "America/Kentucky/Louisville":
        case "America/Kentucky/Monticello":
        case "America/New_York":
          $timezone = "Eastern Standard/Daylight Time";
          break;
        case "America/Bogota":
        case "America/Cayman":
        case "America/Grand_Turk":
        case "America/Guayaquil":
        case "America/Havana":
        case "America/Jamaica":
        case "America/Lima":
        case "America/Nassau":
        case "America/Panama":
        case "America/Port-au-Prince":
        case "Pacific/Easter":
          $timezone = "SA Pacific Standard Time";
          break;
        default:
          $timezone = "US Eastern Standard Time";
          break;
      }
      break;
    case -240:	// GMT-04:00
      switch ( $parameters["TimeZone"])
      {
        case "Atlantic/Bermuda":
          $timezone = "Atlantic Standard/Daylight Time";
          break;
        case "America/Anguilla":
        case "America/Antigua":
        case "America/Aruba":
        case "America/Barbados":
        case "America/Curacao":
        case "America/Dominica":
        case "America/Grenada":
        case "America/Guadeloupe":
        case "America/Guyana":
        case "America/Kralendijk":
        case "America/Lower_Princes":
        case "America/Marigot":
        case "America/Martinique":
        case "America/Montserrat":
        case "America/Port_of_Spain":
        case "America/Puerto_Rico":
        case "America/Santo_Domingo":
        case "America/St_Barthelemy":
        case "America/St_Kitts":
        case "America/St_Lucia":
        case "America/St_Thomas":
        case "America/St_Vincent":
        case "America/Thule":
        case "America/Tortola":
          $timezone = "Pacific SA Standard Time";
          break;
        default:
          $timezone = "SA Western Standard Time";
          break;
      }
      break;
    case -210:	// GMT-03:30
      $timezone = "Newfoundland Standard/Daylight Time";
      break;
    case -180:	// GMT-03:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Araguaina":
        case "America/Belem":
        case "America/Campo_Grande":
        case "America/Cuiaba":
        case "America/Fortaleza":
        case "America/Maceio":
        case "America/Recife":
        case "America/Santarem":
        case "America/Sao_Paulo":
          $timezone = "SA Eastern Standard Time";
          break;
        case "Atlantic/Stanley":
          $timezone = "Pacific SA Daylight Time";
          break;
        default:
          $timezone = "E. South America Standard/Daylight Time";
          break;
      }
      break;
    case -120:	// GMT-02:00
      $timezone = "Mid-Atlantic Standard/Daylight Time";
      break;
    case -60:	// GMT-01:00
      $timezone = "Azores Standard/Daylight Time";
      break;
    case 0:	// GMT+00:00
      $timezone = "Greenwich Standard Time";
      break;
    case 60:	// GMT+01:00
      switch ( $parameters["TimeZone"])
      {
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
          $timezone = "GTB Standard/Daylight Time";
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
          $timezone = "Egypt Standard/Daylight Time";
          break;
        default:
          $timezone = "W. Europe Standard/Daylight Time";
          break;
      }
      break;
    case 120:	// GMT+02:00
      switch ( $parameters["TimeZone"])
      {
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
          $timezone = "South Africa Standard Time";
          break;
        case "Asia/Jerusalem":
          $timezone = "Jerusalem Standard/Daylight Time";
          break;
        default:
          $timezone = "Central Europe Standard/Daylight Time";
          break;
      }
      break;
    case 180:	// GMT+03:00
      switch ( $parameters["TimeZone"])
      {
        case "Europe/Kaliningrad":
        case "Europe/Minsk":
          $timezone = "Russian Standard/Daylight Time";
          break;
        default:
          $timezone = "Saudi Arabia Standard Time";
          break;
      }
      break;
    case 210:	// GMT+03:30
      $timezone = "Iran Standard/Daylight Time";
      break;
    case 240:	// GMT+04:00
      switch ( $parameters["TimeZone"])
      {
        case "Europe/Moscow":
        case "Europe/Samara":
        case "Europe/Volgograd":
          $timezone = "Caucasus Standard/Daylight Time";
          break;
        default:
          $timezone = "Arabian Standard Time";
          break;
      }
      break;
    case 270:	// GMT+04:30
      $timezone = "Afghanistan Standard Time";
      break;
    case 300:	// GMT+05:00
      $timezone = "West Asia Standard Time";
      break;
    case 330:	// GMT+05:30
      $timezone = "India Standard Time";
      break;
    case 360:	// GMT+06:00
      $timezone = "Central Asia Standard Time";
      break;
    case 420:	// GMT+07:00
      $timezone = "SE Asia Standard Time";
      break;
    case 480:	// GMT+08:00
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Taipei":
          $timezone = "Taipei Standard Time";
          break;
        default:
          $timezone = "China Standard/Daylight Time";
          break;
      }
      break;
    case 540:	// GMT+09:00
      $timezone = "Tokyo Standard Time";
      break;
    case 570:	// GMT+09:30
      $timezone = "Cen. Australia Standard/Daylight Time";
      break;
    case 600:	// GMT+10:00
      switch ( $parameters["TimeZone"])
      {
        case "Australia/Brisbane":
        case "Australia/Lindeman":
          $timezone = "AUS Eastern Standard/Daylight Time";
          break;
        case "Pacific/Chuuk":
        case "Pacific/Guam":
        case "Pacific/Port_Moresby":
        case "Pacific/Saipan":
          $timezone = "West Pacific Standard Time";
          break;
        default:
          $timezone = "Tasmania Standard/Daylight Time";
          break;
      }
      break;
    case 660:	// GMT+11:00
      $timezone = "Central Pacific Standard Time";
      break;
    case 720:	// GMT+12:00
      $timezone = "New Zealand Standard/Daylight Time";
      break;
    default:	// GMT+00:00
      $timezone = "Greenwich Standard Time";
      break;
  }
  $content = str_replace ( "{{{ntpservers}}}", $ntpservers, $content);
  $content = str_replace ( "{{{timezone}}}", $timezone, $content);

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
      $dateformat = "D/M/YY";
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
      $dateformat = "D.M.YY";
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = "D-M-YY";
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
      $dateformat = "YY-M-D";
      break;
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
      $dateformat = "YY/M/D";
      break;
    case "HU": // Hungary
    case "KZ": // Kazakhstan
      $dateformat = "YY.M.D";
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = "M/D/YYA";
      break;
    case "ID": // Indonesia
      $dateformat = "M-D-YY";
      break;
    default: // Most used format (M/D/YYA)
      $dateformat = "M/D/YYA";
      break;
  }
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/SEP" . strtoupper ( $parameters["MAC"]) . ".cnf.xml", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify cisco-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Cisco 8961 phones auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_cisco_8961 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  $content = file_get_contents ( dirname ( __FILE__) . "/template-cisco-" . $parameters["Type"] . ".xml");

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
  $content = str_replace ( "{{{g722support}}}", in_array ( "G722", $equipmentconfig["AudioCodecs"]) ? "2" : "1", $content);
  $content = str_replace ( "{{{advertiseg722}}}", in_array ( "G722", $equipmentconfig["AudioCodecs"]) ? "1" : "0", $content);

  // Set phone language:
  $language = "";
  $langcode = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "portuguese_brazil";
      $langcode = "pt_BR";
      break;
    case "en_US":
      $language = "english_united_states";
      $langcode = "en_US";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Arabic:
      case "DZ": // Algeria
        $language = "arabic_algeria";
        $langcode = "ar_DZ";
        break;
      case "BH": // Bahrain
        $language = "arabic_bahrain";
        $langcode = "ar_BH";
        break;
      case "EG": // Egypt
        $language = "arabic_egypt";
        $langcode = "ar_EG";
        break;
      case "IQ": // Iraq
        $language = "arabic_iraq";
        $langcode = "ar_IQ";
        break;
      case "JO": // Jordan
        $language = "arabic_jordan";
        $langcode = "ar_JO";
        break;
      case "KW": // Kuwait
        $language = "arabic_kuwait";
        $langcode = "ar_KW";
        break;
      case "LB": // Lebanon
        $language = "arabic_lebanon";
        $langcode = "ar_LB";
        break;
      case "MO": // Morocco
        $language = "arabic_morocco";
        $langcode = "ar_MA";
        break;
      case "OM": // Oman
        $language = "arabic_oman";
        $langcode = "ar_OM";
        break;
      case "QA": // Qatar
        $language = "arabic_qatar";
        $langcode = "ar_QA";
        break;
      case "SA": // Saudi Arabia
        $language = "arabic_saudi_arabia";
        $langcode = "ar_SA";
        break;
      case "TN": // Tunisia
        $language = "arabic_tunisia";
        $langcode = "ar_TN";
        break;
      case "AE": // United Arab Emirates
      case "TD": // Chad
      case "KM": // Comoros
      case "DJ": // Djibouti
      case "LY": // Libya
      case "ML": // Mali
      case "MR": // Mauritania
      case "PS": // Palestine, State of
      case "SO": // Somalia
      case "SD": // Sudan
      case "SY": // Syrian Arab Republic
        $language = "arabic_united_arab_emirates";
        $langcode = "ar_AE";
        break;
      case "YE": // Yemen
        $language = "arabic_yemen";
        $langcode = "ar_YE";
        break;

      // Bulgarian:
      case "BG": // Bulgaria
        $language = "bulgarian_bulgaria";
        $langcode = "bg_BG";
        break;

      // Chinese:
      case "CN": // China
        $language = "chinese_china";
        $langcode = "zh_CN";
        break;
      case "HK": // Hong Kong
        $language = "chinese_hong_kong";
        $langcode = "zh_HK";
        break;
      case "TW": // Taiwan
        $language = "chinese_taiwan";
        $langcode = "zh_TW";
        break;

      // Croatian:
      case "HR": // Croatia
        $language = "croatian_croatia";
        $langcode = "hr_HR";
        break;

      // Czech:
      case "CZ": // Czech Republic
        $language = "czech_czech_republic";
        $langcode = "cs_CZ";
        break;

      // Danish:
      case "DK": // Denmark
        $language = "danish_denmark";
        $langcode = "da_DK";
        break;

      // Dutch:
      case "NL": // Netherlands
        $language = "dutch_netherlands";
        $langcode = "nl_NL";
        break;

      // English (United Kingdom):
      case "GB": // United Kingdom of Great Britain and Northern Ireland
      case "IE": // Ireland
        $language = "english_united_kingdom";
        $langcode = "en_GB";
        break;

      // Estonian:
      case "EE": // Estonia
        $language = "estonian_estonia";
        $langcode = "et_EE";
        break;

      // Finnish:
      case "FI": // Finland
        $language = "finnish_finland";
        $langcode = "fi_FI";
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
        $language = "french_france";
        $langcode = "fr_FR";
        break;

      // German:
      case "AT": // Austria
        $language = "german_austria";
        $langcode = "de_AT";
        break;
      case "CH": // Switzerland
        $language = "german_switzerland";
        $langcode = "de_CH";
        break;
      case "DE": // Germany
      case "BE": // Belgium
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "german_germany";
        $langcode = "de_DE";
        break;

      // Greek:
      case "GR": // Greece
        $language = "greek_greece";
        $langcode = "el_GR";
        break;

      // Hebrew:
      case "IL": // Israel
        $language = "hebrew_israel";
        $langcode = "he_IL";
        break;

      // Hungarian:
      case "HU": // Hungary
        $language = "hungarian_hungary";
        $langcode = "hu_HU";
        break;

      // Italian:
      case "IT": // Italy
        $language = "italian_italy";
        $langcode = "it_IT";
        break;

      // Japanese:
      case "JP": // Japan
        $language = "japanese_japan";
        $langcode = "ja_JP";
        break;

      // Korean:
      case "KP": // Korea (Democratic People's Republic of)
      case "KR": // Korea (Republic of)
        $language = "korean_korea_republic";
        $langcode = "ko_KR";
        break;

      // Latvian:
      case "LV": // Latvia
        $language = "latvian_latvia";
        $langcode = "lv_LV";
        break;

      // Lithuanian
      case "LT": // Lithuania 
        $language = "lithuanian_lithuania";
        $langcode = "lt_LT";
        break;

      // Norwegian
      case "NO": // Norway
        $language = "norwegian_norway";
        $langcode = "no_NO";
        break;

      // Polish:
      case "PL": // Poland
        $language = "polish_poland";
        $langcode = "pl_PL";
        break;

      // Portuguese:
      case "BR": // Brazil
        $language = "portuguese_brazil";
        $langcode = "pt_BR";
        break;
      case "AO": // Angola
      case "CV": // Cabo Verde
      case "GW": // Guinea-Bissau
      case "TL": // Timor-Leste
      case "MO": // Macao
      case "MZ": // Mozambique
      case "PT": // Portugal
      case "ST": // Sao Tome and Principe
        $language = "portuguese_portugal";
        $langcode = "pt_PT";
        break;

      // Romanian:
      case "RO": // Romania
        $language = "romanian_romania";
        $langcode = "ro_RO";
        break;

      // Russian:
      case "RU": // Russian Federation
        $language = "russian_russian_federation";
        $langcode = "ru_RU";
        break;

      // Serbian:
      case "ME": // Montenegro
        $language = "serbian_republic_of_montenegro";
        $langcode = "sr_ME";
        break;
      case "RS": // Serbia
        $language = "serbian_republic_of_serbia";
        $langcode = "sr_RS";
        break;

      // Slovak:
      case "SK": // Slovakia
        $language = "slovak_slovakia";
        $langcode = "sk_SK";
        break;

      // Slovenian:
      case "SI": // Slovenia
        $language = "slovenian_slovenia";
        $langcode = "sl_SI";
        break;

      // Spanish:
      case "AR": // Argentina
      case "CL": // Chile
      case "PY": // Paraguay
      case "UY": // Uruguay
        $language = "spanish_argentina";
        $langcode = "es_AR";
        break;
      case "CO": // Colombia
      case "PE": // Peru
      case "VE": // Venezuela
      case "GT": // Guatemala
      case "EC": // Ecuador
      case "BO": // Bolivia
      case "CU": // Cuba
      case "DO": // Dominican Republic
      case "HN": // Honduras
      case "SV": // El Salvador
      case "NI": // Nicaragua
      case "PA": // Panama
      case "GQ": // Equatorial Guinea
        $language = "spanish_colombia";
        $langcode = "es_CO";
        break;
      case "ES": // Spain
      case "CR": // Costa Rica
      case "PR": // Puerto Rico
      case "MX": // Mexico
        $language = "spanish_spain";
        $langcode = "es_ES";
        break;

      // Swedish:
      case "SE": // Sweden
        $language = "swedish_sweden";
        $langcode = "sv_SE";
        break;

      // Thai:
      case "TH": // Thailand
        $language = "thai_thailand";
        $langcode = "th_TH";
        break;

      // Turkish:
      case "TR": // Turkey
        $language = "turkish_turkiye";
        $langcode = "tr_TR";
        break;

      // Ukranian:
      case "UA": // Ukraine
        $language = "ukrainian_ukraine";
        $langcode = "uk_UA";
        break;

      // Default (English United States):
      default:
        $language = "english_united_states";
        $langcode = "en_US";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $content = str_replace ( "{{{langcode}}}", $langcode, $content);

  // Set phone country tone:
  $locale = "";
  switch ( $parameters["Country"])
  {
    case "DZ": // Algeria
      $locale = "algeria";
      break;
    case "AR": // Argentina
      $locale = "argentina";
      break;
    case "AU": // Australia
      $locale = "australia";
      break;
    case "AT": // Austria
      $locale = "austria";
      break;
    case "BH": // Bahrain
      $locale = "bahrain";
      break;
    case "BE": // Belgium
      $locale = "belgium";
      break;
    case "BR": // Brazil
      $locale = "brazil";
      break;
    case "BG": // Bulgaria
      $locale = "bulgaria";
      break;
    case "CA": // Canada
      $locale = "canada";
      break;
    case "CL": // Chile
      $locale = "chile";
      break;
    case "CH": // China
      $locale = "china";
      break;
    case "CO": // Colombia
      $locale = "colombia";
      break;
    case "HR": // Croatia
      $locale = "croatia";
      break;
    case "CY": // Cyprus
      $locale = "cyprus";
      break;
    case "CZ": // Czech Republic
      $locale = "czech_republic";
      break;
    case "DK": // Denmark
      $locale = "denmark";
      break;
    case "EG": // Egypt
      $locale = "egypt";
      break;
    case "EE": // Estonia
      $locale = "estonia";
      break;
    case "FI": // Finland
      $locale = "finland";
      break;
    case "FR": // France
      $locale = "france";
      break;
    case "DE": // Germany
      $locale = "germany";
      break;
    case "GH": // Ghana
      $locale = "ghana";
      break;
    case "GR": // Greece
      $locale = "greece";
      break;
    case "HK": // Hong Kong
      $locale = "hong_kong";
      break;
    case "HU": // Hungary
      $locale = "hungary";
      break;
    case "IS": // Iceland
      $locale = "iceland";
      break;
    case "IN": // India
      $locale = "india";
      break;
    case "ID": // Indonesia
      $locale = "indonesia";
      break;
    case "IQ": // Iraq
      $locale = "iraq";
      break;
    case "IE": // Ireland
      $locale = "ireland";
      break;
    case "IL": // Israel
      $locale = "israel";
      break;
    case "IT": // Italy
      $locale = "italy";
      break;
    case "JP": // Japan
      $locale = "japan";
      break;
    case "JO": // Jordan
      $locale = "jordan";
      break;
    case "KE": // Kenya
      $locale = "kenya";
      break;
    case "KP": // Korea (Democratic People's Republic of)
    case "KR": // Korea (Republic of)
      $locale = "korea_republic";
      break;
    case "KW": // Kuwait
      $locale = "kuwait";
      break;
    case "LV": // Latvia
      $locale = "latvia";
      break;
    case "LB": // Lebanon
      $locale = "lebanon";
      break;
    case "LT": // Lithuania
      $locale = "lithuania";
      break;
    case "LU": // Luxembourg
      $locale = "luxembourg";
      break;
    case "MY": // Malaysia
      $locale = "malaysia";
      break;
    case "MR": // Mauritania
      $locale = "mauritania";
      break;
    case "MX": // Mexico
      $locale = "mexico";
      break;
    case "MA": // Morocco
      $locale = "morocco";
      break;
    case "NP": // Nepal
      $locale = "nepal";
      break;
    case "NL": // Netherlands
      $locale = "netherlands";
      break;
    case "NZ": // New Zealand
      $locale = "new_zealand";
      break;
    case "NG": // Nigeria
      $locale = "nigeria";
      break;
    case "NO": // Norway
      $locale = "norway";
      break;
    case "OM": // Oman
      $locale = "oman";
      break;
    case "PK": // Pakistan
      $locale = "pakistan";
      break;
    case "PA": // Panama
      $locale = "panama";
      break;
    case "PE": // Peru
      $locale = "peru";
      break;
    case "PH": // Philippines
      $locale = "philippines";
      break;
    case "PL": // Poland
      $locale = "poland";
      break;
    case "PT": // Portugal
      $locale = "portugal";
      break;
    case "QA": // Qatar
      $locale = "qatar";
      break;
    case "ME": // Montenegro
      $locale = "republic_of_montenegro";
      break;
    case "RS": // Serbia
      $locale = "republic_of_serbia";
      break;
    case "RO": // Romania
      $locale = "romania";
      break;
    case "RU": // Russian Federation
      $locale = "russian_federation";
      break;
    case "SA": // Saudi Arabia
      $locale = "saudi_arabia";
      break;
    case "SG": // Singapore
      $locale = "singapore";
      break;
    case "SK": // Slovakia
      $locale = "slovakia";
      break;
    case "SI": // Slovenia
      $locale = "slovenia";
      break;
    case "ZA": // South Africa
      $locale = "south_africa";
      break;
    case "ES": // Spain
      $locale = "spain";
      break;
    case "SD": // Sudan
      $locale = "sudan";
      break;
    case "SE": // Sweden
      $locale = "sweden";
      break;
    case "CH": // Switzerland
      $locale = "switzerland";
      break;
    case "TW": // Taiwan
      $locale = "taiwan";
      break;
    case "TH": // Thailand
      $locale = "thailand";
      break;
    case "TN": // Tunisia
      $locale = "tunisia";
      break;
    case "TR": // Turkey
      $locale = "turkiye";
      break;
    case "UA": // Ukraine
      $locale = "ukraine";
      break;
    case "AE": // United Arab Emirates
      $locale = "united_arab_emirates";
      break;
    case "GB": // United Kingdom of Great Britain and Northern Ireland
      $locale = "united_kingdom";
      break;
    case "VE": // Venezuela (Bolivarian Republic of)
      $locale = "venezuela";
      break;
    case "VN": // Viet Nam
      $locale = "vietnam";
      break;
    case "YE": // Yemen
      $locale = "yemen";
      break;
    case "ZW": // Zimbabwe
      $locale = "zimbabwe";
      break;

    default: // United States of America
      $locale = "united_states";
      break;
  }
  $content = str_replace ( "{{{locale}}}", $locale, $content);

  // Create dialplan:
  $dialplan = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  $dialplan .= "<DIALTEMPLATE>\n";
  $dialpattern = array ();
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    $dialentry = ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] . "," : "") . $dialplanentry["Pattern"];
    $dialentry = str_replace ( "*", "\\*", $dialentry);
    $dialentry = str_replace ( ".", "*", $dialentry);
    $dialentry = str_replace ( "[0-9]", "X", $dialentry);
    $dialentry = str_replace ( "X", ".", $dialentry);	// We know that X means 0-9 (and not ., that means 0-9, # or *), but Cisco dialpattern are very restrictive and will generate a very big dialtemplate that will fail to load.
    $dialentry = str_replace ( "Z", ".", $dialentry);	// We know that Z means 1-9 (and not ., that means 0-9, # or *), but Cisco dialpattern are very restrictive and will generate a very big dialtemplate that will fail to load.
    $dialentry = str_replace ( "N", ".", $dialentry);	// We know that N means 2-9 (and not ., that means 0-9, # or *), but Cisco dialpattern are very restrictive and will generate a very big dialtemplate that will fail to load.
    $dialpattern[] = $dialentry;
  }
  while ( array_search_substring ( "[", $dialpattern))
  {
    foreach ( $dialpattern as $index => $dialentry)
    {
      if ( strpos ( $dialentry, "[") !== false)
      {
        unset ( $dialpattern[$index]);
        break;
      }
    }
    $start = substr ( $dialentry, 0, strpos ( $dialentry, "["));
    $end = substr ( $dialentry, strpos ( $dialentry, "]") + 1);
    $toexpand = substr ( $dialentry, strlen ( $start) + 1, strlen ( $dialentry) - strlen ( $start) - strlen ( $end) - 2);

    $processed = "";
    for ( $x = 0; $x < strlen ( $toexpand); $x++)
    {
      if ( substr ( $toexpand, $x + 1, 1) == "-")
      {
        foreach ( range ( substr ( $toexpand, $x, 1), substr ( $toexpand, $x + 2, 1)) as $tmp)
        {
          $processed .= $tmp;
        }
        $x += 2;
      } else {
        $processed .= substr ( $toexpand, $x, 1);
      }
    }

    for ( $x = 0; $x < strlen ( $processed); $x++)
    {
      $dialpattern[] = $start . substr ( $processed, $x, 1) . $end;
    }
  }
  foreach ( $dialpattern as $dialentry)
  {
    $dialplan .= "  <TEMPLATE MATCH=\"" . $dialentry . "\" Timeout=\"" . ( strpos ( $dialentry, "*") !== false && substr ( $dialentry, strpos ( $dialentry, "*") - 1, 1) != "\\" ? "3" : "0") . "\" />\n";
  }
  $dialplan .= "  <TEMPLATE MATCH=\"*\" Timeout=\"3\" />\n";
  $dialplan .= "</DIALTEMPLATE>\n";
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/SEP" . strtoupper ( $parameters["MAC"]) . ".dpt.xml.gz", gzencode ( $dialplan, 9)) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }
  $content = str_replace ( "{{{dialtemplate}}}", "SEP" . strtoupper ( $parameters["MAC"]) . ".dpt.xml.gz", $content);

  // Set NTP servers and time offset:
  $ntpservers = "      <ntps>\n";
  $ntpips = array ();
  foreach ( $parameters["NTP"] as $ntp)
  {
    if ( $hosts = gethostbynamel ( $ntp))
    {
      $ntpips = array_merge_recursive ( $ntpips, $hosts);
    }
  }
  foreach ( $ntpips as $ntp)
  {
    $ntpservers .= "        <ntp>\n";
    $ntpservers .= "          <name>" . $ntp . "</name>\n";
    $ntpservers .= "          <ntpMode>unicast</ntpMode>\n";
    $ntpservers .= "        </ntp>\n";
  }
  $ntpservers .= "      </ntps>\n";
  switch ( $parameters["Offset"] * 60)
  {
    case -720:	// GMT-12:00
      $timezone = "Dateline Standard Time";
      break;
    case -660:	// GMT-11:00
      $timezone = "Samoa Standard Time";
      break;
    case -600:	// GMT-10:00
      $timezone = "Hawaiian Standard Time";
      break;
    case -540:	// GMT-09:00
      $timezone = "Alaskan Standard/Daylight Time";
      break;
    case -480:	// GMT-08:00
      $timezone = "Pacific Standard/Daylight Time";
      break;
    case -420:	// GMT-07:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Boise":
        case "America/Denver":
        case "America/Phoenix":
          $timezone = "Mountain Standard/Daylight Time";
          break;
        default:
          $timezone = "US Mountain Standard Time";
          break;
      }
      break;
    case -360:	// GMT-06:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Bahia_Banderas":
        case "America/Cancun":
        case "America/Matamoros":
        case "America/Merida":
        case "America/Mexico_City":
        case "America/Monterrey":
          $timezone = "Mexico Standard/Daylight Time";
          break;
        case "America/Chicago":
        case "America/Indiana/Knox":
        case "America/Indiana/Tell_City":
        case "America/Menominee":
        case "America/North_Dakota/Beulah":
        case "America/North_Dakota/Center":
        case "America/North_Dakota/New_Salem":
          $timezone = "Central Standard/Daylight Time";
          break;
        default:
          $timezone = "Canada Central Standard Time";
          break;
      }
      break;
    case -300:	// GMT-05:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Detroit":
        case "America/Indiana/Indianapolis":
        case "America/Indiana/Marengo":
        case "America/Indiana/Petersburg":
        case "America/Indiana/Vevay":
        case "America/Indiana/Vincennes":
        case "America/Indiana/Winamac":
        case "America/Kentucky/Louisville":
        case "America/Kentucky/Monticello":
        case "America/New_York":
          $timezone = "Eastern Standard/Daylight Time";
          break;
        case "America/Bogota":
        case "America/Cayman":
        case "America/Grand_Turk":
        case "America/Guayaquil":
        case "America/Havana":
        case "America/Jamaica":
        case "America/Lima":
        case "America/Nassau":
        case "America/Panama":
        case "America/Port-au-Prince":
        case "Pacific/Easter":
          $timezone = "SA Pacific Standard Time";
          break;
        default:
          $timezone = "US Eastern Standard Time";
          break;
      }
      break;
    case -240:	// GMT-04:00
      switch ( $parameters["TimeZone"])
      {
        case "Atlantic/Bermuda":
          $timezone = "Atlantic Standard/Daylight Time";
          break;
        case "America/Anguilla":
        case "America/Antigua":
        case "America/Aruba":
        case "America/Barbados":
        case "America/Curacao":
        case "America/Dominica":
        case "America/Grenada":
        case "America/Guadeloupe":
        case "America/Guyana":
        case "America/Kralendijk":
        case "America/Lower_Princes":
        case "America/Marigot":
        case "America/Martinique":
        case "America/Montserrat":
        case "America/Port_of_Spain":
        case "America/Puerto_Rico":
        case "America/Santo_Domingo":
        case "America/St_Barthelemy":
        case "America/St_Kitts":
        case "America/St_Lucia":
        case "America/St_Thomas":
        case "America/St_Vincent":
        case "America/Thule":
        case "America/Tortola":
          $timezone = "Pacific SA Standard Time";
          break;
        default:
          $timezone = "SA Western Standard Time";
          break;
      }
      break;
    case -210:	// GMT-03:30
      $timezone = "Newfoundland Standard/Daylight Time";
      break;
    case -180:	// GMT-03:00
      switch ( $parameters["TimeZone"])
      {
        case "America/Araguaina":
        case "America/Belem":
        case "America/Campo_Grande":
        case "America/Cuiaba":
        case "America/Fortaleza":
        case "America/Maceio":
        case "America/Recife":
        case "America/Santarem":
        case "America/Sao_Paulo":
          $timezone = "SA Eastern Standard Time";
          break;
        case "Atlantic/Stanley":
          $timezone = "Pacific SA Daylight Time";
          break;
        default:
          $timezone = "E. South America Standard/Daylight Time";
          break;
      }
      break;
    case -120:	// GMT-02:00
      $timezone = "Mid-Atlantic Standard/Daylight Time";
      break;
    case -60:	// GMT-01:00
      $timezone = "Azores Standard/Daylight Time";
      break;
    case 0:	// GMT+00:00
      $timezone = "Greenwich Standard Time";
      break;
    case 60:	// GMT+01:00
      switch ( $parameters["TimeZone"])
      {
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
          $timezone = "GTB Standard/Daylight Time";
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
          $timezone = "Egypt Standard/Daylight Time";
          break;
        default:
          $timezone = "W. Europe Standard/Daylight Time";
          break;
      }
      break;
    case 120:	// GMT+02:00
      switch ( $parameters["TimeZone"])
      {
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
          $timezone = "South Africa Standard Time";
          break;
        case "Asia/Jerusalem":
          $timezone = "Jerusalem Standard/Daylight Time";
          break;
        default:
          $timezone = "Central Europe Standard/Daylight Time";
          break;
      }
      break;
    case 180:	// GMT+03:00
      switch ( $parameters["TimeZone"])
      {
        case "Europe/Kaliningrad":
        case "Europe/Minsk":
          $timezone = "Russian Standard/Daylight Time";
          break;
        default:
          $timezone = "Saudi Arabia Standard Time";
          break;
      }
      break;
    case 210:	// GMT+03:30
      $timezone = "Iran Standard/Daylight Time";
      break;
    case 240:	// GMT+04:00
      switch ( $parameters["TimeZone"])
      {
        case "Europe/Moscow":
        case "Europe/Samara":
        case "Europe/Volgograd":
          $timezone = "Caucasus Standard/Daylight Time";
          break;
        default:
          $timezone = "Arabian Standard Time";
          break;
      }
      break;
    case 270:	// GMT+04:30
      $timezone = "Afghanistan Standard Time";
      break;
    case 300:	// GMT+05:00
      $timezone = "West Asia Standard Time";
      break;
    case 330:	// GMT+05:30
      $timezone = "India Standard Time";
      break;
    case 360:	// GMT+06:00
      $timezone = "Central Asia Standard Time";
      break;
    case 420:	// GMT+07:00
      $timezone = "SE Asia Standard Time";
      break;
    case 480:	// GMT+08:00
      switch ( $parameters["TimeZone"])
      {
        case "Asia/Taipei":
          $timezone = "Taipei Standard Time";
          break;
        default:
          $timezone = "China Standard/Daylight Time";
          break;
      }
      break;
    case 540:	// GMT+09:00
      $timezone = "Tokyo Standard Time";
      break;
    case 570:	// GMT+09:30
      $timezone = "Cen. Australia Standard/Daylight Time";
      break;
    case 600:	// GMT+10:00
      switch ( $parameters["TimeZone"])
      {
        case "Australia/Brisbane":
        case "Australia/Lindeman":
          $timezone = "AUS Eastern Standard/Daylight Time";
          break;
        case "Pacific/Chuuk":
        case "Pacific/Guam":
        case "Pacific/Port_Moresby":
        case "Pacific/Saipan":
          $timezone = "West Pacific Standard Time";
          break;
        default:
          $timezone = "Tasmania Standard/Daylight Time";
          break;
      }
      break;
    case 660:	// GMT+11:00
      $timezone = "Central Pacific Standard Time";
      break;
    case 720:	// GMT+12:00
      $timezone = "New Zealand Standard/Daylight Time";
      break;
    default:	// GMT+00:00
      $timezone = "Greenwich Standard Time";
      break;
  }
  $content = str_replace ( "{{{ntpservers}}}", $ntpservers, $content);
  $content = str_replace ( "{{{timezone}}}", $timezone, $content);

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
      $dateformat = "D/M/YY";
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
      $dateformat = "D.M.YY";
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = "D-M-YY";
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
      $dateformat = "YY-M-D";
      break;
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
      $dateformat = "YY/M/D";
      break;
    case "HU": // Hungary
    case "KZ": // Kazakhstan
      $dateformat = "YY.M.D";
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = "M/D/YYA";
      break;
    case "ID": // Indonesia
      $dateformat = "M-D-YY";
      break;
    default: // Most used format (M/D/YYA)
      $dateformat = "M/D/YYA";
      break;
  }
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/SEP" . strtoupper ( $parameters["MAC"]) . ".cnf.xml", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify cisco-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to remove the Cisco auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_cisco_remove ( $buffer, $parameters)
{
  global $_in;

  // Remove AP file
  if ( ! unlink ( $_in["general"]["tftpdir"] . "/SEP" . strtoupper ( $parameters["MAC"]) . ".cnf.xml"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP removed.");
}

/**
 * Function to create the Cisco 3905 audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_cisco_3905 ( $buffer, $parameters)
{
  // Return Cisco audio only SIP account template
  return "[" . $parameters["Username"] . "](vd_extension_" . $parameters["Number"] . ",vd_equipment_" . $parameters["Type"] . ")\n" .
         "transport=transport-tcp\n" .
         "aors=" . $parameters["Username"] . "\n" .
         "auth=" . $parameters["Username"] . "\n" .
         "rpid_immediate=yes\n" .
         "send_diversion=yes\n" .
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

/**
 * Function to create the Cisco 7811 audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_cisco_7811 ( $buffer, $parameters)
{
  // Return Cisco audio only SIP account template
  return "[" . $parameters["Username"] . "](vd_extension_" . $parameters["Number"] . ",vd_equipment_" . $parameters["Type"] . ")\n" . 
         "transport=transport-tcp\n" .
         "aors=" . $parameters["Username"] . "\n" .
         "auth=" . $parameters["Username"] . "\n" .
         "rpid_immediate=yes\n" .
         "send_diversion=yes\n" .
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

/**
 * Function to create the Cisco 7942 audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_cisco_7942 ( $buffer, $parameters)
{
  // Return Cisco audio only SIP account template
  return "[" . $parameters["Username"] . "](vd_extension_" . $parameters["Number"] . ",vd_equipment_" . $parameters["Type"] . ")\n" .
         "transport=transport-tcp\n" .
         "aors=" . $parameters["Username"] . "\n" .
         "auth=" . $parameters["Username"] . "\n" .
         "rpid_immediate=yes\n" .
         "send_diversion=yes\n" .
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

/**
 * Function to create the Cisco 8961 audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_cisco_8961 ( $buffer, $parameters)
{
  // Return Cisco audio only SIP account template
  return "[" . $parameters["Username"] . "](vd_extension_" . $parameters["Number"] . ",vd_equipment_" . $parameters["Type"] . ")\n" .
         "transport=transport-tcp\n" .
         "aors=" . $parameters["Username"] . "\n" .
         "auth=" . $parameters["Username"] . "\n" .
         "rpid_immediate=yes\n" .
         "send_diversion=yes\n" .
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
