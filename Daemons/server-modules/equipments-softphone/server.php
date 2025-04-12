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
 * VoIP Domain Softphone/Webphone models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments VoIP Domain
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_model_softphone_configure", "equipment_model_softphone_configure");
framework_add_hook ( "account_type_softphone", "account_type_softphone");
framework_add_hook ( "equipment_model_webphone_configure", "equipment_model_webphone_configure");
framework_add_hook ( "account_type_webphone", "account_type_webphone");

/**
 * Function to configure the webphone equipment model.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_webphone_configure ( $buffer, $parameters)
{
  // Process audio codecs array
  $codecs = array ();
  foreach ( $parameters["AudioCodecs"] as $codec)
  {
    switch ( $codec)
    {
      case "OPUS":
        $codecs[] = "opus";
        break;
      case "ALAW":
        $codecs[] = "alaw";
        break;
      case "ULAW":
        $codecs[] = "ulaw";
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
        $codecs[] = "h264";
        break;
      default:
        writeLog ( "Invalid video codec \"" . $codec . "\" to model \"" . $parameters["UID"] . "\"!", VoIP_LOG_WARNING);
        break;
    }
  }

  // Return webphone SIP model template
  return "[vd_equipment_" . $parameters["UID"] . "]\n" .
         "allow=!all," . implode ( ",", $codecs) . "\n" . $buffer;
}

/**
 * Function to configure the softphone equipment model.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_softphone_configure ( $buffer, $parameters)
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
      case "G726":
        $codecs[] = "g726";
        break;
      case "EVS":
        $codecs[] = "evs";
        break;
      case "SPEEX":
        $codecs[] = "speex";
        break;
      case "SPEEX16":
        $codecs[] = "speex16";
        break;
      case "SPEEX32":
        $codecs[] = "speex32";
        break;
      case "SIREN7":
        $codecs[] = "siren7";
        break;
      case "SIREN14":
        $codecs[] = "siren14";
        break;
      case "ADPCM":
        $codecs[] = "adpcm";
        break;
      case "SILK8":
        $codecs[] = "silk8";
        break;
      case "SILK12":
        $codecs[] = "silk12";
        break;
      case "SILK16":
        $codecs[] = "silk16";
        break;
      case "SILK24":
        $codecs[] = "silk24";
        break;
      case "G719":
        $codecs[] = "g719";
        break;
      case "G729":
      case "G729A":
        $codecs[] = "g729";
        break;
      case "SLIN":
        $codecs[] = "slin";
        break;
      case "SLIN12":
        $codecs[] = "slin12";
        break;
      case "SLIN16":
        $codecs[] = "slin16";
        break;
      case "SLIN24":
        $codecs[] = "slin24";
        break;
      case "SLIN32":
        $codecs[] = "slin32";
        break;
      case "SLIN44":
        $codecs[] = "slin44";
        break;
      case "SLIN48":
        $codecs[] = "slin48";
        break;
      case "SLIN96":
        $codecs[] = "slin96";
        break;
      case "SLIN192":
        $codecs[] = "slin192";
        break;
      case "LPC10":
        $codecs[] = "lpc10";
        break;
      case "AMR":
        $codecs[] = "amr";
        break;
      case "AMRWB":
        $codecs[] = "amrwb";
        break;
      case "GSM":
        $codecs[] = "gsm";
        break;
      case "ILBC":
        $codecs[] = "ilbc";
        break;
      case "OPUS":
        $codecs[] = "opus";
        break;
      case "G726AAL2":
        $codecs[] = "g726aal2";
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
      case "H261":
        $codecs[] = "h261";
        break;
      case "H263":
        $codecs[] = "h263";
        break;
      case "H265":
        $codecs[] = "h265";
        break;
      case "H263P":
        $codecs[] = "h263p";
        break;
      case "H264":
        $codecs[] = "h264";
        break;
      case "VP9":
        $codecs[] = "vp9";
        break;
      case "VP8":
        $codecs[] = "vp8";
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
 * Function to create the softphone SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_softphone ( $buffer, $parameters)
{
  // Return softphone SIP account template
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
 * Function to create the webphone SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_webphone ( $buffer, $parameters)
{
  // Return webphone SIP account template
  return "[" . $parameters["Username"] . "](vd_extension_" . $parameters["Number"] . ",vd_equipment_" . $parameters["Type"] . ")\n" .
         "aors=" . $parameters["Username"] . "\n" .
         "auth=" . $parameters["Username"] . "\n" .
         "dtls_auto_generate_cert=yes\n" .
         "webrtc=yes\n" .
         "\n" .
         "[" . $parameters["Username"] . "]\n" .
         "type=aor\n" .
         "max_contacts=1\n" .
         "remove_existing=yes\n" .
         "\n" .
         "[" . $parameters["Username"] . "]\n" .
         "type=auth\n" .
         "auth_type=userpass\n" .
         "password=" . $parameters["Password"] . "\n" .
         "username=" . $parameters["Username"] . "\n";
}
?>
