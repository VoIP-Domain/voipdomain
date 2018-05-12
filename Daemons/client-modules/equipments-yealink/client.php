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
 * VoIP Domain Yealink equipments models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Yealink
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_model_t18p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t18p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t18p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t18p", "ap_type_yealink_t18p");
framework_add_hook ( "ap_type_t18p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t18p", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t19pe2_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t19pe2_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t19pe2_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t19pe2", "ap_type_yealink_t19pe2");
framework_add_hook ( "ap_type_t19pe2_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t19pe2", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t20p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t20p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t20p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t20p", "ap_type_yealink_t20p");
framework_add_hook ( "ap_type_t20p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t20p", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t21pe2_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t21pe2_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t21pe2_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t21pe2", "ap_type_yealink_t21pe2");
framework_add_hook ( "ap_type_t21pe2_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t21pe2", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t21p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t21p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t21p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t21p", "ap_type_yealink_t21p");
framework_add_hook ( "ap_type_t21p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t21p", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t22p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t22p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t22p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t22p", "ap_type_yealink_t22p");
framework_add_hook ( "ap_type_t22p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t22p", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t23p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t23p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t23p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t23p", "ap_type_yealink_t23pg");
framework_add_hook ( "ap_type_t23p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t23p", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t23g_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t23g_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t23g_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t23g", "ap_type_yealink_t23pg");
framework_add_hook ( "ap_type_t23g_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t23g", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t26p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t26p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t26p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t26p", "ap_type_yealink_t26p");
framework_add_hook ( "ap_type_t26p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t26p", "account_type_yealink_audio_no_g722");

framework_add_hook ( "equipment_model_t27p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t27p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t27p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t27p", "ap_type_yealink_t27pg");
framework_add_hook ( "ap_type_t27p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t27p", "account_type_yealink_audio_no_g722");

framework_add_hook ( "equipment_model_t27g_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t27g_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t27g_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t27g", "ap_type_yealink_t27pg");
framework_add_hook ( "ap_type_t27g_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t27g", "account_type_yealink_audio_no_g722");

framework_add_hook ( "equipment_model_t28p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t28p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t28p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t28p", "ap_type_yealink_t28p");
framework_add_hook ( "ap_type_t28p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t28p", "account_type_yealink_audio_no_g722");

framework_add_hook ( "equipment_model_t29g_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t29g_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t29g_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t29g", "ap_type_yealink_t29g");
framework_add_hook ( "ap_type_t29g_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t29g", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t30p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t30p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t30p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t30p", "ap_type_yealink_t30p");
framework_add_hook ( "ap_type_t30p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t30p", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t31_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t31_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t31_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t31", "ap_type_yealink_t31");
framework_add_hook ( "ap_type_t31_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t31", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t31p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t31p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t31p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t31p", "ap_type_yealink_t31");
framework_add_hook ( "ap_type_t31p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t31p", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t31g_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t31g_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t31g_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t31g", "ap_type_yealink_t31");
framework_add_hook ( "ap_type_t31g_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t31g", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t38g_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t38g_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t38g_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t38g", "ap_type_yealink_t38g");
framework_add_hook ( "ap_type_t38g_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t38g", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t40p_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t40p_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t40p_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t40p", "ap_type_yealink_t40p");
framework_add_hook ( "ap_type_t40p_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t40p", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t46s_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t46s_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t46s_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t46s", "ap_type_yealink_t4xs");
framework_add_hook ( "ap_type_t46s_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t46s", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_t48s_configure", "equipment_model_yealink_basic_configure");
framework_add_hook ( "equipment_model_t48s_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_t48s_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_t48s", "ap_type_yealink_t4xs");
framework_add_hook ( "ap_type_t48s_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_t48s", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_cp920_configure", "equipment_model_yealink_cp920_configure");
framework_add_hook ( "equipment_model_cp920_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_cp920_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_cp920", "ap_type_yealink_cp920");
framework_add_hook ( "ap_type_cp920_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_cp920", "account_type_yealink_audio");

framework_add_hook ( "equipment_model_vp530_configure", "equipment_model_yealink_vp530_configure");
framework_add_hook ( "equipment_model_vp530_firmware_add", "equipment_model_yealink_add_firmware");
framework_add_hook ( "equipment_model_vp530_firmware_remove", "equipment_model_yealink_remove_firmware");
framework_add_hook ( "ap_type_vp530", "ap_type_yealink_vp530");
framework_add_hook ( "ap_type_vp530_remove", "ap_type_yealink_remove");
framework_add_hook ( "account_type_vp530", "account_type_yealink_audio");

/**
 * Cleanup functions
 */
framework_add_hook ( "ap_yealink_wipe", "ap_yealink_wipe");
cleanup_register ( "Accounts-Yealink", "ap_yealink_wipe", array ( "Before" => array ( "Accounts")));

framework_add_hook ( "firmware_yealink_wipe", "firmware_yealink_wipe");
cleanup_register ( "Firmwares-Yealink", "firmware_yealink_wipe", array ( "Before" => array ( "Equipments")));

/**
 * Function to wipe the Yealink equipment models auto provisioning files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function ap_yealink_wipe ( $buffer, $parameters)
{
  // Remove auto provisioning configuration files:
  foreach ( list_config ( "datafile", "ap") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( equipment_model_exists ( $data["Type"], "Yealink"))
    {
      unlink_config ( "ap", strtolower ( $data["MAC"]) . ".cfg");
      unlink_config ( "datafile", $filename);
    }
  }

  return $buffer;
}

/**
 * Function to wipe the Yealink equipment models firmware files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function firmware_yealink_wipe ( $buffer, $parameters)
{
  // Yealink SIP-T18(P)
  unlink_config ( "ap", "Yealink/SIP-T18P/18.0.0.162.rom");
  unlink_config ( "apdir", "Yealink/SIP-T18P");

  // Yealink SIP-T19(P) E2
  unlink_config ( "ap", "Yealink/SIP-T19PE2/53.84.0.160.rom");
  unlink_config ( "apdir", "Yealink/SIP-T19PE2");

  // Yealink SIP-T20P
  unlink_config ( "ap", "Yealink/SIP-T20P/9.73.0.50.rom");
  unlink_config ( "apdir", "Yealink/SIP-T20P");

  // Yealink SIP-T21(P)
  unlink_config ( "ap", "Yealink/SIP-T21P/34.72.0.200.rom");
  unlink_config ( "apdir", "Yealink/SIP-T21P");

  // Yealink SIP-T21(P) E2
  unlink_config ( "ap", "Yealink/SIP-T21PE2/52.84.0.160.rom");
  unlink_config ( "apdir", "Yealink/SIP-T21PE2");

  // Yealink SIP-T22P
  unlink_config ( "ap", "Yealink/SIP-T22P/7.73.0.50.rom");
  unlink_config ( "apdir", "Yealink/SIP-T22P");

  // Yealink SIP-T23P
  unlink_config ( "ap", "Yealink/SIP-T23P/44.84.0.160.rom");
  unlink_config ( "apdir", "Yealink/SIP-T23P");

  // Yealink SIP-T23G
  unlink_config ( "ap", "Yealink/SIP-T23G/44.84.0.160.rom");
  unlink_config ( "apdir", "Yealink/SIP-T23G");

  // Yealink SIP-T26P
  unlink_config ( "ap", "Yealink/SIP-T26P/6.73.0.50.rom");
  unlink_config ( "apdir", "Yealink/SIP-T26P");

  // Yealink SIP-T27P
  unlink_config ( "ap", "Yealink/SIP-T27P/45.83.0.160.rom");
  unlink_config ( "apdir", "Yealink/SIP-T27P");

  // Yealink SIP-T27G
  unlink_config ( "ap", "Yealink/SIP-T27G/69.86.0.160.rom");
  unlink_config ( "apdir", "Yealink/SIP-T27G");

  // Yealink SIP-T28P
  unlink_config ( "ap", "Yealink/SIP-T28P/2.73.0.50.rom");
  unlink_config ( "apdir", "Yealink/SIP-T28P");

  // Yealink SIP-T29G
  unlink_config ( "ap", "Yealink/SIP-T29G/46.83.0.160.rom");
  unlink_config ( "ap", "Yealink/SIP-T29G/VoIP\ Domain.jpeg");
  unlink_config ( "apdir", "Yealink/SIP-T29G");

  // Yealink SIP-T30(P)
  unlink_config ( "ap", "Yealink/SIP-T30P/124.86.0.40.rom");
  unlink_config ( "apdir", "Yealink/SIP-T30P");

  // Yealink SIP-T31
  unlink_config ( "ap", "Yealink/SIP-T31/124.86.0.40.rom");
  unlink_config ( "apdir", "Yealink/SIP-T31");

  // Yealink SIP-T31P
  unlink_config ( "ap", "Yealink/SIP-T31P/124.86.0.40.rom");
  unlink_config ( "apdir", "Yealink/SIP-T31P");

  // Yealink SIP-T31G
  unlink_config ( "ap", "Yealink/SIP-T31G/124.86.0.40.rom");
  unlink_config ( "apdir", "Yealink/SIP-T31G");

  // Yealink SIP-T38G
  unlink_config ( "ap", "Yealink/SIP-T38G/38.70.0.221.rom");
  unlink_config ( "ap", "Yealink/SIP-T38G/VoIP\ Domain.jpeg");
  unlink_config ( "apdir", "Yealink/SIP-T38G");

  // Yealink SIP-T40P
  unlink_config ( "ap", "Yealink/SIP-T40P/54.84.0.160.rom");
  unlink_config ( "apdir", "Yealink/SIP-T40P");

  // Yealink SIP-T46S
  unlink_config ( "ap", "Yealink/SIP-T46S/66.86.0.160.rom");
  unlink_config ( "ap", "Yealink/SIP-T46S/VoIP\ Domain.jpeg");
  unlink_config ( "apdir", "Yealink/SIP-T46S");

  // Yealink SIP-T48S
  unlink_config ( "ap", "Yealink/SIP-T48S/66.86.0.160.rom");
  unlink_config ( "ap", "Yealink/SIP-T48S/VoIP\ Domain.jpeg");
  unlink_config ( "apdir", "Yealink/SIP-T48S");

  // Yealink CP920
  unlink_config ( "ap", "Yealink/CP920/78.86.0.160.rom");
  unlink_config ( "apdir", "Yealink/CP920");

  // Yealink VP530
  unlink_config ( "ap", "Yealink/VP530/23.70.0.63.rom");
  unlink_config ( "ap", "Yealink/VP530/VoIP\ Domain.jpg");
  unlink_config ( "apdir", "Yealink/VP530");

  // Yealink vendor directory
  unlink_config ( "apdir", "Yealink");

  return $buffer;
}

/**
 * Function to configure the basic Yealink equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_yealink_basic_configure ( $buffer, $parameters)
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
 * Function to configure the HD audio Yealink equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_yealink_hd_configure ( $buffer, $parameters)
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
 * Function to configure the Yealink equipment model T58V.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_yealink_t58v_configure ( $buffer, $parameters)
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
 * Function to configure the Yealink equipment model VP530.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_yealink_vp530_configure ( $buffer, $parameters)
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
 * Function to configure the Yealink equipment model W52P.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_yealink_w52p_configure ( $buffer, $parameters)
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
 * Function to configure the Yealink CP920 equipment model.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_yealink_cp920_configure ( $buffer, $parameters)
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
 * Function to add a Yealink firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_yealink_add_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "18.0.0.162.rom":	// Yealink SIP-T18(P)
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T18P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T18P", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T18P/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "53.84.0.160.rom":	// Yealink SIP-T19(P) E2
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T19PE2") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T19PE2", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T19PE2/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "9.73.0.50.rom":	// Yealink SIP-T20P
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T20P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T20P", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T20P/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "34.72.0.200.rom":	// Yealink SIP-T21(P)
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21P", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21P/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "52.84.0.160.rom":	// Yealink SIP-T21(P) E2
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21PE2") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21PE2", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21PE2/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "7.73.0.50.rom":	// Yealink SIP-T22P
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T22P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T22P", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T22P/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "44.84.0.160.rom":	// Yealink SIP-T23P and SIP-T23G
      if ( $parameters["UID"] == "t23p")
      {
        if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23P", 0777, true))
        {
          return array ( "code" => 500, "message" => "Error creating directory structure.");
        }
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23P/" . $parameters["Filename"], $parameters["Data"]) === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }
      if ( $parameters["UID"] == "t23g")
      {
        if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23G") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23G", 0777, true))
        {
          return array ( "code" => 500, "message" => "Error creating directory structure.");
        }
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23G/" . $parameters["Filename"], $parameters["Data"]) === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "6.73.0.50.rom":	// Yealink SIP-T26P
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T26P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T26P", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T26P/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "45.83.0.160.rom":	// Yealink SIP-T27P
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27P", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27P/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "69.86.0.160.rom":	// Yealink SIP-T27G
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27G") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27G", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27G/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "2.73.0.50.rom":	// Yealink SIP-T28P
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T28P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T28P", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T28P/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "46.83.0.160.rom":	// Yealink SIP-T29G
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T29G") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T29G", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T29G/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      if ( ! copy ( dirname ( __FILE__) . "/wallpaper%20-%20480x272.jpeg", $_in["general"]["tftpdir"] . "/Yealink/SIP-T29G/VoIP%20Domain.jpeg") === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "124.86.0.40.rom":	// Yealink SIP-T30(P), Yealink SIP-T31, Yealink SIP-T31P & Yealink SIP-T31G
      if ( $parameters["UID"] == "t30p")
      {
        if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T30P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T30P", 0777, true))
        {
          return array ( "code" => 500, "message" => "Error creating directory structure.");
        }
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T30P/" . $parameters["Filename"], $parameters["Data"]) === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }
      if ( $parameters["UID"] == "t31")
      {
        if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31", 0777, true))
        {
          return array ( "code" => 500, "message" => "Error creating directory structure.");
        }
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31/" . $parameters["Filename"], $parameters["Data"]) === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }
      if ( $parameters["UID"] == "t31p")
      {
        if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31P", 0777, true))
        {
          return array ( "code" => 500, "message" => "Error creating directory structure.");
        }
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31P/" . $parameters["Filename"], $parameters["Data"]) === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }
      if ( $parameters["UID"] == "t31g")
      {
        if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31G") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31G", 0777, true))
        {
          return array ( "code" => 500, "message" => "Error creating directory structure.");
        }
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31G/" . $parameters["Filename"], $parameters["Data"]) === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "38.70.0.221.rom":	// Yealink SIP-T38G
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T38G") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T38G", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T38G/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      if ( ! copy ( dirname ( __FILE__) . "/wallpaper%20-%20480x272.jpeg", $_in["general"]["tftpdir"] . "/Yealink/SIP-T38G/VoIP%20Domain.jpeg") === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "54.84.0.160.rom":	// Yealink SIP-T40P
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T40P") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T40P", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T40P/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "66.86.0.160.rom":	// Yealink SIP-T46S or SIP-T48S
      if ( $parameters["UID"] == "t46s")
      {
        if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T46S") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T46S", 0777, true))
        {
          return array ( "code" => 500, "message" => "Error creating directory structure.");
        }
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T46S/" . $parameters["Filename"], $parameters["Data"]) === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
        if ( ! copy ( dirname ( __FILE__) . "/wallpaper%20-%20480x272.jpeg", $_in["general"]["tftpdir"] . "/Yealink/SIP-T46S/VoIP%20Domain.jpeg") === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }
      if ( $parameters["UID"] == "t48s")
      {
        if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T48S") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T48S", 0777, true))
        {
          return array ( "code" => 500, "message" => "Error creating directory structure.");
        }
        if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T48S/" . $parameters["Filename"], $parameters["Data"]) === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
        if ( ! copy ( dirname ( __FILE__) . "/wallpaper%20-%20800x480.jpeg", $_in["general"]["tftpdir"] . "/Yealink/SIP-T48S/VoIP%20Domain.jpeg") === false)
        {
          return array ( "code" => 500, "message" => "Error writing file.");
        }
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "78.86.0.160.rom":	// Yealink CP920
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/CP920") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/CP920", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/CP920/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }

      return array ( "code" => 200, "message" => "Firmware file added.");
      break;
    case "23.70.0.63.rom":	// Yealink VP530
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Yealink/VP530") && ! mkdir ( $_in["general"]["tftpdir"] . "/Yealink/VP530", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( ! file_put_contents ( $_in["general"]["tftpdir"] . "/Yealink/VP530/" . $parameters["Filename"], $parameters["Data"]) === false)
      {
        return array ( "code" => 500, "message" => "Error writing file.");
      }
      if ( ! copy ( dirname ( __FILE__) . "/wallpaper%20-%20800x480.jpeg", $_in["general"]["tftpdir"] . "/Yealink/VP530/VoIP%20Domain.jpg") === false)
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
 * Function to remove a Yealink firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_yealink_remove_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "18.0.0.162.rom":	// Yealink SIP-T18(P)
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T18P/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T18P");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "53.84.0.160.rom":	// Yealink SIP-T19(P) E2
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T19PE2/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T19PE2");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "9.73.0.50.rom":	// Yealink SIP-T20P
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T20P/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T20P");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "34.72.0.200.rom":	// Yealink SIP-T21(P)
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21P/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21P");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "52.84.0.160.rom":	// Yealink SIP-T21(P) E2
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21PE2/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T21PE2");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "7.73.0.50.rom":	// Yealink SIP-T22P
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T22P/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T22P");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "44.84.0.160.rom":	// Yealink SIP-T23P or SIP-T23G
      if ( $parameters["UID"] == "t23p")
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23P/" . $parameters["Filename"]);
        @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23P");
      }
      if ( $parameters["UID"] == "t23g")
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23G/" . $parameters["Filename"]);
        @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T23G");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "6.73.0.50.rom":	// Yealink SIP-T26P
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T26P/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T26P");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "45.83.0.160.rom":	// Yealink SIP-T27P
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27P/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27P");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "69.86.0.160.rom":	// Yealink SIP-T27G
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27G/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T27G");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "2.73.0.50.rom":	// Yealink SIP-T28P
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T28P/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T28P");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "46.83.0.160.rom":	// Yealink SIP-T29G
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T29G/" . $parameters["Filename"]);
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T29G/VoIP\ Domain.jpeg");
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T29G");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "124.86.0.40.rom":	// Yealink SIP-T30(P), Yealink SIP-T31, Yealink SIP-T31P & Yealink SIP-T31G
      if ( $parameters["UID"] == "t30p")
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T30P/" . $parameters["Filename"]);
        @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T30P");
      }
      if ( $parameters["UID"] == "t31")
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31/" . $parameters["Filename"]);
        @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31");
      }
      if ( $parameters["UID"] == "t31p")
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31P/" . $parameters["Filename"]);
        @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31P");
      }
      if ( $parameters["UID"] == "t31g")
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31G/" . $parameters["Filename"]);
        @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T31G");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "38.70.0.221.rom":	// Yealink SIP-T38G
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T38G/" . $parameters["Filename"]);
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T38G/VoIP\ Domain.jpeg");
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T38G");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "54.84.0.160.rom":	// Yealink SIP-T40P
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T40P/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T40P");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "66.86.0.160.rom":	// Yealink SIP-T46S or SIP-T48S
      if ( $parameters["UID"] == "t46s")
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T46S/" . $parameters["Filename"]);
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T46S/VoIP\ Domain.jpeg");
        @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T46S");
      }
      if ( $parameters["UID"] == "t46s")
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T48S/" . $parameters["Filename"]);
        @unlink ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T48S/VoIP\ Domain.jpeg");
        @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/SIP-T48S");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "78.86.0.160.rom":	// Yealink CP920
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/CP920/" . $parameters["Filename"]);
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/CP920");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
    case "23.70.0.63.rom":	// Yealink VP530
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/VP530/" . $parameters["Filename"]);
      @unlink ( $_in["general"]["tftpdir"] . "/Yealink/VP530/VoIP\ Domain.jpg");
      @rmdir ( $_in["general"]["tftpdir"] . "/Yealink/VP530");

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to create the Yealink SIP-T18(P) auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t18p ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t18p":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "pcma" => "ALAW",
    "pcmu" => "ULAW",
    "g723_63" => "G723",
    "g726_40" => "G726",
    "g726_32" => "G726",
    "g726_24" => "G726",
    "g726_16" => "G726",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  $language = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "Portugal";
      break;
    case "en_US":
      $language = "English";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
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
        $language = "Portuguese";
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
        $language = "Spanish";
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

      // English (default):
      default:
        $language = "English";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
    $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
      $timezone = "-11 Midway Island";
      break;
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare,Pretoria";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T19(P) E2 auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t19pe2 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t19pe2":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "ilbc_15_2kbps" => "ILBC",
    "pcma" => "ALAW",
    "pcmu" => "ULAW",
    "opus" => "OPUS",
    "g726_40" => "G726",
    "g726_32" => "G726",
    "g726_24" => "G726",
    "g726_16" => "G726",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
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
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
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
        $language = "Portuguese";
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
        $language = "Spanish";
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

      // English (default):
      default:
        $language = "English";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
    $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
      $timezone = "-11 Midway Island";
      break;
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare,Pretoria";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T20P auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t20p ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t20p":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    array ( "ID" => "1", "Codec" => "ULAW", "Type" => "PCMU", "RTPMap" => 0, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "2", "Codec" => "ALAW", "Type" => "PCMA", "RTPMap" => 8, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "3", "Codec" => "G723", "Type" => "G723_53", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "4", "Codec" => "G723", "Type" => "G723_63", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "5", "Codec" => "G729", "Type" => "G729", "RTPMap" => 18, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "6", "Codec" => "G722", "Type" => "G722", "RTPMap" => 9, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "7", "Codec" => "ILBC", "Type" => "iLBC", "RTPMap" => 106, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "8", "Codec" => "G726", "Type" => "G726-16", "RTPMap" => 103, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "9", "Codec" => "G726", "Type" => "G726-24", "RTPMap" => 104, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "10", "Codec" => "G726", "Type" => "G726-32", "RTPMap" => 102, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "11", "Codec" => "G726", "Type" => "G726-40", "RTPMap" => 105, "Priority" => 0, "Enabled" => false)
  );
  $priority = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $vdcodec)
  {
    foreach ( $codecs as $index => $codec)
    {
      if ( $codec["Codec"] == $vdcodec)
      {
        $priority++;
        $codecs[$index]["Enabled"] = true;
        $codecs[$index]["Priority"] = $priority;
      }
    }
  }
  foreach ( $codecs as $codec)
  {
    if ( $codec["Enabled"])
    {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = \n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 0\n";
    }
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".payload_type = " . $codec["Type"] . "\n";
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".rtpmap = " . $codec["RTPMap"] . "\n";
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
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
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
        $language = "Portuguese";
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
        $language = "Spanish";
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

      // English (default):
      default:
        $language = "English";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Brazil";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{tonecountry}}}", $tonecountry, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    if ( $dialplanentry["Kind"] == "Extensions")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Type"] == "Local Landline" || $dialplanentry["Type"] == "Local Mobile" || $dialplanentry["Type"] == "Interstate Landline" || $dialplanentry["Type"] == "Interstate Mobile" || $dialplanentry["Type"] == "Tollfree" || $dialplanentry["Type"] == "PRN" || $dialplanentry["Type"] == "International")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . $parameters["Prefix"] . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Kind"] == "Features" || $dialplanentry["Kind"] == "Services")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Pago_Pago":
    case "Pacific/Midway":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 United States-MST no DST";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "America/Belize":
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Managua":
    case "America/Tegucigalpa":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
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
    case "Pacific/Easter":
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Boa_Vista":
    case "America/Curacao":
    case "America/Dominica":
    case "America/Eirunepe":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Manaus":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&amp;Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Araguaina":
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
    case "Europe/Lisbon":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Morocco";
      break;
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
    case "Africa/Monrovia":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 GMT";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
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
      $timezone = "+1 Chad";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Copenhagen":
    case "Europe/Andorra":
    case "Europe/Belgrade":
    case "Europe/Bratislava":
    case "Europe/Gibraltar":
    case "Europe/Ljubljana":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Stockholm":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Warsaw":
    case "Europe/Zurich":
      $timezone = "+1 Denmark(Kopenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Israel(Tel Aviv)";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Istanbul":
    case "Europe/Mariehamn":
    case "Europe/Sofia":
    case "Europe/Vilnius":
      $timezone = "+2 Turkey(Ankara)";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
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
      $timezone = "+3 East Africa Time";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Europe/Minsk":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = "+5:30 India(Calcutta)";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Hong_Kong":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T21(P) auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t21p ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t21p":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "ilbc_15_2kbps" => "ILBC",
    "pcma" => "ALAW",
    "pcmu" => "ULAW",
    "g723_63" => "G723",
    "g726_40" => "G726",
    "g726_32" => "G726",
    "g726_24" => "G726",
    "g726_16" => "G726",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  $language = "";
  $capturelabel = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "Portuguese";
      $capturelabel = "Captura";
      break;
    case "en_US":
      $language = "English";
      $capturelabel = "Capture";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        $capturelabel = "Aramayı";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        $capturelabel = "Przechwytywanie";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        $capturelabel = "Cattura";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        $capturelabel = "Erfassen";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        $capturelabel = "捕获通话";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
        $capturelabel = "捕捉通話";
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
        $language = "Portuguese";
        $capturelabel = "Captura";
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
        $language = "Spanish";
        $capturelabel = "Capturar";
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
        $capturelabel = "Capturer";
        break;

      // English (default):
      default:
        $language = "English";
        $capturelabel = "Capture";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $content = str_replace ( "{{{capturelabel}}}", $capturelabel, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
    $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Pago_Pago":
    case "Pacific/Midway":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 United States-MST no DST";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
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
    case "America/New_York":
    case "America/Panama":
    case "America/Port-au-Prince":
    case "Pacific/Easter":
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Boa_Vista":
    case "America/Curacao":
    case "America/Dominica":
    case "America/Eirunepe":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Manaus":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Araguaina":
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
    case "Europe/Lisbon":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Morocco";
      break;
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
    case "Africa/Monrovia":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 GMT";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
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
      $timezone = "+1 Chad";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Copenhagen":
    case "Europe/Andorra":
    case "Europe/Belgrade":
    case "Europe/Bratislava":
    case "Europe/Gibraltar":
    case "Europe/Ljubljana":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Stockholm":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Warsaw":
    case "Europe/Zurich":
      $timezone = "+1 Denmark(Kopenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Israel(Tel Aviv)";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Istanbul":
    case "Europe/Mariehamn":
    case "Europe/Sofia":
    case "Europe/Vilnius":
      $timezone = "+2 Turkey(Ankara)";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
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
      $timezone = "+3 East Africa Time";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Europe/Minsk":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = "+5:30 India(Calcutta)";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Hong_Kong":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T21(P) E2 auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t21pe2 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t21pe2":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "ilbc_15_2kbps" => "ILBC",
    "pcma" => "ALAW",
    "pcmu" => "ULAW",
    "opus" => "OPUS",
    "g726_40" => "G726",
    "g726_32" => "G726",
    "g726_24" => "G726",
    "g726_16" => "G726",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
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
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
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
        $language = "Portuguese";
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
        $language = "Spanish";
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

      // English (default):
      default:
        $language = "English";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
    $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 2; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Pickup Value" => "", "Label" => "", "XML Phonebook" => "%NULL%");
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
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-T21(P) (E2) supports up to 2 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Pickup Value" => "*8", "Label" => $extension["Name"], "XML Phonebook" => "%NULL%");
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".pickup_value = " . $entry["Pickup Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".xml_phonebook = " . $entry["XML Phonebook"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Pago_Pago":
    case "Pacific/Midway":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 United States-MST no DST";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
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
    case "America/New_York":
    case "America/Panama":
    case "America/Port-au-Prince":
    case "Pacific/Easter":
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Boa_Vista":
    case "America/Curacao":
    case "America/Dominica":
    case "America/Eirunepe":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Manaus":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Araguaina":
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
    case "Europe/Lisbon":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Morocco";
      break;
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
    case "Africa/Monrovia":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 GMT";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
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
      $timezone = "+1 Chad";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Copenhagen":
    case "Europe/Andorra":
    case "Europe/Belgrade":
    case "Europe/Bratislava":
    case "Europe/Gibraltar":
    case "Europe/Ljubljana":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Stockholm":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Warsaw":
    case "Europe/Zurich":
      $timezone = "+1 Denmark(Kopenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Israel(Tel Aviv)";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Istanbul":
    case "Europe/Mariehamn":
    case "Europe/Sofia":
    case "Europe/Vilnius":
      $timezone = "+2 Turkey(Ankara)";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
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
      $timezone = "+3 East Africa Time";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Europe/Minsk":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = "+5:30 India(Calcutta)";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Hong_Kong":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T22P auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t22p ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t22p":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    array ( "ID" => "1", "Codec" => "ULAW", "Type" => "PCMU", "RTPMap" => 0, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "2", "Codec" => "ALAW", "Type" => "PCMA", "RTPMap" => 8, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "3", "Codec" => "G723", "Type" => "G723_53", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "4", "Codec" => "G723", "Type" => "G723_63", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "5", "Codec" => "G729", "Type" => "G729", "RTPMap" => 18, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "6", "Codec" => "G722", "Type" => "G722", "RTPMap" => 9, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "7", "Codec" => "ILBC", "Type" => "iLBC", "RTPMap" => 106, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "8", "Codec" => "G726", "Type" => "G726-16", "RTPMap" => 103, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "9", "Codec" => "G726", "Type" => "G726-24", "RTPMap" => 104, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "10", "Codec" => "G726", "Type" => "G726-32", "RTPMap" => 102, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "11", "Codec" => "G726", "Type" => "G726-40", "RTPMap" => 105, "Priority" => 0, "Enabled" => false)
  );
  $priority = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $vdcodec)
  {
    foreach ( $codecs as $index => $codec)
    {
      if ( $codec["Codec"] == $vdcodec)
      {
        $priority++;
        $codecs[$index]["Enabled"] = true;
        $codecs[$index]["Priority"] = $priority;
      }
    }
  }
  foreach ( $codecs as $codec)
  {
    if ( $codec["Enabled"])
    {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = \n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 0\n";
    }
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".payload_type = " . $codec["Type"] . "\n";
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".rtpmap = " . $codec["RTPMap"] . "\n";
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
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
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
        $language = "Portuguese";
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
        $language = "Spanish";
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

      // English (default):
      default:
        $language = "English";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Brazil";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{tonecountry}}}", $tonecountry, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    if ( $dialplanentry["Kind"] == "Extensions")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Type"] == "Local Landline" || $dialplanentry["Type"] == "Local Mobile" || $dialplanentry["Type"] == "Interstate Landline" || $dialplanentry["Type"] == "Interstate Mobile" || $dialplanentry["Type"] == "Tollfree" || $dialplanentry["Type"] == "PRN" || $dialplanentry["Type"] == "International")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . $parameters["Prefix"] . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Kind"] == "Features" || $dialplanentry["Kind"] == "Services")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 3; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Label" => "", "Extension" => "", "XML Phonebook" => "0", "Pickup Value" => "");
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
    if ( $x > 3)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-T22P supports up to 3 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Label" => $extension["Name"], "Extension" => $number, "XML Phonebook" => "0", "Pickup Value" => "*8");
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".extension = " . $entry["Extension"] . "\n";
    $linekey .= "linekey." . $id . ".xml_phonebook = " . $entry["XML Phonebook"] . "\n";
    $linekey .= "linekey." . $id . ".pickup_value = " . $entry["Pickup Value"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Pago_Pago":
    case "Pacific/Midway":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 United States-MST no DST";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "America/Belize":
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Managua":
    case "America/Tegucigalpa":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
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
    case "Pacific/Easter":
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Boa_Vista":
    case "America/Curacao":
    case "America/Dominica":
    case "America/Eirunepe":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Manaus":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&amp;Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Araguaina":
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
    case "Europe/Lisbon":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Morocco";
      break;
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
    case "Africa/Monrovia":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 GMT";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
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
      $timezone = "+1 Chad";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Copenhagen":
    case "Europe/Andorra":
    case "Europe/Belgrade":
    case "Europe/Bratislava":
    case "Europe/Gibraltar":
    case "Europe/Ljubljana":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Stockholm":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Warsaw":
    case "Europe/Zurich":
      $timezone = "+1 Denmark(Kopenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Israel(Tel Aviv)";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Istanbul":
    case "Europe/Mariehamn":
    case "Europe/Sofia":
    case "Europe/Vilnius":
      $timezone = "+2 Turkey(Ankara)";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
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
      $timezone = "+3 East Africa Time";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Europe/Minsk":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = "+5:30 India(Calcutta)";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Hong_Kong":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T23P/G auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t23pg ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t23p":
    case "t23g":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    array ( "ID" => "1", "Codec" => "ULAW", "Type" => "PCMU", "RTPMap" => 0, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "2", "Codec" => "ALAW", "Type" => "PCMA", "RTPMap" => 8, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "3", "Codec" => "G723", "Type" => "G723_53", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "4", "Codec" => "G723", "Type" => "G723_63", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "5", "Codec" => "G729", "Type" => "G729", "RTPMap" => 18, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "6", "Codec" => "G722", "Type" => "G722", "RTPMap" => 9, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "7", "Codec" => "ILBC", "Type" => "iLBC", "RTPMap" => 106, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "8", "Codec" => "G726", "Type" => "G726-16", "RTPMap" => 103, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "9", "Codec" => "G726", "Type" => "G726-24", "RTPMap" => 104, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "10", "Codec" => "G726", "Type" => "G726-32", "RTPMap" => 102, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "11", "Codec" => "G726", "Type" => "G726-40", "RTPMap" => 105, "Priority" => 0, "Enabled" => false)
  );
  $priority = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $vdcodec)
  {
    foreach ( $codecs as $index => $codec)
    {
      if ( $codec["Codec"] == $vdcodec)
      {
        $priority++;
        $codecs[$index]["Enabled"] = true;
        $codecs[$index]["Priority"] = $priority;
      }
    }
  }
  foreach ( $codecs as $codec)
  {
    if ( $codec["Enabled"])
    {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = \n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 0\n";
    }
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".payload_type = " . $codec["Type"] . "\n";
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".rtpmap = " . $codec["RTPMap"] . "\n";
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
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
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
        $language = "Portuguese";
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
        $language = "Spanish";
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

      // English (default):
      default:
        $language = "English";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    if ( $dialplanentry["Kind"] == "Extensions")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Type"] == "Local Landline" || $dialplanentry["Type"] == "Local Mobile" || $dialplanentry["Type"] == "Interstate Landline" || $dialplanentry["Type"] == "Interstate Mobile" || $dialplanentry["Type"] == "Tollfree" || $dialplanentry["Type"] == "PRN" || $dialplanentry["Type"] == "International")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . $parameters["Prefix"] . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Kind"] == "Features" || $dialplanentry["Kind"] == "Services")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 3; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Label" => "", "Extension" => "", "XML Phonebook" => "0", "Pickup Value" => "");
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
    if ( $x > 3)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-" . strtoupper ( $parameters["Type"]) . " supports up to 3 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Label" => $extension["Name"], "Extension" => $number, "XML Phonebook" => "0", "Pickup Value" => "*8");
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".extension = " . $entry["Extension"] . "\n";
    $linekey .= "linekey." . $id . ".xml_phonebook = " . $entry["XML Phonebook"] . "\n";
    $linekey .= "linekey." . $id . ".pickup_value = " . $entry["Pickup Value"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Pago_Pago":
    case "Pacific/Midway":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 United States-MST no DST";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "America/Belize":
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Managua":
    case "America/Tegucigalpa":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
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
    case "Pacific/Easter":
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Boa_Vista":
    case "America/Curacao":
    case "America/Dominica":
    case "America/Eirunepe":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Manaus":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&amp;Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Araguaina":
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
    case "Europe/Lisbon":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Morocco";
      break;
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
    case "Africa/Monrovia":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 GMT";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
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
      $timezone = "+1 Chad";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Copenhagen":
    case "Europe/Andorra":
    case "Europe/Belgrade":
    case "Europe/Bratislava":
    case "Europe/Gibraltar":
    case "Europe/Ljubljana":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Stockholm":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Warsaw":
    case "Europe/Zurich":
      $timezone = "+1 Denmark(Kopenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Israel(Tel Aviv)";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Istanbul":
    case "Europe/Mariehamn":
    case "Europe/Sofia":
    case "Europe/Vilnius":
      $timezone = "+2 Turkey(Ankara)";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
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
      $timezone = "+3 East Africa Time";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Europe/Minsk":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = "+5:30 India(Calcutta)";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Hong_Kong":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T26P auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t26p ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t26p":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    array ( "ID" => "1", "Codec" => "ULAW", "Type" => "PCMU", "RTPMap" => 0, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "2", "Codec" => "ALAW", "Type" => "PCMA", "RTPMap" => 8, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "3", "Codec" => "G723", "Type" => "G723_53", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "4", "Codec" => "G723", "Type" => "G723_63", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "5", "Codec" => "G729", "Type" => "G729", "RTPMap" => 18, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "6", "Codec" => "G722", "Type" => "G722", "RTPMap" => 9, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "7", "Codec" => "ILBC", "Type" => "iLBC", "RTPMap" => 106, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "8", "Codec" => "G726", "Type" => "G726-16", "RTPMap" => 103, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "9", "Codec" => "G726", "Type" => "G726-24", "RTPMap" => 104, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "10", "Codec" => "G726", "Type" => "G726-32", "RTPMap" => 102, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "11", "Codec" => "G726", "Type" => "G726-40", "RTPMap" => 105, "Priority" => 0, "Enabled" => false)
  );
  $priority = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $vdcodec)
  {
    foreach ( $codecs as $index => $codec)
    {
      if ( $codec["Codec"] == $vdcodec)
      {
        $priority++;
        $codecs[$index]["Enabled"] = true;
        $codecs[$index]["Priority"] = $priority;
      }
    }
  }
  foreach ( $codecs as $codec)
  {
    if ( $codec["Enabled"])
    {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = \n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 0\n";
    }
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".payload_type = " . $codec["Type"] . "\n";
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".rtpmap = " . $codec["RTPMap"] . "\n";
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  $language = "";
  $capturelabel = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "Portuguese";
      $capturelabel = "Captura";
      break;
    case "en_US":
      $language = "English";
      $capturelabel = "Capture";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        $capturelabel = "Aramayı";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        $capturelabel = "Przechwytywanie";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        $capturelabel = "Cattura";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        $capturelabel = "Erfassen";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        $capturelabel = "捕获通话";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
        $capturelabel = "捕捉通話";
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
        $language = "Portuguese";
        $capturelabel = "Captura";
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
        $language = "Spanish";
        $capturelabel = "Capturar";
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
        $capturelabel = "Capturer";
        break;

      // English (default):
      default:
        $language = "English";
        $capturelabel = "Capture";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $content = str_replace ( "{{{capturelabel}}}", $capturelabel, $content);

  // Set phone country tone:
  $tonecountry = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Brazil";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{tonecountry}}}", $tonecountry, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    if ( $dialplanentry["Kind"] == "Extensions")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Type"] == "Local Landline" || $dialplanentry["Type"] == "Local Mobile" || $dialplanentry["Type"] == "Interstate Landline" || $dialplanentry["Type"] == "Interstate Mobile" || $dialplanentry["Type"] == "Tollfree" || $dialplanentry["Type"] == "PRN" || $dialplanentry["Type"] == "International")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . $parameters["Prefix"] . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Kind"] == "Features" || $dialplanentry["Kind"] == "Services")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $memorykeys = array ();
  for ( $x = 1; $x <= 10; $x++)
  {
    $memorykeys[$x] = array ( "Type" => "", "Line" => "", "Value" => "", "XML Phonebook" => "%NULL%", "Pickup Value" => "");
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
    if ( $x > 10)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-T26P supports up to 10 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $memorykeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "XML Phonebook" => "%NULL%", "Pickup Value" => "*8");
  }
  $memorykey = "";
  foreach ( $memorykeys as $id => $entry)
  {
    $memorykey .= "memorykey." . $id . ".type = " . $entry["Type"] . "\n";
    $memorykey .= "memorykey." . $id . ".line = " . $entry["Line"] . "\n";
    $memorykey .= "memorykey." . $id . ".value = " . $entry["Value"] . "\n";
    $memorykey .= "memorykey." . $id . ".xml_phonebook = " . $entry["XML Phonebook"] . "\n";
    $memorykey .= "memorykey." . $id . ".pickup_value = " . $entry["Pickup Value"] . "\n";
  }
  $content = str_replace ( "{{{memorykey}}}", $memorykey, $content);
  $content = str_replace ( "{{{linekey}}}", "\n", $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Pago_Pago":
    case "Pacific/Midway":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 United States-MST no DST";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "America/Belize":
    case "America/Costa_Rica":
    case "America/El_Salvador":
    case "America/Guatemala":
    case "America/Managua":
    case "America/Tegucigalpa":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
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
    case "Pacific/Easter":
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Anguilla":
    case "America/Antigua":
    case "America/Aruba":
    case "America/Barbados":
    case "America/Boa_Vista":
    case "America/Curacao":
    case "America/Dominica":
    case "America/Eirunepe":
    case "America/Grenada":
    case "America/Guadeloupe":
    case "America/Guyana":
    case "America/Kralendijk":
    case "America/La_Paz":
    case "America/Lower_Princes":
    case "America/Manaus":
    case "America/Marigot":
    case "America/Martinique":
    case "America/Montserrat":
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&amp;Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Araguaina":
    case "America/Asuncion":
    case "America/Belem":
    case "America/Campo_Grande":
    case "America/Cayenne":
    case "America/Cuiaba":
    case "America/Fortaleza":
    case "America/Maceio":
    case "America/Miquelon":
    case "America/Paramaribo":
    case "America/Recife":
    case "America/Santarem":
    case "America/Santiago":
    case "America/Sao_Paulo":
    case "Atlantic/Stanley":
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
    case "Europe/Lisbon":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Morocco";
      break;
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
    case "Africa/Monrovia":
    case "Africa/Nouakchott":
    case "Africa/Ouagadougou":
    case "Africa/Sao_Tome":
    case "Atlantic/Reykjavik":
    case "Atlantic/St_Helena":
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 GMT";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
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
      $timezone = "+1 Chad";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Copenhagen":
    case "Europe/Andorra":
    case "Europe/Belgrade":
    case "Europe/Bratislava":
    case "Europe/Gibraltar":
    case "Europe/Ljubljana":
    case "Europe/Malta":
    case "Europe/Monaco":
    case "Europe/Oslo":
    case "Europe/Podgorica":
    case "Europe/San_Marino":
    case "Europe/Sarajevo":
    case "Europe/Stockholm":
    case "Europe/Vaduz":
    case "Europe/Vatican":
    case "Europe/Warsaw":
    case "Europe/Zurich":
      $timezone = "+1 Denmark(Kopenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Israel(Tel Aviv)";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Istanbul":
    case "Europe/Mariehamn":
    case "Europe/Sofia":
    case "Europe/Vilnius":
      $timezone = "+2 Turkey(Ankara)";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
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
      $timezone = "+3 East Africa Time";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Asia/Riyadh":
    case "Europe/Minsk":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Moscow":
    case "Europe/Samara":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Asia/Samarkand":
    case "Asia/Tashkent":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = "+5:30 India(Calcutta)";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Dhaka":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Bangkok":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Hovd":
    case "Asia/Jakarta":
    case "Asia/Phnom_Penh":
    case "Asia/Pontianak":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Choibalsan":
    case "Asia/Hong_Kong":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Taipei":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T27G or SIP-T27P auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t27pg ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t27p":
    case "t27g":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    array ( "ID" => "1", "Codec" => "ULAW", "Type" => "PCMU", "RTPMap" => 0, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "2", "Codec" => "ALAW", "Type" => "PCMA", "RTPMap" => 8, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "3", "Codec" => "G723", "Type" => "G723_53", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "4", "Codec" => "G723", "Type" => "G723_63", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "5", "Codec" => "G729", "Type" => "G729", "RTPMap" => 18, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "6", "Codec" => "G722", "Type" => "G722", "RTPMap" => 9, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "7", "Codec" => "ILBC", "Type" => "iLBC", "RTPMap" => 106, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "8", "Codec" => "G726", "Type" => "G726-16", "RTPMap" => 103, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "9", "Codec" => "G726", "Type" => "G726-24", "RTPMap" => 104, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "10", "Codec" => "G726", "Type" => "G726-32", "RTPMap" => 102, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "11", "Codec" => "G726", "Type" => "G726-40", "RTPMap" => 105, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "12", "Codec" => "OPUS", "Type" => "opus", "RTPMap" => 106, "Priority" => 0, "Enabled" => false)
  );
  $priority = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $vdcodec)
  {
    foreach ( $codecs as $index => $codec)
    {
      if ( $codec["Codec"] == $vdcodec)
      {
        $priority++;
        $codecs[$index]["Enabled"] = true;
        $codecs[$index]["Priority"] = $priority;
      }
    }
  }
  foreach ( $codecs as $codec)
  {
    if ( $codec["Enabled"])
    {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = \n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 0\n";
    }
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".payload_type = " . $codec["Type"] . "\n";
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".rtpmap = " . $codec["RTPMap"] . "\n";
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  $language = "";
  $capturelabel = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "Portuguese";
      $capturelabel = "Captura";
      break;
    case "en_US":
      $language = "English";
      $capturelabel = "Capture";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        $capturelabel = "Aramayı";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        $capturelabel = "Przechwytywanie";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        $capturelabel = "Cattura";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        $capturelabel = "Erfassen";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        $capturelabel = "捕获通话";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
        $capturelabel = "捕捉通話";
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
        $language = "Portuguese";
        $capturelabel = "Captura";
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
        $language = "Spanish";
        $capturelabel = "Capturar";
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
        $capturelabel = "Capturer";
        break;

      // English (default):
      default:
        $language = "English";
        $capturelabel = "Capture";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $content = str_replace ( "{{{capturelabel}}}", $capturelabel, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    if ( $dialplanentry["Kind"] == "Extensions")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Type"] == "Local Landline" || $dialplanentry["Type"] == "Local Mobile" || $dialplanentry["Type"] == "Interstate Landline" || $dialplanentry["Type"] == "Interstate Mobile" || $dialplanentry["Type"] == "Tollfree" || $dialplanentry["Type"] == "PRN" || $dialplanentry["Type"] == "International")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . $parameters["Prefix"] . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Kind"] == "Features" || $dialplanentry["Kind"] == "Services")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 8; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Pickup Value" => "", "Label" => "", "XML Phonebook" => "%NULL%");
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
    if ( $x > 8)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-" . strtoupper ( $parameters["Type"]) . " supports up to 8 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Pickup Value" => "*8", "Label" => $extension["Name"], "XML Phonebook" => "%NULL%");
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".pickup_value = " . $entry["Pickup Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".xml_phonebook = " . $entry["XML Phonebook"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
      $timezone = "-11 Midway Island";
      break;
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare,Pretoria";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T28P auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t28p ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t28p":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-t28p.cfg");
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
    array ( "ID" => "1", "Codec" => "ULAW", "Type" => "PCMU", "RTPMap" => 0, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "2", "Codec" => "ALAW", "Type" => "PCMA", "RTPMap" => 8, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "3", "Codec" => "G723", "Type" => "G723_53", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "4", "Codec" => "G723", "Type" => "G723_63", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "5", "Codec" => "G729", "Type" => "G729", "RTPMap" => 18, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "6", "Codec" => "G722", "Type" => "G722", "RTPMap" => 9, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "7", "Codec" => "ILBC", "Type" => "iLBC", "RTPMap" => 106, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "8", "Codec" => "G726", "Type" => "G726-16", "RTPMap" => 103, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "9", "Codec" => "G726", "Type" => "G726-24", "RTPMap" => 104, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "10", "Codec" => "G726", "Type" => "G726-32", "RTPMap" => 102, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "11", "Codec" => "G726", "Type" => "G726-40", "RTPMap" => 105, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "12", "Codec" => "OPUS", "Type" => "opus", "RTPMap" => 106, "Priority" => 0, "Enabled" => false)
  );
  $priority = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $vdcodec)
  {
    foreach ( $codecs as $index => $codec)
    {
      if ( $codec["Codec"] == $vdcodec)
      {
        $priority++;
        $codecs[$index]["Enabled"] = true;
        $codecs[$index]["Priority"] = $priority;
      }
    }
  }
  foreach ( $codecs as $codec)
  {
    if ( $codec["Enabled"])
    {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = \n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 0\n";
    }
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".payload_type = " . $codec["Type"] . "\n";
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".rtpmap = " . $codec["RTPMap"] . "\n";
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  $language = "";
  $capturelabel = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "Portuguese";
      $capturelabel = "Captura";
      break;
    case "en_US":
      $language = "English";
      $capturelabel = "Capture";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        $capturelabel = "Aramayı";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        $capturelabel = "Przechwytywanie";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        $capturelabel = "Cattura";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        $capturelabel = "Erfassen";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        $capturelabel = "捕获通话";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
        $capturelabel = "捕捉通話";
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
        $language = "Portuguese";
        $capturelabel = "Captura";
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
        $language = "Spanish";
        $capturelabel = "Capturar";
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
        $capturelabel = "Capturer";
        break;

      // English (default):
      default:
        $language = "English";
        $capturelabel = "Capture";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $content = str_replace ( "{{{capturelabel}}}", $capturelabel, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    if ( $dialplanentry["Kind"] == "Extensions")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Type"] == "Local Landline" || $dialplanentry["Type"] == "Local Mobile" || $dialplanentry["Type"] == "Interstate Landline" || $dialplanentry["Type"] == "Interstate Mobile" || $dialplanentry["Type"] == "Tollfree" || $dialplanentry["Type"] == "PRN" || $dialplanentry["Type"] == "International")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . $parameters["Prefix"] . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  foreach ( $parameters["DialPlan"] as $dialplanentry)
  {
    if ( strpos ( $dialplanentry["Pattern"], ".") !== false)
    {
      continue;
    }
    if ( $dialplanentry["Kind"] == "Features" || $dialplanentry["Kind"] == "Services")
    {
      $id++;
      $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
      $dialplan .= "dialplan.dialnow.line_id." . $id . " = \n";
    }
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 16; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Pickup Value" => "", "Label" => "", "XML Phonebook" => "%NULL%");
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
    if ( $x > 16)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-T28P supports up to 16 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Pickup Value" => "*8", "Label" => $extension["Name"], "XML Phonebook" => "%NULL%");
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".pickup_value = " . $entry["Pickup Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".xml_phonebook = " . $entry["XML Phonebook"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
      $timezone = "-11 Midway Island";
      break;
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare,Pretoria";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T29G auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t29g ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t29g":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "ilbc_15_2kbps" => "ILBC",
    "pcma" => "ALAW",
    "pcmu" => "ULAW",
    "opus" => "OPUS",
    "g726_40" => "G726",
    "g726_32" => "G726",
    "g726_24" => "G726",
    "g726_16" => "G726",
    "g723_63" => "G723",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  switch ( $parameters["Country"])
  {
    // Turkish:
    case "TR": // Turkey
      $language = "Turkish";
      break;

    // Polish:
    case "PL": // Poland
      $language = "Polish";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "Italian";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "German";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = "Chinese_S";
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "Chinese_T";
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
      $language = "Portuguese";
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
      $language = "Spanish";
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

    // English (default):
    default:
      $language = "English";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 16; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Label" => "", "Extension" => "");
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
    if ( $x > 10)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-T29G supports up to 10 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Label" => $extension["Name"], "Extension" => "*8");
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".extension = " . $entry["Extension"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
      $timezone = "-11 Midway Island";
      break;
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare,Pretoria";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T38G auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t38g ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t38g":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "ilbc_15_2kbps" => "ILBC",
    "pcma" => "ALAW",
    "pcmu" => "ULAW",
    "opus" => "OPUS",
    "g726_40" => "G726",
    "g726_32" => "G726",
    "g726_24" => "G726",
    "g726_16" => "G726",
    "g723_63" => "G723",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  $language = "";
  $capturelabel = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = "Portuguese";
      $capturelabel = "Captura";
      break;
    case "en_US":
      $language = "English";
      $capturelabel = "Capture";
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
      // Turkish:
      case "TR": // Turkey
        $language = "Turkish";
        $capturelabel = "Aramayı";
        break;

      // Polish:
      case "PL": // Poland
        $language = "Polish";
        $capturelabel = "Przechwytywanie";
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = "Italian";
        $capturelabel = "Cattura";
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = "German";
        $capturelabel = "Erfassen";
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = "Chinese_S";
        $capturelabel = "捕获通话";
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = "Chinese_T";
        $capturelabel = "捕捉通話";
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
        $language = "Portuguese";
        $capturelabel = "Captura";
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
        $language = "Spanish";
        $capturelabel = "Capturar";
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
        $capturelabel = "Capturer";
        break;

      // English (default):
      default:
        $language = "English";
        $capturelabel = "Capture";
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);
  $content = str_replace ( "{{{capturelabel}}}", $capturelabel, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 16; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Label" => "", "Extension" => "");
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
    if ( $x > 16)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-T38G supports up to 16 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Label" => $extension["Name"], "Extension" => "*8");
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".extension = " . $entry["Extension"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
      $timezone = "-11 Midway Island";
      break;
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare,Pretoria";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T4XS auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t4xs ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t46s":
      $maxblf = 10;
      break;
    case "t48s":
      $maxblf = 29;
      break;
    default:
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
  }
  $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");

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
    "ilbc_15_2kbps" => "ILBC",
    "pcma" => "ALAW",
    "pcmu" => "ULAW",
    "opus" => "OPUS",
    "g726_40" => "G726",
    "g726_32" => "G726",
    "g726_24" => "G726",
    "g726_16" => "G726",
    "g723_63" => "G723",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  switch ( $parameters["Country"])
  {
    // Turkish:
    case "TR": // Turkey
      $language = "Turkish";
      break;

    // Polish:
    case "PL": // Poland
      $language = "Polish";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "Italian";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "German";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = "Chinese_S";
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "Chinese_T";
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
      $language = "Portuguese";
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
      $language = "Spanish";
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

    // English (default):
    default:
      $language = "English";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= $maxblf; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Label" => "", "Extension" => "");
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
    if ( $x > $maxblf)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-" . strtoupper ( $parameters["Type"]) . " supports up to " . $maxblf . " BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Label" => $extension["Name"], "Extension" => "*8");
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".extension = " . $entry["Extension"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
      $timezone = "-11 Midway Island";
      break;
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare,Pretoria";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T30(P) auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t30p ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t30p":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "ilbc_15_2kbps" => "ILBC",
    "pcma" => "ALAW",
    "pcmu" => "ULAW",
    "opus" => "OPUS",
    "g726_40" => "G726",
    "g726_32" => "G726",
    "g726_24" => "G726",
    "g726_16" => "G726",
    "g723_63" => "G723",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  switch ( $parameters["Country"])
  {
    // Turkish:
    case "TR": // Turkey
      $language = "Turkish";
      break;

    // Polish:
    case "PL": // Poland
      $language = "Polish";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "Italian";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "German";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = "Chinese_S";
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "Chinese_T";
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
      $language = "Portuguese";
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
      $language = "Spanish";
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

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = "Russian";
      break;

    // Czech:
    case "CZ": // Czech Republic
      $language = "Czech";
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

    // English (default):
    default:
      $language = "English";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Midway Island";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
      $timezone = "+5 Kazakhstan(Aktau)";
      break;
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Bishkek":
      $timezone = "+6 Kyrgyzstan(Bishkek)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T31, SIP-T31P or SIP-T31G auto provision
 * file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t31 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t31":
    case "t31p":
    case "t31g":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "iLBC" => "ILBC",
    "PCMA" => "ALAW",
    "PCMU" => "ULAW",
    "opus" => "OPUS",
    "g726-40" => "G726",
    "g726-32" => "G726",
    "g726-24" => "G726",
    "g726-16" => "G726",
    "G723_53" => "G723",
    "G723_63" => "G723",
    "G729" => "G729",
    "G729AB" => "G729A",
    "G722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  switch ( $parameters["Country"])
  {
    // Turkish:
    case "TR": // Turkey
      $language = "Turkish";
      break;

    // Polish:
    case "PL": // Poland
      $language = "Polish";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "Italian";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "German";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = "Chinese_S";
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "Chinese_T";
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
      $language = "Portuguese";
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
      $language = "Spanish";
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

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = "Russian";
      break;

    // Czechlang:
    case "CZ": // Czech Republic
      $language = "Czechlang";
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
    case "ML": // Mali
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
      $language = "Arabic";
      break;

    // English (default):
    default:
      $language = "English";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 2; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Label" => "", "Extension" => "");
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
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-" . strtoupper ( $parameters["Type"]) . " supports up to 2 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Extension" => "*8", "Label" => $extension["Name"]);
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".extension = " . $entry["Extension"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Midway Island";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
      $timezone = "+5 Kazakhstan(Aktau)";
      break;
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Bishkek":
      $timezone = "+6 Kyrgyzstan(Bishkek)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink SIP-T40P auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_t40p ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "t40p":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "ilbc_15_2kbps" => "ILBC",
    "pcma" => "ALAW",
    "pcmu" => "ULAW",
    "opus" => "OPUS",
    "g726_40" => "G726",
    "g726_32" => "G726",
    "g726_24" => "G726",
    "g726_16" => "G726",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  switch ( $parameters["Country"])
  {
    // Turkish:
    case "TR": // Turkey
      $language = "Turkish";
      break;

    // Polish:
    case "PL": // Poland
      $language = "Polish";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "Italian";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "German";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = "Chinese_S";
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "Chinese_T";
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
      $language = "Portuguese";
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
      $language = "Spanish";
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

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = "Russian";
      break;

    // English (default):
    default:
      $language = "English";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Configure BLF's:
  $linekeys = array ();
  for ( $x = 1; $x <= 3; $x++)
  {
    $linekeys[$x] = array ( "Type" => 0, "Line" => "", "Value" => "", "Label" => "", "Extension" => "");
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
    if ( $x > 3)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. Yealink SIP-T40P supports up to 3 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $linekeys[$x] = array ( "Type" => 16, "Line" => "1", "Value" => $number, "Extension" => "*8", "Label" => $extension["Name"]);
  }
  $linekey = "";
  foreach ( $linekeys as $id => $entry)
  {
    $linekey .= "linekey." . $id . ".type = " . $entry["Type"] . "\n";
    $linekey .= "linekey." . $id . ".line = " . $entry["Line"] . "\n";
    $linekey .= "linekey." . $id . ".value = " . $entry["Value"] . "\n";
    $linekey .= "linekey." . $id . ".label = " . $entry["Label"] . "\n";
    $linekey .= "linekey." . $id . ".extension = " . $entry["Extension"] . "\n";
  }
  $content = str_replace ( "{{{linekey}}}", $linekey, $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Midway Island";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
      $timezone = "+5 Kazakhstan(Aktau)";
      break;
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Bishkek":
      $timezone = "+6 Kyrgyzstan(Bishkek)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink CP920 auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_cp920 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "cp920":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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
    "opus" => "OPUS",
    "g723_53" => "G723",
    "g723_63" => "G723",
    "ilbc_15_2kbps" => "ILBC",
    "g726_16" => "G726",
    "g726_24" => "G726",
    "g726_32" => "G726",
    "g726_40" => "G726",
    "pcmu" => "ULAW",
    "pcma" => "ALAW",
    "g722_1c_24kpbs" => "SIREN14",
    "g722_1c_32kpbs" => "SIREN14",
    "g722_1c_48kpbs" => "SIREN14",
    "g722_1_24kpbs" => "SIREN7",
    "g729" => "G729",
    "g722" => "G722"
  );
  $priority = 0;
  foreach ( $codecs as $cfgname => $codec)
  {
    if ( in_array ( $codec, $equipmentconfig["AudioCodecs"]))
    {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $cfgname . ".priority = \n";
      $codecconfig .= "account.1.codec." . $cfgname . ".enable = 0\n";
    }
  }
  $content = str_replace ( "{{{codecconfig}}}", $codecconfig, $content);

  // Set phone language:
  switch ( $parameters["Country"])
  {
    // Turkish:
    case "TR": // Turkey
      $language = "Turkish";
      break;

    // Polish:
    case "PL": // Poland
      $language = "Polish";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "Italian";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "German";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
      $language = "Chinese_S";
      break;

    // Chinese Traditional:
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "Chinese_T";
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
      $language = "Portuguese";
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
      $language = "Spanish";
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

    // Russian:
    case "BY": // Belarus
    case "RU": // Russia
      $language = "Russian";
      break;

    // Czech:
    case "CZ": // Czech Republic
      $language = "Czech";
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

    // English (default):
    default:
      $language = "English";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Set phone country tone:
  $tonecountry = "";
  $customtones = "";
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $tonecountry = "Australia";
      break;
    case "AT": // Austria
      $tonecountry = "Austria";
      break;
    case "BR": // Brazil
      $tonecountry = "Custom";
      $customtones = "voice.tone.dial = 425/975,0/50\n";
      $customtones .= "voice.tone.secondary_dial = 425/10000\n";
      $customtones .= "voice.tone.busy = 425/250,0/250\n";
      $customtones .= "voice.tone.ring = 425/1000,0/4000\n";
      $customtones .= "voice.tone.congestion = 425/750,0/250,425/250,0/250\n";
      $customtones .= "voice.tone.callwaiting = 425/10000\n";
      $customtones .= "voice.tone.dialrecall = 425/10000\n";
      $customtones .= "voice.tone.info = 425/10000\n";
      $customtones .= "voice.tone.stutterdial = 425/10000\n";
      $customtones .= "voice.tone.message = 425/10000\n";
      $customtones .= "voice.tone.autoanswer = 425/10000\n";
      break;
    case "BE": // Belgium
      $tonecountry = "Belgium";
      break;
    case "CL": // Chile
      $tonecountry = "Chile";
      break;
    case "CN": // China
      $tonecountry = "China";
      break;
    case "CZ": // Czech
      $tonecountry = "Czech";
      break;
    case "DK": // Denmark
      $tonecountry = "Denmark";
      break;
    case "FI": // Finland
      $tonecountry = "Finland";
      break;
    case "FR": // France
      $tonecountry = "France";
      break;
    case "DE": // Germany
      $tonecountry = "Germany";
      break;
    case "GB": // Great Britain
      $tonecountry = "Great Britain";
      break;
    case "GR": // Greece
      $tonecountry = "Greece";
      break;
    case "HU": // Hungary
      $tonecountry = "Hungary";
      break;
    case "LT": // Lithuania
      $tonecountry = "Lithuania";
      break;
    case "IN": // India
      $tonecountry = "India";
      break;
    case "IT": // Italy
      $tonecountry = "Italy";
      break;
    case "JP": // Japan
      $tonecountry = "Japan";
      break;
    case "MX": // Mexico
      $tonecountry = "Mexico";
      break;
    case "NZ": // New Zealand
      $tonecountry = "New Zealand";
      break;
    case "NL": // Netherlands
      $tonecountry = "Netherlands";
      break;
    case "NO": // Norway
      $tonecountry = "Norway";
      break;
    case "PT": // Portugal
      $tonecountry = "Portugal";
      break;
    case "ES": // Spain
      $tonecountry = "Spain";
      break;
    case "CH": // Switzerland
      $tonecountry = "Switzerland";
      break;
    case "SE": // Sweden
      $tonecountry = "Sweden";
      break;
    case "RU": // Russia
      $tonecountry = "Russia";
      break;
    case "US": // United States of America
      $tonecountry = "United States";
      break;
    default: // United States of America
      $tonecountry = "United States";
      break;
  }
  $content = str_replace ( "{{{voicetones}}}", "voice.tone.country = " . $tonecountry . "\n" . $customtones, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Midway Island";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii-Aleutian";
      break;
    case "Pacific/Marquesas":
      $timezone = "-9:30 French Polynesia";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = "-9 United States-Alaska Time";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = "-8 United States-Pacific Time";
      break;
    case "America/Tijuana":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Chihuahua":
      $timezone = "-7 Chihuahua,La Paz";
      break;
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain Time";
      break;
    case "America/Phoenix":
      $timezone = "-7 Arizona";
      break;
    case "America/Belize":
      $timezone = "-6 Belize";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Costa_Rica":
      $timezone = "-6 Costa Rica";
      break;
    case "America/El_Salvador":
      $timezone = "-6 El Salvador";
      break;
    case "America/Guatemala":
      $timezone = "-6 Guatemala";
      break;
    case "America/Tegucigalpa":
      $timezone = "-6 Honduras";
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Monterrey":
      $timezone = "-6 Monterrey";
      break;
    case "America/Managua":
      $timezone = "-6 Nicaragua";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = "-6 United States-Central Time";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Bogota":
      $timezone = "-5 Bogota,Lima";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
      $timezone = "-5 Indiana (East)";
      break;
    case "America/Lima":
      $timezone = "-5 Peru";
      break;
    case "America/Guayaquil":
      $timezone = "-5 Quito";
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
      $timezone = "-5 United States-Eastern Time";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
      $timezone = "-4 Manaus,Cuiaba";
      break;
    case "America/Puerto_Rico":
      $timezone = "-4 San Juan";
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad&Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;
    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
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
      $timezone = "-3 Argentina(Buenos Aires)";
      break;
    case "America/Cayenne":
    case "America/Fortaleza":
      $timezone = "-3 Cayenne,Fortaleza";
      break;
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
      $timezone = "-3 Brazil(no DST)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
      $timezone = "-2 Brazil(no DST)";
      break;
    case "Atlantic/South_Georgia":
      $timezone = "-2 Mid-Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Cape Verde Islands";
      break;
    case "Africa/Casablanca":
      $timezone = "0 Casablanca";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands(Torshavn)";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Europe/Lisbon":
      $timezone = "0 Lisbon";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
      $timezone = "0 Monrovia";
      break;
    case "Atlantic/Reykjavik":
      $timezone = "0 Reykjavik";
      break;
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
    case "Atlantic/St_Helena":
      $timezone = "0 GMT";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 Western Europe Time";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Belgrade":
      $timezone = "+1 Belgrade";
      break;
    case "Europe/Bratislava":
      $timezone = "+1 Bratislava";
      break;
    case "Africa/Ndjamena":
      $timezone = "+1 Chad";
      break;
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
      $timezone = "+1 West Central Africa";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Zagreb":
      $timezone = "+1 Croatia(Zagreb)";
      break;
    case "Europe/Prague":
      $timezone = "+1 Czech Republic(Prague)";
      break;
    case "Europe/Warsaw":
      $timezone = "+1 Poland(Warsaw)";
      break;
    case "Europe/Ljubljana":
      $timezone = "+1 Ljubljana";
      break;
    case "Europe/Zurich":
      $timezone = "+1 Switzerland(Bern)";
      break;
    case "Europe/Stockholm":
      $timezone = "+1 Sweden(Stockholm)";
      break;
    case "Europe/Copenhagen":
      $timezone = "+1 Copenhagen";
      break;
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
      $timezone = "+1 Denmark(Copenhagen)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Africa/Cairo":
      $timezone = "+2 Cairo";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Africa/Harare":
      $timezone = "+2 Harare";
      break;
    case "Africa/Tripoli":
      $timezone = "+2 Tripoli";
      break;
    case "Europe/Athens":
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
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Jerusalem":
      $timezone = "+2 Jerusalem";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
      $timezone = "+2 Bulgaria(Sofia)";
      break;
    case "Europe/Vilnius":
      $timezone = "+2 Lithuania(Vilnius)";
      break;
    case "Europe/Mariehamn":
      $timezone = "+2 E.Europe";
      break;
    case "Europe/Istanbul":
      $timezone = "+2 Istanbul";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
      break;
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
      $timezone = "+3 East Africa Time";
      break;
    case "Africa/Nairobi":
      $timezone = "+3 Nairobi";
      break;
    case "Asia/Riyadh":
      $timezone = "+3 Kuwait,Riyadh";
      break;
    case "Europe/Minsk":
      $timezone = "+3 Minsk";
      break;
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Kaliningrad":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Muscat":
      $timezone = "+4 Abu Dhabi,Muscat";
      break;
    case "Asia/Tbilisi":
    case "Asia/Dubai":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Kabul":
      $timezone = "+4:30 Afghanistan(Kabul)";
      break;
    case "Asia/Aqtau":
      $timezone = "+5 Kazakhstan(Aktau)";
      break;
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
      $timezone = "+5 Karachi";
      break;
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Tashkent";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
      $timezone = "+5:30 Sri Jayawardenepura";
      break;
    case "Asia/Kolkata":
      $timezone = "+5:30 Kolkota,New Delhi";
      break;
    case "Asia/Kathmandu":
      $timezone = "+5:45 Nepal(Katmandu)";
      break;
    case "Asia/Dhaka":
      $timezone = "+6 Bangladesh(Dhaka)";
      break;
    case "Asia/Bishkek":
      $timezone = "+6 Kyrgyzstan(Bishkek)";
      break;
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Rangoon":
    case "Indian/Cocos":
      $timezone = "+6:30 Myanmar(Naypyitaw)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
      $timezone = "+7 Jakarta";
      break;
    case "Asia/Ho_Chi_Minh":
      $timezone = "+7 Vietnam(Hanoi)";
      break;
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Hong_Kong":
      $timezone = "+8 Hong Kong,Urumqi";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
      $timezone = "+8 Kuala Lumpur";
      break;
    case "Asia/Taipei":
      $timezone = "+8 Taipei";
      break;
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = "+8 Ulaanbaatar";
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Russia(Irkutsk, Ulan-Ude)";
      break;
    case "Australia/Eucla":
      $timezone = "+8:45 Eucla";
      break;
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Asia/Irkutsk":
      $timezone = "+9 Russia(Yakutsk, Chita)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = "+10 Guam,Port Moresby";
      break;
    case "Australia/Lindeman":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 Russia(Srednekolymsk Time)";
      break;
    case "Pacific/Norfolk":
      $timezone = "+11:30 Norfolk Island";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
      $timezone = "+12 Marshall Islands";
      break;
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Asia/Anadyr":
      $timezone = "+12 Anadyr";
      break;
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 Russia(Kamchatka Time)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
    case "Pacific/Chatham":
      $timezone = "+13:30 Chatham Islands";
      break;
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+14 Kiribati";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to create the Yealink VP530 auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_vp530 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "vp530":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-yealink-" . $parameters["Type"] . ".cfg");
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

  // Configure equipment audio CODEC's:
  $codecconfig = "";
  $codecs = array (
    array ( "ID" => "1", "Codec" => "ULAW", "Type" => "PCMU", "RTPMap" => 0, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "2", "Codec" => "ALAW", "Type" => "PCMA", "RTPMap" => 8, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "3", "Codec" => "G723", "Type" => "G723", "RTPMap" => 4, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "5", "Codec" => "G729", "Type" => "G729", "RTPMap" => 18, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "6", "Codec" => "G722", "Type" => "G722", "RTPMap" => 9, "Priority" => 0, "Enabled" => false),
    array ( "ID" => "7", "Codec" => "ILBC", "Type" => "iLBC", "RTPMap" => 122, "Priority" => 0, "Enabled" => false)
  );
  $priority = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $vdcodec)
  {
    foreach ( $codecs as $index => $codec)
    {
      if ( $codec["Codec"] == $vdcodec)
      {
        $priority++;
        $codecs[$index]["Enabled"] = true;
        $codecs[$index]["Priority"] = $priority;
      }
    }
  }
  foreach ( $codecs as $codec)
  {
    if ( $codec["Enabled"])
    {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".priority = \n";
      $codecconfig .= "account.1.codec." . $codec["ID"] . ".enable = 0\n";
    }
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".payload_type = " . $codec["Type"] . "\n";
    $codecconfig .= "account.1.codec." . $codec["ID"] . ".rtpmap = " . $codec["RTPMap"] . "\n";
  }
  $content = str_replace ( "{{{audiocodecconfig}}}", $codecconfig, $content);

  // Configure equipment video CODEC's:
  $codecconfig = "";
  $codecs = array (
    array ( "ID" => "1", "Codec" => "H264", "Type" => "H264", "RTPMap" => 99, "Parameters" => "profile-level-id=42800D; packetization-mode=0; max-mbps=11880", "Priority" => 0, "Enabled" => false),
    array ( "ID" => "2", "Codec" => "H263", "Type" => "H264", "RTPMap" => 34, "Parameters" => "CIF=1; QCIF=1", "Priority" => 0, "Enabled" => false),
    array ( "ID" => "3", "Codec" => "MPEG4", "Type" => "mp4v-es", "RTPMap" => 102, "Parameters" => "CIF=1; QCIF=1; MaxBR=3840", "Priority" => 0, "Enabled" => false)
  );
  $priority = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $vdcodec)
  {
    foreach ( $codecs as $index => $codec)
    {
      if ( $codec["Codec"] == $vdcodec)
      {
        $priority++;
        $codecs[$index]["Enabled"] = true;
        $codecs[$index]["Priority"] = $priority;
      }
    }
  }
  foreach ( $codecs as $codec)
  {
    if ( $codec["Enabled"])
    {
      $codecconfig .= "account.1.video_codec." . $codec["ID"] . ".priority = " . $priority . "\n";
      $codecconfig .= "account.1.video_codec." . $codec["ID"] . ".enable = 1\n";
      $priority++;
    } else {
      $codecconfig .= "account.1.video_codec." . $codec["ID"] . ".priority = \n";
      $codecconfig .= "account.1.video_codec." . $codec["ID"] . ".enable = 0\n";
    }
    $codecconfig .= "account.1.video_codec." . $codec["ID"] . ".payload_type = " . $codec["Type"] . "\n";
    $codecconfig .= "account.1.video_codec." . $codec["ID"] . ".rtpmap = " . $codec["RTPMap"] . "\n";
    $codecconfig .= "account.1.video_codec." . $codec["ID"] . ".para = " . $codec["Parameters"] . "\n";
  }
  $content = str_replace ( "{{{videocodecconfig}}}", $codecconfig, $content);

  // Set phone language:
  switch ( $parameters["Country"])
  {
    // Turkish:
    case "TR": // Turkey
      $language = "Turkish";
      break;

    // Italian:
    case "IT": // Italy
    case "SM": // San Marino
      $language = "Italian";
      break;

    // German:
    case "DE": // Germany
    case "BE": // Belgium
    case "AT": // Austria
    case "CH": // Switzerland
    case "LU": // Luxembourg
    case "LI": // Liechtenstein
      $language = "German";
      break;

    // Chinese Simplified:
    case "CN": // China
    case "TW": // Taiwan
    case "SG": // Singapore
    case "HK": // Hong Kong
    case "MO": // Macao
      $language = "Chinese_S";
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
      $language = "Portugal";
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
      $language = "Spanish";
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

    // English (default):
    default:
      $language = "English";
      break;
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

  // Create dialplan:
  $dialplan = "";
  $id = 0;
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
    $id++;
    $dialplan .= "dialplan.dialnow.rule." . $id . " = " . ( $dialplanentry["Type"] == "External" ? $parameters["Prefix"] : "") . str_replace ( "X", "[0-9]", str_replace ( "Z", "[1-9]", str_replace ( "N", "[2-9]", $dialplanentry["Pattern"]))) . "\n";
  }
  $content = str_replace ( "{{{dialplan}}}", $dialplan, $content);
  $content = str_replace ( "{{{emergencynumbers}}}", substr ( $emergency, 1), $content);
  $content = str_replace ( "{{{prefix}}}", $parameters["Prefix"], $content);

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = "-11 Samoa";
      break;
    case "America/Adak":
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
      $timezone = "-10 United States-Hawaii";
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
    case "Pacific/Marquesas":
      $timezone = "-9 United States-Alaska";
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = "-8 Canada(Vancouver,Whitehorse)";
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
      $timezone = "-8 United States-Pacific";
      break;
    case "America/Santa_Isabel":
    case "America/Tijuana":
    case "Pacific/Pitcairn":
      $timezone = "-8 Mexico(Tijuana,Mexicali)";
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = "-7 Canada(Edmonton,Calgary)";
      break;
    case "America/Mazatlan":
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Ojinaga":
      $timezone = "-7 Mexico(Mazatlan,Chihuahua)";
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = "-7 United States-Mountain";
      break;
    case "America/Phoenix":
      $timezone = "-7 United States-MST";
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = "-6 Canada-Manitoba(Winnipeg)";
      break;
    case "America/Belize":
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
    case "Pacific/Galapagos":
      $timezone = "-6 Mexico(Mexico City,Acapulco)";
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
      $timezone = "-6 United States-Central";
      break;
    case "America/Nassau":
      $timezone = "-5 Bahamas(Nassau)";
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = "-5 Canada(Montreal,Ottawa,Quebec)";
      break;
    case "America/Havana":
    case "America/Bogota":
    case "America/Lima":
    case "America/Guayaquil":
    case "America/Cayman":
    case "America/Grand_Turk":
    case "America/Panama":
    case "America/Port-au-Prince":
      $timezone = "-5 Cuba(Havana)";
      break;
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
    case "America/Detroit":
    case "America/Jamaica":
    case "America/Kentucky/Louisville":
    case "America/Kentucky/Monticello":
    case "America/New_York":
    case "Pacific/Easter":
      $timezone = "-5 United States-Eastern";
      break;
    case "America/Caracas":
      $timezone = "-4:30 Venezuela(Caracas)";
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = "-4 Canada(Halifax,Saint John)";
      break;
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
    case "America/Puerto_Rico":
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
      $timezone = "-4 Chile(Santiago)";
      break;
    case "Atlantic/Bermuda":
      $timezone = "-4 United Kingdom-Bermuda(Bermuda)";
      break;
    case "America/Port_of_Spain":
      $timezone = "-4 Trinidad And Tobago";
      break;
    case "America/St_Johns":
      $timezone = "-3:30 Canada-New Foundland(St.Johns)";
      break;

    case "America/Godthab":
      $timezone = "-3 Denmark-Greenland(NUUK)";
      break;
    case "America/Argentina/Buenos_Aires":
      $timezone = "-3 Buenos Aires";
      break;
    case "America/Cayenne":
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
      $timezone = "-3 Argentina(Buenos Aires)";
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
      $timezone = "-3 Brazil(Brasilia)";
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = "-2 Middle Atlantic";
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = "-1 Portugal(Azores)";
      break;
    case "America/Danmarkshavn":
      $timezone = "0 Greenland";
      break;
    case "Atlantic/Faroe":
      $timezone = "0 Denmark-Faroe Islands";
      break;
    case "Europe/Dublin":
      $timezone = "0 Ireland(Dublin)";
      break;
    case "Europe/Lisbon":
    case "Atlantic/Madeira":
      $timezone = "0 Portugal(Lisboa,Porto,Funchal)";
      break;
    case "Atlantic/Canary":
      $timezone = "0 Spain-Canary Islands(Las Palmas)";
      break;
    case "Europe/London":
      $timezone = "0 United Kingdom(London)";
      break;
    case "Africa/Monrovia":
    case "Atlantic/Reykjavik":
    case "Africa/Casablanca":
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
    case "Atlantic/St_Helena":
      $timezone = "0 Morocco";
      break;
    case "Europe/Guernsey":
    case "Europe/Isle_of_Man":
    case "Europe/Jersey":
      $timezone = "0 GMT";
      break;
    case "Europe/Tirane":
      $timezone = "+1 Albania(Tirane)";
      break;
    case "Europe/Vienna":
      $timezone = "+1 Austria(Vienna)";
      break;
    case "Europe/Brussels":
      $timezone = "+1 Belgium(Brussels)";
      break;
    case "Europe/Zagreb":
    case "Europe/Belgrade":
    case "Europe/Bratislava":
      $timezone = "+1 Croatia(Zagreb)";
      break;
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
      $timezone = "+1 Namibia(Windhoek)";
      break;
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = "+1 Spain(Madrid)";
      break;
    case "Europe/Prague":
    case "Europe/Warsaw":
      $timezone = "+1 Czech Republic(Prague)";
      break;
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
      $timezone = "+1 Denmark(Kopenhaven)";
      break;
    case "Europe/Paris":
      $timezone = "+1 France(Paris)";
      break;
    case "Europe/Berlin":
      $timezone = "+1 Germany(Berlin)";
      break;
    case "Europe/Budapest":
      $timezone = "+1 Hungary(Budapest)";
      break;
    case "Europe/Rome":
      $timezone = "+1 Italy(Rome)";
      break;
    case "Europe/Luxembourg":
      $timezone = "+1 Luxembourg(Luxembourg)";
      break;
    case "Europe/Skopje":
      $timezone = "+1 Macedonia(Skopje)";
      break;
    case "Europe/Amsterdam":
      $timezone = "+1 Netherlands(Amsterdam)";
      break;
    case "Europe/Tallinn":
      $timezone = "+2 Estonia(Tallinn)";
      break;
    case "Europe/Helsinki":
      $timezone = "+2 Finland(Helsinki)";
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
    case "Asia/Jerusalem":
      $timezone = "+2 Gaza Strip(Gaza)";
      break;
    case "Europe/Athens":
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
    case "Africa/Cairo":
    case "Africa/Harare":
    case "Africa/Tripoli":
      $timezone = "+2 Greece(Athens)";
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = "+2 Jordan(Amman)";
      break;
    case "Europe/Riga":
      $timezone = "+2 Latvia(Riga)";
      break;
    case "Asia/Beirut":
      $timezone = "+2 Lebanon(Beirut)";
      break;
    case "Europe/Chisinau":
      $timezone = "+2 Moldova(Kishinev)";
      break;
    case "Europe/Simferopol":
      $timezone = "+2 Russia(Kaliningrad)";
      break;
    case "Europe/Bucharest":
      $timezone = "+2 Romania(Bucharest)";
      break;
    case "Asia/Damascus":
      $timezone = "+2 Syria(Damascus)";
      break;
    case "Europe/Sofia":
    case "Europe/Vilnius":
    case "Europe/Mariehamn":
    case "Europe/Istanbul":
      $timezone = "+2 Turkey(Ankara)";
      break;
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = "+2 Ukraine(Kyiv,Odessa)";
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
      $timezone = "+3 East Africa";
      break;
    case "Asia/Baghdad":
    case "Asia/Riyadh":
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
      $timezone = "+3 Iraq(Baghdad)";
      break;
    case "Europe/Moscow":
    case "Europe/Kaliningrad":
    case "Europe/Minsk":
      $timezone = "+3 Russia(Moscow)";
      break;
    case "Asia/Tehran":
      $timezone = "+3:30 Iran(Teheran)";
      break;
    case "Asia/Yerevan":
      $timezone = "+4 Armenia(Yerevan)";
      break;
    case "Asia/Baku":
    case "Asia/Muscat":
      $timezone = "+4 Azerbaijan(Baku)";
      break;
    case "Asia/Dubai":
      $timezone = "+4 United Arab Emirates(Dubai)";
      break;
    case "Asia/Tbilisi":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = "+4 Georgia(Tbilisi)";
      break;
    case "Europe/Samara":
    case "Europe/Volgograd":
      $timezone = "+4 Russia(Samara)";
      break;
    case "Asia/Aqtobe":
    case "Asia/Aqtau":
    case "Asia/Oral":
      $timezone = "+5 Kazakhstan(Aqtobe)";
      break;
    case "Asia/Karachi":
    case "Asia/Tashkent":
    case "Asia/Samarkand":
      $timezone = "+5 Kyrgyzstan(Bishkek)";
      break;
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = "+5 Pakistan(Islamabad)";
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
    case "Asia/Kathmandu":
      $timezone = "+5:30 India(Calcutta)";
      break;
    case "Asia/Dhaka":
    case "Asia/Bishkek":
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = "+6 Kazakhstan(Astana,Almaty)";
      break;
    case "Asia/Yekaterinburg":
      $timezone = "+6 Russia(Novosibirsk,Omsk)";
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = "+7 Russia(Krasnoyarsk)";
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Asia/Rangoon":
    case "Indian/Cocos":
    case "Indian/Christmas":
      $timezone = "+7 Thailand(Bangkok)";
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
    case "Asia/Hong_Kong":
      $timezone = "+8 China(Beijing)";
      break;
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Taipei":
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Krasnoyarsk":
      $timezone = "+8 Singapore(Singapore)";
      break;
    case "Australia/Perth":
      $timezone = "+8 Australia(Perth)";
      break;
    case "Australia/Eucla":
    case "Asia/Seoul":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Pyongyang":
    case "Asia/Irkutsk":
    case "Pacific/Palau":
      $timezone = "+9 Korea(Seoul)";
      break;
    case "Asia/Tokyo":
      $timezone = "+9 Japan(Tokyo)";
      break;
    case "Australia/Darwin":
      $timezone = "+9:30 Australia(Darwin)";
      break;
    case "Australia/Lindeman":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = "+10 Australia(Sydney,Melbourne,Canberra)";
      break;
    case "Australia/Brisbane":
      $timezone = "+10 Australia(Brisbane)";
      break;
    case "Asia/Yakutsk":
      $timezone = "+10 Russia(Vladivostok)";
      break;
    case "Australia/Adelaide":
    case "Australia/Broken_Hill":
      $timezone = "+10:30 Australia(Lord Howe Islands)";
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
    case "Pacific/Norfolk":
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
      $timezone = "+11 New Caledonia(Noumea)";
      break;
    case "Pacific/Kwajalein":
    case "Pacific/Majuro":
    case "Pacific/Funafuti":
    case "Pacific/Nauru":
    case "Pacific/Tarawa":
    case "Pacific/Wake":
    case "Pacific/Wallis":
    case "Asia/Anadyr":
    case "Asia/Kamchatka":
    case "Asia/Magadan":
      $timezone = "+12 New Zealand(Wellington,Auckland)";
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
    case "Pacific/Chatham":
    case "Pacific/Apia":
    case "Pacific/Kiritimati":
      $timezone = "+13 Tonga(Nukualofa)";
      break;
  }
  $ntpaddr1 = ( sizeof ( $parameters["NTP"]) > 0 ? $parameters["NTP"][0] : "");
  $ntpaddr2 = ( sizeof ( $parameters["NTP"]) > 1 ? $parameters["NTP"][1] : "");
  $content = str_replace ( "{{{timeoffset}}}", substr ( $timezone, 0, strpos ( $timezone, " ")), $content);
  $content = str_replace ( "{{{timezone}}}", substr ( $timezone, strpos ( $timezone, " ") + 1), $content);
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
      $dateformat = 3; // DD/MM/YYYY
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
      $dateformat = 5; // DD MMM YYYY
      $timeformat = 1; // 24h
      break;
    case "IN": // India
    case "IE": // Ireland
    case "MY": // Malaysia
    case "NL": // Netherlands
      $dateformat = 1; // DD-MMM-YY
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
      $dateformat = 2; // YYYY-MM-DD
      $timeformat = 1; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 4; // MM/DD/YY
      $timeformat = 0; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 4; // MM/DD/YY
      $timeformat = 1; // 24h
      break;
    default:
      $dateformat = 0; // WWW MMM DD
      $timeformat = 0; // 12h
      break;
  }
  $content = str_replace ( "{{{timeformat}}}", $timeformat, $content);
  $content = str_replace ( "{{{dateformat}}}", $dateformat, $content);

  // Set admin and non-admin username and password:
  $content = str_replace ( "{{{webusername}}}", $equipmentconfig["ExtraSettings"]["User"]["Name"], $content);
  $content = str_replace ( "{{{webuserpassword}}}", $equipmentconfig["ExtraSettings"]["User"]["Password"], $content);
  $content = str_replace ( "{{{webadminname}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Name"], $content);
  $content = str_replace ( "{{{webadminpassword}}}", $equipmentconfig["ExtraSettings"]["Admin"]["Password"], $content);

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", "tftp://" . $_in["general"]["address"] . "/", $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/" . strtolower ( $parameters["MAC"]) . ".cfg", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify yealink-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to remove the Yealink auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_yealink_remove ( $buffer, $parameters)
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
 * Function to create the Yealink audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_yealink_audio ( $buffer, $parameters)
{
  // Return Yealink audio only SIP account template
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
 * Function to create the Yealink audio only (without G722) SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_yealink_audio_no_g722 ( $buffer, $parameters)
{
  // Return Yealink audio only (without G722) SIP account template
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
 * Function to create the Yealink audio and video SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_yealink_video ( $buffer, $parameters)
{
  // Return Yealink audio and video SIP account template
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
