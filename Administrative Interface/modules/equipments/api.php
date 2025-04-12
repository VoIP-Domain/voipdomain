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
 * VoIP Domain equipments module API. This module add the API calls related to
 * equipments.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search equipments
 */
framework_add_hook (
  "equipments_search",
  "equipments_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all equipments."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,UID,Vendor,Model,Active,Type",
          "example" => "Vendor,Model"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system equipments."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "equipment"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The equipment internal system unique identifier."),
                "example" => 1
              ),
              "UID" => array (
                "type" => "string",
                "description" => __ ( "The equipment unique identifier string."),
                "example" => "t20p"
              ),
              "Vendor" => array (
                "type" => "string",
                "description" => __ ( "The equipment vendor."),
                "example" => "Yealink"
              ),
              "Model" => array (
                "type" => "string",
                "description" => __ ( "The equipment model."),
                "example" => "T20P"
              ),
              "Type" => array (
                "type" => "string",
                "enum" => array ( "IPP", "IPVP", "IPM", "IPCP", "ATA"),
                "description" => __ ( "The equipment type."),
                "example" => "IPP"
              ),
              "Active" => array (
                "type" => "boolean",
                "description" => __ ( "If equipment is active."),
                "example" => true
              ),
              "VideoSupport" => array (
                "type" => "boolean",
                "description" => __ ( "If equipment has video support."),
                "example" => false
              ),
              "AutoProvision" => array (
                "type" => "boolean",
                "description" => __ ( "If equipment has auto provisioning support."),
                "example" => true
              ),
              "BLFSupport" => array (
                "type" => "boolean",
                "description" => __ ( "If equipment has BLF support."),
                "example" => true
              ),
              "Accounts" => array (
                "type" => "integer",
                "description" => __ ( "Number of accounts supported."),
                "example" => 2
              ),
              "Shortcuts" => array (
                "type" => "integer",
                "description" => __ ( "Number of shortcuts supported."),
                "example" => 10
              ),
              "Extensions" => array (
                "type" => "integer",
                "description" => __ ( "Number of extensions supported."),
                "example" => 2
              ),
              "ShortcutsPerExtension" => array (
                "type" => "integer",
                "description" => __ ( "Number of shortcuts supported per extension."),
                "example" => 5
              ),
              "SupportLevel" => array (
                "type" => "string",
                "enum" => array ( "None", "Basic", "Complete", "Premium"),
                "description" => __ ( "The level of support to the equipment."),
                "example" => "Premium"
              )
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Filter" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid filter content.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "equipments_search", __ ( "Search equipments"));
framework_add_api_call (
  "/equipments",
  "Read",
  "equipments_search",
  array (
    "permissions" => array ( "user", "equipments_search"),
    "title" => __ ( "Search equipments"),
    "description" => __ ( "Search for system equipments.")
  )
);

/**
 * Function to search equipments.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "equipments_search_start"))
  {
    $parameters = framework_call ( "equipments_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Equipments");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "equipments_search_validate"))
  {
    $data = framework_call ( "equipments_search_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "equipments_search_sanitize"))
  {
    $parameters = framework_call ( "equipments_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "equipments_search_pre"))
  {
    $parameters = framework_call ( "equipments_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search equipments
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `UID` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Vendor` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Model` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Vendor`, `Model`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Vendor,Model", "ID,UID,Vendor,Model,Active,Type,VideoSupport,AutoProvision,BLFSupport,Accounts,Shortcuts,Extensions,ShortcutsPerExtension,SupportLevel");
  while ( $result = $results->fetch_assoc ())
  {
    $result["Active"] = (boolean) $result["Active"];
    $result["VideoSupport"] = (boolean) $result["VideoSupport"];
    $result["AutoProvision"] = (boolean) $result["AutoProvision"];
    $result["BLFSupport"] = (boolean) $result["BLFSupport"];
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "equipments_search_post"))
  {
    $data = framework_call ( "equipments_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "equipments_search_finish"))
  {
    framework_call ( "equipments_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get equipment information
 */
framework_add_hook (
  "equipments_view",
  "equipments_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the equipment."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "integer",
              "description" => __ ( "The equipment internal system unique identifier."),
              "example" => 1
            ),
            "UID" => array (
              "type" => "string",
              "description" => __ ( "The equipment unique identifier name."),
              "pattern" => "/^[0-9a-z\-]$/",
              "example" => "t20p"
            ),
            "Vendor" => array (
              "type" => "string",
              "description" => __ ( "The name of the equipment vendor."),
              "example" => "Yealink"
            ),
            "Model" => array (
              "type" => "string",
              "description" => __ ( "The name of the equipment model."),
              "example" => "T20P"
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "A more detailed description of the equipment."),
              "example" => __ ( "An IP telephone from a great vendor.")
            ),
            "Active" => array (
              "type" => "boolean",
              "description" => __ ( "If equipment is active."),
              "example" => true
            ),
            "VendorLink" => array (
              "type" => "string",
              "description" => __ ( "The URL to the vendor homepage."),
              "example" => "https://voipdomain.io/"
            ),
            "ModelLink" => array (
              "type" => "string",
              "description" => __ ( "The URL to the vendor model homepage."),
              "example" => "https://voipdomain.io/"
            ),
            "Image" => array (
              "type" => "string",
              "description" => __ ( "The name of the image file for the equipment."),
              "example" => "voipdomain-webphone.png"
            ),
            "Type" => array (
              "type" => "string",
              "enum" => array ( "IPP", "IPVP", "IPCP", "IPM", "ATA"),
              "description" => __ ( "The type of the equipment."),
              "example" => "IPP"
            ),
            "VideoSupport" => array (
              "type" => "boolean",
              "description" => __ ( "If equipment has video support."),
              "example" => false
            ),
            "AutoProvision" => array (
              "type" => "boolean",
              "description" => __ ( "If equipment has auto provisioning support."),
              "example" => true
            ),
            "BLFSupport" => array (
              "type" => "boolean",
              "description" => __ ( "If equipment has BLF support."),
              "example" => true
            ),
            "Accounts" => array (
              "type" => "integer",
              "description" => __ ( "Number of accounts supported."),
              "example" => 2
            ),
            "Shortcuts" => array (
              "type" => "integer",
              "description" => __ ( "Number of shortcuts supported."),
              "example" => 10
            ),
            "Extensions" => array (
              "type" => "integer",
              "description" => __ ( "Number of extensions supported."),
              "example" => 2
            ),
            "ShortcutsPerExtension" => array (
              "type" => "integer",
              "description" => __ ( "Number of shortcuts supported per extension."),
              "example" => 5
            ),
            "SupportedAudioCodecs" => array (
              "type" => "array",
              "description" => __ ( "An array with equipment supported audio codecs."),
              "items" => array (
                "type" => "string",
                "description" => __ ( "Supported audio codec.")
              )
            ),
            "AudioCodecs" => array (
              "type" => "array",
              "description" => __ ( "An array with allowed audio codecs."),
              "items" => array (
                "type" => "string",
                "description" => __ ( "Allowed audio codec.")
              )
            ),
            "SupportedVideoCodecs" => array (
              "type" => "array",
              "description" => __ ( "An array with equipment supported video codecs."),
              "items" => array (
                "type" => "string",
                "description" => __ ( "Supported video codec.")
              )
            ),
            "VideoCodecs" => array (
              "type" => "array",
              "description" => __ ( "An array with allowed video codecs."),
              "items" => array (
                "type" => "string",
                "description" => __ ( "Allowed video codec.")
              )
            ),
            "SupportLevel" => array (
              "type" => "string",
              "enum" => array ( "None", "Basic", "Complete", "Premium"),
              "description" => __ ( "The level of support to the equipment."),
              "example" => "Premium"
            ),
            "SupportedFirmwares" => array (
              "type" => "array",
              "description" => __ ( "The list of supported firmwares."),
              "items" => array (
                "type" => "object",
                "properties" => array (
                  "Version" => array (
                    "type" => "string",
                    "description" => __ ( "The firmware version."),
                    "example" => "1.0.2"
                  ),
                  "Priority" => array (
                    "type" => "integer",
                    "description" => __ ( "The firmware priority (less is better)."),
                    "minimum" => 0,
                    "example" => 0
                  ),
                  "Available" => array (
                    "type" => "boolean",
                    "description" => __ ( "If the firmware is available in the system."),
                    "example" => true
                  ),
                  "Files" => array (
                    "type" => "array",
                    "description" => __ ( "The list of files that this firmware version requires."),
                    "items" => array (
                      "anyOf" => array (
                        array (
                          "type" => "object",
                          "description" => __ ( "A firmware file properties."),
                          "properties" => array (
                            "Filename" => array (
                              "type" => "string",
                              "description" => __ ( "The filename."),
                              "example" => "firmware-1.0.2.rom"
                            ),
                            "SHA256" => array (
                              "type" => "string",
                              "description" => __ ( "The SHA256 sum of file content."),
                              "example" => "3cc074a609817601df1d7e2e186958fddb101b60957d95df6ddeb51c36108d7f"
                            ),
                            "Size" => array (
                              "type" => "integer",
                              "description" => __ ( "The size of the file."),
                              "example" => 8189344
                            ),
                            "Available" => array (
                              "type" => "boolean",
                              "description" => __ ( "If file is available in the system."),
                              "example" => true
                            )
                          )
                        )
                      )
                    )
                  )
                )
              )
            ),
            "ExtraSettings" => array (
              "type" => "array",
              "description" => __ ( "An array with any extra settings that equipment support."),
              "items" => array (
                "type" => "string"
              )
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid equipment ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "equipments_view", __ ( "View equipment information"));
framework_add_api_call (
  "/equipments/:ID",
  "Read",
  "equipments_view",
  array (
    "permissions" => array ( "user", "equipments_view"),
    "title" => __ ( "View equipments"),
    "description" => __ ( "Get a equipment information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The equipment internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate equipment information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "equipments_view_start"))
  {
    $parameters = framework_call ( "equipments_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Equipments");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid equipment ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "equipments_view_validate"))
  {
    $data = framework_call ( "equipments_view_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "equipments_view_sanitize"))
  {
    $parameters = framework_call ( "equipments_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "equipments_view_pre"))
  {
    $parameters = framework_call ( "equipments_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search equipments
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $equipment = $result->fetch_assoc ();

  /**
   * Format data
   */
  $equipment["Active"] = (boolean) $equipment["Active"];
  $equipment["VideoSupport"] = (boolean) $equipment["VideoSupport"];
  $equipment["AutoProvision"] = (boolean) $equipment["AutoProvision"];
  $equipment["BLFSupport"] = (boolean) $equipment["BLFSupport"];
  $equipment["SupportedAudioCodecs"] = json_decode ( $equipment["SupportedAudioCodecs"]);
  $equipment["AudioCodecs"] = json_decode ( $equipment["AudioCodecs"]);
  $equipment["SupportedVideoCodecs"] = json_decode ( $equipment["SupportedVideoCodecs"]);
  $equipment["VideoCodecs"] = json_decode ( $equipment["VideoCodecs"]);
  $equipment["SupportedFirmwares"] = json_decode ( $equipment["SupportedFirmwares"]);
  $equipment["ExtraSettings"] = json_decode ( $equipment["ExtraSettings"]);
  $data = api_filter_entry ( array ( "ID", "UID", "Vendor", "Model", "Type", "Active", "VideoSupport", "AutoProvision", "BLFSupport", "Accounts", "Shortcuts", "Extensions", "ShortcutsPerExtension", "SupportedAudioCodecs", "AudioCodecs", "SupportedVideoCodecs", "VideoCodecs", "ExtraSettings", "SupportLevel", "Description", "Image", "VendorLink", "ModelLink", "SupportedFirmwares"), $equipment);

  /**
   * Call subtype view hook if exist
   */
  if ( framework_has_hook ( "equipments_view_" . $data["UID"]))
  {
    $data = framework_call ( "equipments_view_" . $data["UID"], $parameters, false, $data);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "equipments_view_post"))
  {
    $data = framework_call ( "equipments_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "equipments_view_finish"))
  {
    framework_call ( "equipments_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to configure an existing equipment
 */
framework_add_hook (
  "equipments_configure",
  "equipments_configure",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "ID" => array (
          "type" => "integer",
          "description" => __ ( "The system unique identifier of the equipment."),
          "required" => true,
          "example" => 1
        ),
        "AudioCodecs" => array (
          "type" => "array",
          "description" => __ ( "The list of allowed audio codecs to this equipment."),
          "items" => array (
            "integer"
          )
        ),
        "VideoCodecs" => array (
          "type" => "array",
          "description" => __ ( "The list of allowed video codecs to this equipment."),
          "items" => array (
            "integer"
          )
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system equipment was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid equipment ID.")
            ),
            "AudioCodecs" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "At least one audio codec must be selected.")
            ),
            "VideoCodecs" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "At least one video codec must be selected.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "equipments_configure", __ ( "Configure equipments"));
framework_add_api_call (
  "/equipments/:ID",
  "Modify",
  "equipments_configure",
  array (
    "permissions" => array ( "user", "equipments_configure"),
    "title" => __ ( "Configure equipments"),
    "description" => __ ( "Change a equipment information.")
  )
);
framework_add_api_call (
  "/equipments/:ID",
  "Edit",
  "equipments_configure",
  array (
    "permissions" => array ( "user", "equipments_configure"),
    "title" => __ ( "Configure equipment"),
    "description" => __ ( "Change a equipment information.")
  )
);

/**
 * Function to configure an existing equipment.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_configure ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_start"))
  {
    $parameters = framework_call ( "equipments_configure_start", $parameters);
  }

  /**
   * Check if equipment exist
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Sanitize parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Ensure that only AudioCodecs, VideoCodecs and ExtraSettings are changed
   */
  $filtered = array ();
  $filtered["ORIGINAL"] = $result->fetch_assoc ();
  foreach ( $filtered["ORIGINAL"] as $index => $val)
  {
    $filtered[$index] = $val;
  }
  $filtered["AudioCodecs"] = $parameters["AudioCodecs"];
  $filtered["VideoCodecs"] = $parameters["VideoCodecs"];
  $filtered["ExtraSettings"] = $parameters["ExtraSettings"];
  $parameters = $filtered;
  unset ( $filtered);

  /**
   * Call subtype sanitize hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_" . $parameters["UID"] . "_sanitize"))
  {
    $parameters = framework_call ( "equipments_configure_" . $parameters["UID"] . "_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_sanitize"))
  {
    $parameters = framework_call ( "equipments_configure_sanitize", $parameters, false, $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid equipment ID.");
  }
  if ( ! is_array ( $parameters["AudioCodecs"]))
  {
    if ( ! empty ( $parameters["AudioCodecs"]))
    {
      $parameters["AudioCodecs"] = array ( $parameters["AudioCodecs"]);
    } else {
      $parameters["AudioCodecs"] = array ();
    }
  }
  foreach ( $parameters["AudioCodecs"] as $codec)
  {
    if ( ! defined ( "VD_AUDIO_CODEC_" . $codec))
    {
      $data["AudioCodecs"] = __ ( "Invalid codec found.");
    }
  }
  if ( ! is_array ( $parameters["VideoCodecs"]))
  {
    if ( ! empty ( $parameters["VideoCodecs"]))
    {
      $parameters["VideoCodecs"] = array ( $parameters["VideoCodecs"]);
    } else {
      $parameters["VideoCodecs"] = array ();
    }
  }
  foreach ( $parameters["VideoCodecs"] as $codec)
  {
    if ( ! defined ( "VD_VIDEO_CODEC_" . $codec))
    {
      $data["VideoCodecs"] = __ ( "Invalid codec found.");
    }
  }

  /**
   * Check if codecs are filled
   */
  if ( sizeof ( $parameters["AudioCodecs"]) == 0)
  {
    $data["AudioCodecs"] = __ ( "At least one audio codec must be selected.");
  }
  if ( $parameters["ORIGINAL"]["VideoSupport"] && sizeof ( $parameters["VideoCodecs"]) == 0)
  {
    $data["VideoCodecs"] = __ ( "At least one video codec must be selected.");
  }

  /**
   * Call subtype validate hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_" . $parameters["UID"] . "_validate"))
  {
    $data = framework_call ( "equipments_configure_" . $parameters["UID"] . "_validate", $parameters, false, $data);
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_validate"))
  {
    $data = framework_call ( "equipments_configure_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call subtype pre hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_" . $parameters["UID"] . "_pre"))
  {
    $parameters = framework_call ( "equipments_configure_" . $parameters["UID"] . "_pre", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_pre"))
  {
    $parameters = framework_call ( "equipments_configure_pre", $parameters, false, $parameters);
  }

  /**
   * Change equipment record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Equipments` SET `AudioCodecs` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["AudioCodecs"])) . "', `VideoCodecs` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["VideoCodecs"])) . "', `ExtraSettings` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["ExtraSettings"])) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call subtype post hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_" . $parameters["UID"] . "_post"))
  {
    framework_call ( "equipments_configure_" . $parameters["UID"] . "_post", $parameters);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_post"))
  {
    framework_call ( "equipments_configure_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "UID" => $parameters["ORIGINAL"]["UID"], "AudioCodecs" => $parameters["AudioCodecs"], "VideoCodecs" => $parameters["VideoCodecs"], "ExtraSettings" => $parameters["ExtraSettings"]);
  if ( framework_has_hook ( "equipments_configure_notify"))
  {
    $notify = framework_call ( "equipments_configure_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "equipment_configure", $notify);

  /**
   * Call subtype finish hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_" . $parameters["UID"] . "_finish"))
  {
    framework_call ( "equipments_configure_" . $parameters["UID"] . "_finish", $parameters);
  }

  /**
   * Call finish hook if exist
   */
  if ( framework_has_hook ( "equipments_configure_finish"))
  {
    framework_call ( "equipments_configure_finish", $parameters);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to upload firmware file
 */
framework_add_hook (
  "equipments_firmware_add",
  "equipments_firmware_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Filename" => array (
          "type" => "string",
          "description" => __ ( "The filename of the firmware file."),
          "required" => true,
          "example" => "55.55.0.0.rom"
        ),
        "SHA256" => array (
          "type" => "string",
          "description" => __ ( "The SHA256 hash of the firmware file."),
          "required" => true,
          "example" => "c416e1f428949e0b3e3733fbee9fb22b6bf683522aa4eaa5c8647058465577f5"
        ),
        "Size" => array (
          "type" => "integer",
          "description" => __ ( "The size of the firmware file."),
          "required" => true,
          "example" => 7321150
        ),
        "FileContent" => array (
          "type" => "string",
          "format" => "byte",
          "description" => __ ( "The base64 encoded file content."),
          "required" => true
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New equipment firmware file added sucessfully."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Active" => array (
              "type" => "boolean",
              "description" => __ ( "The new status of Active equipment field (automatically actived if all firmware file for an available version are provided)."),
              "example" => true
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Filename" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Equipment not found.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "equipments_firmware_add", __ ( "Add equipments firmware file"));
framework_add_api_call (
  "/equipments/:ID/firmware",
  "Create",
  "equipments_firmware_add",
  array (
    "permissions" => array ( "user", "equipments_firmware_add"),
    "title" => __ ( "Add equipments firmware file"),
    "description" => __ ( "Add an equipment firmware file.")
  )
);

/**
 * Function to add an equipment firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_firmware_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_add_start"))
  {
    $parameters = framework_call ( "equipments_firmware_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid equipment ID.");
  } else {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["Filename"] = __ ( "Equipment not found.");
    } else {
      $equipment = $result->fetch_assoc ();
      $equipment["SupportedFirmwares"] = json_decode ( $equipment["SupportedFirmwares"], true);
      $found = false;
      foreach ( $equipment["SupportedFirmwares"] as $id => $firmware)
      {
        foreach ( $firmware["Files"] as $fileid => $file)
        {
          if ( $file["Filename"] == $parameters["Filename"] && $file["SHA256"] == $parameters["SHA256"] && $file["Size"] == $parameters["Size"] && $file["SHA256"] == hash ( "SHA256", base64_decode ( $parameters["FileContent"])))
          {
            $equipment["SupportedFirmwares"][$id]["Files"][$fileid]["Available"] = true;
            $found = true;
          }
        }
      }
      if ( ! $found)
      {
        $data["Filename"] = __ ( "The file was not recognized.");
      }
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_add_validate"))
  {
    $data = framework_call ( "equipments_firmware_add_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_add_sanitize"))
  {
    $parameters = framework_call ( "equipments_firmware_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_add_pre"))
  {
    $parameters = framework_call ( "equipments_firmware_add_pre", $parameters, false, $parameters);
  }

  /**
   * Check if all firmwares are available (enable equipment in this case)
   */
  $active = false;
  foreach ( $equipment["SupportedFirmwares"] as $id => $firmware)
  {
    $thisfile = true;
    foreach ( $firmware["Files"] as $fileid => $file)
    {
      if ( ! $file["Available"])
      {
        $thisfile = false;
      }
    }
    if ( $thisfile)
    {
      $equipment["SupportedFirmwares"][$id]["Available"] = true;
      $active = true;
    }
  }

  /**
   * Update equipment record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Equipments` SET `SupportedFirmwares` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $equipment["SupportedFirmwares"])) . "', `Active` = " . $_in["mysql"]["id"]->real_escape_string ( $active ? 1 : 0) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Check all vendor equipments for the uploaded firmware (some vendors share firmware files between models)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `Vendor` = '" . $_in["mysql"]["id"]->real_escape_string ( $equipment["Vendor"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $tmp = $result->fetch_assoc ())
  {
    $tmp["SupportedFirmwares"] = json_decode ( $tmp["SupportedFirmwares"], true);
    $found = false;
    foreach ( $tmp["SupportedFirmwares"] as $id => $firmware)
    {
      foreach ( $firmware["Files"] as $fileid => $file)
      {
        if ( $file["Filename"] == $parameters["Filename"] && $file["SHA256"] == $parameters["SHA256"] && $file["Size"] == $parameters["Size"] && $file["SHA256"] == hash ( "SHA256", base64_decode ( $parameters["FileContent"])))
        {
          $tmp["SupportedFirmwares"][$id]["Files"][$fileid]["Available"] = true;
          $found = true;
        }
      }
    }
    if ( $found)
    {
      $active = $tmp["Active"];
      if ( ! $active)
      {
        foreach ( $tmp["SupportedFirmwares"] as $id => $firmware)
        {
          $thisfile = true;
          foreach ( $firmware["Files"] as $fileid => $file)
          {
            if ( ! $file["Available"])
            {
              $thisfile = false;
            }
          }
          if ( $thisfile)
          {
            $tmp["SupportedFirmwares"][$id]["Available"] = true;
            $active = true;
          }
        }
        if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Equipments` SET `SupportedFirmwares` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $tmp["SupportedFirmwares"])) . "', `Active` = " . $_in["mysql"]["id"]->real_escape_string ( $active ? 1 : 0) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $tmp["ID"])))
        {
          header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
          exit ();
        }
      }
    }
  }

  /**
   * Move uploaded file to firmwares directory
   */
  mkdir ( $_in["general"]["storagedir"] . "/firmwares/" . $equipment["Vendor"], 0777, true);
  file_put_contents ( $_in["general"]["storagedir"] . "/firmwares/" . $equipment["Vendor"] . "/". $parameters["Filename"], base64_decode ( $parameters["FileContent"]));

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_add_post"))
  {
    framework_call ( "equipments_firmware_add_post", $parameters);
  }

  /**
   * Add new firmware at Asterisk servers
   */
  $notify = array ( "Vendor" => $equipment["Vendor"], "UID" => $equipment["UID"], "Filename" => $parameters["Filename"], "Data" => $parameters["FileContent"]);
  if ( framework_has_hook ( "equipments_firmware_add_notify"))
  {
    $notify = framework_call ( "equipments_firmware_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "firmware_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_add_finish"))
  {
    framework_call ( "equipments_firmware_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/equipments/" . $parameters["Equipment"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "Active" => $active));
}

/**
 * API call to remove firmware
 */
framework_add_hook (
  "equipments_firmware_remove",
  "equipments_firmware_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "Equipment firmware file removed sucessfully."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Active" => array (
              "type" => "boolean",
              "description" => __ ( "The new status of Active equipment field (automatically deactived if no firmware file for an available version exists)."),
              "example" => true
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid equipment ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "equipments_firmware_remove", __ ( "Remove equipments firmware file"));
framework_add_api_call (
  "/equipments/:ID/firmware/:Filename",
  "Delete",
  "equipments_firmware_remove",
  array (
    "permissions" => array ( "user", "equipments_firmware_remove"),
    "title" => __ ( "Remove equipments firmware file"),
    "description" => __ ( "Remove an equipment firmware file.")
  )
);

/**
 * Function to remove an equipment firmware file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_firmware_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_remove_start"))
  {
    $parameters = framework_call ( "equipments_firmware_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid equipment ID.");
  }

  /**
   * Check if equipment exists
   */
  if ( ! array_key_exists ( "ID", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["Filename"] = __ ( "Equipment not found.");
    } else {
      $equipment = $result->fetch_assoc ();
      $equipment["SupportedFirmwares"] = json_decode ( $equipment["SupportedFirmwares"], true);
      $found = false;
      foreach ( $equipment["SupportedFirmwares"] as $id => $firmware)
      {
        $available = $firmware["Available"];
        foreach ( $firmware["Files"] as $fileid => $file)
        {
          if ( $file["Filename"] == $parameters["Filename"] && $file["Available"])
          {
            $equipment["SupportedFirmwares"][$id]["Files"][$fileid]["Available"] = false;
            $found = true;
            $available = false;
          }
        }
        if ( ! $available)
        {
          $equipment["SupportedFirmwares"][$id]["Available"] = false;
        }
      }
      if ( ! $found)
      {
        $data["Filename"] = __ ( "The file was not found.");
      }
    }
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_remove_sanitize"))
  {
    $parameters = framework_call ( "equipments_firmware_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_remove_pre"))
  {
    $parameters = framework_call ( "equipments_firmware_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Check if any firmware are still available (disable equipment if not the case)
   */
  $active = $equipment["Available"];
  if ( $active)
  {
    $active = false;
    foreach ( $equipment["SupportedFirmwares"] as $id => $firmware)
    {
      $thisfile = true;
      foreach ( $firmware["Files"] as $fileid => $file)
      {
        if ( ! $file["Available"])
        {
          $thisfile = false;
        }
      }
      if ( $thisfile)
      {
        $active = true;
      }
    }
  }

  /**
   * Update equipment record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Equipments` SET `SupportedFirmwares` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $equipment["SupportedFirmwares"])) . "', `Active` = " . $_in["mysql"]["id"]->real_escape_string ( $active ? 1 : 0) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Check all vendor equipments for the removed firmware (some vendors share firmware files between models)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `Vendor` = '" . $_in["mysql"]["id"]->real_escape_string ( $equipment["Vendor"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $tmp = $result->fetch_assoc ())
  {
    $tmp["SupportedFirmwares"] = json_decode ( $tmp["SupportedFirmwares"], true);
    $found = false;
    foreach ( $tmp["SupportedFirmwares"] as $id => $firmware)
    {
      foreach ( $firmware["Files"] as $fileid => $file)
      {
        if ( $file["Filename"] == $parameters["Filename"] && $file["Available"])
        {
          $tmp["SupportedFirmwares"][$id]["Files"][$fileid]["Available"] = false;
          $found = true;
        }
      }
    }
    if ( $found)
    {
      $active = false;
      foreach ( $tmp["SupportedFirmwares"] as $id => $firmware)
      {
        $thisfile = $firmware["Available"];
        foreach ( $firmware["Files"] as $fileid => $file)
        {
          if ( ! $file["Available"])
          {
            $thisfile = false;
          }
        }
        if ( ! $thisfile)
        {
          $tmp["SupportedFirmwares"][$id]["Available"] = false;
        } else {
          $active = true;
        }
      }
      if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Equipments` SET `SupportedFirmwares` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $tmp["SupportedFirmwares"])) . "', `Active` = " . $_in["mysql"]["id"]->real_escape_string ( $active ? 1 : 0) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $tmp["ID"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
    }
  }

  /**
   * Remove file from firmwares directory
   */
  @unlink ( $_in["general"]["storagedir"] . "/firmwares/" . $equipment["Vendor"] . "/". $parameters["Filename"]);
  @rmdir ( $_in["general"]["storagedir"] . "/firmwares/" . $equipment["Vendor"]);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_remove_post"))
  {
    framework_call ( "equipments_firmware_remove_post", $parameters);
  }

  /**
   * Remove firmware at Asterisk servers
   */
  $notify = array ( "Vendor" => $equipment["Vendor"], "UID" => $equipment["UID"], "Filename" => $parameters["Filename"]);
  if ( framework_has_hook ( "equipments_firmware_remove_notify"))
  {
    $notify = framework_call ( "equipments_firmware_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "firmware_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "equipments_firmware_remove_finish"))
  {
    framework_call ( "equipments_firmware_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "Active" => $active));
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "equipments_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "equipments_server_reconfig");

/**
 * Function to notify server to include all equipments.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all equipments and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $equipment = $result->fetch_assoc ())
  {
    $notify = array ( "UID" => $equipment["UID"], "AudioCodecs" => json_decode ( $equipment["AudioCodecs"]), "VideoCodecs" => json_decode ( $equipment["VideoCodecs"]), "ExtraSettings" => json_decode ( $equipment["ExtraSettings"]));
    if ( framework_has_hook ( "equipments_configure_notify"))
    {
      $notify = framework_call ( "equipments_configure_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "equipment_configure", $notify);

    /**
     * Process firmwares if available
     */
    $equipment["SupportedFirmwares"] = json_decode ( $equipment["SupportedFirmwares"], true);
    foreach ( $equipment["SupportedFirmwares"] as $firmware)
    {
      if ( $firmware["Available"])
      {
        foreach ( $firmware["Files"] as $file)
        {
          $notify = array ( "Vendor" => $equipment["Vendor"], "UID" => $equipment["UID"], "Filename" => $file["Filename"], "Data" => base64_encode ( file_get_contents ( $_in["general"]["storagedir"] . "/firmwares/" . $equipment["Vendor"] . "/". $file["Filename"])));
          if ( framework_has_hook ( "equipments_firmware_add_notify"))
          {
            $notify = framework_call ( "equipments_firmware_add_notify", $parameters, false, $notify);
          }
          notify_server ( (int) $parameters["ID"], "firmware_add", $notify);
        }
      }
    }
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
