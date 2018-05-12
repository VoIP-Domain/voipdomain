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
 * VoIP Domain Grandstream equipments models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Grandstream
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_model_gxp1160_configure", "equipment_model_grandstream_basic_configure");
framework_add_hook ( "equipment_model_gxp1160_firmware_add", "equipment_model_grandstream_add_firmware");
framework_add_hook ( "equipment_model_gxp1160_firmware_remove", "equipment_model_grandstream_remove_firmware");
framework_add_hook ( "ap_type_gxp1160", "ap_type_grandstream_gxp116x");
framework_add_hook ( "ap_type_gxp1160_remove", "ap_type_grandstream_remove");
framework_add_hook ( "account_type_gxp1160", "account_type_grandstream_audio");

framework_add_hook ( "equipment_model_gxp1165_configure", "equipment_model_grandstream_basic_configure");
framework_add_hook ( "equipment_model_gxp1165_firmware_add", "equipment_model_grandstream_add_firmware");
framework_add_hook ( "equipment_model_gxp1165_firmware_remove", "equipment_model_grandstream_remove_firmware");
framework_add_hook ( "ap_type_gxp1165", "ap_type_grandstream_gxp116x");
framework_add_hook ( "ap_type_gxp1165_remove", "ap_type_grandstream_remove");
framework_add_hook ( "account_type_gxp1165", "account_type_grandstream_audio");

framework_add_hook ( "equipment_model_gxp1610_configure", "equipment_model_grandstream_basic_configure");
framework_add_hook ( "equipment_model_gxp1610_firmware_add", "equipment_model_grandstream_add_firmware");
framework_add_hook ( "equipment_model_gxp1610_firmware_remove", "equipment_model_grandstream_remove_firmware");
framework_add_hook ( "ap_type_gxp1610", "ap_type_grandstream_gxp16xx");
framework_add_hook ( "ap_type_gxp1610_remove", "ap_type_grandstream_remove");
framework_add_hook ( "account_type_gxp1610", "account_type_grandstream_audio");

framework_add_hook ( "equipment_model_gxp1615_configure", "equipment_model_grandstream_basic_configure");
framework_add_hook ( "equipment_model_gxp1615_firmware_add", "equipment_model_grandstream_add_firmware");
framework_add_hook ( "equipment_model_gxp1615_firmware_remove", "equipment_model_grandstream_remove_firmware");
framework_add_hook ( "ap_type_gxp1615", "ap_type_grandstream_gxp16xx");
framework_add_hook ( "ap_type_gxp1615_remove", "ap_type_grandstream_remove");
framework_add_hook ( "account_type_gxp1615", "account_type_grandstream_audio");

framework_add_hook ( "equipment_model_gxp1620_configure", "equipment_model_grandstream_basic_configure");
framework_add_hook ( "equipment_model_gxp1620_firmware_add", "equipment_model_grandstream_add_firmware");
framework_add_hook ( "equipment_model_gxp1620_firmware_remove", "equipment_model_grandstream_remove_firmware");
framework_add_hook ( "ap_type_gxp1620", "ap_type_grandstream_gxp16xx");
framework_add_hook ( "ap_type_gxp1620_remove", "ap_type_grandstream_remove");
framework_add_hook ( "account_type_gxp1620", "account_type_grandstream_audio");

framework_add_hook ( "equipment_model_gxp1625_configure", "equipment_model_grandstream_basic_configure");
framework_add_hook ( "equipment_model_gxp1625_firmware_add", "equipment_model_grandstream_add_firmware");
framework_add_hook ( "equipment_model_gxp1625_firmware_remove", "equipment_model_grandstream_remove_firmware");
framework_add_hook ( "ap_type_gxp1625", "ap_type_grandstream_gxp16xx");
framework_add_hook ( "ap_type_gxp1625_remove", "ap_type_grandstream_remove");
framework_add_hook ( "account_type_gxp1625", "account_type_grandstream_audio");

framework_add_hook ( "equipment_model_gxp1628_configure", "equipment_model_grandstream_basic_configure");
framework_add_hook ( "equipment_model_gxp1628_firmware_add", "equipment_model_grandstream_add_firmware");
framework_add_hook ( "equipment_model_gxp1628_firmware_remove", "equipment_model_grandstream_remove_firmware");
framework_add_hook ( "ap_type_gxp1628", "ap_type_grandstream_gxp16xx");
framework_add_hook ( "ap_type_gxp1628_remove", "ap_type_grandstream_remove");
framework_add_hook ( "account_type_gxp1628", "account_type_grandstream_audio");

framework_add_hook ( "equipment_model_gxp1630_configure", "equipment_model_grandstream_basic_configure");
framework_add_hook ( "equipment_model_gxp1630_firmware_add", "equipment_model_grandstream_add_firmware");
framework_add_hook ( "equipment_model_gxp1630_firmware_remove", "equipment_model_grandstream_remove_firmware");
framework_add_hook ( "ap_type_gxp1630", "ap_type_grandstream_gxp16xx");
framework_add_hook ( "ap_type_gxp1630_remove", "ap_type_grandstream_remove");
framework_add_hook ( "account_type_gxp1630", "account_type_grandstream_audio");

/**
 * Cleanup functions
 */
framework_add_hook ( "ap_grandstream_wipe", "ap_grandstream_wipe");
cleanup_register ( "Accounts-Grandstream", "ap_grandstream_wipe", array ( "Before" => array ( "Accounts")));

framework_add_hook ( "firmware_grandstream_wipe", "firmware_grandstream_wipe");
cleanup_register ( "Firmwares-Grandstream", "firmware_grandstream_wipe", array ( "Before" => array ( "Equipments")));

/**
 * Function to wipe the Grandstream equipment models auto provisioning files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function ap_grandstream_wipe ( $buffer, $parameters)
{
  // Remove auto provisioning configuration files:
  foreach ( list_config ( "datafile", "ap") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( equipment_model_exists ( $data["Type"], "Grandstream"))
    {
      unlink_config ( "ap", "cfg" . strtolower ( $data["MAC"]) . ".xml");
      unlink_config ( "ap", "cfg" . strtolower ( $data["MAC"]) . "-screen.xml");
      unlink_config ( "datafile", $filename);
    }
  }

  return $buffer;
}

/**
 * Function to wipe the Grandstream equipment models firmware files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function firmware_grandstream_wipe ( $buffer, $parameters)
{
  // Grandstream GXP1160 and GXP1165
  unlink_config ( "ap", "Grandstream/GXP116X/gxp1160fw.bin");
  unlink_config ( "apdir", "Grandstream/GXP116X");

  // Grandstream GXP1610, GXP1615, GXP1620, GXP1625, GXP1628 and GXP1630
  unlink_config ( "ap", "Grandstream/GXP16XX/gxp1600fw.bin");
  unlink_config ( "apdir", "Grandstream/GXP16XX");

  // Grandstream vendor directory
  unlink_config ( "apdir", "Grandstream");

  return $buffer;
}

/**
 * Function to configure the basic Grandstream equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_grandstream_basic_configure ( $buffer, $parameters)
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
      case "G723":
        $codecs[] = "g723";
        break;
      case "G729":
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
 * Function to configure the HD audio Grandstream equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_grandstream_hd_configure ( $buffer, $parameters)
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
      case "AMRWB":
        $codecs[] = "amrwb";
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
 * Function to configure the Grandstream equipment model T58V.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_grandstream_t58v_configure ( $buffer, $parameters)
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
      case "G723":
        $codecs[] = "g723";
        break;
      case "G729":
      case "G729A":
        $codecs[] = "g729";
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
      case "G722":
        $codecs[] = "g722";
        break;
      case "SIREN7":
        $codecs[] = "siren7";
        break;
      case "SIREN14":
        $codecs[] = "siren14";
        break;
      default:
        writeLog ( "Invalid audio codec \"" . $codec . "\" to model \"" . $parameters["UID"] . "\"!", VoIP_LOG_WARNING);
        break;
    }
  }

  // Process video codecs array
  foreach ( $parameters["VideoCodecs"] as $codec)
  {
    switch ( $codec)
    {
      case "VP8":
        $codecs[] = "vp8";
        break;
      case "H264":
      case "H264P":
        $codecs[] = "h264";
        break;
      default:
        writeLog ( "Invalid video codec \"" . $codec . "\" to model \"" . $parameters["UID"] . "\"!", VoIP_LOG_WARNING);
        break;
    }
  }

  // Return softphone SIP model template
  return "[vd_equipment_" . $parameters["UID"] . "]\n" .
         "allow=!all," . implode ( ",", $codecs) . "\n" . $buffer;
}

/**
 * Function to configure the Grandstream equipment model VP530.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_grandstream_vp530_configure ( $buffer, $parameters)
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
      case "G723":
        $codecs[] = "g723";
        break;
      case "G729":
      case "G729A":
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
    switch ( $codec)
    {
      case "H263":
        $codecs[] = "h263";
        break;
      case "H264":
        $codecs[] = "h264";
        break;
      case "MPEG4":
        $codecs[] = "mpeg4";
        break;
      default:
        writeLog ( "Invalid video codec \"" . $codec . "\" to model \"" . $parameters["UID"] . "\"!", VoIP_LOG_WARNING);
        break;
    }
  }

  // Return softphone SIP model template
  return "[vd_equipment_" . $parameters["UID"] . "]\n" .
         "allow=!all," . implode ( ",", $codecs) . "\n" . $buffer;
}

/**
 * Function to configure the Grandstream equipment model W52P.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_grandstream_w52p_configure ( $buffer, $parameters)
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
      case "G723":
        $codecs[] = "g723";
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
 * Function to configure the Grandstream CP920 equipment model.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_grandstream_cp920_configure ( $buffer, $parameters)
{
  // Process audio codecs array
  $codecs = array ();
  foreach ( $parameters["AudioCodecs"] as $codec)
  {
    switch ( $codec)
    {
      case "G726":
        $codecs[] = "g726";
        break;
      case "OPUS":
        $codecs[] = "opus";
        break;
      case "G723":
        $codecs[] = "g723";
        break;
      case "ILBC":
        $codecs[] = "ilbc";
        break;
      case "SIREN7":
        $codecs[] = "siren7";
        break;
      case "SIREN14":
        $codecs[] = "siren14";
        break;
      case "G722":
        $codecs[] = "g722";
        break;
      case "ULAW":
        $codecs[] = "ulaw";
        break;
      case "ALAW":
        $codecs[] = "alaw";
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
 * Function to add a Grandstream firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_grandstream_add_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "gxp1160fw.bin":	// Grandstream GXP1160 or GXP1165
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Grandstream/GXP116X") && ! mkdir ( $_in["general"]["tftpdir"] . "/Grandstream/GXP116X", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Grandstream/GXP116X/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      break;
    case "gxp1600fw.bin":	// Grandstream GXP1610 or GXP1615 or GXP1620 or GXP1625 or GXP1628 or GXP1630
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Grandstream/GXP16XX") && ! mkdir ( $_in["general"]["tftpdir"] . "/Grandstream/GXP16XX", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Grandstream/GXP16XX/" . $parameters["Filename"], $parameters["Data"]) === false)
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
 * Function to remove a Grandstream firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_grandstream_remove_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "gxp1160fw.bin":	// Grandstream GXP1160 or GXP1165
      if ( ! check_config ( "datafile", "gxp1160") && ! check_config ( "datafile", "gxp1165"))
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Grandstream/GXP116X/" . $parameters["Filename"]);
        @rmdir ( $_in["general"]["tftpdir"] . "/Grandstream/GXP116X");
      }
      break;
    case "gxp1600fw.bin":	// Grandstream GXP1610 or GXP1615 or GXP1620 or GXP1625 or GXP1628 or GXP1630
      if ( ! check_config ( "datafile", "gxp1610") && ! check_config ( "datafile", "gxp1615") && ! check_config ( "datafile", "gxp1620") && ! check_config ( "datafile", "gxp1625") && ! check_config ( "datafile", "gxp1628") && ! check_config ( "datafile", "gxp1630"))
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Grandstream/GXP16XX/" . $parameters["Filename"]);
        @rmdir ( $_in["general"]["tftpdir"] . "/Grandstream/GXP16XX");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to create the Grandstream GXP1610/GXP1615/GXP1620/GXP1625 auto
 * provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_grandstream_gxp16xx ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "gxp1610":
    case "gxp1615":
    case "gxp1620":
    case "gxp1625":
    case "gxp1628":
    case "gxp1630":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-grandstream-" . $parameters["Type"] . ".xml");
      $screen = file_get_contents ( dirname ( __FILE__) . "/template-grandstream-" . $parameters["Type"] . "-idle-screen.xml");
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

  // Configure equipment CODEC's:
  $codecconfig = "";
  $codecs = array (
    "ULAW" => 0,
    "G726" => 2,
    "G723" => 4,
    "ALAW" => 8,
    "G722" => 9,
    "G729" => 18,
    "ILBC" => 98
  );
  $codecsorder = array ( 57, 58, 59, 60, 61, 62, 46);
  $order = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $codec)
  {
    $codecconfig .= "    <P" . $codecsorder[$order] . ">" . $codecs[$codec] . "</P" . $codecsorder[$order] . ">\n";
    $order++;
  }
  while ( $order < 7)
  {
    $codecconfig .= "    <P" . $codecsorder[$order] . ">" . $codecs[$codec] . "</P" . $codecsorder[$order] . ">\n";
    $order++;
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

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
      $language = "ar";
      $history = "تاريخي";
      $catalog = "فهرس";
      $capture = "يأسر";
      break;

    // Czech:
    case "CZ": // Czech Republic
      $language = "cs";
      $history = "Dějiny";
      $catalog = "Katalog";
      $capture = "Zachyťte";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "de";
      $history = "Geschichte";
      $catalog = "Katalog";
      $capture = "Erfassen";
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
      $history = "Historia";
      $catalog = "Catalogar";
      $capture = "Captura";
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
      $history = "Histoire";
      $catalog = "Catalogue";
      $capture = "Capturer";
      break;

    // Hebrew:
    case "IL": // Israel
      $language = "he";
      $history = "הִיסטוֹרִיָה";
      $catalog = "קָטָלוֹג";
      $capture = "לִלְכּוֹד";
      break;

    // Croatian:
    case "HR": // Croatia
    case "BA": // Bosnia and Herzegovina
    case "ME": // Montenegro
    case "RO": // Romania
    case "RS": // Serbia
      $language = "hr";
      $history = "Povijest";
      $catalog = "Katalog";
      $capture = "Uhvatiti";
      break;

    // Hungarian:
    case "HU": // Hungary
    case "AT": // Austria
    case "SK": // Slovakia
      $language = "hu";
      $history = "Történelem";
      $catalog = "Katalógus";
      $capture = "Elfog";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "it";
      $history = "Storia";
      $catalog = "Catalogare";
      $capture = "Catturare";
      break;

    // Japanese:
    case "JP": // Japan
    case "PW": // Palau
      $language = "ja";
      $history = "歴史";
      $catalog = "カタログ";
      $capture = "捕獲";
      break;

    // Korean:
    case "KP": // Korea (Democratic People's Republic of)
    case "KR": // Korea (Republic of)
      $language = "ko";
      $history = "역사";
      $catalog = "목록";
      $capture = "포착";
      break;

    // Latvian:
    case "LV": // Latvia
      $language = "lv";
      $history = "Vēsture";
      $catalog = "Katalogs";
      $capture = "Uzņemt";
      break;

    // Dutch:
    case "BE": // Belgium
    case "NL": // Netherlands
    case "AW": // Aruba
    case "CW": // Curaçao
    case "SX": // Sint Maarten (Dutch part)
      $language = "nl";
      $history = "Geschiedenis";
      $catalog = "Catalogus";
      $capture = "Vastlegging";
      break;

    // Polish:
    case "PL": // Poland
      $language = "pl";
      $history = "Historia";
      $catalog = "Katalog";
      $capture = "Schwytać";
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
      $language = "pt";
      $history = "Histórico";
      $catalog = "Catálogo";
      $capture = "Capturar";
      break;

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = "ru";
      $history = "История";
      $catalog = "Каталог";
      $capture = "Захватывать";
      break;

    // Slovenian:
    case "SI": // Slovenia
      $language = "sl";
      $history = "Zgodovina";
      $catalog = "Katalog";
      $capture = "Zajemi";
      break;

    // Swedish:
    case "FI": // Finland
    case "SE": // Sweden
    case "AX": // Åland Islands
      $language = "se";
      $history = "Historia";
      $catalog = "Katalog";
      $capture = "Fånga";
      break;

    // Turkish:
    case "TR": // Turkey
      $language = "tr";
      $history = "Tarih";
      $catalog = "Katalog";
      $capture = "Esir almak";
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "zh-tw";
      $history = "歷史";
      $catalog = "目錄";
      $capture = "捕獲";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = "zh";
      $history = "历史";
      $catalog = "目录";
      $capture = "捕获";
      break;

    // English (default):
    default:
      $language = "en";
      $history = "History";
      $catalog = "Catalog";
      $capture = "Capture";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $screen = str_replace ( "{{{history}}}", $history, $screen);
  $screen = str_replace ( "{{{catalog}}}", $catalog, $screen);
  $screen = str_replace ( "{{{capture}}}", $capture, $screen);

  // Set phone country tone:
  $tones = array (
    "Ring" => "",
    "Dial" => "",
    "SecondDial" => "",
    "MessageWaiting" => "",
    "RingBack" => "",
    "CallWaiting" => "",
    "Busy" => "",
    "Reorder" => ""
  );
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tones["Ring"] = "c=425/225-750/1500;";
      $tones["Dial"] = "f1=413@-11,f2=438@-11,c=100/0;";
      $tones["RingBack"] = "f1=425@-11,f2=450@-11,c=400/200-500/1750;";
      $tones["Busy"] = "f1=425@-11,f2=425@-11,c=375/375;";
      break;
    case "AT": // Austria
      $tones["Ring"] = "f1=420,f2=450,c=100/500;";
      $tones["Dial"] = "f1=380,f2=420,c=100/0;";
      $tones["SecondDial"] = "f1=420,f2=450,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=420,c=4/195;";
      $tones["Busy"] = "f1=420,c=40/40;";
      $tones["Reorder"] = "";
      break;
    case "BR": // Brazil
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=97/5;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=5/100;";
      $tones["Busy"] = "f1=425,c=25/25;";
      $tones["Reorder"] = "";
      break;
    case "BE": // Belgium
      $tones["Ring"] = "f1=440,f2=425,c=170/330;";
      $tones["Dial"] = "f1=330,f2=440,c=100/0;";
      $tones["SecondDial"] = "f1=440,f2=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=1400,c=17/17-17/350;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "f1=425,c=17/17;";
      break;
    case "CL": // Chile
      $tones["Ring"] = "f1=400,c=100/300;";
      $tones["Dial"] = "f1=330,f2=440,c=100/0;";
      $tones["SecondDial"] = "f1=400,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "";
      $tones["Busy"] = "f1=400,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "CN": // China
      $tones["Ring"] = "f1=450,c=100/400;";
      $tones["Dial"] = "f1=450,c=100/0;";
      $tones["SecondDial"] = "f1=450,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=450,c=40/40;";
      $tones["Busy"] = "f1=450,c=35/35;";
      $tones["Reorder"] = "";
      break;
    case "CZ": // Czech
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=33/33-66/66;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=33/900;";
      $tones["Busy"] = "f1=425,c=33/33;";
      $tones["Reorder"] = "";
      break;
    case "DK": // Denmark
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/20-20/360;";
      $tones["Busy"] = "f1=425,c=25/25;";
      $tones["Reorder"] = "";
      break;
    case "FI": // Finland
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=65/3;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=15/15-15/800;";
      $tones["Busy"] = "f1=425,c=30/30;";
      $tones["Reorder"] = "";
      break;
    case "FR": // France
      $tones["Ring"] = "f1=440,c=150/350;";
      $tones["Dial"] = "f1=440,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=440,c=30/1000;";
      $tones["Busy"] = "f1=440,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "DE": // Germany
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,f2=400,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,=20/20-20/50;";
      $tones["Busy"] = "f1=425,c=48/48;";
      $tones["Reorder"] = "";
      break;
    case "GB": // Great Britain
      $tones["Ring"] = "f1=400@-20,f2=450@-20,c=400/200-400/2000;";
      $tones["Dial"] = "f1=350@-19,f2=440@-22,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "f1=400@-20,f2=450@-20,c=400/200-400/2000;";
      $tones["CallWaiting"] = "f1=400@-20,c=100/2000;";
      $tones["Busy"] = "f1=400@-20,c=375/375;";
      $tones["Reorder"] = "";
      break;
    case "GR": // Greece
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=400,f2=425,c=20/30,f1=425,f2=450,c=70/80;";
      $tones["SecondDial"] = "f1=425,c=20/30-70-80;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=30/1000;";
      $tones["Busy"] = "f1=425,c=30/30;";
      $tones["Reorder"] = "";
      break;
    case "HU": // Hungary
      $tones["Ring"] = "f1=425,c=125/375;";
      $tones["Dial"] = "f1=425,f2=450,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=4/196;";
      $tones["Busy"] = "f1=425,c=30/30;";
      $tones["Reorder"] = "";
      break;
    case "IN": // India
      $tones["Ring"] = "f1=400,c=40/20;";
      $tones["Dial"] = "f1=400,c=280/20;";
      $tones["SecondDial"] = "f1=400,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=400,c=20/10-20/750;";
      $tones["Busy"] = "f1=400,c=75/75;";
      $tones["Reorder"] = "";
      break;
    case "IT": // Italy
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=20/20-60/100;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=40/10-25/10-15/100;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "JP": // Japan
      $tones["Ring"] = "f1=400,c=100/200;";
      $tones["Dial"] = "f1=400,c=25/25;";
      $tones["SecondDial"] = "f1=400,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=400,c=50/400-5/45-5/345;";
      $tones["Busy"] = "f1=400,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "LT": // Lithuania
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=15/15-15-400;";
      $tones["Busy"] = "f1=425,c=35/35;";
      $tones["Reorder"] = "";
      break;
    case "MX": // Mexico
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "";
      $tones["Busy"] = "f1=425,c=25/25;";
      $tones["Reorder"] = "";
      break;
    case "NL": // Netherlands
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=50/5;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=50/950;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "NZ": // New Zealand
      $tones["Ring"] = "f1=400,f2=450,c=40/20-40/200;";
      $tones["Dial"] = "f1=400,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "f1=400,c=10/10;";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=400,c=20/300;";
      $tones["Busy"] = "f1=400,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "NO": // Norway
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=470,f2=425,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/60-20/1000;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "PT": // Portugal
      $tones["Ring"] = "f1=425,c=100/500;";
      $tones["Dial"] = "f1=425,c=100/20;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "ES": // Spain
      $tones["Ring"] = "f1=425,c=150/300;";
      $tones["Dial"] = "f1=425,c=100/10;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=175/175-175/350;";
      $tones["Busy"] = "f1=425,c=20/20;";
      $tones["Reorder"] = "";
      break;
    case "CH": // Switzerland
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,f2=340,c=110/110;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/20-20/400;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "SE": // Sweden
      $tones["Ring"] = "f1=425,c=100/500;";
      $tones["Dial"] = "f1=425,c=32/2;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/50-20/1000;";
      $tones["Busy"] = "f1=425,c=25/25;";
      $tones["Reorder"] = "";
      break;
    case "RU": // Russia
      $tones["Ring"] = "f1=425,c=10/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/500;";
      $tones["Busy"] = "f1=425,c=35/35;";
      $tones["Reorder"] = "";
      break;
    default: // United States of America
      $tones["Ring"] = "f1=440,f2=480,c=200/400;";
      $tones["Dial"] = "f1=350,f2=440;";
      $tones["SecondDial"] = "f1=350,f2=440;";
      $tones["MessageWaiting"] = "f1=350,f2=440,c=10/10;";
      $tones["RingBack"] = "f1=440,f2=480,c=200/400;";
      $tones["CallWaiting"] = "f1=440,f2=440,c=25/525;";
      $tones["Busy"] = "f1=480,f2=620,c=50/50;";
      $tones["Reorder"] = "f1=480,f2=620,c=25/25;";
      break;
  }
  $phonetones = "    <P345>" . $tones["Ring"] . "</P345>\n";
  $phonetones .= "    <P343>" . $tones["Dial"] . "</P343>\n";
  $phonetones .= "    <P2909>" . $tones["SecondDial"] . "</P2909>\n";
  $phonetones .= "    <P344>" . $tones["MessageWaiting"] . "</P344>\n";
  $phonetones .= "    <P346>" . $tones["RingBack"] . "</P346>\n";
  $phonetones .= "    <P347>" . $tones["CallWaiting"] . "</P347>\n";
  $phonetones .= "    <P348>" . $tones["Busy"] . "</P348>\n";
  $phonetones .= "    <P349>" . $tones["Reorder"] . "</P349>\n";
  $content = str_replace ( "{{{phonetones}}}", $phonetones, $content);

  // Create dialplan:
  $dialplan = "";
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
    $dialplan .= "|" . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] . "," : "") . str_replace ( "X", "x", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"])));
  }
  $content = str_replace ( "{{{dialplan}}}", "{" . substr ( $dialplan, 1) . "}", $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
      $timezone = "TAB+11";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
    case "Pacific/Marquesas":
      $timezone = "HAW10";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "AKST9AKDT";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "PST8PDT,M3.2.0,M11.1.0";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "America/Tijuana":
    case "Pacific/Pitcairn":
      $timezone = "PST8PDT";
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
      $timezone = "MST7MDT";
      break;
    case "America/Phoenix":
      $timezone = "MST7";
      break;
    case "America/Winnipeg":
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
      $timezone = "CST6CDT";
      break;
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Tegucigalpa":
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
    case "America/Managua":
    case "America/Belize":
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "Pacific/Galapagos":
      $timezone = "CST+6";
      break;
    case "America/Nassau":
    case "America/Bogota":
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
    case "America/Havana":
    case "America/Lima":
    case "America/Guayaquil":
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "EST5";
      break;
    case "America/Cayman":
    case "America/Detroit":
    case "America/Grand_Turk":
    case "America/Jamaica":
    case "America/Kentucky/Louisville":
    case "America/Kentucky/Monticello":
    case "America/New_York":
    case "America/Panama":
    case "America/Port-au-Prince":
    case "Pacific/Easter":
      $timezone = "EST5EDT";
      break;
    case "America/Caracas":
      $timezone = "TZf+4:30";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "AST4ADT,M3.2.0,M11.1.0";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
    case "America/Puerto_Rico":
    case "Atlantic/Bermuda":
    case "America/Port_of_Spain":
      $timezone = "AST4ADT";
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
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
    case "America/Santo_Domingo":
    case "America/St_Barthelemy":
    case "America/St_Kitts":
    case "America/St_Lucia":
    case "America/St_Thomas":
    case "America/St_Vincent":
    case "America/Thule":
    case "America/Tortola":
      $timezone = "CLT4CLST,M9.1.6/24:00,M4.1.6/24:00";
      break;
    case "America/St_Johns":
      $timezone = "NST+3:30NDT+2:30,M3.2.0/02:00:00,M11.1.0/02:00:00";
      break;
    case "America/Godthab":
      $timezone = "TZK+3";
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
      $timezone = "UTC+3";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
    case "America/Araguaina":
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cuiaba":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = "BRST+3";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "TZL+2";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = "TZM+1";
      break;
    case "America/Danmarkshavn":
    case "Atlantic/Faroe":
    case "Atlantic/Canary":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Africa/Casablanca":
    case "Africa/Monrovia":
    case "Africa/Abidjan":
    case "Africa/Accra":
    case "Africa/Bamako":
    case "Africa/Banjul":
    case "Africa/Bissau":
    case "Africa/Conakry":
    case "Africa/Dakar":
    case "Africa/El_Aaiun":
    case "Africa/Freetown":
    case "Africa/Lome":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
      $timezone = "TZN+0";
      break;
    case "Europe/London":
      $timezone = "GMT+0BST-1,M3.5.0/01:00:00,M10.5.0/02:00:00";
      break;
    case "Europe/Lisbon":
    case "Atlantic/Madeira":
      $timezone = "WET-0WEST-1,M3.5.0/01:00:00,M10.5.0/02:00:00";
      break;
    case "Europe/Dublin":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "GMT+0IST-1,M3.5.0/01:00:00,M10.5.0/02:00:00";
      break;
    case "Europe/Tirane":
    case "Europe/Vienna":
    case "Europe/Brussels":
    case "Europe/Belgrade":
    case "Europe/Bratislava":
    case "Africa/Ndjamena":
    case "Africa/Algiers":
    case "Africa/Bangui":
    case "Africa/Brazzaville":
    case "Africa/Douala":
    case "Africa/Kinshasa":
    case "Africa/Lagos":
    case "Africa/Libreville":
    case "Africa/Luanda":
    case "Africa/Malabo":
    case "Africa/Niamey":
    case "Africa/Porto-Novo":
    case "Africa/Tunis":
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
    case "Europe/Zagreb":
    case "Europe/Prague":
    case "Europe/Warsaw":
    case "Europe/Ljubljana":
    case "Europe/Zurich":
    case "Europe/Stockholm":
    case "Europe/Copenhagen":
    case "Europe/Andorra":
    case "Europe/Gibraltar":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Paris":
    case "Europe/Berlin":
    case "Europe/Budapest":
    case "Europe/Rome":
    case "Europe/Luxembourg":
    case "Europe/Skopje":
    case "Europe/Amsterdam":
      $timezone = "CET-1CEST-2,M3.5.0/02:00:00,M10.5.0/03:00:00";
      break;
    case "Africa/Cairo":
    case "Africa/Harare":
    case "Africa/Tripoli":
    case "Africa/Blantyre":
    case "Africa/Bujumbura":
    case "Africa/Gaborone":
    case "Africa/Johannesburg":
    case "Africa/Kigali":
    case "Africa/Lubumbashi":
    case "Africa/Lusaka":
    case "Africa/Maputo":
    case "Africa/Maseru":
    case "Africa/Mbabane":
    case "Africa/Windhoek":
      $timezone = "TZP-2";
      break;
    case "Europe/Tallinn":
    case "Europe/Helsinki":
    case "Europe/Athens":
    case "Europe/Bucharest":
    case "Europe/Riga":
    case "Europe/Chisinau":
    case "Europe/Simferopol":
    case "Europe/Sofia":
    case "Europe/Vilnius":
    case "Europe/Mariehamn":
    case "Europe/Istanbul":
      $timezone = "EET-2EEST-3,M3.5.0/00:00:00,M10.4.0/00:00:00";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "EET-2EEST,M3.5.0/3,M10.5.0/4";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
    case "Asia/Jerusalem":
    case "Asia/Amman":
    case "Asia/Nicosia":
    case "Asia/Beirut":
    case "Asia/Damascus":
      $timezone = "EET-2EEST-3,M3.4.0/03:00:00,M10.5.0/04:00:00";
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
    case "Asia/Riyadh":
    case "Europe/Minsk":
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "TZQ-3";
      break;
    case "Europe/Kaliningrad":
      $timezone = "MSK-3";
      break;
    case "Asia/Tehran":
      $timezone = "IRST-3:30IRDT-4:30,M3.3.0/24:00:00,M9.3.2/24:00:00";
      break;
    case "Asia/Muscat":
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Asia/Yerevan":
    case "Asia/Baku":
    case "Asia/Kabul":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "TZR-4";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
    case "Asia/Karachi":
    case "Asia/Tashkent":
    case "Asia/Samarkand":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "TZS-5";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = "TZT-5:30";
      break;
    case "Asia/Kathmandu":
      $timezone = "TZU-5:45";
      break;
    case "Asia/Dhaka":
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
    case "Asia/Yekaterinburg":
      $timezone = "TZV-6";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "TZW-6:30";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "TZX-7";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "WIB-7";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Taipei":
    case "Asia/Krasnoyarsk":
      $timezone = "TZY-8";
      break;
    case "Asia/Hong_Kong":
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "SGT-8";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "ULAT-8";
      break;
    case "Australia/Perth":
    case "Australia/Eucla":
      $timezone = "WST-8";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
    case "Asia/Tokyo":
    case "Asia/Irkutsk":
      $timezone = "TZZ-9";
      break;
    case "Australia/Darwin":
      $timezone = "CST-9:30";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "TZb-10";
      break;
    case "Australia/Lindeman":
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "EST-10EDT-11,M10.1.0/02:00:00,M4.1.0/03:00:00";
      break;
    case "Australia/Brisbane":
      $timezone = "EST-10";
      break;
    case "Asia/Yakutsk":
      $timezone = "EST-10EDT-11,M10.1.0/02:00:00,M3.5.0/03:00:00";
      break;
    case "Pacific/Noumea":
    case "Antarctica/Macquarie":
    case "Australia/Currie":
    case "Australia/Hobart":
    case "Australia/Lord_Howe":
    case "Australia/Melbourne":
    case "Australia/Sydney":
    case "Pacific/Efate":
    case "Pacific/Guadalcanal":
    case "Pacific/Kosrae":
    case "Pacific/Pohnpei":
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
    case "Pacific/Norfolk":
      $timezone = "TZc-11";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "NZST-12NZDT-13,M9.4.0/02:00:00,M4.1.0/03:00:00";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "NZST-12NZDT-13,M9.4.0/02:00:00,M4.1.0/03:00:00";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
    case "Pacific/Chatham":
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "TZe-13";
      break;
  }
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
  $ntpaddr = "";
  foreach ( $parameters["NTP"] as $ntp)
  {
    if ( empty ( $ntpaddr) && $hosts = gethostbynamel ( $ntp))
    {
      $ntpaddr = $hosts[0];
    }
  }
  $content = str_replace ( "{{{ntpserver1}}}", $ntpaddr, $content);

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
      $datestring = "$D-$o-$Y";
      $timestring = "$H:$m $s";
      $dateformat = 2; // DD-MM-YYYY
      $timeformat = 1; // 24h
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
      $datestring = "$D $M $Y";
      $timestring = "$H:$m $s";
      $dateformat = 3; // DDDD, MMM DD
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $datestring = "$D-$M-$Y";
      $timestring = "$H:$m $s";
      $dateformat = 2; // DD-MM-YYYY
      $timeformat = 1; // 24h
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
      $datestring = "$Y-$o-$D";
      $timestring = "$H:$m $s";
      $dateformat = 0; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $datestring = "$o-$D-$Y";
      $timestring = "$h:$m $P $s";
      $dateformat = 1; // MM-DD-YYYY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $datestring = "$o-$D-$Y";
      $timestring = "$H:$m $s";
      $dateformat = 1; // MM-DD-YYYY
      $timeformat = 1; // 24h
      break;
    default:
      $datestring = "$Y-$o-$D";
      $timestring = "$h:$m $P $s";
      $dateformat = 0; // YYYY-MM-DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);
  $screen = str_replace ( "{{{timestring}}}", $timestring, $screen);
  $screen = str_replace ( "{{{datestring}}}", $datestring, $screen);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["UserPassword"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["AdminPassword"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", $_in["general"]["address"], $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/cfg" . strtolower ( $parameters["MAC"]) . ".xml", $content) === false || file_put_contents ( $_in["general"]["tftpdir"] . "/cfg" . strtolower ( $parameters["MAC"]) . "-screen.xml", $screen) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify grandstream-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Grandstream GXP1160/GXP1165 auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_grandstream_gxp116x ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "gxp1160":
    case "gxp1165":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-grandstream-" . $parameters["Type"] . ".xml");
      $screen = file_get_contents ( dirname ( __FILE__) . "/template-grandstream-" . $parameters["Type"] . "-idle-screen.xml");
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

  // Configure equipment CODEC's:
  $codecconfig = "";
  $codecs = array (
    "ULAW" => 0,
    "G726" => 2,
    "G723" => 4,
    "ALAW" => 8,
    "G722" => 9,
    "G729" => 18,
    "ILBC" => 98
  );
  $codecsorder = array ( 57, 58, 59, 60, 61, 62, 46);
  $order = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $codec)
  {
    $codecconfig .= "    <P" . $codecsorder[$order] . ">" . $codecs[$codec] . "</P" . $codecsorder[$order] . ">\n";
    $order++;
  }
  while ( $order < 7)
  {
    $codecconfig .= "    <P" . $codecsorder[$order] . ">" . $codecs[$codec] . "</P" . $codecsorder[$order] . ">\n";
    $order++;
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

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
      $language = "ar";
      $history = "تاريخي";
      $catalog = "فهرس";
      $capture = "يأسر";
      break;

    // Czech:
    case "CZ": // Czech Republic
      $language = "cs";
      $history = "Dějiny";
      $catalog = "Katalog";
      $capture = "Zachyťte";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "de";
      $history = "Geschichte";
      $catalog = "Katalog";
      $capture = "Erfassen";
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
      $history = "Historia";
      $catalog = "Catalogar";
      $capture = "Captura";
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
      $history = "Histoire";
      $catalog = "Catalogue";
      $capture = "Capturer";
      break;

    // Hebrew:
    case "IL": // Israel
      $language = "he";
      $history = "הִיסטוֹרִיָה";
      $catalog = "קָטָלוֹג";
      $capture = "לִלְכּוֹד";
      break;

    // Croatian:
    case "HR": // Croatia
    case "BA": // Bosnia and Herzegovina
    case "ME": // Montenegro
    case "RO": // Romania
    case "RS": // Serbia
      $language = "hr";
      $history = "Povijest";
      $catalog = "Katalog";
      $capture = "Uhvatiti";
      break;

    // Hungarian:
    case "HU": // Hungary
    case "AT": // Austria
    case "SK": // Slovakia
      $language = "hu";
      $history = "Történelem";
      $catalog = "Katalógus";
      $capture = "Elfog";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "it";
      $history = "Storia";
      $catalog = "Catalogare";
      $capture = "Catturare";
      break;

    // Japanese:
    case "JP": // Japan
    case "PW": // Palau
      $language = "ja";
      $history = "歴史";
      $catalog = "カタログ";
      $capture = "捕獲";
      break;

    // Korean:
    case "KP": // Korea (Democratic People's Republic of)
    case "KR": // Korea (Republic of)
      $language = "ko";
      $history = "역사";
      $catalog = "목록";
      $capture = "포착";
      break;

    // Latvian:
    case "LV": // Latvia
      $language = "lv";
      $history = "Vēsture";
      $catalog = "Katalogs";
      $capture = "Uzņemt";
      break;

    // Dutch:
    case "BE": // Belgium
    case "NL": // Netherlands
    case "AW": // Aruba
    case "CW": // Curaçao
    case "SX": // Sint Maarten (Dutch part)
      $language = "nl";
      $history = "Geschiedenis";
      $catalog = "Catalogus";
      $capture = "Vastlegging";
      break;

    // Polish:
    case "PL": // Poland
      $language = "pl";
      $history = "Historia";
      $catalog = "Katalog";
      $capture = "Schwytać";
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
      $language = "pt";
      $history = "Histórico";
      $catalog = "Catálogo";
      $capture = "Capturar";
      break;

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = "ru";
      $history = "История";
      $catalog = "Каталог";
      $capture = "Захватывать";
      break;

    // Slovenian:
    case "SI": // Slovenia
      $language = "sl";
      $history = "Zgodovina";
      $catalog = "Katalog";
      $capture = "Zajemi";
      break;

    // Swedish:
    case "FI": // Finland
    case "SE": // Sweden
    case "AX": // Åland Islands
      $language = "se";
      $history = "Historia";
      $catalog = "Katalog";
      $capture = "Fånga";
      break;

    // Turkish:
    case "TR": // Turkey
      $language = "tr";
      $history = "Tarih";
      $catalog = "Katalog";
      $capture = "Esir almak";
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "zh-tw";
      $history = "歷史";
      $catalog = "目錄";
      $capture = "捕獲";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = "zh";
      $history = "历史";
      $catalog = "目录";
      $capture = "捕获";
      break;

    // English (default):
    default:
      $language = "en";
      $history = "History";
      $catalog = "Catalog";
      $capture = "Capture";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $screen = str_replace ( "{{{history}}}", $history, $screen);
  $screen = str_replace ( "{{{catalog}}}", $catalog, $screen);
  $screen = str_replace ( "{{{capture}}}", $capture, $screen);

  // Set phone country tone:
  $tones = array (
    "Ring" => "",
    "Dial" => "",
    "SecondDial" => "",
    "MessageWaiting" => "",
    "RingBack" => "",
    "CallWaiting" => "",
    "Busy" => "",
    "Reorder" => ""
  );
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tones["Ring"] = "c=425/225-750/1500;";
      $tones["Dial"] = "f1=413@-11,f2=438@-11,c=100/0;";
      $tones["RingBack"] = "f1=425@-11,f2=450@-11,c=400/200-500/1750;";
      $tones["Busy"] = "f1=425@-11,f2=425@-11,c=375/375;";
      break;
    case "AT": // Austria
      $tones["Ring"] = "f1=420,f2=450,c=100/500;";
      $tones["Dial"] = "f1=380,f2=420,c=100/0;";
      $tones["SecondDial"] = "f1=420,f2=450,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=420,c=4/195;";
      $tones["Busy"] = "f1=420,c=40/40;";
      $tones["Reorder"] = "";
      break;
    case "BR": // Brazil
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=97/5;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=5/100;";
      $tones["Busy"] = "f1=425,c=25/25;";
      $tones["Reorder"] = "";
      break;
    case "BE": // Belgium
      $tones["Ring"] = "f1=440,f2=425,c=170/330;";
      $tones["Dial"] = "f1=330,f2=440,c=100/0;";
      $tones["SecondDial"] = "f1=440,f2=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=1400,c=17/17-17/350;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "f1=425,c=17/17;";
      break;
    case "CL": // Chile
      $tones["Ring"] = "f1=400,c=100/300;";
      $tones["Dial"] = "f1=330,f2=440,c=100/0;";
      $tones["SecondDial"] = "f1=400,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "";
      $tones["Busy"] = "f1=400,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "CN": // China
      $tones["Ring"] = "f1=450,c=100/400;";
      $tones["Dial"] = "f1=450,c=100/0;";
      $tones["SecondDial"] = "f1=450,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=450,c=40/40;";
      $tones["Busy"] = "f1=450,c=35/35;";
      $tones["Reorder"] = "";
      break;
    case "CZ": // Czech
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=33/33-66/66;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=33/900;";
      $tones["Busy"] = "f1=425,c=33/33;";
      $tones["Reorder"] = "";
      break;
    case "DK": // Denmark
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/20-20/360;";
      $tones["Busy"] = "f1=425,c=25/25;";
      $tones["Reorder"] = "";
      break;
    case "FI": // Finland
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=65/3;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=15/15-15/800;";
      $tones["Busy"] = "f1=425,c=30/30;";
      $tones["Reorder"] = "";
      break;
    case "FR": // France
      $tones["Ring"] = "f1=440,c=150/350;";
      $tones["Dial"] = "f1=440,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=440,c=30/1000;";
      $tones["Busy"] = "f1=440,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "DE": // Germany
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,f2=400,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,=20/20-20/50;";
      $tones["Busy"] = "f1=425,c=48/48;";
      $tones["Reorder"] = "";
      break;
    case "GB": // Great Britain
      $tones["Ring"] = "f1=400@-20,f2=450@-20,c=400/200-400/2000;";
      $tones["Dial"] = "f1=350@-19,f2=440@-22,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "f1=400@-20,f2=450@-20,c=400/200-400/2000;";
      $tones["CallWaiting"] = "f1=400@-20,c=100/2000;";
      $tones["Busy"] = "f1=400@-20,c=375/375;";
      $tones["Reorder"] = "";
      break;
    case "GR": // Greece
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=400,f2=425,c=20/30,f1=425,f2=450,c=70/80;";
      $tones["SecondDial"] = "f1=425,c=20/30-70-80;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=30/1000;";
      $tones["Busy"] = "f1=425,c=30/30;";
      $tones["Reorder"] = "";
      break;
    case "HU": // Hungary
      $tones["Ring"] = "f1=425,c=125/375;";
      $tones["Dial"] = "f1=425,f2=450,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=4/196;";
      $tones["Busy"] = "f1=425,c=30/30;";
      $tones["Reorder"] = "";
      break;
    case "IN": // India
      $tones["Ring"] = "f1=400,c=40/20;";
      $tones["Dial"] = "f1=400,c=280/20;";
      $tones["SecondDial"] = "f1=400,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=400,c=20/10-20/750;";
      $tones["Busy"] = "f1=400,c=75/75;";
      $tones["Reorder"] = "";
      break;
    case "IT": // Italy
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=20/20-60/100;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=40/10-25/10-15/100;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "JP": // Japan
      $tones["Ring"] = "f1=400,c=100/200;";
      $tones["Dial"] = "f1=400,c=25/25;";
      $tones["SecondDial"] = "f1=400,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=400,c=50/400-5/45-5/345;";
      $tones["Busy"] = "f1=400,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "LT": // Lithuania
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=15/15-15-400;";
      $tones["Busy"] = "f1=425,c=35/35;";
      $tones["Reorder"] = "";
      break;
    case "MX": // Mexico
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "";
      $tones["Busy"] = "f1=425,c=25/25;";
      $tones["Reorder"] = "";
      break;
    case "NL": // Netherlands
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,c=50/5;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=50/950;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "NZ": // New Zealand
      $tones["Ring"] = "f1=400,f2=450,c=40/20-40/200;";
      $tones["Dial"] = "f1=400,c=100/0;";
      $tones["SecondDial"] = "";
      $tones["MessageWaiting"] = "f1=400,c=10/10;";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=400,c=20/300;";
      $tones["Busy"] = "f1=400,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "NO": // Norway
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=470,f2=425,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/60-20/1000;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "PT": // Portugal
      $tones["Ring"] = "f1=425,c=100/500;";
      $tones["Dial"] = "f1=425,c=100/20;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "ES": // Spain
      $tones["Ring"] = "f1=425,c=150/300;";
      $tones["Dial"] = "f1=425,c=100/10;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=175/175-175/350;";
      $tones["Busy"] = "f1=425,c=20/20;";
      $tones["Reorder"] = "";
      break;
    case "CH": // Switzerland
      $tones["Ring"] = "f1=425,c=100/400;";
      $tones["Dial"] = "f1=425,f2=340,c=110/110;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/20-20/400;";
      $tones["Busy"] = "f1=425,c=50/50;";
      $tones["Reorder"] = "";
      break;
    case "SE": // Sweden
      $tones["Ring"] = "f1=425,c=100/500;";
      $tones["Dial"] = "f1=425,c=32/2;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/50-20/1000;";
      $tones["Busy"] = "f1=425,c=25/25;";
      $tones["Reorder"] = "";
      break;
    case "RU": // Russia
      $tones["Ring"] = "f1=425,c=10/400;";
      $tones["Dial"] = "f1=425,c=100/0;";
      $tones["SecondDial"] = "f1=425,c=100/0;";
      $tones["MessageWaiting"] = "";
      $tones["RingBack"] = "";
      $tones["CallWaiting"] = "f1=425,c=20/500;";
      $tones["Busy"] = "f1=425,c=35/35;";
      $tones["Reorder"] = "";
      break;
    default: // United States of America
      $tones["Ring"] = "f1=440,f2=480,c=200/400;";
      $tones["Dial"] = "f1=350,f2=440;";
      $tones["SecondDial"] = "f1=350,f2=440;";
      $tones["MessageWaiting"] = "f1=350,f2=440,c=10/10;";
      $tones["RingBack"] = "f1=440,f2=480,c=200/400;";
      $tones["CallWaiting"] = "f1=440,f2=440,c=25/525;";
      $tones["Busy"] = "f1=480,f2=620,c=50/50;";
      $tones["Reorder"] = "f1=480,f2=620,c=25/25;";
      break;
  }
  $phonetones = "    <P345>" . $tones["Ring"] . "</P345>\n";
  $phonetones .= "    <P343>" . $tones["Dial"] . "</P343>\n";
  $phonetones .= "    <P2909>" . $tones["SecondDial"] . "</P2909>\n";
  $phonetones .= "    <P344>" . $tones["MessageWaiting"] . "</P344>\n";
  $phonetones .= "    <P346>" . $tones["RingBack"] . "</P346>\n";
  $phonetones .= "    <P347>" . $tones["CallWaiting"] . "</P347>\n";
  $phonetones .= "    <P348>" . $tones["Busy"] . "</P348>\n";
  $phonetones .= "    <P349>" . $tones["Reorder"] . "</P349>\n";
  $content = str_replace ( "{{{phonetones}}}", $phonetones, $content);

  // Create dialplan:
  $dialplan = "";
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
    $dialplan .= "|" . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] . "," : "") . str_replace ( "X", "x", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"])));
  }
  $content = str_replace ( "{{{dialplan}}}", "{" . substr ( $dialplan, 1) . "}", $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
      $timezone = "TAB+11";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
    case "Pacific/Marquesas":
      $timezone = "HAW10";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "AKST9AKDT";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "PST8PDT,M3.2.0,M11.1.0";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "America/Tijuana":
    case "Pacific/Pitcairn":
      $timezone = "PST8PDT";
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
      $timezone = "MST7MDT";
      break;
    case "America/Phoenix":
      $timezone = "MST7";
      break;
    case "America/Winnipeg":
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
      $timezone = "CST6CDT";
      break;
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Tegucigalpa":
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
    case "America/Managua":
    case "America/Belize":
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "Pacific/Galapagos":
      $timezone = "CST+6";
      break;
    case "America/Nassau":
    case "America/Bogota":
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
    case "America/Havana":
    case "America/Lima":
    case "America/Guayaquil":
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "EST5";
      break;
    case "America/Cayman":
    case "America/Detroit":
    case "America/Grand_Turk":
    case "America/Jamaica":
    case "America/Kentucky/Louisville":
    case "America/Kentucky/Monticello":
    case "America/New_York":
    case "America/Panama":
    case "America/Port-au-Prince":
    case "Pacific/Easter":
      $timezone = "EST5EDT";
      break;
    case "America/Caracas":
      $timezone = "TZf+4:30";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "AST4ADT,M3.2.0,M11.1.0";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
    case "America/Puerto_Rico":
    case "Atlantic/Bermuda":
    case "America/Port_of_Spain":
      $timezone = "AST4ADT";
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
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
    case "America/Santo_Domingo":
    case "America/St_Barthelemy":
    case "America/St_Kitts":
    case "America/St_Lucia":
    case "America/St_Thomas":
    case "America/St_Vincent":
    case "America/Thule":
    case "America/Tortola":
      $timezone = "CLT4CLST,M9.1.6/24:00,M4.1.6/24:00";
      break;
    case "America/St_Johns":
      $timezone = "NST+3:30NDT+2:30,M3.2.0/02:00:00,M11.1.0/02:00:00";
      break;
    case "America/Godthab":
      $timezone = "TZK+3";
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
      $timezone = "UTC+3";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
    case "America/Araguaina":
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cuiaba":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = "BRST+3";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "TZL+2";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = "TZM+1";
      break;
    case "America/Danmarkshavn":
    case "Atlantic/Faroe":
    case "Atlantic/Canary":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Africa/Casablanca":
    case "Africa/Monrovia":
    case "Africa/Abidjan":
    case "Africa/Accra":
    case "Africa/Bamako":
    case "Africa/Banjul":
    case "Africa/Bissau":
    case "Africa/Conakry":
    case "Africa/Dakar":
    case "Africa/El_Aaiun":
    case "Africa/Freetown":
    case "Africa/Lome":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
      $timezone = "TZN+0";
      break;
    case "Europe/London":
      $timezone = "GMT+0BST-1,M3.5.0/01:00:00,M10.5.0/02:00:00";
      break;
    case "Europe/Lisbon":
    case "Atlantic/Madeira":
      $timezone = "WET-0WEST-1,M3.5.0/01:00:00,M10.5.0/02:00:00";
      break;
    case "Europe/Dublin":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "GMT+0IST-1,M3.5.0/01:00:00,M10.5.0/02:00:00";
      break;
    case "Europe/Tirane":
    case "Europe/Vienna":
    case "Europe/Brussels":
    case "Europe/Belgrade":
    case "Europe/Bratislava":
    case "Africa/Ndjamena":
    case "Africa/Algiers":
    case "Africa/Bangui":
    case "Africa/Brazzaville":
    case "Africa/Douala":
    case "Africa/Kinshasa":
    case "Africa/Lagos":
    case "Africa/Libreville":
    case "Africa/Luanda":
    case "Africa/Malabo":
    case "Africa/Niamey":
    case "Africa/Porto-Novo":
    case "Africa/Tunis":
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
    case "Europe/Zagreb":
    case "Europe/Prague":
    case "Europe/Warsaw":
    case "Europe/Ljubljana":
    case "Europe/Zurich":
    case "Europe/Stockholm":
    case "Europe/Copenhagen":
    case "Europe/Andorra":
    case "Europe/Gibraltar":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Paris":
    case "Europe/Berlin":
    case "Europe/Budapest":
    case "Europe/Rome":
    case "Europe/Luxembourg":
    case "Europe/Skopje":
    case "Europe/Amsterdam":
      $timezone = "CET-1CEST-2,M3.5.0/02:00:00,M10.5.0/03:00:00";
      break;
    case "Africa/Cairo":
    case "Africa/Harare":
    case "Africa/Tripoli":
    case "Africa/Blantyre":
    case "Africa/Bujumbura":
    case "Africa/Gaborone":
    case "Africa/Johannesburg":
    case "Africa/Kigali":
    case "Africa/Lubumbashi":
    case "Africa/Lusaka":
    case "Africa/Maputo":
    case "Africa/Maseru":
    case "Africa/Mbabane":
    case "Africa/Windhoek":
      $timezone = "TZP-2";
      break;
    case "Europe/Tallinn":
    case "Europe/Helsinki":
    case "Europe/Athens":
    case "Europe/Bucharest":
    case "Europe/Riga":
    case "Europe/Chisinau":
    case "Europe/Simferopol":
    case "Europe/Sofia":
    case "Europe/Vilnius":
    case "Europe/Mariehamn":
    case "Europe/Istanbul":
      $timezone = "EET-2EEST-3,M3.5.0/00:00:00,M10.4.0/00:00:00";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "EET-2EEST,M3.5.0/3,M10.5.0/4";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
    case "Asia/Jerusalem":
    case "Asia/Amman":
    case "Asia/Nicosia":
    case "Asia/Beirut":
    case "Asia/Damascus":
      $timezone = "EET-2EEST-3,M3.4.0/03:00:00,M10.5.0/04:00:00";
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
    case "Asia/Riyadh":
    case "Europe/Minsk":
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "TZQ-3";
      break;
    case "Europe/Kaliningrad":
      $timezone = "MSK-3";
      break;
    case "Asia/Tehran":
      $timezone = "IRST-3:30IRDT-4:30,M3.3.0/24:00:00,M9.3.2/24:00:00";
      break;
    case "Asia/Muscat":
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Asia/Yerevan":
    case "Asia/Baku":
    case "Asia/Kabul":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "TZR-4";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
    case "Asia/Karachi":
    case "Asia/Tashkent":
    case "Asia/Samarkand":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "TZS-5";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = "TZT-5:30";
      break;
    case "Asia/Kathmandu":
      $timezone = "TZU-5:45";
      break;
    case "Asia/Dhaka":
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
    case "Asia/Yekaterinburg":
      $timezone = "TZV-6";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "TZW-6:30";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "TZX-7";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "WIB-7";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Taipei":
    case "Asia/Krasnoyarsk":
      $timezone = "TZY-8";
      break;
    case "Asia/Hong_Kong":
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "SGT-8";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "ULAT-8";
      break;
    case "Australia/Perth":
    case "Australia/Eucla":
      $timezone = "WST-8";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
    case "Asia/Tokyo":
    case "Asia/Irkutsk":
      $timezone = "TZZ-9";
      break;
    case "Australia/Darwin":
      $timezone = "CST-9:30";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "TZb-10";
      break;
    case "Australia/Lindeman":
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "EST-10EDT-11,M10.1.0/02:00:00,M4.1.0/03:00:00";
      break;
    case "Australia/Brisbane":
      $timezone = "EST-10";
      break;
    case "Asia/Yakutsk":
      $timezone = "EST-10EDT-11,M10.1.0/02:00:00,M3.5.0/03:00:00";
      break;
    case "Pacific/Noumea":
    case "Antarctica/Macquarie":
    case "Australia/Currie":
    case "Australia/Hobart":
    case "Australia/Lord_Howe":
    case "Australia/Melbourne":
    case "Australia/Sydney":
    case "Pacific/Efate":
    case "Pacific/Guadalcanal":
    case "Pacific/Kosrae":
    case "Pacific/Pohnpei":
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
    case "Pacific/Norfolk":
      $timezone = "TZc-11";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "NZST-12NZDT-13,M9.4.0/02:00:00,M4.1.0/03:00:00";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "NZST-12NZDT-13,M9.4.0/02:00:00,M4.1.0/03:00:00";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
    case "Pacific/Chatham":
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "TZe-13";
      break;
  }
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
  $ntpaddr = "";
  foreach ( $parameters["NTP"] as $ntp)
  {
    if ( empty ( $ntpaddr) && $hosts = gethostbynamel ( $ntp))
    {
      $ntpaddr = $hosts[0];
    }
  }
  $content = str_replace ( "{{{ntpserver1}}}", $ntpaddr, $content);

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
      $datestring = "$D-$o-$Y";
      $timestring = "$H:$m $s";
      $dateformat = 2; // DD-MM-YYYY
      $timeformat = 1; // 24h
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
      $datestring = "$D $M $Y";
      $timestring = "$H:$m $s";
      $dateformat = 3; // DDDD, MMM DD
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $datestring = "$D-$M-$Y";
      $timestring = "$H:$m $s";
      $dateformat = 2; // DD-MM-YYYY
      $timeformat = 1; // 24h
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
      $datestring = "$Y-$o-$D";
      $timestring = "$H:$m $s";
      $dateformat = 0; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $datestring = "$o-$D-$Y";
      $timestring = "$h:$m $P $s";
      $dateformat = 1; // MM-DD-YYYY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $datestring = "$o-$D-$Y";
      $timestring = "$H:$m $s";
      $dateformat = 1; // MM-DD-YYYY
      $timeformat = 1; // 24h
      break;
    default:
      $datestring = "$Y-$o-$D";
      $timestring = "$h:$m $P $s";
      $dateformat = 0; // YYYY-MM-DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);
  $screen = str_replace ( "{{{timestring}}}", $timestring, $screen);
  $screen = str_replace ( "{{{datestring}}}", $datestring, $screen);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["UserPassword"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["AdminPassword"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", $_in["general"]["address"], $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/cfg" . strtolower ( $parameters["MAC"]) . ".xml", $content) === false || file_put_contents ( $_in["general"]["tftpdir"] . "/cfg" . strtolower ( $parameters["MAC"]) . "-screen.xml", $screen) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify grandstream-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to remove the Grandstream auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_grandstream_remove ( $buffer, $parameters)
{
  global $_in;

  // Remove AP file
  if ( ! unlink ( $_in["general"]["tftpdir"] . "/cfg" . strtolower ( $parameters["MAC"]) . ".xml"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Remove optional screen configuration file
  unlink ( $_in["general"]["tftpdir"] . "/cfg" . strtolower ( $parameters["MAC"]) . "-screen.xml");

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP removed.");
}

/**
 * Function to create the Grandstream audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_grandstream_audio ( $buffer, $parameters)
{
  // Return Grandstream audio only SIP account template
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

/**
 * Function to create the Grandstream audio only (without G722) SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_grandstream_audio_no_g722 ( $buffer, $parameters)
{
  // Return Grandstream audio only (without G722) SIP account template
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

/**
 * Function to create the Grandstream audio and video SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_grandstream_video ( $buffer, $parameters)
{
  // Return Grandstream audio and video SIP account template
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
