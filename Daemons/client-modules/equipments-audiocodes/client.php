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
 * VoIP Domain AudioCodes equipments models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments AudioCodes
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_model_310hd_configure", "equipment_model_audiocodes_basic_configure");
framework_add_hook ( "equipment_model_310hd_firmware_add", "equipment_model_audiocodes_add_firmware");
framework_add_hook ( "equipment_model_310hd_firmware_remove", "equipment_model_audiocodes_remove_firmware");
framework_add_hook ( "ap_type_310hd", "ap_type_audiocodes_310hd");
framework_add_hook ( "ap_type_310hd_remove", "ap_type_audiocodes_remove");
framework_add_hook ( "account_type_310hd", "account_type_audiocodes_default");

framework_add_hook ( "equipment_model_405hd_configure", "equipment_model_audiocodes_basic_configure");
framework_add_hook ( "equipment_model_405hd_firmware_add", "equipment_model_audiocodes_add_firmware");
framework_add_hook ( "equipment_model_405hd_firmware_remove", "equipment_model_audiocodes_remove_firmware");
framework_add_hook ( "ap_type_405hd", "ap_type_audiocodes_400hd_series");
framework_add_hook ( "ap_type_405hd_remove", "ap_type_audiocodes_remove");
framework_add_hook ( "account_type_405hd", "account_type_audiocodes_default");

framework_add_hook ( "equipment_model_440hd_configure", "equipment_model_audiocodes_basic_configure");
framework_add_hook ( "equipment_model_440hd_firmware_add", "equipment_model_audiocodes_add_firmware");
framework_add_hook ( "equipment_model_440hd_firmware_remove", "equipment_model_audiocodes_remove_firmware");
framework_add_hook ( "ap_type_440hd", "ap_type_audiocodes_400hd_series");
framework_add_hook ( "ap_type_440hd_remove", "ap_type_audiocodes_remove");
framework_add_hook ( "account_type_440hd", "account_type_audiocodes_default");

framework_add_hook ( "equipment_model_450hd_configure", "equipment_model_audiocodes_basic_configure");
framework_add_hook ( "equipment_model_450hd_firmware_add", "equipment_model_audiocodes_add_firmware");
framework_add_hook ( "equipment_model_450hd_firmware_remove", "equipment_model_audiocodes_remove_firmware");
framework_add_hook ( "ap_type_450hd", "ap_type_audiocodes_400hd_series");
framework_add_hook ( "ap_type_450hd_remove", "ap_type_audiocodes_remove");
framework_add_hook ( "account_type_450hd", "account_type_audiocodes_default");

/**
 * Cleanup functions
 */
framework_add_hook ( "ap_audiocodes_wipe", "ap_audiocodes_wipe");
cleanup_register ( "Accounts-AudioCodes", "ap_audiocodes_wipe", array ( "Before" => array ( "Accounts")));

framework_add_hook ( "firmware_audiocodes_wipe", "firmware_audiocodes_wipe");
cleanup_register ( "Firmwares-AudioCodes", "firmware_audiocodes_wipe", array ( "Before" => array ( "Equipments")));

/**
 * Function to wipe the AudioCodes equipment models auto provisioning files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function ap_audiocodes_wipe ( $buffer, $parameters)
{
  // Remove auto provisioning configuration files:
  foreach ( list_config ( "datafile", "ap") as $ap)
  {
    $data = read_config ( "datafile", $ap);
    if ( equipment_model_exists ( $ap["Type"], "AudioCodes"))
    {
      unlink_config ( "ap", strtolower ( $data["MAC"]) . ".cfg");
      unlink_config ( "datafile", $ap);
    }
  }

  return $buffer;
}

/**
 * Function to wipe the AudioCodes equipment models firmware files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function firmware_audiocodes_wipe ( $buffer, $parameters)
{
  // AudioCodes 310HD
  unlink_config ( "ap", "AudioCodes/310HD/310HD.img");
  unlink_config ( "apdir", "AudioCodes/310HD");

  // AudioCodes 405HD
  unlink_config ( "ap", "AudioCodes/405HD/405HD.img");
  unlink_config ( "apdir", "AudioCodes/405HD");

  // AudioCodes 440HD
  unlink_config ( "ap", "AudioCodes/440HD/440HD.img");
  unlink_config ( "apdir", "AudioCodes/440HD");

  // AudioCodes 450HD
  unlink_config ( "ap", "AudioCodes/450HD/450HD.img");
  unlink_config ( "apdir", "AudioCodes/450HD");

  // AudioCodes vendor directory
  unlink_config ( "apdir", "AudioCodes");

  return $buffer;
}

/**
 * Function to configure the basic AudioCodes equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_audiocodes_basic_configure ( $buffer, $parameters)
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
      case "SILK8":
        $codecs[] = "silk8";
        break;
      case "SILK16":
        $codecs[] = "silk16";
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
 * Function to add a AudioCodes firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_audiocodes_add_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "310HD.img":		// AudioCodes 310HD
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/AudioCodes/310HD") && ! mkdir ( $_in["general"]["tftpdir"] . "/AudioCodes/310HD", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/AudioCodes/310HD/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "405HD.img":		// AudioCodes 405HD
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/AudioCodes/405HD") && ! mkdir ( $_in["general"]["tftpdir"] . "/AudioCodes/405HD", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/AudioCodes/405HD/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "440HD.img":		// AudioCodes 440HD
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/AudioCodes/440HD") && ! mkdir ( $_in["general"]["tftpdir"] . "/AudioCodes/440HD", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/AudioCodes/440HD/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "450HD_3_4_8_808.img":		// AudioCodes 450HD
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/AudioCodes/450HD") && ! mkdir ( $_in["general"]["tftpdir"] . "/AudioCodes/450HD", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/AudioCodes/450HD/450HD.img", $parameters["Data"]) === false)
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
 * Function to remove a AudioCodes firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_audiocodes_remove_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "310HD.img":		// AudioCodes 310HD
      @unlink ( $_in["general"]["tftpdir"] . "/AudioCodes/310HD/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/AudioCodes/310HD");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "405HD.img":		// AudioCodes 405HD
      @unlink ( $_in["general"]["tftpdir"] . "/AudioCodes/405HD/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/AudioCodes/405HD");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "440HD.img":		// AudioCodes 440HD
      @unlink ( $_in["general"]["tftpdir"] . "/AudioCodes/440HD/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/AudioCodes/440HD");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "450HD_3_4_8_808.img":	// AudioCodes 450HD
      @unlink ( $_in["general"]["tftpdir"] . "/AudioCodes/450HD/450HD.img");
      @rmdir ( $_in["general"]["tftpdir"] . "/AudioCodes/450HD");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to create the AudioCodes 400HD series phones auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_audiocodes_400hd_series ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "405hd":
    case "440hd":
    case "450hd":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-audiocodes-" . $parameters["Type"] . ".cfg");
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
  $codecconfig = "";
  $codecs = array (
    "PCMA" => "ALAW",
    "PCMU" => "ULAW",
    "G722_8000" => "G722",
    "SILK_8000" => "SILK8",
    "SILK_16000" => "SILK16"
  );
  $position = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "voip/codec/codec_info/" . $position . "/enabled=1\n";
      $codecconfig .= "voip/codec/codec_info/" . $position . "/name=" . $cfgname . "\n";
      $codecconfig .= "voip/codec/codec_info/" . $position . "/pname=20\n";
      $position++;
    } else {
      $codecconfig .= "voip/codec/codec_info/" . $position . "/enabled=0\n";
      $codecconfig .= "voip/codec/codec_info/" . $position . "/name=\n";
      $codecconfig .= "voip/codec/codec_info/" . $position . "/pname=\n";
    }
  }
  for ( ; $position <= 5; $position++)
  {
    $codecconfig .= "voip/codec/codec_info/" . $position . "/enabled=0\n";
    $codecconfig .= "voip/codec/codec_info/" . $position . "/name=\n";
    $codecconfig .= "voip/codec/codec_info/" . $position . "/pname=\n";
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  $language = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "Portuguese";
      break;
    case "en_US":
      $language = "English";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Chinese (traditional):
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese Traditional";
        break;

      // Chinese (simplified):
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese Simplified";
        break;

      // Czech:
      case "CZ": // Czech Republic
        $language = "Czech";
        break;

      // Dutch:
      case "NL": // Netherlands
        $language = "Dutch";
        break;

      // Finnish:
      case "FI": // Finland
        $language = "Suom alainen";
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
        $language = "French";
        break;

      // German:
      case "AT": // Austria
      case "CH": // Switzerland
      case "DE": // Germany
      case "BE": // Belgium
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        break;

      // Hungarian:
      case "HU": // Hungary
        $language = "Hungarian";
        break;

      // Hebrew:
      case "IL": // Israel
        $language = "Hebrew";
        break;

      // Italian:
      case "IT": // Italy
        $language = "Italian";
        break;

      // Japanese:
      case "JP": // Japan
        $language = "Japanese";
        break;

      // Korean:
      case "KP": // Korea (Democratic People's Republic of)
      case "KR": // Korea (Republic of)
        $language = "Korean";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        break;

      // Portuguese:
      case "BR": // Brazil
      case "AO": // Angola
      case "CV": // Cabo Verde
      case "GW": // Guinea-Bissau
      case "TL": // Timor-Leste
      case "MO": // Macao
      case "MZ": // Mozambique
      case "PT": // Portugal
      case "ST": // Sao Tome and Principe
        $language = "Portuguese";
        break;

      // Russian:
      case "RU": // Russian Federation
        $language = "Russian";
        break;

      // Slovak:
      case "SK": // Slovakia
        $language = "Slovak";
        break;

      // Spanish:
      case "AR": // Argentina
      case "CL": // Chile
      case "PY": // Paraguay
      case "UY": // Uruguay
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
      case "ES": // Spain
      case "CR": // Costa Rica
      case "PR": // Puerto Rico
      case "MX": // Mexico
        $language = "Spanish";
        break;

      // Ukranian:
      case "UA": // Ukraine
        $language = "Ukrainian";
        break;

      // Default (English United States):
      default:
        $language = "English";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $locale = "";
  switch ( $parameters["Country"])
  {
    case "IL": // Israel
      $locale = "Israel";
      break;
    case "CH": // China
      $locale = "China";
      break;
    case "FR": // France
      $locale = "France";
      break;
    case "DE": // Germany
      $locale = "Germany";
      break;
    case "NL": // Netherlands
      $locale = "Netherlands";
      break;
    case "GB": // United Kingdom of Great Britain and Northern Ireland
      $locale = "UK";
      break;
    case "BR": // Brazil
      $locale = "Brazil";
      break;
    case "IT": // Italy
      $locale = "Italy";
      break;
    case "AR": // Argentina
      $locale = "Argentina";
      break;
    case "PT": // Portugal
      $locale = "Portugal";
      break;
    case "RU": // Russian Federation
      $locale = "Russia";
      break;
    case "AU": // Australia
      $locale = "Australia";
      break;
    case "IN": // India
      $locale = "India";
      break;
    default: // United States of America
      $locale = "USA";
      break;
  }
  $content = str_replace ( "{{{locale}}}", $locale, $content);

  // Create dialplan:
  $dialplan = "";
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    $dialplan .= "|" . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "x", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"])));
  }
  $content = str_replace ( "{{{dialplan}}}", substr ( $dialplan, 1), $content);
  $content = str_replace ( "{{{externalprefix}}}", $parameters["Prefix"], $content);
 
  // Set NTP servers and time offset:
  $timezone = ( $parameters["Offset"] < 0 ? "-" : "+") . floor ( abs ( $parameters["Offset"])) . ":";
  switch ( (int) ( abs ( $parameters["Offset"] - (int) $parameters["Offset"]) * 10))
  {
    case 3:
      $timezone .= "15";
      break;
    case 5:
      $timezone .= "30";
      break;
    case 8:
      $timezone .= "45";
      break;
    default:
      $timezone .= "00";
      break;
  }
  $content = str_replace ( "{{{ntpserver1}}}", sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "", $content);
  $content = str_replace ( "{{{ntpserver2}}}", sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "", $content);
  $content = str_replace ( "{{{offset}}}", $timezone, $content);

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
      $dateformat = "European";
      $timeformat = "24Hour";
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
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
    case "ID": // Indonesia
      $dateformat = "American";
      $timeformat = "12Hour";
      break;
    default: // Most used format (M/D/YYA)
      $dateformat = "American";
      $timeformat = "12Hour";
      break;
  }
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", crypt ( $equipmentconfig["ExtraSettings"]["User"]["Password"], "$1$" . gensalt () . "$"), $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", crypt ( $equipmentconfig["ExtraSettings"]["Admin"]["Password"], "$1$" . gensalt () . "$"), $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtoupper ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify audiocodes-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to remove the AudioCodes auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_audiocodes_remove ( $buffer, $parameters)
{
  global $_in;

  // Remove AP file
  if ( ! unlink ( $_in["general"]["tftpdir"] . "/" . strtoupper ( $parameters["MAC"]) . ".cfg"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP removed.");
}

/**
 * Function to create the AudioCodes audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_audiocodes_default ( $buffer, $parameters)
{
  // Return AudioCodes audio only SIP account template
  return "[" . $parameters["Username"] . "](vd_extension_" . $parameters["Number"] . ",vd_equipment_" . $parameters["Type"] . ")\n" .
         "transport=transport-udp\n" .
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
