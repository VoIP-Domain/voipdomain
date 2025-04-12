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
 * VoIP Domain audio module API. This module add the API calls related to
 * audio file manipulation.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Audio
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search audios (datatables compatible response)
 */
framework_add_hook (
  "audios_search",
  "audios_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all audio files."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Filename,Uses",
          "example" => "ID,Description,Filename"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system audio files."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "audio"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The audio file internal system unique identifier."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the audio file."),
                "example" => __ ( "Default Music On Hold")
              ),
              "Filename" => array (
                "type" => "string",
                "description" => __ ( "The filename of the audio file."),
                "example" => "moh.mp3"
              ),
              "Uses" => array (
                "type" => "integer",
                "description" => __ ( "How many times this audio are in use into the system."),
                "example" => 3
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
              "example" => __ ( "Invalid filter.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "audios_search", __ ( "Search audios"));
framework_add_api_call (
  "/audios",
  "Read",
  "audios_search",
  array (
    "permissions" => array ( "user", "audios_search"),
    "title" => __ ( "Search audios"),
    "description" => __ ( "Search for system audio files.")
  )
);

/**
 * Function to search audios.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audios_search_start"))
  {
    $parameters = framework_call ( "audios_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Audios");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "audios_search_validate"))
  {
    $data = framework_call ( "audios_search_validate", $parameters);
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
  if ( framework_has_hook ( "audios_search_sanitize"))
  {
    $parameters = framework_call ( "audios_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audios_search_pre"))
  {
    $parameters = framework_call ( "audios_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search audios
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Filename` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Description`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Filename,Uses", "ID,Description,Filename,Uses");
  while ( $result = $results->fetch_assoc ())
  {
    $result["Uses"] = filters_call ( "audio_inuse", array ( "ID" => $result["ID"]));
    if ( ! is_array ( $result["Uses"]))
    {
      $result["Uses"] = 0;
    } else {
      $result["Uses"] = sizeof ( $result["Uses"]);
    }
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audios_search_finish"))
  {
    $data = framework_call ( "audios_search_finish", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audios_search_finish"))
  {
    framework_call ( "audios_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get audios information
 */
framework_add_hook (
  "audios_view",
  "audios_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the audio file."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the audio file."),
              "example" => __ ( "Default Music On Hold")
            ),
            "Filename" => array (
              "type" => "string",
              "description" => __ ( "The filename of the audio file."),
              "example" => "moh.mp3"
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
              "example" => __ ( "Invalid audio ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "audios_view", __ ( "View audio information"));
framework_add_api_call (
  "/audios/:ID",
  "Read",
  "audios_view",
  array (
    "permissions" => array ( "user", "audios_view"),
    "title" => __ ( "View audios"),
    "description" => __ ( "Get an audio file information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The audio file internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate audio information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audios_view_start"))
  {
    $parameters = framework_call ( "audios_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Audios");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid audio ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "audios_view_validate"))
  {
    $data = framework_call ( "audios_view_validate", $parameters);
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
  if ( framework_has_hook ( "audios_view_sanitize"))
  {
    $parameters = framework_call ( "audios_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audios_view_pre"))
  {
    $parameters = framework_call ( "audios_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search audio
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $audio = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = api_filter_entry ( array ( "Description", "Filename"), $audio);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audios_view_post"))
  {
    $data = framework_call ( "audios_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audios_view_finish"))
  {
    framework_call ( "audios_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to download audios files
 */
framework_add_hook (
  "audios_download",
  "audios_download",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Chunked" => array (
          "type" => "integer",
          "description" => __ ( "If greater than zero, the result filecontent variable will be an array split of the audio file in chunks of size of this variable."),
          "minimum" => 0,
          "default" => 0
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the audio file."),
        "schema" => array (
          "oneOf" => array (
            array (
              "type" => "object",
              "properties" => array (
                "Description" => array (
                  "type" => "string",
                  "description" => __ ( "The description of the audio file."),
                  "example" => __ ( "Default Music On Hold")
                ),
                "Filename" => array (
                  "type" => "string",
                  "description" => __ ( "The filename of the audio file."),
                  "example" => "moh.mp3"
                ),
                "Mimetype" => array (
                  "type" => "string",
                  "description" => __ ( "The mime type of the audio file."),
                  "example" => "audio/wav"
                ),
                "Filecontent" => array (
                  "type" => "string",
                  "format" => "byte",
                  "description" => __ ( "The base64 encoded file content.")
                )
              )
            ),
            array (
              "type" => "object",
              "properties" => array (
                "Description" => array (
                  "type" => "string",
                  "description" => __ ( "The description of the audio file."),
                  "example" => __ ( "Default Music On Hold")
                ),
                "Filename" => array (
                  "type" => "string",
                  "description" => __ ( "The filename of the audio file."),
                  "example" => "moh.mp3"
                ),
                "Mimetype" => array (
                  "type" => "string",
                  "description" => __ ( "The mime type of the audio file."),
                  "example" => "audio/wav"
                ),
                "Filecontent" => array (
                  "type" => "array",
                  "xml" => array (
                    "name" => "chunks",
                    "wrapped" => true
                  ),
                  "items" => array (
                    "type" => "string",
                    "format" => "byte",
                    "description" => __ ( "The base64 encoded file piece content.")
                  )
                )
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
              "example" => __ ( "Invalid audio ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "audios_download", __ ( "Download audio"));
framework_add_api_call (
  "/audios/:ID/download",
  "Read",
  "audios_download",
  array (
    "permissions" => array ( "user", "server", "audios_download"),
    "title" => __ ( "Download audios"),
    "description" => __ ( "Download system audio files."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The audio file internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to download audio files.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_download ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audios_download_start"))
  {
    $parameters = framework_call ( "audios_download_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid audio ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "audios_download_validate"))
  {
    $data = framework_call ( "audios_download_validate", $parameters);
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
  if ( framework_has_hook ( "audios_download_sanitize"))
  {
    $parameters = framework_call ( "audios_download_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audios_download_pre"))
  {
    $parameters = framework_call ( "audios_download_pre", $parameters, false, $parameters);
  }

  /**
   * Search audio
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $audio = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["Description"] = $audio["Description"];
  $data["Filename"] = $audio["Filename"];
  $data["MimeType"] = mime_content_type ( $_in["general"]["storagedir"] . "/sounds/" . $audio["Filename"]);
  $audiofile = file_get_contents ( $_in["general"]["storagedir"] . "/sounds/" . $audio["Filename"]);
  if ( (int) $parameters["Chunked"] > 0)
  {
    $data["Filecontent"] = array ();
    for ( $x = 0; $x <= strlen ( $audiofile); $x += (int) $parameters["Chunked"])
    {
      $data["Filecontent"][] = base64_encode ( substr ( $audiofile, $x, (int) $parameters["Chunked"]));
    }
  } else {
    $data["Filecontent"] = base64_encode ( $audiofile);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audios_download_post"))
  {
    $data = framework_call ( "audios_download_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audios_download_finish"))
  {
    framework_call ( "audios_download_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new audio
 */
framework_add_hook (
  "audios_add",
  "audios_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the audio file."),
          "required" => true,
          "example" => __ ( "Default Music On Hold")
        ),
        "Filename" => array (
          "type" => "string",
          "description" => __ ( "The filename of the audio file."),
          "required" => true,
          "example" => "moh.mp3"
        ),
        "Filecontent" => array (
          "type" => "string",
          "format" => "byte",
          "description" => __ ( "The base64 encoded file content."),
          "required" => true
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system audio file added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The audio description is required.")
            ),
            "Filename" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The file type must be WAV or MP3.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "audios_add", __ ( "Add audio"));
framework_add_api_call (
  "/audios",
  "Create",
  "audios_add",
  array (
    "permissions" => array ( "user", "audios_add"),
    "title" => __ ( "Add audios"),
    "description" => __ ( "Add a new system audio file.")
  )
);

/**
 * Function to add a new audio.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audios_add_start"))
  {
    $parameters = framework_call ( "audios_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The audio description is required.");
  }
  $parameters["Filename"] = basename ( $parameters["Filename"]);
  if ( substr ( $parameters["Filename"], 0, 12) == "C:\\fakepath\\")
  {
    $parameters["Filename"] = substr ( $parameters["Filename"], 12);
  }
  if ( ! array_key_exists ( "Filename", $data) && strtolower ( substr ( $parameters["Filename"], strrpos ( $parameters["Filename"], ".") + 1)) != "mp3" && strtolower ( substr ( $parameters["Filename"], strrpos ( $parameters["Filename"], ".") + 1)) != "wav")
  {
    $data["Filename"] = __ ( "The file type must be WAV or MP3.");
  }

  /**
   * Check if file was already added
   */
  if ( ! array_key_exists ( "Filename", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `Filename` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filename"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Filename"] = __ ( "The provided audio file filename already exist on system.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "audios_add_validate"))
  {
    $data = framework_call ( "audios_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "audios_add_sanitize"))
  {
    $parameters = framework_call ( "audios_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audios_add_pre"))
  {
    $parameters = framework_call ( "audios_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new audio record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Audios` (`Filename`, `Description`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filename"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Move uploaded file to audio files directory
   */
  file_put_contents ( $_in["general"]["storagedir"] . "/sounds/" . $parameters["Filename"], base64_decode ( $parameters["Filecontent"]));

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audios_add_post"))
  {
    framework_call ( "audios_add_post", $parameters);
  }

  /**
   * Add new audio at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["ID"], "Filename" => $parameters["Filename"], "Data" => $parameters["Filecontent"]);
  if ( framework_has_hook ( "audios_add_notify"))
  {
    $notify = framework_call ( "audios_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "audio_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audios_add_finish"))
  {
    framework_call ( "audios_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/audios/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing audio
 */
framework_add_hook (
  "audios_edit",
  "audios_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the audio file."),
          "required" => true,
          "example" => __ ( "Default Music On Hold")
        ),
        "Filename" => array (
          "type" => "string",
          "description" => __ ( "The filename of the audio file."),
          "required" => true,
          "example" => "moh.mp3"
        ),
        "Filecontent" => array (
          "type" => "string",
          "format" => "byte",
          "description" => __ ( "The base64 encoded file content."),
          "required" => true
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system audio file was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The audio description is required.")
            ),
            "Filename" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The file type must be WAV or MP3.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "audios_edit", __ ( "Edit audio"));
framework_add_api_call (
  "/audios/:ID",
  "Modify",
  "audios_edit",
  array (
    "permissions" => array ( "user", "audios_edit"),
    "title" => __ ( "Edit audio"),
    "description" => __ ( "Edit a system audio file.")
  )
);
framework_add_api_call (
  "/audios/:ID",
  "Edit",
  "audios_edit",
  array (
    "permissions" => array ( "user", "audios_edit"),
    "title" => __ ( "Edit audio"),
    "description" => __ ( "Edit a system audio file.")
  )
);

/**
 * Function to edit an existing audio.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audios_edit_start"))
  {
    $parameters = framework_call ( "audios_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The audio description is required.");
  }
  if ( ! empty ( $parameters["Filename"]))
  {
    $parameters["Filename"] = basename ( $parameters["Filename"]);
    if ( substr ( $parameters["Filename"], 0, 12) == "C:\\fakepath\\")
    {
      $parameters["Filename"] = substr ( $parameters["Filename"], 12);
    }
    if ( ! array_key_exists ( "Filename", $data) && strtolower ( substr ( $parameters["Filename"], strrpos ( $parameters["Filename"], ".") + 1)) != "mp3" && strtolower ( substr ( $parameters["Filename"], strrpos ( $parameters["Filename"], ".") + 1)) != "wav")
    {
      $data["Filename"] = __ ( "The file type must be WAV or MP3.");
    }

    /**
     * Check if file was already added
     */
    if ( ! array_key_exists ( "Filename", $data))
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `Filename` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filename"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows != 0)
      {
        $data["Filename"] = __ ( "The provided audio file filename already exist on system.");
      }
    }
  }

  /**
   * Check if audio file exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $parameters["ORIGINAL"] = $result->fetch_assoc ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "audios_edit_validate"))
  {
    $data = framework_call ( "audios_edit_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "audios_edit_sanitize"))
  {
    $parameters = framework_call ( "audios_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audios_edit_pre"))
  {
    $parameters = framework_call ( "audios_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change audio file record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Audios` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "'" . ( ! empty ( $parameters["Filename"]) ? ", `Filename` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filename"]) . "'" : "") . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove audio file (if another uploaded)
   */
  if ( ! empty ( $parameters["Filename"]))
  {
    unlink ( $_in["general"]["storagedir"] . "/sounds/" . $parameters["ORIGINAL"]["Filename"]);

    /**
     * Move uploaded file to audio files directory
     */
    file_put_contents ( $_in["general"]["storagedir"] . "/sounds/" . $parameters["Filename"], base64_decode ( $parameters["Filecontent"]));
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audios_edit_post"))
  {
    framework_call ( "audios_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  if ( $parameters["ORIGINAL"]["Filename"] != $parameters["Filename"])
  {
    $notify = array ( "ID" => $parameters["ID"], "Filename" => $parameters["ORIGINAL"]["Filename"], "NewFilename" => $parameters["Filename"], "Data" => $parameters["Filecontent"]);
    if ( framework_has_hook ( "audios_edit_notify"))
    {
      $notify = framework_call ( "audios_edit_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "audio_change", $notify);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audios_edit_finish"))
  {
    framework_call ( "audios_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove an audio
 */
framework_add_hook (
  "audios_remove",
  "audios_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system audio file was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid audio ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "audios_remove", __ ( "Remove audio"));
framework_add_api_call (
  "/audios/:ID",
  "Delete",
  "audios_remove",
  array (
    "permissions" => array ( "user", "audios_remove"),
    "title" => __ ( "Remove audio"),
    "description" => __ ( "Remove a system audio file.")
  )
);

/**
 * Function to remove an existing audio.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "audios_remove_start"))
  {
    $parameters = framework_call ( "audios_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid audio ID.");
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
  if ( framework_has_hook ( "audios_remove_sanitize"))
  {
    $parameters = framework_call ( "audios_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if audio exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $parameters["ORIGINAL"] = $result->fetch_assoc ();

  /**
   * Check if audio is in use by the system
   */
  if ( sizeof ( filters_call ( "audio_inuse", array ( "ID" => $parameters["ID"]))) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "audios_remove_pre"))
  {
    $parameters = framework_call ( "audios_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove audio database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Audios` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove file from filesystem
   */
  unlink ( $_in["general"]["storagedir"] . "/sounds/" . $parameters["ORIGINAL"]["Filename"]);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "audios_remove_post"))
  {
    framework_call ( "audios_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["ID"]);
  if ( framework_has_hook ( "audios_remove_notify"))
  {
    $notify = framework_call ( "audios_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "audio_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "audios_remove_finish"))
  {
    framework_call ( "audios_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "audios_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "audios_server_reconfig");

/**
 * Function to notify server to include all audios.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all audios and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $audio = $result->fetch_assoc ())
  {
    $notify = array ( "Filename" => $audio["Filename"], "ID" => $audio["ID"], "Data" => base64_encode ( file_get_contents ( $_in["general"]["storagedir"] . "/sounds/" . $audio["Filename"])));
    if ( framework_has_hook ( "audios_add_notify"))
    {
      $notify = framework_call ( "audios_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "audio_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
