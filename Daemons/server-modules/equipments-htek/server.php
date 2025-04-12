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
 * VoIP Domain Htek equipments models configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Htek
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_model_uc902sp_configure", "equipment_model_htek_basic_configure");
framework_add_hook ( "equipment_model_uc902sp_firmware_add", "equipment_model_htek_add_firmware");
framework_add_hook ( "equipment_model_uc902sp_firmware_remove", "equipment_model_htek_remove_firmware");
framework_add_hook ( "ap_type_uc902sp", "ap_type_htek_uc900");
framework_add_hook ( "ap_type_uc902sp_remove", "ap_type_htek_remove");
framework_add_hook ( "account_type_uc902sp", "account_type_htek_audio");

/**
 * Cleanup functions
 */
framework_add_hook ( "ap_htek_wipe", "ap_htek_wipe");
cleanup_register ( "Accounts-Htek", "ap_htek_wipe", array ( "Before" => array ( "Accounts")));

framework_add_hook ( "firmware_htek_wipe", "firmware_htek_wipe");
cleanup_register ( "Firmwares-Htek", "firmware_htek_wipe", array ( "Before" => array ( "Equipments")));

/**
 * Function to wipe the Htek equipment models auto provisioning files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function ap_htek_wipe ( $buffer, $parameters)
{
  // Remove auto provisioning configuration files:
  foreach ( list_config ( "datafile", "ap") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( equipment_model_exists ( $data["Type"], "Htek"))
    {
      unlink_config ( "ap", "cfg" . $data["MAC"] . ".xml");
      unlink_config ( "datafile", $filename);
    }
  }

  return $buffer;
}

/**
 * Function to wipe the Htek equipment models firmware files.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function firmware_htek_wipe ( $buffer, $parameters)
{
  // Htek UC900 series
  unlink_config ( "ap", "Htek/UC900/fw910M.rom");
  unlink_config ( "apdir", "Htek/UC900");

  // Htek vendor directory
  unlink_config ( "apdir", "Htek");

  return $buffer;
}

/**
 * Function to configure the basic Htek equipment models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_htek_basic_configure ( $buffer, $parameters)
{
  // Process audio codecs array
  $codecs = array ();
  foreach ( $parameters["AudioCodecs"] as $codec)
  {
    switch ( $codec)
    {
      case "ULAW":
        $codecs[] = "ulaw";
        break;
      case "ALAW":
        $codecs[] = "alaw";
        break;
      case "G722":
        $codecs[] = "g722";
        break;
      case "ILBC":
        $codecs[] = "iLBC";
        break;
      case "OPUS":
        $codecs[] = "Opus";
        break;
      case "G726":
        $codecs[] = "g726";
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
 * Function to add a Htek firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_htek_add_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "fw910M.rom":	// Htek UC900 series
      if ( ! is_dir ( $_in["general"]["tftpdir"] . "/Htek/UC900") && ! mkdir ( $_in["general"]["tftpdir"] . "/Htek/UC900", 0777, true))
      {
        return array ( "code" => 500, "message" => "Error creating directory structure.");
      }
      if ( file_put_contents ( $_in["general"]["tftpdir"] . "/Htek/UC900/" . $parameters["Filename"], $parameters["Data"]) === false)
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
 * Function to remove a Htek firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipment_model_htek_remove_firmware ( $buffer, $parameters)
{
  global $_in;

  // Process the file based on the filename:
  switch ( $parameters["Filename"])
  {
    case "fw910M.rom":	// Htek UC900 series
      if ( ! check_config ( "datafile", "uc902sp"))
      {
        @unlink ( $_in["general"]["tftpdir"] . "/Htek/UC900/" . $parameters["Filename"]);
        @rmdir ( $_in["general"]["tftpdir"] . "/Htek/UC900");
      }

      return array ( "code" => 200, "message" => "Firmware file removed.");
      break;
  }

  // If reached here, firmware file was not recognized!
  return array ( "code" => 400, "message" => "Invalid firmware file!");
}

/**
 * Function to create the Htek UC900 series auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_htek_uc900 ( $buffer, $parameters)
{
  global $_in;

  // Create AP file content from template:
  switch ( $parameters["Type"])
  {
    case "uc902sp":
      $content = file_get_contents ( dirname ( __FILE__) . "/template-htek-uc902sp.xml");
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
  $codecs = array (
    "ULAW" => 0,
    "G726" => 2,
    "G723" => 4,
    "ALAW" => 8,
    "G722" => 9,
    "G729" => 18,
    "ILBC" => 20,
    "OPUS" => 120
  );
  $codecorder = 0;
  foreach ( $equipmentconfig["AudioCodecs"] as $codec)
  {
    $codecorder++;
    if ( $codecorder <= 6)
    {
      $content = str_replace ( "{{{codecconfig" . $codecorder . "}}}", $codecs[$codec], $content);
    } else {
      writeLog ( "ap_add: Ignoring CODEC \"" . $codec . "\" due to hardware codec prefference limit. Htek UC902SP supports up to 6 codec's.", VoIP_LOG_WARNING);
    }
  }
  if ( $codecorder < 6)
  {
    for ( ; $codecorder <= 6; $codecorder++)
    {
      $content = str_replace ( "{{{codecconfig" . $codecorder . "}}}", 255, $content);
    }
  }

  // Set phone language:
  $language = "";
  switch ( $parameters["Language"])
  {
    case "pt_BR":
      $language = 4;
      break;
    case "en_US":
      $language = 0;
      break;
  }
  if ( empty ( $language))
  {
    switch ( $parameters["Country"])
    {
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
        $language = 1;
        break;

      // German:
      case "DE": // Germany
      case "BE": // Belgium
      case "AT": // Austria
      case "CH": // Switzerland
      case "LU": // Luxembourg
      case "LI": // Liechtenstein
        $language = 2;
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
        $language = 3;
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
        $language = 4;
        break;

      // Russian
      case "BY": // Belarus
      case "RU": // Russian Federation
        $language = 5;
        break;

      // Italian:
      case "IT": // Italy
      case "SM": // San Marino
        $language = 6;
        break;

      // Polish:
      case "PL": // Poland
        $language = 7;
        break;

      // Turkish:
      case "TR": // Turkey
        $language = 8;
        break;

      // Serbian:
      case "RS": // Serbia
      case "BA": // Bosnia and Herzegovina
        $language = 9;
        break;

      // Chinese Simplified:
      case "CN": // China
      case "TW": // Taiwan
      case "SG": // Singapore
        $language = 10;
        break;

      // Chinese Traditional:
      case "HK": // Hong Kong
      case "MO": // Macao
        $language = 11;
        break;

      // Slovenian:
      case "SI": // Slovenia
        $language = 13;
        break;

      // Farsi:
      case "IR": // Iran (Islamic Republic of)
      case "AF": // Afghanistan
      case "TJ": // Tajikistan
        $language = 14;
        break;

      // Hebrew:
      case "IL": // Israel
        $language = 15;
        break;

      // Slovak:
      case "SK": // Slovakia
        $language = 16;
        break;

      // Czech:
      case "CZ": // Czech Republic
        $language = 17;
        break;

      // Japanese:
      case "JP": // Japan
      case "PW": // Palau
        $language = 18;
        break;

      // Dutch:
      case "NL": // Netherlands
      case "AW": // Aruba
      case "BQ": // Bonaire, Sint Eustatius and Saba
      case "CW": // Curaçao
      case "SX": // Sint Maarten (Dutch part)
      case "BE": // Belgium
      case "SR": // Suriname
        $language = 19;
        break;

      // English (default):
      default:
        $language = 0;
        break;
    }
  }
  $content = str_replace ( "{{{language}}}", $language, $content);

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
  $content = str_replace ( "{{{dialplan}}}", "{" . substr ( $dialplan, 1) . "}", $content);

  // Configure BLF's:
  $functionkeys = array (
    1 => array ( "Type" => 0, "Value" => "", "Label" => "", "Extension" => ""),
    2 => array ( "Type" => 0, "Value" => "", "Label" => "", "Extension" => ""),
    3 => array ( "Type" => 0, "Value" => "", "Label" => "", "Extension" => ""),
    4 => array ( "Type" => 0, "Value" => "", "Label" => "", "Extension" => "")
  );
  $x = 1;
  foreach ( $parameters["Hints"] as $number)
  {
    if ( ! $extension = read_config ( "datafile", "extension-" . $number))
    {
      return array ( "code" => 500, "message" => "Error writing file.");
      break;
    }
    if ( $x > 2)
    {
      writeLog ( "ap_add: Ignoring BLF \"" . $number . "\" (" . $extension["Name"] . ") due to hardware BLF limit. HTEK UC902SP supports up to 2 BLF's.", VoIP_LOG_WARNING);
      continue;
    }
    $functionkeys[$x] = array ( "Type" => 3, "Value" => $number, "Label" => $extension["Name"], "Extension" => "*8" . $number);
    $x++;
  }
  foreach ( $functionkeys as $id => $entry)
  {
    if ( $id <= 2)
    {
      $content = str_replace ( "{{{functionkey" . $id . "type}}}", $entry["Type"], $content);
      $content = str_replace ( "{{{functionkey" . $id . "value}}}", $entry["Value"], $content);
      $content = str_replace ( "{{{functionkey" . $id . "label}}}", $entry["Label"], $content);
      $content = str_replace ( "{{{functionkey" . $id . "extension}}}", $entry["Extension"], $content);
    }
  }

  // Set NTP servers and time offset:
  switch ( $parameters["TimeZone"])
  {
    case "Pacific/Midway":
    case "Pacific/Pago_Pago":
    case "Pacific/Niue":
      $timezone = 105;	// -11:00 Samoa
      break;
    case "Pacific/Honolulu":
    case "Pacific/Johnston":
    case "Pacific/Rarotonga":
    case "Pacific/Tahiti":
    case "Pacific/Marquesas":
      $timezone = 1;	// -10:00 United States-Hawaii-Aleutian
      break;
    case "America/Adak":
      $timezone = 2;	// -10:00 United States-Alaska-Aleutian
      break;
    case "America/Anchorage":
    case "America/Juneau":
    case "America/Nome":
    case "America/Sitka":
    case "America/Yakutat":
    case "Pacific/Gambier":
      $timezone = 3;	// -09:00 United States-Alaska Time
      break;
    case "America/Dawson":
    case "America/Vancouver":
    case "America/Whitehorse":
      $timezone = 4;	// -08:00 Canada(Vancouver,Whitehorse)
      break;
    case "America/Tijuana":
      $timezone = 5;	// -08:00 Mexico(Tijuana,Mexicali)
      break;
    case "America/Los_Angeles":
    case "America/Metlakatla":
    case "America/Santa_Isabel":
    case "Pacific/Pitcairn":
      $timezone = 6;	// -08:00 United States-Pacific Time
      break;
    case "America/Cambridge_Bay":
    case "America/Creston":
    case "America/Dawson_Creek":
    case "America/Edmonton":
    case "America/Inuvik":
    case "America/Yellowknife":
      $timezone = 7;	// -07:00 Canada(Edmonton,Calgary)
      break;
    case "America/Chihuahua":
    case "America/Hermosillo":
    case "America/Mazatlan":
    case "America/Ojinaga":
      $timezone = 8;	 // -07:00 Mexico(Mazatlan,Chihuahua)
      break;
    case "America/Boise":
    case "America/Denver":
      $timezone = 9;	// -07:00 United States-Mountain Time
      break;
    case "America/Phoenix":
      $timezone = 10;	// -07:00 United States-MST no DST
      break;
    case "America/Rainy_River":
    case "America/Rankin_Inlet":
    case "America/Regina":
    case "America/Resolute":
    case "America/Swift_Current":
    case "America/Winnipeg":
      $timezone = 11;	// -06:00 Canada-Manitoba(Winnipeg)
      break;
    case "Pacific/Easter":
      $timezone = 12;	// -06:00 Chile(Easter Islands)
      break;
    case "America/Bahia_Banderas":
    case "America/Cancun":
    case "America/Matamoros":
    case "America/Merida":
    case "America/Mexico_City":
    case "America/Monterrey":
      $timezone = 13;	// -06:00 Mexico(Mexico City,Acapulco)
      break;
    case "America/Chicago":
    case "America/Indiana/Knox":
    case "America/Indiana/Tell_City":
    case "America/Menominee":
    case "America/North_Dakota/Beulah":
    case "America/North_Dakota/Center":
    case "America/North_Dakota/New_Salem":
    case "Pacific/Galapagos":
      $timezone = 14;	// -06:00 United States-Central Time
      break;
    case "America/Guatemala":
      $timezone = 107;	// -06:00 Guatemala
      break;
    case "America/El_Salvador":
      $timezone = 108;	// -06:00 El Salvador
      break;
    case "America/Tegucigalpa":
      $timezone = 109;	// -06:00 Honduras
      break;
    case "America/Managua":
      $timezone = 110;	// -06:00 Nicaragua
      break;
    case "America/Costa_Rica":
      $timezone = 111;	// -06:00 Cost Rica
      break;
    case "America/Belize":
      $timezone = 112;	// -06:00 Belize
      break;
    case "America/Nassau":
      $timezone = 15;	// -05:00 Bahamas(Nassau)
      break;
    case "America/Atikokan":
    case "America/Coral_Harbour":
    case "America/Iqaluit":
    case "America/Montreal":
    case "America/Nipigon":
    case "America/Pangnirtung":
    case "America/Thunder_Bay":
    case "America/Toronto":
      $timezone = 16;	// -05:00 Canada(Montreal,Ottawa,Quebec)
      break;
    case "America/Havana":
      $timezone = 17;	// -05:00 Cuba(Havana)
      break;
    case "America/Cayman":
    case "America/Detroit":
    case "America/Jamaica":
    case "America/Kentucky/Louisville":
    case "America/Kentucky/Monticello":
    case "America/New_York":
    case "America/Panama":
    case "America/Port-au-Prince":
    case "America/Indiana/Indianapolis":
    case "America/Indiana/Marengo":
    case "America/Indiana/Petersburg":
    case "America/Indiana/Vevay":
    case "America/Indiana/Vincennes":
    case "America/Indiana/Winamac":
    case "Pacific/Easter":
      $timezone = 18;	// -05:00 United States-Eastern Time
      break;
    case "America/Lima":
    case "America/Bogota":
    case "America/Guayaquil":
      $timezone = 113;	// -05:00 Peru
      break;
    case "America/Caracas":
      $timezone = 19;	// -04:30 Venezuela(Caracas)
      break;
    case "America/Blanc-Sablon":
    case "America/Glace_Bay":
    case "America/Goose_Bay":
    case "America/Halifax":
    case "America/Moncton":
      $timezone = 20;	// -04:00 Canada(Halifax,Saint John)
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
    case "America/Boa_Vista":
    case "America/Eirunepe":
    case "America/Manaus":
    case "America/Porto_Velho":
    case "America/Rio_Branco":
    case "America/Puerto_Rico":
      $timezone = 21;	// -04:00 Chile(Santiago)
      break;
    case "America/Asuncion":
      $timezone = 22;	// -04:00 Paraguay(Asuncion)
      break;
    case "Atlantic/Bermuda":
      $timezone = 23;	// -04:00 United Kingdom-Bermuda(Bermuda)
      break;
    case "Atlantic/Stanley":
      $timezone = 24;	// -04:00 United Kingdom(Falkland Islands)
      break;
    case "America/Port_of_Spain":
      $timezone = 25;	// -04:00 Trinidad & Tobago
      break;
    case "America/St_Johns":
      $timezone = 26;	// -03:30 Canada-New Foundland(St.Johns)
      break;
    case "America/Godthab":
      $timezone = 27;	// -03:00 Denmark-Greenland(Nuuk)
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
      $timezone = 28;	// -03:00 Argentina(Buenos Aires)
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
      $timezone = 29;	// -03:00 Brazil(no DST)
      break;
    case "America/Bahia":
    case "America/Montevideo":
    case "America/Noronha":
    case "Atlantic/South_Georgia":
      $timezone = 31;	// -02:00 Brazil(no DST)
      break;
    case "America/Scoresbysund":
    case "Atlantic/Azores":
    case "Atlantic/Cape_Verde":
      $timezone = 32;	// -01:00 Portugal(Azores)
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
      $timezone = 33;	// +00:00 GMT
      break;
    case "America/Danmarkshavn":
      $timezone = 34;	// +00:00 Greenland
      break;
    case "Atlantic/Faroe":
    case "Atlantic/Reykjavik":
      $timezone = 35;	// +00:00 Denmark-Faroe Islands(Torshaven)
      break;
    case "Europe/Dublin":
      $timezone = 36;	// +00:00 Ireland(Dublin)
      break;
    case "Atlantic/Madeira":
    case "Europe/Lisbon":
      $timezone = 37;	// +00:00 Portugal(Lisboa,Porto,Funchal)
      break;
    case "Atlantic/Canary":
      $timezone = 38;	// +00:00 Spain-Canary Islands(Las Palmas)
      break;
    case "Europe/London":
      $timezone = 39;	// +00:00 United Kingdom(London)
      break;
    case "Africa/Casablanca":
    case "Africa/Monrovia":
      $timezone = 40;	// +00:00 Morocco
      break;
    case "Europe/Tirane":
      $timezone = 41;	// +01:00 Albania(Tirane)
      break;
    case "Europe/Vienna":
      $timezone = 42;	// +01:00 Austria(Vienna)
      break;
    case "Europe/Brussels":
      $timezone = 43;	// +01:00 Belgium(Brussels)
      break;
    case "America/Grand_Turk":
      $timezone = 44;	// +01:00 Caicos
      break;
    case "Europe/Zagreb":
      $timezone = 46;	// +01:00 Croatia(Zagreb)
      break;
    case "Europe/Prague":
      $timezone = 47;	// +01:00 Czech Republic(Prague)
      break;
    case "Europe/Copenhagen":
    case "Europe/Andorra":
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
    case "Europe/Zurich":
      $timezone = 48;	// +01:00 Denmark(Kopenhaven)
      break;
    case "Europe/Paris":
      $timezone = 49;	// +01:00 France(Paris)
      break;
    case "Europe/Berlin":
      $timezone = 50;	// +01:00 Germany(Berlin)
      break;
    case "Europe/Budapest":
      $timezone = 51;	// +01:00 Hungary(Budapest)
      break;
    case "Europe/Rome":
      $timezone = 52;	// +01:00 Italy(Rome)
      break;
    case "Europe/Luxembourg":
      $timezone = 53;	// +01:00 Luxembourg(Luxembourg)
      break;
    case "Europe/Amsterdam":
      $timezone = 54;	// +01:00 Netherlands(Amsterdam)
      break;
    case "Europe/Warsaw":
    case "Europe/Skopje":
      $timezone = 55;	// +01:00 Poland(Warsaw)
      break;
    case "Europe/Belgrade":
      $timezone = 56;	// +01:00 Serbia(Belgrade)
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
    case "Africa/Ceuta":
    case "Europe/Madrid":
    case "Arctic/Longyearbyen":
      $timezone = 114;	// +01:00 Spain(Madrid)
      break;
    case "Europe/Tallinn":
      $timezone = 57;	// +02:00 Estonia(Tallinn)
      break;
    case "Europe/Helsinki":
      $timezone = 58;	// +02:00 Finland(Helsinki)
      break;
    case "Asia/Gaza":
    case "Asia/Hebron":
      $timezone = 59;	// +02:00 Gaza Strip(Gaza)
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
      $timezone = 106;	// +02:00 Greece(Athens)
      break;
    case "Asia/Jerusalem":
      $timezone = 61;	// +02:00 Israel(Tel Aviv)
      break;
    case "Asia/Amman":
    case "Asia/Nicosia":
      $timezone = 62;	// +02:00 Jordan(Amman)
      break;
    case "Europe/Riga":
      $timezone = 63;	// +02:00 Latvia(Riga)
      break;
    case "Asia/Beirut":
      $timezone = 64;	// +02:00 Lebanon(Beirut)
      break;
    case "Europe/Chisinau":
      $timezone = 65;	// +02:00 Moldova(Kishinev)
      break;
    case "Europe/Simferopol":
      $timezone = 66;	// +02:00 Russia(Kaliningrad)
      break;
    case "Europe/Bucharest":
      $timezone = 67;	// +02:00 Romania(Bucharest)
      break;
    case "Africa/Harare":
    case "Africa/Tripoli":
    case "Africa/Cairo":
    case "Asia/Damascus":
      $timezone = 68;	// +02:00 South Africa(Harare, Pretoria),Syria(Damascus)
      break;
    case "Europe/Sofia":
    case "Europe/Vilnius":
    case "Europe/Mariehamn":
    case "Europe/Kiev":
    case "Europe/Uzhgorod":
    case "Europe/Zaporozhye":
      $timezone = 70;	// +02:00 Ukraine(Kyiv,Odessa)
      break;
    case "Europe/Istanbul":
      $timezone = 69;	// +03:00 Turkey(Ankara)
    case "Europe/Minsk":
    case "Africa/Addis_Ababa":
    case "Africa/Asmara":
    case "Africa/Dar_es_Salaam":
    case "Africa/Djibouti":
    case "Africa/Juba":
    case "Africa/Kampala":
    case "Africa/Khartoum":
    case "Africa/Mogadishu":
    case "Africa/Nairobi":
      $timezone = 71;	// +03:00 Belarus(Minsk),East Africa Time
      break;
    case "Asia/Riyadh":
    case "Asia/Aden":
    case "Asia/Bahrain":
    case "Asia/Kuwait":
    case "Asia/Qatar":
    case "Indian/Antananarivo":
    case "Indian/Comoro":
    case "Indian/Mayotte":
    case "Asia/Baghdad":
      $timezone = 72;	// +03:00 Bahrain,Kuwait,Iraq(Baghdad)
      break;
    case "Europe/Kaliningrad":
      $timezone = 73;	// +03:00 Russia(Moscow)
      break;
    case "Asia/Tehran":
      $timezone = 74;	// +03:30 Iran(Tehran)
      break;
    case "Asia/Yerevan":
      $timezone = 75;	// +04:00 Armenia(Yerevan)
      break;
    case "Asia/Baku":
      $timezone = 76;	// +04:00 Azerbaijan(Baku)
      break;
    case "Asia/Tbilisi":
    case "Asia/Kabul":
    case "Asia/Dubai":
    case "Asia/Muscat":
    case "Indian/Mahe":
    case "Indian/Mauritius":
    case "Indian/Reunion":
      $timezone = 77;	// +04:00 Georgia(Tbilisi)
      break;
    case "Asia/Aqtau":
      $timezone = 78;	// +04:00 Kazakstan(Aqtau)
      break;
    case "Europe/Samara":
    case "Europe/Moscow":
    case "Europe/Volgograd":
      $timezone = 79;	// +04:00 Russia(Samara)
      break;
    case "Asia/Aqtobe":
    case "Asia/Oral":
      $timezone = 80;	// +05:00 Kazakstan(Aqtobe)
      break;
    case "Asia/Bishkek":
      $timezone = 81;	// +05:00 Kyrgyzstan(Bishkek)
      break;
    case "Asia/Karachi":
    case "Asia/Tashkent":
    case "Asia/Samarkand":
    case "Asia/Ashgabat":
    case "Asia/Dushanbe":
    case "Indian/Kerguelen":
    case "Indian/Maldives":
      $timezone = 82;	// +05:00 Pakistan(Islamabad)
      break;
    case "Asia/Yekaterinburg":
      $timezone = 83;	// +05:00 Russia(Yekaterinburg)
      break;
    case "Asia/Colombo":
    case "Asia/Kolkata":
      $timezone = 84;	// +05:30 India(Calcutta)
      break;
    case "Asia/Kathmandu":
    case "Asia/Dhaka":
    case "Asia/Almaty":
    case "Asia/Qyzylorda":
    case "Asia/Bishkek":
    case "Asia/Kashgar":
    case "Asia/Thimphu":
    case "Asia/Urumqi":
    case "Indian/Chagos":
      $timezone = 85;	// +06:00 Kazakstan(Astana,Almaty)
      break;
    case "Asia/Yekaterinburg":
      $timezone = 86;	// +06:00 Russia(Novosibirsk,Omsk)
      break;
    case "Indian/Cocos":
    case "Asia/Rangoon":
      $timezone = 115;	// +06:30 Myanmar(Naypyitaw),Yangon(Rangoon)
      break;
    case "Asia/Novokuznetsk":
    case "Asia/Novosibirsk":
    case "Asia/Omsk":
      $timezone = 87;	// +07:00 Russia(Krasnoyarsk)
      break;
    case "Asia/Jakarta":
    case "Asia/Pontianak":
    case "Asia/Ho_Chi_Minh":
    case "Asia/Bangkok":
    case "Asia/Hovd":
    case "Asia/Phnom_Penh":
    case "Asia/Vientiane":
    case "Indian/Christmas":
      $timezone = 88;	// +07:00 Thailand(Bangkok)
      break;
    case "Asia/Chongqing":
    case "Asia/Harbin":
    case "Asia/Shanghai":
    case "Asia/Krasnoyarsk":
      $timezone = 89;	// +08:00 China(Beijing),Russia(Irkutsk,Buryatia)
      break;
    case "Asia/Singapore":
    case "Asia/Brunei":
    case "Asia/Macau":
    case "Asia/Makassar":
    case "Asia/Manila":
    case "Asia/Hong_Kong":
    case "Asia/Taipei":
      $timezone = 90;	// +08:00 Singapore(Singapore)
      break;
    case "Australia/Perth":
    case "Australia/Eucla":
    case "Asia/Kuala_Lumpur":
    case "Asia/Kuching":
    case "Asia/Choibalsan":
    case "Asia/Ulaanbaatar":
      $timezone = 91;	// +08:00 Australia(Perth)
      break;
    case "Asia/Seoul":
    case "Asia/Pyongyang":
    case "Asia/Dili":
    case "Asia/Jayapura":
    case "Asia/Yakutsk":
    case "Asia/Irkutsk":
    case "Pacific/Palau":
      $timezone = 92;	// +09:00 Korea(Seoul),Russia(Yakutsk)
      break;
    case "Asia/Tokyo":
      $timezone = 93;	// +09:00 Japan(Tokyo)
      break;
    case "Australia/Adelaide":
      $timezone = 94;	// +09:30 Australia(Adelaide)
      break;
    case "Australia/Darwin":
      $timezone = 95;	// +09:30 Australia(Darwin)
      break;
    case "Australia/Melbourne":
    case "Australia/Sydney":
    case "Australia/Lindeman":
    case "Australia/Broken_Hill":
    case "Australia/Currie":
    case "Pacific/Chuuk":
    case "Pacific/Saipan":
      $timezone = 96;	// +10:00 Australia(Sydney,Melbourne,Canberra)
      break;
    case "Australia/Brisbane":
      $timezone = 97;	// +10:00 Australia(Brisbane)
      break;
    case "Australia/Hobart":
    case "Pacific/Guam":
    case "Pacific/Port_Moresby":
      $timezone = 98;	// +10:00 Australia(Hobart)
      break;
    case "Asia/Yakutsk":
      $timezone = 99;	// +10:00 Russia(Vladivostok)
      break;
    case "Australia/Lord_Howe":
      $timezone = 100;	// +10:30 Australia(Lord Howe Islands)
      break;
    case "Pacific/Noumea":
    case "Pacific/Efate":
    case "Pacific/Guadalcanal":
    case "Pacific/Kosrae":
    case "Pacific/Pohnpei":
    case "Pacific/Norfolk":
    case "Asia/Sakhalin":
    case "Asia/Vladivostok":
    case "Antarctica/Macquarie":
      $timezone = 101;	// +11:00 Russia(Magadan,Sakhalin),New Caledonia(Noumea)
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
      $timezone = 102;	// +12:00 Russia(Kamchatka,Chukotka),New Zeland(Wellington,Auckland)
      break;
    case "Pacific/Chatham":
      $timezone = 103;	// +12:45 New Zeland(Chatham Islands)
      break;
    case "Pacific/Auckland":
    case "Pacific/Enderbury":
    case "Pacific/Fakaofo":
    case "Pacific/Fiji":
    case "Pacific/Tongatapu":
      $timezone = 104;	// +13:00 Tonga(Nukualofa)
      break;
    default:
      $timezone = 18;	// -05:00 United States-Eastern Time
      break;
  }
  $content = str_replace ( "{{{timezone}}}", $timezone, $content);
  $content = str_replace ( "{{{ntpserver1}}}", $parameters["NTP"][0], $content);
  $content = str_replace ( "{{{ntpserver2}}}", $parameters["NTP"][1], $content);

  // Set dialtones:
  switch ( $parameters["Country"])
  {
    case "AU": // Australia
      $dialtone = 1;
      break;
    case "AT": // Austria
      $dialtone = 2;
      break;
    case "BR": // Brazil
      $dialtone = 3;
      break;
    case "BE": // Belgium
      $dialtone = 4;
      break;
    case "CN": // China
      $dialtone = 5;
      break;
    case "CL": // Chile
      $dialtone = 6;
      break;
    case "CZ": // Czech Republic
      $dialtone = 7;
      break;
    case "DK": // Denmark
      $dialtone = 8;
      break;
    case "FI": // Finland
      $dialtone = 9;
      break;
    case "FR": // France
      $dialtone = 10;
      break;
    case "DE": // Germany
      $dialtone = 11;
      break;
    case "GB": // Great Britain
      $dialtone = 12;
      break;
    case "GR": // Greece
      $dialtone = 13;
      break;
    case "HU": // Hungary
      $dialtone = 14;
      break;
    case "LT": // Lithuania
      $dialtone = 15;
      break;
    case "IN": // India
      $dialtone = 16;
      break;
    case "IT": // Italy
      $dialtone = 17;
      break;
    case "JP": // Japan
      $dialtone = 18;
      break;
    case "MX": // Mexico
      $dialtone = 19;
      break;
    case "NZ": // New Zealand
      $dialtone = 20;
      break;
    case "NL": // Netherlands
      $dialtone = 21;
      break;
    case "NO": // Norway
      $dialtone = 22;
      break;
    case "PT": // Portugal
      $dialtone = 23;
      break;
    case "ES": // Spain
      $dialtone = 24;
      break;
    case "CH": // Switzerland
      $dialtone = 25;
      break;
    case "SE": // Sweden
      $dialtone = 26;
      break;
    case "RU": // Russian Federation
      $dialtone = 27;
      break;
    case "US": // United States of America
      $dialtone = 28;
      break;
    default:
      $dialtone = 28;
      break;
  }
  $content = str_replace ( "{{{dialtone}}}", $dialtone, $content);

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
      $dateformat = 2; // DD-MM-YYYY
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
      $dateformat = 1; // MM-DD-YYYY
      $timeformat = 0; // 24h
      break;
    case "US": // United States of America
    case "AS": // American Samoa
    case "CA": // Canada
    case "AU": // Australia
    case "PH": // Philippines
    case "PR": // Puerto Rico
    case "UM": // United States Minor Outlying Islands
      $dateformat = 1; // MM-DD-YYYY
      $timeformat = 1; // 12h
      break;
    case "ID": // Indonesia
      $dateformat = 1; // MM-DD-YYYY
      $timeformat = 0; // 24h
      break;
    default:
      $dateformat = 1; // MM-DD-YYYY
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

  // Set TFTP server address:
  $content = str_replace ( "{{{tftpserver}}}", $_in["general"]["address"], $content);

  // Write auto provision file
  if ( file_put_contents ( $_in["general"]["tftpdir"] . "/cfg" . strtolower ( $parameters["MAC"]) . ".xml", $content) === false)
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Notify extension to reconfigure:
  asterisk_exec ( "pjsip send notify htek-check-cfg endpoint " . $parameters["Username"]);

  // Return OK
  return array ( "code" => 200, "message" => "OK, AP created.");
}

/**
 * Function to remove the Htek auto provision file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ap_type_htek_remove ( $buffer, $parameters)
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
 * Function to create the Htek audio only SIP account file content.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function account_type_htek_audio ( $buffer, $parameters)
{
  // Return Htek audio only SIP account template
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
