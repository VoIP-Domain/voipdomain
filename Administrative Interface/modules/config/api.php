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
 * VoIP Domain configuration module API. This module add the API calls related to
 * system configuration.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Configuration
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Permissions component documentation
 */
framework_add_component_documentation (
  "permissions",
  array (
    "type" => "object",
    "xml" => array (
      "name" => "permissions"
    ),
    "properties" => array (
      "Landline" => array (
        "type" => "object",
        "required" => true,
        "description" => __ ( "Landline calls system maximum permissions."),
        "properties" => array (
          "Local" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to local landline call."),
            "example" => "y"
          ),
          "Interstate" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to interstate landline call."),
            "example" => "y"
          ),
          "International" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to international landline call."),
            "example" => "y"
          )
        ),
      ),
      "Mobile" => array (
        "type" => "object",
        "required" => true,
        "description" => __ ( "Mobile calls system maximum permissions."),
        "properties" => array (
          "Local" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to local mobile call."),
            "example" => "y"
          ),
          "Interstate" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to interstate mobile call."),
            "example" => "y"
          ),
          "International" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to international mobile call."),
            "example" => "y"
          )
        )
      ),
      "Marine" => array (
        "type" => "object",
        "required" => true,
        "description" => __ ( "Marine calls system maximum permissions."),
        "properties" => array (
          "Local" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to local marine call."),
            "example" => "y"
          ),
          "Interstate" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to interstate marine call."),
            "example" => "y"
          ),
          "International" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to international marine call."),
            "example" => "y"
          )
        )
      ),
      "Tollfree" => array (
        "type" => "object",
        "required" => true,
        "description" => __ ( "Toll free calls system maximum permissions."),
        "properties" => array (
          "Local" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to local toll free call."),
            "example" => "y"
          ),
          "International" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to international toll free call."),
            "example" => "y"
          )
        )
      ),
      "PRN" => array (
        "type" => "object",
        "required" => true,
        "description" => __ ( "Premium rate numbers calls system maximum permissions."),
        "properties" => array (
          "Local" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to local premium rate number call."),
            "example" => "y"
          ),
          "International" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to international premium rate number call."),
            "example" => "y"
          )
        )
      ),
      "Satellite" => array (
        "type" => "object",
        "required" => true,
        "description" => __ ( "Satellite calls system maximum permissions."),
        "properties" => array (
          "Local" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to local satellite call."),
            "example" => "y"
          ),
          "International" => array (
            "type" => "string",
            "required" => true,
            "enum" => array ( "y", "p", "n"),
            "description" => __ ( "Permission to international satellite call."),
            "example" => "y"
          )
        )
      )
    )
  )
);

/**
 * API call to get system permissions information
 */
framework_add_hook (
  "permissions_view",
  "permissions_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the maximum call type permissions on system. Permissions could be `y` to allow, `p` to allow with password or `n` to deny."),
        "schema" => array (
          "\$ref" => "#/components/schemas/permissions"
        )
      )
    )
  )
);
framework_add_permission ( "permissions_view", __ ( "View system permission information"));
framework_add_api_call (
  "/config/permissions",
  "Read",
  "permissions_view",
  array (
    "permissions" => array ( "administrator", "permissions_view"),
    "title" => __ ( "View permissions"),
    "description" => __ ( "View system permission information.")
  )
);

/**
 * Function to generate system permissions information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function permissions_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "permissions_view_start"))
  {
    $parameters = framework_call ( "permissions_view_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "permissions_view_validate"))
  {
    $data = framework_call ( "permissions_view_validate", $parameters);
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
  if ( framework_has_hook ( "permissions_view_sanitize"))
  {
    $parameters = framework_call ( "permissions_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "permissions_view_pre"))
  {
    $parameters = framework_call ( "permissions_view_pre", $parameters, false, $parameters);
  }

  /**
   * Get permissions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` = 'Permissions'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $config = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = json_decode ( $config["Data"], true);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "permissions_view_post"))
  {
    $data = framework_call ( "permissions_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "permissions_view_finish"))
  {
    framework_call ( "permissions_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to change system permissions
 */
framework_add_hook (
  "permissions_edit",
  "permissions_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "\$ref" => "#/components/schemas/permissions"
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system permissions was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "LandlineLocal" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "LandlineInterstate" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "LandlineInternational" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "MobileLocal" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "MobileInterstate" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "MobileInternational" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "MarineLocal" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "MarineInterstate" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "MarineInternational" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "TollfreeLocal" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "TollfreeInternational" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "PRNLocal" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "PRNInternational" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "SatelliteLocal" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            ),
            "SatelliteInternational" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid value.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "permissions_edit", __ ( "Edit system permissions"));
framework_add_api_call (
  "/config/permissions",
  "Modify",
  "permissions_edit",
  array (
    "permissions" => array ( "administrator", "permissions_edit"),
    "title" => __ ( "Edit permissions"),
    "description" => __ ( "Change the system maximum permissions.")
  )
);
framework_add_api_call (
  "/config/permissions",
  "Edit",
  "permissions_edit",
  array (
    "permissions" => array ( "administrator", "permissions_edit"),
    "title" => __ ( "Edit permissions"),
    "description" => __ ( "Change the system maximum permissions.")
  )
);

/**
 * Function to change system permissions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function permissions_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "permissions_edit_start"))
  {
    $parameters = framework_call ( "permissions_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( $parameters["Landline"]["Local"] != "y" && $parameters["Landline"]["Local"] != "p" && $parameters["Landline"]["Local"] != "n")
  {
    if ( ! array_key_exists ( "Landline", $data))
    {
      $data["Landline"] = array ();
    }
    $data["Landline"]["Local"] = __ ( "Invalid value.");
  }
  if ( $parameters["Landline"]["Interstate"] != "y" && $parameters["Landline"]["Interstate"] != "p" && $parameters["Landline"]["Interstate"] != "n")
  {
    if ( ! array_key_exists ( "Landline", $data))
    {
      $data["Landline"] = array ();
    }
    $data["Landline"]["Interstate"] = __ ( "Invalid value.");
  }
  if ( $parameters["Landline"]["International"] != "y" && $parameters["Landline"]["International"] != "p" && $parameters["Landline"]["International"] != "n")
  {
    if ( ! array_key_exists ( "Landline", $data))
    {
      $data["Landline"] = array ();
    }
    $data["Landline"]["International"] = __ ( "Invalid value.");
  }
  if ( $parameters["Mobile"]["Local"] != "y" && $parameters["Mobile"]["Local"] != "p" && $parameters["Mobile"]["Local"] != "n")
  {
    if ( ! array_key_exists ( "Mobile", $data))
    {
      $data["Mobile"] = array ();
    }
    $data["Mobile"]["Local"] = __ ( "Invalid value.");
  }
  if ( $parameters["Mobile"]["Interstate"] != "y" && $parameters["Mobile"]["Interstate"] != "p" && $parameters["Mobile"]["Interstate"] != "n")
  {
    if ( ! array_key_exists ( "Mobile", $data))
    {
      $data["Mobile"] = array ();
    }
    $data["Mobile"]["Interstate"] = __ ( "Invalid value.");
  }
  if ( $parameters["Mobile"]["International"] != "y" && $parameters["Mobile"]["International"] != "p" && $parameters["Mobile"]["International"] != "n")
  {
    if ( ! array_key_exists ( "Mobile", $data))
    {
      $data["Mobile"] = array ();
    }
    $data["Mobile"]["International"] = __ ( "Invalid value.");
  }
  if ( $parameters["Marine"]["Local"] != "y" && $parameters["Marine"]["Local"] != "p" && $parameters["Marine"]["Local"] != "n")
  {
    if ( ! array_key_exists ( "Marine", $data))
    {
      $data["Marine"] = array ();
    }
    $data["Marine"]["Local"] = __ ( "Invalid value.");
  }
  if ( $parameters["Marine"]["Interstate"] != "y" && $parameters["Marine"]["Interstate"] != "p" && $parameters["Marine"]["Interstate"] != "n")
  {
    if ( ! array_key_exists ( "Marine", $data))
    {
      $data["Marine"] = array ();
    }
    $data["Marine"]["Interstate"] = __ ( "Invalid value.");
  }
  if ( $parameters["Marine"]["International"] != "y" && $parameters["Marine"]["International"] != "p" && $parameters["Marine"]["International"] != "n")
  {
    if ( ! array_key_exists ( "Marine", $data))
    {
      $data["Marine"] = array ();
    }
    $data["Marine"]["International"] = __ ( "Invalid value.");
  }
  if ( $parameters["Tollfree"]["Local"] != "y" && $parameters["Tollfree"]["Local"] != "p" && $parameters["Tollfree"]["Local"] != "n")
  {
    if ( ! array_key_exists ( "Tollfree", $data))
    {
      $data["Tollfree"] = array ();
    }
    $data["Tollfree"]["Local"] = __ ( "Invalid value.");
  }
  if ( $parameters["Tollfree"]["International"] != "y" && $parameters["Tollfree"]["International"] != "p" && $parameters["Tollfree"]["International"] != "n")
  {
    if ( ! array_key_exists ( "Tollfree", $data))
    {
      $data["Tollfree"] = array ();
    }
    $data["Tollfree"]["International"] = __ ( "Invalid value.");
  }
  if ( $parameters["PRN"]["Local"] != "y" && $parameters["PRN"]["Local"] != "p" && $parameters["PRN"]["Local"] != "n")
  {
    if ( ! array_key_exists ( "PRN", $data))
    {
      $data["PRN"] = array ();
    }
    $data["PRN"]["Local"] = __ ( "Invalid value.");
  }
  if ( $parameters["PRN"]["International"] != "y" && $parameters["PRN"]["International"] != "p" && $parameters["PRN"]["International"] != "n")
  {
    if ( ! array_key_exists ( "PRN", $data))
    {
      $data["PRN"] = array ();
    }
    $data["PRN"]["International"] = __ ( "Invalid value.");
  }
  if ( $parameters["Satellite"]["Local"] != "y" && $parameters["Satellite"]["Local"] != "p" && $parameters["Satellite"]["Local"] != "n")
  {
    if ( ! array_key_exists ( "Satellite", $data))
    {
      $data["Satellite"] = array ();
    }
    $data["Satellite"]["Local"] = __ ( "Invalid value.");
  }
  if ( $parameters["Satellite"]["International"] != "y" && $parameters["Satellite"]["International"] != "p" && $parameters["Satellite"]["International"] != "n")
  {
    if ( ! array_key_exists ( "Satellite", $data))
    {
      $data["Satellite"] = array ();
    }
    $data["Satellite"]["International"] = __ ( "Invalid value.");
  }

  /**
   * Get permissions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` = 'Permissions'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ORIGINAL"] = json_decode ( $result->fetch_assoc ()["Data"], true);

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "permissions_edit_validate"))
  {
    $data = framework_call ( "permissions_edit_validate", $parameters, false, $data);
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
  $parameters["Permissions"] = array ();
  $parameters["Permissions"]["Landline"] = array ( "Local" => $parameters["Landline"]["Local"], "Interstate" => $parameters["Landline"]["Interstate"], "International" => $parameters["Landline"]["International"]);
  $parameters["Permissions"]["Mobile"] = array ( "Local" => $parameters["Mobile"]["Local"], "Interstate" => $parameters["Mobile"]["Interstate"], "International" => $parameters["Mobile"]["International"]);
  $parameters["Permissions"]["Marine"] = array ( "Local" => $parameters["Marine"]["Local"], "Interstate" => $parameters["Marine"]["Interstate"], "International" => $parameters["Marine"]["International"]);
  $parameters["Permissions"]["Tollfree"] = array ( "Local" => $parameters["Tollfree"]["Local"], "International" => $parameters["Tollfree"]["International"]);
  $parameters["Permissions"]["PRN"] = array ( "Local" => $parameters["PRN"]["Local"], "International" => $parameters["PRN"]["International"]);
  $parameters["Permissions"]["Satellite"] = array ( "Local" => $parameters["Satellite"]["Local"], "International" => $parameters["Satellite"]["International"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "permissions_edit_sanitize"))
  {
    $parameters = framework_call ( "permissions_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "permissions_edit_pre"))
  {
    $parameters = framework_call ( "permissions_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change permission configuration record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Config` SET `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Permissions"])) . "' WHERE `Key` = 'Permissions'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "permissions_edit_post"))
  {
    framework_call ( "permissions_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = $parameters["Permissions"];
  if ( framework_has_hook ( "permissions_edit_notify"))
  {
    $notify = framework_call ( "permissions_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "changepermissions", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "permissions_edit_finish"))
  {
    framework_call ( "permissions_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * DNS entry component documentation
 */
framework_add_component_documentation (
  "dnsentry",
  array (
    "type" => "object",
    "description" => __ ( "DNS entry details."),
    "xml" => array (
      "name" => "dnsentry"
    ),
    "properties" => array (
      "Host" => array (
        "type" => "string",
        "description" => __ ( "Full qualified domain name for entry."),
        "example" => "example.com"
      ),
      "Class" => array (
        "type" => "string",
        "description" => __ ( "Class of DNS zone entry."),
        "example" => "IN"
      ),
      "TTL" => array (
        "type" => "integer",
        "description" => __ ( "The time to live (TTL) in seconds for entry."),
        "example" => 1800
      ),
      "Type" => array (
        "type" => "string",
        "description" => __ ( "The type of entry."),
        "example" => "NAPTR"
      ),
      "Order" => array (
        "type" => "integer",
        "description" => __ ( "The order of entry."),
        "example" => 10
      ),
      "Preference" => array (
        "type" => "integer",
        "description" => __ ( "The preference of entry."),
        "example" => 100
      ),
      "Flags" => array (
        "type" => "string",
        "description" => __ ( "The flags of entry."),
        "example" => "S"
      ),
      "Services" => array (
        "type" => "string",
        "description" => __ ( "The service of entry."),
        "example" => "SIP+D2U"
      ),
      "Regex" => array (
        "type" => "string",
        "description" => __ ( "The regex of entry."),
        "example" => "!^.*\$!mailto:azevedo@voipdomain.io!i"
      ),
      "Replacement" => array (
        "type" => "string",
        "description" => __ ( "The replacement of entry."),
        "example" => "azevedo@voipdomain.io"
      ),
      "Priority" => array (
        "type" => "integer",
        "description" => __ ( "The priority of entry."),
        "example" => 0
      ),
      "Weight" => array (
        "type" => "integer",
        "description" => __ ( "The weight of entry."),
        "example" => 0
      ),
      "Port" => array (
        "type" => "integer",
        "description" => __ ( "The port of entry."),
        "example" => 5060
      ),
      "Target" => array (
        "type" => "string",
        "description" => __ ( "The target of entry."),
        "example" => "example.com"
      ),
      "TXT" => array (
        "type" => "string",
        "description" => __ ( "The text extract of entry."),
        "example" => "example.com IN NAPTR 0 0 5060 example.com"
      )
    )
  )
);

/**
 * API call to check VoIP DNS zone
 */
framework_add_hook (
  "dns_check",
  "dns_check",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "The DNS check results."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "NAPTR" => array (
              "type" => "array",
              "description" => __ ( "The Name Authority Pointer (NAPTR) for VoIP at requested zone."),
              "items" => array (
                "\$ref" => "#/components/schemas/dnsentry",
                "nullable" => true
              )
            ),
            "SRVUDP" => array (
              "type" => "array",
              "description" => __ ( "The UDP SIP service (SRV) for VoIP at requested zone."),
              "items" => array (
                "\$ref" => "#/components/schemas/dnsentry",
                "nullable" => true
              )
            ),
            "SRVTCP" => array (
              "type" => "array",
              "description" => __ ( "The TCP SIP service (SRV) for VoIP at requested zone."),
              "items" => array (
                "\$ref" => "#/components/schemas/dnsentry",
                "nullable" => true
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
            "Zone" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Zone not found.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "dns_check", __ ( "Check VoIP DNS zone"));
framework_add_api_call (
  "/config/dns/:Zone",
  "Read",
  "dns_check",
  array (
    "permissions" => array ( "user", "dns_check"),
    "title" => __ ( "Check DNS"),
    "description" => __ ( "Check VoIP DNS zone."),
    "parameters" => array (
      array (
        "name" => "Zone",
        "type" => "string",
        "description" => __ ( "The DNS zone to be checked."),
        "example" => __ ( "example.com")
      )
    )
  )
);

/**
 * Function to check a VoIP DNS zone.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dns_check ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "dns_check_start"))
  {
    $parameters = framework_call ( "dns_check_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Check if domain exist
   */
  if ( ! checkdnsrr ( trim ( $parameters["Zone"]) . ( substr ( trim ( $parameters["Zone"]), -1) != "." ? "." : ""), "ANY"))
  {
    $data["Zone"] = __ ( "Zone not found.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "dns_check_validate"))
  {
    $data = framework_call ( "dns_check_validate", $parameters);
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
  $parameters["Zone"] = trim ( $parameters["Zone"]);
  if ( substr ( $parameters["Zone"], -1) != ".")
  {
    $parameters["Zone"] = $parameters["Zone"] . ".";
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "dns_check_sanitize"))
  {
    $parameters = framework_call ( "dns_check_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "dns_check_pre"))
  {
    $parameters = framework_call ( "dns_check_pre", $parameters, false, $parameters);
  }

  /**
   * First, check for Name Authority Pointer (NAPTR) record
   */
  $data = array ();
  $naptr = dns_get_record ( $parameters["Zone"], DNS_NAPTR);
  usort ( $naptr, "sort_naptr");
  $data["NAPTR"] = array ();
  foreach ( $naptr as $record)
  {
    $data["NAPTR"][] = array (
      "Host" => $record["host"],
      "Class" => $record["class"],
      "TTL" => $record["ttl"],
      "Type" => $record["type"],
      "Order" => $record["order"],
      "Preference" => $record["pref"],
      "Flags" => $record["flags"],
      "Services" => $record["services"],
      "Regex" => $record["regex"],
      "Replacement" => $record["replacement"],
      "Priority" => $record["pri"],
      "Weight" => $record["weight"],
      "Port" => $record["port"],
      "Target" => $record["target"],
      "TXT" => $record["class"] . " " . $record["type"] . " " . $record["order"] . " " . $record["pref"] . " \"" . $record["flags"] . "\" \"" . $record["services"] . "\" \"" . $record["regex"] . "\" \"" . $record["replacement"] . "\""
    );
  }

  /**
   * Second, check for Service (SRV) _sip._udp record
   */
  $srvudp = dns_get_record ( "_sip._udp." . $parameters["Zone"], DNS_SRV);
  usort ( $srvudp, "sort_srv");
  $data["SRVUDP"] = array ();
  foreach ( $srvudp as $record)
  {
    $data["SRVUDP"][] = array (
      "Host" => $record["host"],
      "Class" => $record["class"],
      "TTL" => $record["ttl"],
      "Type" => $record["type"],
      "Order" => $record["order"],
      "Preference" => $record["pref"],
      "Flags" => $record["flags"],
      "Services" => $record["services"],
      "Regex" => $record["regex"],
      "Replacement" => $record["replacement"],
      "Priority" => $record["pri"],
      "Weight" => $record["weight"],
      "Port" => $record["port"],
      "Target" => $record["target"],
      "TXT" => $record["host"] . " " . $record["class"] . " " . $record["type"] . " " . $record["pri"] . " " . $record["weight"] . " " . $record["port"] . " " . $record["target"]
    );
  }

  /**
   * Third, check for Service (SRV) _sip._tcp, _sips._tcp and _sip._tls record
   */
  $srvtcp = dns_get_record ( "_sip._tcp." . $parameters["Zone"], DNS_SRV);
  usort ( $srvtcp, "sort_srv");
  $data["SRVTCP"] = array ();
  foreach ( $srvtcp as $record)
  {
    $data["SRVTCP"][] = array (
      "Host" => $record["host"],
      "Class" => $record["class"],
      "TTL" => $record["ttl"],
      "Type" => $record["type"],
      "Order" => $record["order"],
      "Preference" => $record["pref"],
      "Flags" => $record["flags"],
      "Services" => $record["services"],
      "Regex" => $record["regex"],
      "Replacement" => $record["replacement"],
      "Priority" => $record["pri"],
      "Weight" => $record["weight"],
      "Port" => $record["port"],
      "Target" => $record["target"],
      "TXT" => $record["host"] . " " . $record["class"] . " " . $record["type"] . " " . $record["pri"] . " " . $record["weight"] . " " . $record["port"] . " " . $record["target"]
    );
  }
  $srvtcp = dns_get_record ( "_sips._tcp." . $parameters["Zone"], DNS_SRV);
  usort ( $srvtcp, "sort_srv");
  foreach ( $srvtcp as $record)
  {
    $data["SRVTCP"][] = array (
      "Host" => $record["host"],
      "Class" => $record["class"],
      "TTL" => $record["ttl"],
      "Type" => $record["type"],
      "Order" => $record["order"],
      "Preference" => $record["pref"],
      "Flags" => $record["flags"],
      "Services" => $record["services"],
      "Regex" => $record["regex"],
      "Replacement" => $record["replacement"],
      "Priority" => $record["pri"],
      "Weight" => $record["weight"],
      "Port" => $record["port"],
      "Target" => $record["target"],
      "TXT" => $record["host"] . " " . $record["class"] . " " . $record["type"] . " " . $record["pri"] . " " . $record["weight"] . " " . $record["port"] . " " . $record["target"]
    );
  }
  $srvtcp = dns_get_record ( "_sip._tls." . $parameters["Zone"], DNS_SRV);
  usort ( $srvtcp, "sort_srv");
  foreach ( $srvtcp as $record)
  {
    $data["SRVTCP"][] = array (
      "Host" => $record["host"],
      "Class" => $record["class"],
      "TTL" => $record["ttl"],
      "Type" => $record["type"],
      "Order" => $record["order"],
      "Preference" => $record["pref"],
      "Flags" => $record["flags"],
      "Services" => $record["services"],
      "Regex" => $record["regex"],
      "Replacement" => $record["replacement"],
      "Priority" => $record["pri"],
      "Weight" => $record["weight"],
      "Port" => $record["port"],
      "Target" => $record["target"],
      "TXT" => $record["host"] . " " . $record["class"] . " " . $record["type"] . " " . $record["pri"] . " " . $record["weight"] . " " . $record["port"] . " " . $record["target"]
    );
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "dns_check_post"))
  {
    $data = framework_call ( "dns_check_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "dns_check_finish"))
  {
    framework_call ( "dns_check_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get system global configuration information
 */
framework_add_hook (
  "configs_view",
  "configs_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing system configurations information."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Language" => array (
              "type" => "string",
              "enum" => array ( "en_US", "pt_BR"),
              "description" => __ ( "The default system language."),
              "example" => "en_US"
            ),
            "Operator" => array (
              "type" => "integer",
              "description" => __ ( "The extension internal system unique identifier of the system operator."),
              "example" => 1
            ),
            "MOH" => array (
              "type" => "integer",
              "description" => __ ( "The system unique identifier audio file for default system music on hold."),
              "example" => 1
            ),
            "NTP" => array (
              "type" => "array",
              "description" => __ ( "A list of NTP servers to be used in the system."),
              "items" => array (
                "type" => "string",
                "description" => __ ( "The NTP server address."),
                "example" => array (
                  "pool.ntp.org",
                  "ntp.example.com"
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "configs_view", __ ( "View system configurations information"));
framework_add_api_call (
  "/config",
  "Read",
  "configs_view",
  array (
    "permissions" => array ( "administrator", "configs_view"),
    "title" => __ ( "View configurations"),
    "description" => __ ( "View system configurations information.")
  )
);

/**
 * Function to generate system configurations information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function configs_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "configs_view_start"))
  {
    $parameters = framework_call ( "configs_view_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "configs_view_validate"))
  {
    $data = framework_call ( "configs_view_validate", $parameters);
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
  if ( framework_has_hook ( "configs_view_sanitize"))
  {
    $parameters = framework_call ( "configs_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "configs_view_pre"))
  {
    $parameters = framework_call ( "configs_view_pre", $parameters, false, $parameters);
  }

  /**
   * Get configurations from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` = 'Language' OR `Key` = 'Operator' OR `Key` = 'MOH' OR `Key` = 'NTP'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $data = array ( "Language" => "", "Operator" => "", "MOH" => "", "NTP" => array ());
  while ( $entry = $result->fetch_assoc ())
  {
    if ( is_json ( $entry["Data"]))
    {
      $entry["Data"] = json_decode ( $entry["Data"], true);
    }
    $data[$entry["Key"]] = $entry["Data"];
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "configs_view_post"))
  {
    $data = framework_call ( "configs_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "configs_view_finish"))
  {
    framework_call ( "configs_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to change system configurations
 */
framework_add_hook (
  "configs_edit",
  "configs_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The default system language."),
          "required" => false,
          "enum" => array_keys ( $_in["languages"]),
          "example" => "en_US"
        ),
        "Operator" => array (
          "type" => "integer",
          "description" => __ ( "The extension internal system unique identifier of the system operator."),
          "required" => false,
          "example" => 1
        ),
        "MOH" => array (
          "type" => "integer",
          "description" => __ ( "The audio internal system unique identified of the default music on hold of the system."),
          "required" => false,
          "example" => 1
        ),
        "NTP" => array (
          "type" => "array",
          "xml" => array (
            "name" => "NTP",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "string",
            "description" => __ ( "The NTP server hostname."),
            "example" => array (
              "pool.ntp.org",
              "ntp.example.com"
            )
          )
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "System configurations updated sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Language" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected language is not valid.")
            ),
            "Operator" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected operator is not valid.")
            ),
            "MOH" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected MOH audio is not valid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "configs_edit", __ ( "Change configurations"));
framework_add_api_call (
  "/config",
  "Modify",
  "configs_edit",
  array (
    "permissions" => array ( "user", "configs_edit"),
    "title" => __ ( "Change configurations"),
    "description" => __ ( "Change system global configurations.")
  )
);
framework_add_api_call (
  "/config",
  "Edit",
  "configs_edit",
  array (
    "permissions" => array ( "user", "configs_edit"),
    "title" => __ ( "Change configurations"),
    "description" => __ ( "Change system global configurations.")
  )
);

/**
 * Function to change system global configurations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function configs_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "configs_edit_start"))
  {
    $parameters = framework_call ( "configs_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! empty ( $parameters["Language"]) && ! array_key_exists ( $parameters["Language"], $_in["languages"]))
  {
    $data["Language"] = __ ( "The selected language is not valid.");
  }

  /**
   * Check for valid operator extension
   */
  if ( ! empty ( $parameters["Operator"]))
  {
    // **TODO**: Check if extension type is "dialable" (phone, group, etc) and not a "block" or other non-dialable number
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Operator"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["Operator"] = __ ( "The selected operator is not valid.");
    }
    $operatorextension = $result->fetch_assoc ()["Number"];
  }

  /**
   * Check for valid MOH audio
   */
  if ( ! empty ( $parameters["MOH"]))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["MOH"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["MOH"] = __ ( "The selected MOH audio is not valid.");
    }
  }

  /**
   * Check if every NTP server is valid
   */
  if ( ! is_array ( $parameters["NTP"]))
  {
    $parameters["NTP"] = array ();
  }
  foreach ( $parameters["NTP"] as $index => $ntpserver)
  {
    if ( ! filter_var ( gethostbyname ( $ntpserver), FILTER_VALIDATE_IP))
    {
      $data["NTP_" . ( $index + 1)] = __ ( "The NTP server is not valid.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "configs_edit_validate"))
  {
    $data = framework_call ( "configs_edit_validate", $parameters, false, $data);
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
  $parameters["MOH"] = (int) $parameters["MOH"];
  $parameters["Operator"] = (int) $parameters["Operator"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "configs_edit_sanitize"))
  {
    $parameters = framework_call ( "configs_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "configs_edit_pre"))
  {
    $parameters = framework_call ( "configs_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Get configurations from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` = 'Language' OR `Key` = 'Operator' OR `Key` = 'MOH' OR `Key` = 'NTP'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parmeters["ORIGINAL"] = array ( "Language" => "", "Operator" => "", "MOH" => "", "NTP" => array ());
  while ( $entry = $result->fetch_assoc ())
  {
    if ( is_json ( $entry["Data"]))
    {
      $entry["Data"] = json_decode ( $entry["Data"], true);
    }
    $parameters["ORIGINAL"][$entry["Key"]] = $entry["Data"];
  }

  /**
   * Change each configuration entry
   */
  if ( ! empty ( $parameters["Language"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Data`) VALUES ('Language', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }
  if ( ! empty ( $parameters["MOH"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Data`) VALUES ('MOH', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["MOH"]) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["MOH"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }
  if ( ! empty ( $parameters["Operator"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Data`) VALUES ('Operator', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Operator"]) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Operator"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }
  if ( sizeof ( $parameters["NTP"]) != 0)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Data`) VALUES ('NTP', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["NTP"])) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["NTP"])) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "configs_edit_post"))
  {
    framework_call ( "configs_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  if ( ! empty ( $parameters["Language"]))
  {
    $notify = array ( "Language" => $parameters["Language"]);
    if ( framework_has_hook ( "configs_language_notify"))
    {
      $notify = framework_call ( "configs_language_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "configs_language", $notify);
  }
  if ( ! empty ( $parameters["Operator"]))
  {
    $notify = array ( "Operator" => $operatorextension);
    if ( framework_has_hook ( "configs_operator_notify"))
    {
      $notify = framework_call ( "configs_operator_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "configs_operator", $notify);
  }
  if ( ! empty ( $parameters["MOH"]))
  {
    $notify = array ( "MOH" => $parameters["MOH"]);
    if ( framework_has_hook ( "configs_moh_notify"))
    {
      $notify = framework_call ( "configs_moh_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "configs_moh", $notify);
  }
  if ( sizeof ( $parameters["NTP"]) != 0)
  {
    $notify = array ( "NTP" => $parameters["NTP"]);
    if ( framework_has_hook ( "configs_ntp_notify"))
    {
      $notify = framework_call ( "configs_ntp_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "configs_ntp", $notify);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "configs_edit_finish"))
  {
    framework_call ( "configs_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to import plugin files
 */
framework_add_hook (
  "plugin_import",
  "plugin_import",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "plugin" => array (
          "type" => "string",
          "format" => "binary",
          "description" => __ ( "The plugion file to be installed."),
          "required" => true
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system plugin installed sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "result" => array (
              "type" => "boolean",
              "description" => __ ( "The status of add request."),
              "example" => false
            ),
            "message" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid plugin file.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "plugin_import", __ ( "Import plugin file"));
framework_add_api_call (
  "/config/plugin",
  "Create",
  "plugin_import",
  array (
    "permissions" => array ( "administrator", "plugin_import"),
    "title" => __ ( "Import plugin"),
    "description" => __ ( "Import a plugin file.")
  )
);

/**
 * Function to import plugin files.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function plugin_import ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "plugin_import_start"))
  {
    $parameters = framework_call ( "plugin_import_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Process plugin file
   */
  $parameters["zipfile"] = "";
  if ( is_uploaded_file ( $_FILES["plugin"]["tmp_name"]))
  {
    if ( strtolower ( substr ( $_FILES["plugin"]["name"], strrpos ( $_FILES["plugin"]["name"], ".") + 1)) != "zip" || $_FILES["plugin"]["type"] != "application/zip")
    {
      $data["Message"] = __ ( "Invalid plugin extension.");
    } else {
      /**
       * Move uploaded plugin file to spool plugin directory
       */
      $parameters["zipfile"] = $_in["general"]["spooldir"] . "/plugins/" . basename ( $_FILES["plugin"]["name"]);
      $parameters["tmpdir"] = $_in["general"]["spooldir"] . "/plugins/" . md5 ( uniqid ( rand (), true));
      move_uploaded_file ( $_FILES["plugin"]["tmp_name"], $parameters["zipfile"]);
    }
  } else {
    $data["Message"] = __ ( "Invalid plugin file.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "plugin_import_validate"))
  {
    $data = framework_call ( "plugin_import_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    if ( ! empty ( $parameters["zipfile"]))
    {
      unlink ( $parameters["zipfile"]);
    }
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "plugin_import_pre"))
  {
    $parameters = framework_call ( "plugin_import_pre", $parameters, false, $parameters);
  }

  /**
   * Before uncompress zip file, remove any directory with same name
   */
  if ( file_exists ( $parameters["tmpdir"]))
  {
    unlink_recursive ( $parameters["tmpdir"]);
  }
  mkdir ( $parameters["tmpdir"]);

  /**
   * Uncompress zip and check basic file structure
   *
   * Plugin zip must have at least:
   * - all files inside a directory with their own plugin name
   * - at least one file of "api.php", "webui.php" or "filter.php"
   */
  $parameters["files"] = array ();
  $parameters["integrity"] = array ();
  $parameters["name"] = "";
  if ( ! array_key_exists ( "file", $data))
  {
    $zip = zip_open ( $parameters["zipfile"]);
    $consistency = array ( "reqFile" => false, "confFile" => false, "directory" => true);
    if ( is_resource ( $zip))
    {
      while ( $file = zip_read ( $zip))
      {
        $filename = zip_entry_name ( $file);
        $parameters["files"][] = $filename;
        if ( substr ( $filename, -1) == "/")
        {
          if ( empty ( $parameters["name"]))
          {
            $parameters["name"] = substr ( $filename, 0, strrpos ( $filename, "/"));
          }
          mkdir ( $parameters["tmpdir"] . "/" . $filename);
        } else {
          zip_entry_open ( $zip, $file, "rb");
          file_put_contents ( $parameters["tmpdir"] . "/" . $filename, zip_entry_read ( $file, zip_entry_filesize ( $file)));
          zip_entry_close ( $file);
        }
        if ( $filename == $parameters["name"] . "/" . $parameters["name"] . ".cfg")
        {
          $consistency["confFile"] = true;
        }
        if ( $filename == $parameters["name"] . "/api.php")
        {
          $consistency["reqFile"] = true;
          $parameters["integrity"]["api"] = "A";
        }
        if ( $filename == $parameters["name"] . "/filter.php")
        {
          $consistency["reqFile"] = true;
          $parameters["integrity"]["filter"] = "F";
        }
        if ( $filename == $parameters["name"] . "/webui.php")
        {
          $consistency["reqFile"] = true;
          $parameters["integrity"]["module"] = "M";
        }
        if ( $filename == $parameters["name"] . "/config.php")
        {
          $parameters["integrity"]["config"] = "C";
        }
        if ( $filename == $parameters["name"] . "/language.php")
        {
          $parameters["integrity"]["language"] = "L";
        }
        if ( $filename == $parameters["name"] . "/install.php")
        {
          $parameters["integrity"]["install"] = "I";
        }
        if ( substr ( $filename, 0, strpos ( $filename, "/")) != $parameters["name"])
        {
          $consistency["directory"] = false;
        }
      }
      if ( $consistency["directory"] == false)
      {
        $data["message"] = __ ( "Invalid plugin file structure.");
      }
      if ( ! array_key_exists ( "message", $data) && $consistency["confFile"] != true)
      {
        $data["message"] = __ ( "Plugin configuration file missing.");
      }
      if ( ! array_key_exists ( "message", $data) && $consistency["reqFile"] != true)
      {
        $data["message"] = __ ( "Plugin file doesn't have the minimum required files.");
      }
      zip_close ( $zip);
    } else {
      $data["message"] = __ ( "Error opening plugin zip file.");
    }
  }

  /**
   * Check basic plugin information
   */
  if ( ! array_key_exists ( "message", $data) && $consistency["confFile"] == true)
  {
    $config = parse_ini_file ( $parameters["tmpdir"] . "/" . $parameters["name"] . "/" . $parameters["name"] . ".cfg", true);
    if ( ! array_key_exists ( $parameters["name"], $config))
    {
      $data["message"] = __ ( "Plugin configuration file misses own plugin section.");
    }
    if ( ! array_key_exists ( "message", $data) && ! array_key_exists ( "version", $config[$parameters["name"]]))
    {
      $data["message"] = __ ( "Plugin configuration file misses own plugin section.");
    }
    if ( ! array_key_exists ( "message", $data) && ! array_key_exists ( "name", $config[$parameters["name"]]))
    {
      $data["message"] = __ ( "Plugin configuration file misses own plugin name.");
    }
    if ( ! array_key_exists ( "message", $data) && ! array_key_exists ( "description", $config[$parameters["name"]]))
    {
      $data["message"] = __ ( "Plugin configuration file misses own plugin section.");
    }
    if ( ! array_key_exists ( "message", $data) && ! array_key_exists ( "author", $config[$parameters["name"]]))
    {
      $data["message"] = __ ( "Plugin configuration file misses own plugin section.");
    }
    if ( ! array_key_exists ( "message", $data) && ! array_key_exists ( "license", $config[$parameters["name"]]))
    {
      $data["message"] = __ ( "Plugin configuration file misses own plugin section.");
    }
    if ( ! array_key_exists ( "message", $data) && ! array_key_exists ( "require", $config[$parameters["name"]]))
    {
      $data["message"] = __ ( "Plugin configuration file misses own plugin section.");
    }
    if ( ! array_key_exists ( "message", $data))
    {
      $parameters["config"] = array ();
      $parameters["config"]["name"] = $config[$parameters["name"]]["name"];
      $parameters["config"]["version"] = $config[$parameters["name"]]["version"];
      $parameters["config"]["description"] = $config[$parameters["name"]]["description"];
      $parameters["config"]["author"] = $config[$parameters["name"]]["author"];
      $parameters["config"]["email"] = $config[$parameters["name"]]["email"];
      $parameters["config"]["license"] = $config[$parameters["name"]]["license"];
      $parameters["config"]["require"] = explode ( ",", $config[$parameters["name"]]["require"]);
      $parameters["config"]["filesize"] = filesize ( $parameters["zipfile"]);
      $parameters["config"]["supported"] = true;
      $parameters["config"]["missing"] = array ();
      foreach ( $parameters["config"]["require"] as $reqstring)
      {
        if ( ! preg_match ( "/^(\w+) (=|==|<|<=|=<|>|>=|=>|!=|<>) ([\d]+(\.[\d+])?)$/", trim ( $reqstring), $matches))
        {
          $data["message"] = __ ( "Error parsing plugin minimum system requirements.");
          break;
        }
        $reqmodule = $matches[1];
        if ( sizeof ( $matches) > 2)
        {
          $reqcond = $matches[2];
          $reqversion = (float) $matches[3];
        } else {
          $reqcond = ">=";
          $reqversion = 0;
        }
        if ( $reqmodule == "core")
        {
          $reqmoduleversion = (float) $_in["version"];
        } else {
          if ( ! array_key_exists ( $reqmodule, $_in["modules"]))
          {
            $parameters["config"]["supported"] = false;
            $parameters["config"]["missing"][] = array ( "module" => $reqmodule, "condition" => $reqcond, "version" => $reqversion);
            continue;
          }
          $reqmoduleversion = (float) $_in["modules"][$reqmodule]["version"];
        }
        switch ( $reqcond)
        {
          case ">=":
          case "=>":
            if ( $reqmoduleversion < $reqversion)
            {
              $parameters["config"]["supported"] = false;
              $parameters["config"]["missing"][] = array ( "module" => $reqmodule, "condition" => $reqcond, "version" => $reqversion);
            }
            break;
          case ">":
            if ( $reqmoduleversion <= $reqversion)
            {
              $parameters["config"]["supported"] = false;
              $parameters["config"]["missing"][] = array ( "module" => $reqmodule, "condition" => $reqcond, "version" => $reqversion);
            }
            break;
          case "<=":
          case "=<":
            if ( $reqmoduleversion > $reqversion)
            {
              $parameters["config"]["supported"] = false;
              $parameters["config"]["missing"][] = array ( "module" => $reqmodule, "condition" => $reqcond, "version" => $reqversion);
            }
            break;
          case "<":
            if ( $reqmoduleversion >= $reqversion)
            {
              $parameters["config"]["supported"] = false;
              $parameters["config"]["missing"][] = array ( "module" => $reqmodule, "condition" => $reqcond, "version" => $reqversion);
            }
            break;
          case "=":
          case "==":
            if ( $reqmoduleversion != $reqversion)
            {
              $parameters["config"]["supported"] = false;
              $parameters["config"]["missing"][] = array ( "module" => $reqmodule, "condition" => $reqcond, "version" => $reqversion);
            }
            break;
          case "!=":
          case "<>":
            if ( $reqmoduleversion == $reqversion)
            {
              $parameters["config"]["supported"] = false;
              $parameters["config"]["missing"][] = array ( "module" => $reqmodule, "condition" => $reqcond, "version" => $reqversion);
            }
            break;
        }
      }
    }
  }

  /**
   * Process all plugin files, calling our internal /api/config/plugin/file API
   * call.
   * We need to do this way calling our own API because we check for PHP syntax
   * errors, and if you just include or try to parse the plugin file with an
   * error, it will fail and finish our process, making impossible to return the
   * exactly error.
   */
  if ( ! array_key_exists ( "message", $data))
  {
    $apiurl = "http" . ( $_SERVER["HTTPS"] == "on" ? "s" : "") . "://" . $_SERVER["SERVER_NAME"] . ( ( $_SERVER["HTTPS"] == "on" && $_SERVER["SERVER_PORT"] != "443") || ( $_SERVER["HTTPS"] != "on" && $_SERVER["SERVER_PORT"] != "80") ? ":" . $_SERVER["SERVER_PORT"] : "") . "/api/config/testfile";
    foreach ( $parameters["integrity"] as $filedesc => $filetype)
    {
      $socket = curl_init ();
      curl_setopt ( $socket, CURLOPT_URL, $apiurl);
      curl_setopt ( $socket, CURLOPT_POST, true);
      curl_setopt ( $socket, CURLOPT_POSTFIELDS, json_encode ( array ( "name" => $parameters["name"], "tmpdir" => basename ( $parameters["tmpdir"]), "file" => $filetype)));
      curl_setopt ( $socket, CURLOPT_USERAGENT, "VoIP Domain Interface v" . $_in["version"] . " (Linux; U)");
      curl_setopt ( $socket, CURLOPT_TIMEOUT, 60);
      curl_setopt ( $socket, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt ( $socket, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt ( $socket, CURLOPT_HTTPHEADER, array ( "Content-Type: application/json", "Accept: application/json", "X-HTTP-Method-Override: GET"));
      curl_setopt ( $socket, CURLOPT_HEADER, false);
      curl_setopt ( $socket, CURLOPT_RETURNTRANSFER, true);
      $result = @curl_exec ( $socket);
      curl_close ( $socket);
      $parameters["integrity"][$filedesc] = json_decode ( $result, true);
      if ( $result == "" || json_last_error () != JSON_ERROR_NONE)
      {
        $parameters["integrity"][$filedesc] = array ( "result" => false, "message" => $result);
        $data["message"] = __ ( "Plugin parser error.");
      }
    }
  }

  /**
   * Check if plugin already exists
   */
  if ( ! array_key_exists ( "message", $data))
  {
    if ( file_exists ( dirname ( __FILE__) . "../../plugins/" . $parameters["name"]))
    {
      $data["message"] = __ ( "Plugin already installed.");
    } else {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Plugins` WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "'"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows != 0)
      {
        $data["message"] = __ ( "Plugin already installed.");
      }
    }
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    unlink ( $parameters["zipfile"]);
    unlink_recursive ( $parameters["tmpdir"]);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Add plugin to database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Plugins` (`Dirname`, `Name`, `Version`, `Author`, `Description`, `License`, `Status`, `Requires`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( (float) $parameters["config"]["version"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["author"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["license"]) . "', 'A', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["config"]["require"])) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $data["message"] = sprintf ( __ ( "Plugin %s (version %s) sucessfully installed."), $parameters["config"]["name"], $parameters["config"]["version"]);

  /**
   * Move plugin to system plugins directory
   */
  rename ( $parameters["tmpdir"] . "/" . $parameters["name"], dirname ( __FILE__) . "/../../plugins/" . $parameters["name"]);

  // **TODO**: Need to implement "install.php" call to deploy database, "update.php" if updating plugin, and need to export PHP files to Asterisk Server, FastAGI server, etc...

  /**
   * Remove temporary directory
   */
  unlink_recursive ( $parameters["tmpdir"]);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "plugin_import_post"))
  {
    $data = framework_call ( "plugin_import_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "plugin_import_finish"))
  {
    framework_call ( "plugin_import_finish", $parameters, false);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to check validate VoIP Domain plugin file (internal use only)
 */
framework_add_hook (
  "plugin_check_file",
  "plugin_check_file",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "tmpdir" => array (
          "type" => "string",
          "description" => __ ( "The temporary plugin directory location."),
          "required" => true,
          "example" => "777721ffbaef1d13d4661b3184b3b753"
        ),
        "name" => array (
          "type" => "string",
          "description" => __ ( "The name of the plugin."),
          "required" => true,
          "example" => "users-avatar"
        ),
        "file" => array (
          "type" => "string",
          "enum" => array ( "A", "C", "F", "L", "M", "I"),
          "description" => __ ( "The type of file that will be checked. A letter corresponding to the file:<br />`A` = api.php<br />`C` = config.php<br />`F` = filter.php<br />`L` = language.php<br />`M` = webui.php<br />`I` = install.php", false, false),
          "required" => true,
          "example" => "A"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the plugin file information."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "result" => array (
              "type" => "boolean",
              "description" => __ ( "The status of current view request."),
              "example" => true
            ),
            "api" => array (
              "type" => "object",
              "description" => __ ( "The installed API hooks array description."),
              "additionalProperties" => array (
                "type" => "string"
              )
            ),
            "filters" => array (
              "type" => "object",
              "description" => __ ( "The installed filters array description."),
              "additionalProperties" => array (
                "type" => "string"
              )
            ),
            "plugins" => array (
              "type" => "object",
              "description" => __ ( "The installed plugins array description."),
              "additionalProperties" => array (
                "type" => "string"
              )
            ),
            "includes" => array (
              "type" => "object",
              "description" => __ ( "The installed included files array description."),
              "additionalProperties" => array (
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
            "result" => array (
              "type" => "boolean",
              "description" => __ ( "The status of add request."),
              "example" => false
            ),
            "file" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid file type.")
            ),
            "name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Plugin file not found!")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/config/testfile",
  "Read",
  "plugin_check_file",
  array (
    "permissions" => array ( "internal"),
    "title" => __ ( "Check plugin file"),
    "description" => __ ( "Internal endpoint to check and validate a plugin file.")
  )
);

/**
 * Function to check a VoIP Domain plugin internal file. This function has been
 * created to internal system call, to validate a plugin file when checking it,
 * because PHP will trigger a fatal error if any syntax or other error happens
 * when include file, making it impossible to return a detailed check to system,
 * so, we include here and watch for response error message if happens,
 * otherwise, return a complete list of included files, included system hook's,
 * filters, etc.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @global array $_filters Framework global filter configuration variable
 * @global array $_plugins Framework global plugin configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function plugin_check_file ( $buffer, $parameters)
{
  global $_in, $_api, $_filters, $_plugins;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "plugin_check_file_start"))
  {
    $parameters = framework_call ( "plugin_check_file_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  switch ( basename ( $parameters["file"]))
  {
    case "A":
      $parameters["file"] = "api.php";
      break;
    case "C":
      $parameters["file"] = "config.php";
      break;
    case "F":
      $parameters["file"] = "filter.php";
      break;
    case "L":
      $parameters["file"] = "language.php";
      break;
    case "M":
      $parameters["file"] = "webui.php";
      break;
    case "I":
      $parameters["file"] = "install.php";
      break;
    default:
      $data["file"] = __ ( "Invalid file type.");
      break;
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "plugin_check_file_validate"))
  {
    $data = framework_call ( "plugin_check_file_validate", $parameters, false, $data);
  }

  /**
   * Check if file to validate exist
   */
  if ( ! array_key_exists ( "file", $data) && ! file_exists ( $_in["general"]["spooldir"] . "/plugins/" . basename ( $parameters["tmpdir"]) . "/" . basename ( $parameters["name"]) . "/" . $parameters["file"]))
  {
    $data["name"] = __ ( "Plugin file not found!");
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
  $parameters["tmpdir"] = basename ( $parameters["tmpdir"]);
  $parameters["name"] = basename ( $parameters["name"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "plugin_check_file_sanitize"))
  {
    $parameters = framework_call ( "plugin_check_file_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "plugin_check_file_pre"))
  {
    $parameters = framework_call ( "plugin_check_file_pre", $parameters, false, $parameters);
  }

  /**
   * Change current module to plugin name
   */
  $current = $_in["module"];
  $_in["module"] = $parameters["name"];

  /**
   * Process plugin module file
   */
  $beforeapi = $_api;
  $beforefilters = $_filters;
  $beforeplugins = $_plugins;
  $beforeincluded = get_included_files ();
  $olderrors = ini_set ( "display_errors", true);
  $oldhtml = ini_set ( "html_errors", false);
  $oldlevel = ini_set ( "error_reporting", E_ERROR | E_PARSE);
  include ( $_in["general"]["spooldir"] . "/plugins/" . $parameters["tmpdir"] . "/" . $parameters["name"] . "/" . $parameters["file"]);
  ini_set ( "display_errors", $olderrors);
  ini_set ( "html_errors", $oldhtml);
  ini_set ( "error_reporting", $oldlevel);
  $data["api"] = array_diff_recursive ( $beforeapi, $_api);
  $data["filters"] = array_diff_recursive ( $beforefilters, $_filters);
  $data["plugins"] = array_diff_recursive ( $beforeplugins, $_plugins);
  $data["includes"] = array_diff_recursive ( $beforeincluded, get_included_files ());
  foreach ( $data["includes"] as $index => $value)
  {
    $data["includes"][$index] = str_replace ( $_in["general"]["spooldir"] . "/plugins/" . $parameters["tmpdir"] . "/", "", $value);
  }

  /**
   * Restore current module name
   */
  $_in["module"] = $current;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "plugin_check_file_post"))
  {
    $data = framework_call ( "plugin_check_file_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "plugin_check_file_finish"))
  {
    framework_call ( "plugin_check_file_finish", $parameters, false);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to search plugins
 */
framework_add_hook (
  "plugins_search",
  "plugins_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all plugins."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "Dirname,Name,Version,Description,Status",
          "example" => "Dirname,Name,Version,Author,Description,Status,Requires"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system plugins."),
        "schema" => array (
          "type" => "array",
          "items" => array (
            "type" => "object",
            "properties" => array (
              "Dirname" => array (
                "type" => "string",
                "description" => __ ( "The directory name of the plugin."),
                "example" => "users-avatar"
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the plugin."),
                "example" => __ ( "Users avatar")
              ),
              "Version" => array (
                "type" => "integer",
                "format" => "float",
                "description" => __ ( "The version of the plugin."),
                "example" => 1.1
              ),
              "Author" => array (
                "type" => "string",
                "description" => __ ( "The author of the plugin."),
                "example" => "Ernani José Camargo Azevedo"
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the plugin."),
                "example" => __ ( "Add avatar to user profile at system interface.")
              ),
              "Status" => array (
                "type" => "boolean",
                "description" => __ ( "The status of the plugin. If true, it's installed and in use.", true, false),
                "example" => true
              ),
              "Requires" => array (
                "type" => "array",
                "description" => __ ( "An array containing all plugin requirements."),
                "items" => array (
                  "type" => "string",
                  "description" => __ ( "The required module or plugin."),
                  "example" => "core >= 1.0"
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "plugins_search", __ ( "Search plugins"));
framework_add_api_call (
  "/config/plugin",
  "Read",
  "plugins_search",
  array (
    "permissions" => array ( "administrator", "plugins_search"),
    "title" => __ ( "Search plugins"),
    "description" => __ ( "Search for system plugins.")
  )
);

/**
 * Function to search plugins.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function plugins_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "plugins_search_start"))
  {
    $parameters = framework_call ( "plugins_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Plugins");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "plugins_search_validate"))
  {
    $data = framework_call ( "plugins_search_validate", $parameters);
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
  if ( framework_has_hook ( "plugins_search_sanitize"))
  {
    $parameters = framework_call ( "plugins_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "plugins_search_pre"))
  {
    $parameters = framework_call ( "plugins_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search plugins
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Plugins`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Name`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "Dirname,Name,Version,Description,Status", "Dirname,Name,Version,Author,Description,Status,Requires");
  while ( $result = $results->fetch_assoc ())
  {
    if ( strpos ( (string) $result["Version"], ".") === false)
    {
      $result["Version"] = $result["Version"] . ".0";
    }
    $result["Status"] = $result["Status"] == "A";
    $result["Requires"] = json_decode ( $result["Requires"], true);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "plugins_search_post"))
  {
    $data = framework_call ( "plugins_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "plugins_search_finish"))
  {
    framework_call ( "plugins_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to enable/disable plugins
 */
framework_add_hook (
  "plugins_toggle",
  "plugins_toggle",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "Plugin toggled enabled/disabled sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "result" => array (
              "type" => "boolean",
              "description" => __ ( "The status of add request."),
              "example" => false
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "plugins_toggle", __ ( "Toggle plugins enable/disable"));
framework_add_api_call (
  "/config/plugin/:Plugin/toggle",
  "Modify",
  "plugins_toggle",
  array (
    "permissions" => array ( "administrator", "plugins_toggle"),
    "title" => __ ( "Toggle plugin status"),
    "description" => __ ( "Toggle plugin status enabled/disabled."),
    "parameters" => array (
      array (
        "name" => "Plugin",
        "type" => "string",
        "description" => __ ( "The plugin directory name."),
        "example" => "users-avatar"
      )
    )
  )
);
framework_add_api_call (
  "/config/plugin/:Plugin/toggle",
  "Edit",
  "plugins_toggle",
  array (
    "permissions" => array ( "administrator", "plugins_toggle"),
    "title" => __ ( "Toggle plugin status"),
    "description" => __ ( "Toggle plugin status enabled/disabled.")
  )
);
framework_add_api_call (
  "/config/plugin/:Plugin/enable",
  "Modify",
  "plugins_toggle",
  array (
    "permissions" => array ( "administrator", "plugins_toggle"),
    "title" => __ ( "Enable plugin"),
    "description" => __ ( "Enable a system plugin."),
    "parameters" => array (
      array (
        "name" => "Plugin",
        "type" => "string",
        "description" => __ ( "The plugin directory name."),
        "example" => "users-avatar"
      )
    )
  )
);
framework_add_api_call (
  "/config/plugin/:Plugin/enable",
  "Edit",
  "plugins_toggle",
  array (
    "permissions" => array ( "administrator", "plugins_toggle"),
    "title" => __ ( "Enable plugin"),
    "description" => __ ( "Enable a system plugin.")
  )
);
framework_add_api_call (
  "/config/plugin/:Plugin/disable",
  "Modify",
  "plugins_toggle",
  array (
    "permissions" => array ( "administrator", "plugins_toggle"),
    "title" => __ ( "Disable plugin"),
    "description" => __ ( "Disable a system plugin."),
    "parameters" => array (
      array (
        "name" => "Plugin",
        "type" => "string",
        "description" => __ ( "The plugin directory name."),
        "example" => "users-avatar"
      )
    )
  )
);
framework_add_api_call (
  "/config/plugin/:Plugin/disable",
  "Edit",
  "plugins_toggle",
  array (
    "permissions" => array ( "administrator", "plugins_toggle"),
    "title" => __ ( "Disable plugin"),
    "description" => __ ( "Disable a system plugin.")
  )
);

/**
 * Function to toggle plugins enable/disable.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function plugins_toggle ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "plugins_toggle_start"))
  {
    $parameters = framework_call ( "plugins_toggle_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "plugins_toggle_validate"))
  {
    $data = framework_call ( "plugins_toggle_validate", $parameters);
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
  $parameters["Plugin"] = basename ( $parameters["Plugin"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "plugins_toggle_sanitize"))
  {
    $parameters = framework_call ( "plugins_toggle_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if plugin exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Plugins` WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Plugin"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $plugin = $result->fetch_assoc ();

  /**
   * Check plugin action
   */
  switch ( substr ( $parameters["api"]["path"], strrpos ( $parameters["api"]["path"], "/") + 1))
  {
    case "toggle":
      $newstatus = $plugin["Status"] == "A" ? "I" : "A";
      break;
    case "enable":
      $newstatus = "A";
      break;
    case "disable":
      $newstatus = "I";
      break;
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "plugins_toggle_pre"))
  {
    $parameters = framework_call ( "plugins_toggle_pre", $parameters, false, $parameters);
  }

  /**
   * Enable/disable plugin at database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Plugins` SET `Status` = '" . $_in["mysql"]["id"]->real_escape_string ( $newstatus) . "' WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Plugin"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Rename directory
   */
  if ( ! rename ( dirname ( __FILE__) . "/../../plugins/" . ( $newstatus == "A" ? "disabled_" : "") . $parameters["Plugin"], dirname ( __FILE__) . "/../../plugins/" . ( $newstatus == "A" ? "" : "disabled_") . $parameters["Plugin"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "plugins_toggle_post"))
  {
    $data = framework_call ( "plugins_toggle_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "plugins_toggle_finish"))
  {
    framework_call ( "plugins_toggle_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get plugin information
 */
framework_add_hook (
  "plugins_view",
  "plugins_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system plugin."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "result" => array (
              "type" => "boolean",
              "description" => __ ( "The status of current view request."),
              "example" => true
            ),
            "plugin" => array (
              "type" => "string",
              "description" => __ ( "The directory name of the plugin."),
              "example" => "users-avatar"
            ),
            "name" => array (
              "type" => "string",
              "description" => __ ( "The name of the plugin."),
              "example" => __ ( "Users avatar")
            ),
            "version" => array (
              "type" => "integer",
              "format" => "float",
              "description" => __ ( "The version of the plugin."),
              "example" => 1.1
            ),
            "author" => array (
              "type" => "string",
              "description" => __ ( "The author of the plugin."),
              "example" => "Ernani José Camargo Azevedo"
            ),
            "description" => array (
              "type" => "string",
              "description" => __ ( "The description of the plugin."),
              "example" => __ ( "Add avatar to user profile at system interface.")
            ),
            "license" => array (
              "type" => "string",
              "description" => __ ( "The license of the plugin."),
              "example" => "GPL v3"
            ),
            "status" => array (
              "type" => "boolean",
              "description" => __ ( "The status of the plugin. If true, it's installed and in use.", true, false),
              "example" => true
            ),
            "requires" => array (
              "type" => "array",
              "description" => __ ( "An array containing all plugin requirements."),
              "items" => array (
                "type" => "string",
                "description" => __ ( "The required module or plugin."),
                "example" => "core >= 1.0"
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
            "Plugin" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid plugin name.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "plugins_view", __ ( "View plugins"));
framework_add_api_call (
  "/config/plugin/:Plugin",
  "Read",
  "plugins_view",
  array (
    "permissions" => array ( "administrator", "plugins_view"),
    "title" => __ ( "View plugin"),
    "description" => __ ( "View system plugin information."),
    "parameters" => array (
      array (
        "name" => "plugin",
        "type" => "string",
        "description" => __ ( "The plugin directory name."),
        "example" => "users-avatar"
      )
    )
  )
);

/**
 * Function to view plugins.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function plugins_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "plugins_view_start"))
  {
    $parameters = framework_call ( "plugins_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Plugins");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Plugin", $parameters))
  {
    $data["Plugin"] = __ ( "Invalid plugin name.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "plugins_view_validate"))
  {
    $data = framework_call ( "plugins_view_validate", $parameters);
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
  $parameters["Plugin"] = basename ( $parameters["Plugin"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "plugins_view_sanitize"))
  {
    $parameters = framework_call ( "plugins_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "plugins_view_pre"))
  {
    $parameters = framework_call ( "plugins_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search plugins
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Plugins` WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Plugin"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $plugin = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["plugin"] = $plugin["Dirname"];
  $data["name"] = $plugin["Name"];
  $data["version"] = $plugin["Version"] . ( strpos ( (string) $plugin["Version"], ".") === false ? ".0" : "");
  $data["author"] = $plugin["Author"];
  $data["description"] = $plugin["Description"];
  $data["license"] = $plugin["License"];
  $data["status"] = $plugin["Status"] == "A" ? "on" : "off";
  $data["requires"] = json_decode ( $plugin["Requires"], true);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "plugins_view_post"))
  {
    $data = framework_call ( "plugins_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "plugins_view_finish"))
  {
    framework_call ( "plugins_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a plugin
 */
framework_add_hook (
  "plugins_remove",
  "plugins_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system plugin was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Plugin" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid plugin name.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "plugins_remove", __ ( "Remove plugins"));
framework_add_api_call (
  "/config/plugin/:Plugin",
  "Delete",
  "plugins_remove",
  array (
    "permissions" => array ( "administrator", "plugins_remove"),
    "title" => __ ( "Remove plugin"),
    "description" => __ ( "Remove a system plugin.")
  )
);

/**
 * Function to remove plugins.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function plugins_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "plugins_remove_start"))
  {
    $parameters = framework_call ( "plugins_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Plugin", $parameters))
  {
    $data["Plugin"] = __ ( "Invalid plugin name.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "plugins_remove_validate"))
  {
    $data = framework_call ( "plugins_remove_validate", $parameters, false, $data);
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
  $parameters["Plugin"] = basename ( $parameters["Plugin"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "plugins_remove_sanitize"))
  {
    $parameters = framework_call ( "plugins_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if plugin exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Plugins` WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Plugin"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ORIGINAL"] = $result->fetch_assoc ();

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "plugins_remove_pre"))
  {
    $parameters = framework_call ( "plugins_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove plugin database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Plugins` WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Plugin"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove plugin directory and information files
   */
  unlink_recursive ( $_in["general"]["spooldir"] . "/plugins/" . ( $parameters["ORIGINAL"]["Status"] == "I" ? "disabled_" : "") . $parameters["ORIGINAL"]["Dirname"]);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "plugins_remove_post"))
  {
    framework_call ( "plugins_remove_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "plugins_remove_finish"))
  {
    framework_call ( "plugins_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}
?>
