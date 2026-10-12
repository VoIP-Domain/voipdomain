<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain Polycom equipments models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Polycom
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call hooks
 */
framework_add_hook ( "equipment_model_spip301_configure", "equipment_model_polycom_basic_configure");
framework_add_hook ( "equipment_model_spip430_configure", "equipment_model_polycom_basic_configure");
framework_add_hook ( "ap_type_spip301", "ap_type_polycom_spip");
framework_add_hook ( "ap_type_spip301_remove", "ap_type_polycom_remove");
framework_add_hook ( "account_type_spip301", "account_type_polycom_audio");
framework_add_hook ( "ap_type_spip430", "ap_type_polycom_spip");
framework_add_hook ( "ap_type_spip430_remove", "ap_type_polycom_remove");
framework_add_hook ( "account_type_spip430", "account_type_polycom_audio");

/**
 * Cleanup functions
 */
framework_add_hook ( "ap_polycom_wipe", "ap_polycom_wipe");
cleanup_register ( "Accounts-Polycom", "ap_polycom_wipe", array ( "Before" => array ( "Accounts")));

/**
 * Function to wipe the Polycom equipment models auto provisioning files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function ap_polycom_wipe ( $buffer, $parameters)
{
  global $_in;

  foreach ( list_config ( "datafile", "ap") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( equipment_model_exists ( $data["Type"], "Polycom"))
    {
      @unlink ( $_in["general"]["tftpdir"] . "/" . strtolower ( $data["MAC"]) . ".cfg");
      unlink_config ( "datafile", $filename);
    }
  }

  return $buffer;
}

/**
 * Function to configure the basic Polycom equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_polycom_basic_configure ( $buffer, $parameters)
{
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

  foreach ( $parameters["VideoCodecs"] as $codec)
  {
    writeLog ( "Invalid video codec \"" . $codec . "\" to model \"" . $parameters["UID"] . "\"!", VoIP_LOG_WARNING);
  }

  return "[vd_equipment_" . $parameters["UID"] . "]\n" .
         "allow=!all," . implode ( ",", $codecs) . "\n" . $buffer;
}

/**
 * Function to create the Polycom SoundPoint auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation
 */
function ap_type_polycom_spip ( $buffer, $parameters)
{
  global $_in;

  switch ( $parameters["Type"])
  {
    case "spip301":
    case "spip430":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-polycom-spip.cfg");
      break;
    default:
      return array ( "code" => 500, "message" => "Error writing file.");
  }

  $content = str_replace ( "{{{displayname}}}", $parameters["Name"], $content);
  $content = str_replace ( "{{{username}}}", $parameters["Username"], $content);
  $content = str_replace ( "{{{password}}}", $parameters["Password"], $content);
  $content = str_replace ( "{{{server1addr}}}", $_in["general"]["address"], $content);
  $content = str_replace ( "{{{server1port}}}", $_in["general"]["port"], $content);
  $content = str_replace ( "{{{tftpserver}}}", $_in["general"]["address"], $content);
  $content = str_replace ( "{{{ntp}}}", ( sizeof ( $parameters["NTP"]) != 0 ? $parameters["NTP"][0] : $_in["general"]["address"]), $content);
  $content = str_replace ( "{{{offset}}}", (int) ( $parameters["Offset"] * 3600), $content);
  $content = str_replace ( "{{{timeformat}}}", "0", $content);
  $content = str_replace ( "{{{dateformat}}}", "dd-mm-yy", $content);

  $dialplan = array ();
  foreach ( $parameters["DialPlan"] as $entry)
  {
    if ( $entry["Type"] == "Internal")
    {
      $dialplan[] = $entry["Pattern"];
    }
  }
  $content = str_replace ( "{{{dialplan}}}", implode ( "|", $dialplan), $content);

  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to remove the Polycom auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation
 */
function ap_type_polycom_remove ( $buffer, $parameters)
{
  global $_in;

  if ( ! @unlink ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  return array ( "code" => 200, "message" => "OK, AP removed.");
}

/**
 * Function to create the Polycom audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_polycom_audio ( $buffer, $parameters)
{
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
