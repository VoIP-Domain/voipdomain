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
 * VoIP Domain configuration module API. This module add the API calls related to
 * system configuration.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Configuration
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
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
    "permissions" => array ( "Administrator", "permissions_view"),
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Key` = 'Permissions'"))
  {
    database_query_error ( "permissions_view");
  }
  if ( ! $config = $result->fetch_assoc ())
  {
    database_query_error ( "permissions_view");
  }

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
        "description" => __ ( "The system permissions were successfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
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
  array ( "Modify", "Edit"),
  "permissions_edit",
  array (
    "permissions" => array ( "Administrator", "permissions_edit"),
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Key` = 'Permissions'"))
  {
    database_query_error ( "permissions_edit");
  }
  if ( ! $parameters["ORIGINAL"] = json_decode ( $result->fetch_assoc ()["Data"], true))
  {
    database_query_error ( "permissions_edit");
  }

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
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Config` SET `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Permissions"])) . "' WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Key` = 'Permissions'"))
  {
    database_query_error ( "permissions_edit");
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
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
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
    "permissions" => array ( "User", "Administrator", "Super-Administrator", "dns_check"),
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
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dns_check ( $buffer, $parameters)
{
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
            "NumbersLength" => array (
              "type" => "integer",
              "minimum" => 2,
              "maximum" => 6,
              "description" => __ ( "The default system extension number length."),
              "example" => 4
            ),
            "ImmutableLength" => array (
              "type" => "boolean",
              "description" => __ ( "If the system extension number length is immutable."),
              "example" => false
            ),
            "Country" => array (
              "type" => "object",
              "description" => __ ( "The country code information of the tenant."),
              "properties" => array (
                "ID" => array (
                  "type" => "integer",
                  "description" => __ ( "The country code unique identifier."),
                  "example" => 840
                ),
                "NameEN" => array (
                  "type" => "string",
                  "description" => __ ( "The English name of the country."),
                  "example" => "United States of America"
                ),
                "Name" => array (
                  "type" => "string",
                  "description" => __ ( "The translated name of the country."),
                  "example" => __ ( "United States of America")
                ),
                "ISO3166-2" => array (
                  "type" => "string",
                  "description" => __ ( "The *ISO3166-2* of the country."),
                  "example" => __ ( "US")
                )
              )
            ),
            "TimeZone" => array (
              "type" => "string",
              "description" => __ ( "The time zone of the tenant."),
              "example" => __ ( "America/Los_Angeles")
            ),
            "Offset" => array (
              "type" => "float",
              "description" => __ ( "The time offset of the tenant."),
              "example" => -8
            ),
            "Language" => array (
              "type" => "object",
              "description" => __ ( "The language used on this tenant."),
              "properties" => array (
                "Code" => array (
                  "type" => "string",
                  "description" => __ ( "The code of the language."),
                  "example" => "en_US"
                ),
                "DescriptionEN" => array (
                  "type" => "string",
                  "description" => __ ( "The description in English of the language."),
                  "example" => "English (United States)"
                ),
                "Description" => array (
                  "type" => "string",
                  "description" => __ ( "The translated description of the language."),
                  "example" => __ ( "English (United States)")
                )
              )
            ),
            "Currency" => array (
              "type" => "string",
              "description" => __ ( "The currency code of the tenant."),
              "example" => __ ( "BRL")
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
    "permissions" => array ( "Administrator", "configs_view"),
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND (`Key` = 'NumbersLength' OR `Key` = 'Operator' OR `Key` = 'MOH' OR `Key` = 'NTP')"))
  {
    database_query_error ( "configs_view");
  }
  $data = array ( "NumbersLength" => "", "Operator" => "", "MOH" => "", "NTP" => array ());
  while ( $entry = $result->fetch_assoc ())
  {
    if ( is_json ( $entry["Data"]))
    {
      $entry["Data"] = json_decode ( $entry["Data"], true);
    }
    $data[$entry["Key"]] = $entry["Data"];
  }

  /**
   * Check if any range exists, if so, number length is immutable
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT COUNT(`Tenant`) AS `Total` FROM `Ranges` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"]))
  {
    database_query_error ( "configs_view");
  }
  $ranges = $result->fetch_assoc ();
  $data["ImmutableLength"] = $ranges["Total"] != 0;

  /**
   * Get tenant language and currency configurations
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Tenants`.`Country`, `Tenants`.`TimeZone`, `Tenants`.`Offset`, `Currencies`.`Code` AS `Currency`, `Tenants`.`Language`, `Countries`.`Name` AS `CountryName`, `Countries`.`ISO3166-2` AS `CountryISO` FROM `Tenants` LEFT JOIN `Countries` ON `Tenants`.`Country` = `Countries`.`Code` LEFT JOIN `Currencies` ON `Tenants`.`Currency` = `Currencies`.`ISO4217` WHERE `Tenants`.`ID` = " . (int) $_in["session"]["Tenant"]))
  {
    database_query_error ( "configs_view");
  }
  if ( ! $tenantdata = $result->fetch_assoc ())
  {
    database_query_error ( "configs_view");
  }
  $countryList = isset ( $_in["countries"][$_in["general"]["language"]]) ? $_in["countries"][$_in["general"]["language"]] : array ();
  $data["Country"] = array ( "ID" => $tenantdata["Country"], "NameEN" => $tenantdata["CountryName"], "Name" => ( is_array ( $countryList) && array_key_exists ( $tenantdata["CountryISO"], $countryList) ? $countryList[$tenantdata["CountryISO"]] : $tenantdata["CountryName"]), "ISO3166-2" => $tenantdata["CountryISO"]);
  $data["TimeZone"] = $tenantdata["TimeZone"];
  $data["Offset"] = $tenantdata["Offset"];
  $data["Currency"] = $tenantdata["Currency"];
  $data["Language"] = filters_call ( "get_locale", array ( "Code" => $tenantdata["Language"]));

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
        "NumbersLength" => array (
          "type" => "integer",
          "minimum" => 2,
          "description" => __ ( "The default system extension number length."),
          "required" => false,
          "example" => 4
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
        "Country" => array (
          "type" => "string",
          "description" => __ ( "The *ISO3166-2* of the default system country."),
          "required" => false,
          "example" => __ ( "US")
        ),
        "TimeZone" => array (
          "type" => "string",
          "description" => __ ( "The time zone of the tenant."),
          "required" => false,
          "example" => __ ( "America/Los_Angeles")
        ),
        "Offset" => array (
          "type" => "float",
          "description" => __ ( "The time offset of the tenant."),
          "required" => false,
          "example" => -8
        ),
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The default system language."),
          "required" => false,
          "enum" => array_keys ( $_in["languages"]),
          "example" => "en_US"
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
        "description" => __ ( "System configurations updated successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
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
  array ( "Modify", "Edit"),
  "configs_edit",
  array (
    "permissions" => array ( "Administrator", "configs_edit"),
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
  if ( ! empty ( $parameters["NumbersLength"]) && ( $parameters["NumbersLength"] < 2 || $parameters["NumbersLength"] > 6))
  {
    $data["NumbersLength"] = __ ( "The numbers length must be between 2 and 6.");
  }
  if ( empty ( $parameters["Country"]))
  {
    $data["Country"] = __ ( "The country is required.");
  }
  if ( ! array_key_exists ( "Country", $data) && ! preg_match ( "/^[A-Z]{2}$/", $parameters["Country"]))
  {
    $data["Country"] = __ ( "The country is invalid.");
  }
  if ( empty ( $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The time zone is required.");
  }
  if ( ! array_key_exists ( "TimeZone", $data) && ! preg_match ( "/^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$/", $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The time zone is invalid.");
  }
  if ( ! array_key_exists ( "TimeZone", $data))
  {
    voipdomain_timezonepicker_apply_parameters ( $parameters);
  }
  if ( empty ( $parameters["Language"]))
  {
    $data["Language"] = __ ( "The language is required.");
  }
  if ( ! array_key_exists ( "Language", $data) && ! array_key_exists ( $parameters["Language"], $_in["languages"]))
  {
    $data["Language"] = __ ( "The provided language is not valid.");
  }
  if ( empty ( $parameters["Currency"]))
  {
    $data["Currency"] = __ ( "The currency is required.");
  }

  /**
   * Check if numbers length can be changed
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Data` FROM `Config` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Key` = 'NumbersLength'"))
  {
    database_query_error ( "configs_edit");
  }
  $parameters["ORIGINAL"] = array ();
  if ( ! $parameters["ORIGINAL"]["NumbersLength"] = $result->fetch_assoc ()["Data"])
  {
    $parameters["ORIGINAL"]["NumbersLength"] = NULL;
  }
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT COUNT(`Tenant`) AS `Total` FROM `Ranges` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"]))
  {
    database_query_error ( "configs_edit");
  }
  $ranges = $result->fetch_assoc ();
  $immutablelength = $ranges["Total"] != 0;
  if ( ! empty ( $parameters["NumbersLength"]) && ! array_key_exists ( "NumbersLength", $data) && $parameters["NumbersLength"] != $parameters["ORIGINAL"]["NumbersLength"] && $immutablelength)
  {
    $data["NumbersLength"] = __ ( "The number length is immutable.");
  }

  /**
   * Check if provided currency is recognized by the system
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Currencies` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Currency"]) . "'"))
  {
    database_query_error ( "configs_edit");
  }
  if ( ! $currency = $result->fetch_assoc ())
  {
    if ( ! array_key_exists ( "Currency", $data))
    {
      $data["Currency"] = __ ( "The provided currency is invalid.");
    }
  }

  /**
   * Check if provided country is recognized by the system
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Countries` WHERE `ISO3166-2` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Country"]) . "'"))
  {
    database_query_error ( "configs_edit");
  }
  if ( ! $country = $result->fetch_assoc ())
  {
    if ( ! array_key_exists ( "Country", $data))
    {
      $data["Country"] = __ ( "The provided country is invalid.");
    }
  }

  /**
   * Check for valid operator extension
   */
  if ( ! empty ( $parameters["Operator"]))
  {
    $objects = filters_call ( "objects_types");
    $dialable = array ();
    foreach ( $objects as $object)
    {
      if ( $object["type"] == "human")
      {
        $dialable[] = "'" . $_in["mysql"]["id"]->real_escape_string ( substr ( $object["object"], 9)) . "'";
      }
    }
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Type` IN (" . implode ( ",", $dialable) . ") AND `ID` = " . (int) $parameters["Operator"]))
    {
      database_query_error ( "configs_edit");
    }
    if ( ! $operatorextension = $result->fetch_assoc ()["Number"])
    {
      $data["Operator"] = __ ( "The selected operator is not valid.");
    }
  }

  /**
   * Check for valid MOH audio
   */
  if ( ! empty ( $parameters["MOH"]))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["MOH"]))
    {
      database_query_error ( "configs_edit");
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Key`, `Data` FROM `Config` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND (`Key` = 'Operator' OR `Key` = 'MOH' OR `Key` = 'NTP')"))
  {
    database_query_error ( "configs_edit");
  }
  $parameters["ORIGINAL"]["Operator"] = 0;
  $parameters["ORIGINAL"]["MOH"] = 0;
  $parameters["ORIGINAL"]["NTP"] = array ();
  while ( $entry = $result->fetch_assoc ())
  {
    if ( is_json ( $entry["Data"]))
    {
      $entry["Data"] = json_decode ( $entry["Data"], true);
    }
    $parameters["ORIGINAL"][$entry["Key"]] = $entry["Data"];
  }
  $parameters["ORIGINAL"]["Operator"] = (int) $parameters["ORIGINAL"]["Operator"];
  $parameters["ORIGINAL"]["MOH"] = (int) $parameters["ORIGINAL"]["MOH"];

  /**
   * Get tenant configurations from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Tenants`.`Country`, `Tenants`.`TimeZone`, `Tenants`.`Offset`, `Currencies`.`Code` AS `Currency`, `Tenants`.`Language`, `Countries`.`Name` AS `CountryName`, `Countries`.`ISO3166-2` AS `CountryISO` FROM `Tenants` LEFT JOIN `Countries` ON `Tenants`.`Country` = `Countries`.`Code` LEFT JOIN `Currencies` ON `Tenants`.`Currency` = `Currencies`.`ISO4217` WHERE `Tenants`.`ID` = " . (int) $_in["session"]["Tenant"]))
  {
    database_query_error ( "configs_edit");
  }
  if ( ! $tenantdata = $result->fetch_assoc ())
  {
    database_query_error ( "configs_edit");
  }
  $parameters["ORIGINAL"]["Country"] = $tenantdata["CountryISO"];
  $parameters["ORIGINAL"]["TimeZone"] = $tenantdata["TimeZone"];
  $parameters["ORIGINAL"]["Offset"] = $tenantdata["Offset"];
  $parameters["ORIGINAL"]["Currency"] = $tenantdata["Currency"];
  $parameters["ORIGINAL"]["Language"] = $tenantdata["Language"];

  /**
   * Change each configuration entry
   */
  if ( $parameters["ORIGINAL"]["NumbersLength"] != $parameters["NumbersLength"])
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('NumbersLength', " . (int) $_in["session"]["Tenant"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["NumbersLength"]) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["NumbersLength"]) . "'"))
    {
      database_query_error ( "configs_edit");
    }
  }
  if ( ! empty ( $parameters["MOH"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('MOH', " . (int) $_in["session"]["Tenant"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["MOH"]) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["MOH"]) . "'"))
    {
      database_query_error ( "configs_edit");
    }
  }
  if ( ! empty ( $parameters["Operator"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Operator', " . (int) $_in["session"]["Tenant"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Operator"]) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Operator"]) . "'"))
    {
      database_query_error ( "configs_edit");
    }
  }
  if ( sizeof ( $parameters["NTP"]) != 0)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('NTP', " . (int) $_in["session"]["Tenant"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["NTP"])) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["NTP"])) . "'"))
    {
      database_query_error ( "configs_edit");
    }
  }

  /**
   * Update tenant configuration
   */
  if ( ! empty ( $parameters["TimeZone"]))
  {
    voipdomain_timezonepicker_apply_parameters ( $parameters);
  }
  if ( $parameters["Country"] != $parameters["ORIGINAL"]["Country"] || $parameters["TimeZone"] != $parameters["ORIGINAL"]["TimeZone"] || $parameters["Offset"] != $parameters["ORIGINAL"]["Offset"] || $currency["ISO4217"] != $parameters["ORIGINAL"]["Currency"] || $parameters["Language"] != $parameters["ORIGINAL"]["Language"])
  {
    $updates = "";
    if ( $parameters["Country"] != $parameters["ORIGINAL"]["Country"])
    {
      $updates .= ", `Country` = " . (int) $country["Code"];
    }
    if ( $parameters["TimeZone"] != $parameters["ORIGINAL"]["TimeZone"])
    {
      $updates .= ", `TimeZone` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["TimeZone"]) . "'";
    }
    if ( $parameters["Offset"] != $parameters["ORIGINAL"]["Offset"])
    {
      $updates .= ", `Offset` = '" . (float) $parameters["Offset"] . "'";
    }
    if ( $currency["ISO4217"] != $parameters["ORIGINAL"]["Currency"])
    {
      $updates .= ", `Currency` = " . (int) $currency["ISO4217"];
    }
    if ( $parameters["Language"] != $parameters["ORIGINAL"]["Language"])
    {
      $updates .= ", `Language` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "'";
    }
    if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Tenants` SET" . substr ( $updates, 2) . " WHERE `ID` = " . (int) $_in["session"]["Tenant"]))
    {
      database_query_error ( "configs_edit");
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
  if ( $parameters["Language"] != $parameters["ORIGINAL"]["Language"])
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
  "plugins_import",
  "plugins_import",
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
        "description" => __ ( "New system plugin installed successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
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
framework_add_permission ( "plugins_import", __ ( "Import plugin file"));
framework_add_api_call (
  "/config/plugin",
  "Create",
  "plugins_import",
  array (
    "permissions" => array ( "Super-Administrator", "plugins_import"),
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
function plugins_import ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "plugins_import_start"))
  {
    $parameters = framework_call ( "plugins_import_start", $parameters);
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
      $pluginspool = $_in["general"]["spooldir"] . "/plugins";
      if ( ! is_dir ( $pluginspool))
      {
        if ( ! @mkdir ( $pluginspool, 0770, true))
        {
          $data["Message"] = sprintf ( __ ( "Cannot create spool directory: %s"), $pluginspool);
        }
      }
      if ( ! array_key_exists ( "Message", $data) && ! is_writable ( $pluginspool))
      {
        $data["Message"] = sprintf ( __ ( "Spool directory is not writable: %s"), $pluginspool);
      }
      if ( ! array_key_exists ( "Message", $data))
      {
        $parameters["zipfile"] = $pluginspool . "/" . basename ( $_FILES["plugin"]["name"]);
        $parameters["tmpdir"] = $pluginspool . "/" . md5 ( uniqid ( rand (), true));
        if ( ! @move_uploaded_file ( $_FILES["plugin"]["tmp_name"], $parameters["zipfile"]))
        {
          $data["Message"] = sprintf ( __ ( "Cannot move uploaded file to: %s"), $parameters["zipfile"]);
        }
      }
    }
  } else {
    $data["Message"] = __ ( "Invalid plugin file.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "plugins_import_validate"))
  {
    $data = framework_call ( "plugins_import_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "plugins_import_pre"))
  {
    $parameters = framework_call ( "plugins_import_pre", $parameters, false, $parameters);
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
    $zip = new ZipArchive ();
    $consistency = array ( "reqFile" => false, "manifestFile" => false, "directory" => true);
    if ( $zip->open ( $parameters["zipfile"]) === true)
    {
      for ( $i = 0; $i < $zip->numFiles; $i++)
      {
        $filename = $zip->getNameIndex ( $i);
        $parameters["files"][] = $filename;
        if ( substr ( $filename, -1) == "/")
        {
          if ( empty ( $parameters["name"]))
          {
            $parameters["name"] = substr ( $filename, 0, strrpos ( $filename, "/"));
          }
          @mkdir ( $parameters["tmpdir"] . "/" . $filename, 0755, true);
        } else {
          $dirname = dirname ( $parameters["tmpdir"] . "/" . $filename);
          if ( ! is_dir ( $dirname))
          {
            @mkdir ( $dirname, 0755, true);
          }
          file_put_contents ( $parameters["tmpdir"] . "/" . $filename, $zip->getFromIndex ( $i));
        }
        if ( $filename == $parameters["name"] . "/plugin.json")
        {
          $consistency["manifestFile"] = true;
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
      if ( ! array_key_exists ( "message", $data) && $consistency["manifestFile"] != true)
      {
        $data["message"] = __ ( "Plugin manifest (plugin.json) missing.");
      }
      if ( ! array_key_exists ( "message", $data) && $consistency["reqFile"] != true)
      {
        $data["message"] = __ ( "Plugin file doesn't have the minimum required files.");
      }
      $zip->close ();
    } else {
      $data["message"] = __ ( "Error opening plugin zip file.");
    }
  }

  /**
   * Parse plugin.json manifest
   */
  if ( ! array_key_exists ( "message", $data) && $consistency["manifestFile"] == true)
  {
    $manifestpath = $parameters["tmpdir"] . "/" . $parameters["name"] . "/plugin.json";
    $manifest = plugin_validate_manifest ( $manifestpath);
    if ( $manifest === false)
    {
      $data["message"] = __ ( "Invalid or incomplete plugin.json manifest.");
    }
    if ( ! array_key_exists ( "message", $data) && $manifest["name"] != $parameters["name"])
    {
      $data["message"] = __ ( "Plugin manifest name does not match directory name.");
    }
    if ( ! array_key_exists ( "message", $data))
    {
      $parameters["config"] = plugin_manifest_to_config ( $manifest);
      $parameters["manifest"] = $manifest;
      $parameters["config"]["filesize"] = filesize ( $parameters["zipfile"]);
      $parameters["config"]["supported"] = true;
      $parameters["config"]["missing"] = array ();
      foreach ( $parameters["config"]["require"] as $reqstring)
      {
        if ( ! preg_match ( "/^(\w[\w-]*) (=|==|<|<=|=<|>|>=|=>|!=|<>) ([\d]+(\.[\d]+)*(\.[\d]+)*)$/", trim ( $reqstring), $matches))
        {
          $data["message"] = __ ( "Error parsing plugin minimum system requirements.");
          break;
        }
        $reqmodule = $matches[1];
        if ( sizeof ( $matches) > 2)
        {
          $reqcond = $matches[2];
          $reqversion = $matches[3];
        } else {
          $reqcond = ">=";
          $reqversion = "0.0.0";
        }
        if ( $reqmodule == "core")
        {
          $reqmoduleversion = defined ( "MODULE_CORE_VERSION") ? MODULE_CORE_VERSION : "1.0.0";
        } else {
          if ( $reqmodule == "php")
          {
            $reqmoduleversion = PHP_VERSION;
          } else {
            if ( ! array_key_exists ( $reqmodule, $_in["modules"]))
            {
              $pluginversion = @$_in["mysql"]["id"]->query ( "SELECT `Version` FROM `Plugins` WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $reqmodule) . "' AND `Status` = 'A'");
              if ( $pluginversion && $pluginversion->num_rows > 0)
              {
                $pvrow = $pluginversion->fetch_assoc ();
                $reqmoduleversion = $pvrow["Version"];
              } else {
                $parameters["config"]["supported"] = false;
                $parameters["config"]["missing"][] = array ( "module" => $reqmodule, "condition" => $reqcond, "version" => $reqversion);
                continue;
              }
            } else {
              $reqmoduleversion = $_in["modules"][$reqmodule]["version"];
            }
          }
        }
        $op = $reqcond;
        if ( $op == "=>" ) $op = ">=";
        if ( $op == "=<" ) $op = "<=";
        if ( $op == "=" ) $op = "==";
        if ( ! version_compare ( $reqmoduleversion, $reqversion, $op))
        {
          $parameters["config"]["supported"] = false;
          $parameters["config"]["missing"][] = array ( "module" => $reqmodule, "condition" => $reqcond, "version" => $reqversion);
        }
      }
    }
  }

  /**
   * Check for conflicting plugins
   */
  if ( ! array_key_exists ( "message", $data) && array_key_exists ( "config", $parameters) && ! empty ( $parameters["config"]["conflicts"]))
  {
    foreach ( $parameters["config"]["conflicts"] as $conflict)
    {
      $conflict = trim ( $conflict);
      if ( empty ( $conflict))
      {
        continue;
      }
      $confresult = @$_in["mysql"]["id"]->query ( "SELECT `Name`, `Dirname` FROM `Plugins` WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $conflict) . "' AND `Status` = 'A'");
      if ( $confresult && $confresult->num_rows > 0)
      {
        $confrow = $confresult->fetch_assoc ();
        $data["message"] = sprintf ( __ ( "Plugin conflicts with active plugin: %s"), $confrow["Name"]);
        break;
      }
    }
  }

  /**
   * Process all plugin files, calling our internal /api/config/testfile API
   * call.
   * We need to do this way calling our own API because we check for PHP syntax
   * errors, and if you just include or try to parse the plugin file with an
   * error, it will fail and finish our process, making impossible to return the
   * exactly error.
   */
  if ( ! array_key_exists ( "message", $data))
  {
    $apischeme = ( isset ( $_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
    $apiport = $_SERVER["SERVER_PORT"] ?? ( $apischeme == "https" ? "443" : "80");
    $apiurl = $apischeme . "://127.0.0.1:" . $apiport . "/api/config/testfile";
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
      curl_setopt ( $socket, CURLOPT_HTTPHEADER, array ( "Content-Type: application/json", "Accept: application/json", "X-HTTP-Method-Override: GET", "Host: " . $_SERVER["SERVER_NAME"]));
      curl_setopt ( $socket, CURLOPT_HEADER, false);
      curl_setopt ( $socket, CURLOPT_RETURNTRANSFER, true);
      $result = @curl_exec ( $socket);
      $curlerror = curl_error ( $socket);
      $parameters["integrity"][$filedesc] = json_decode ( $result, true);
      if ( $result == "" || json_last_error () != JSON_ERROR_NONE)
      {
        $parameters["integrity"][$filedesc] = array ( "result" => false, "message" => ( $curlerror != "" ? $curlerror : $result));
        $data["message"] = __ ( "Plugin parser error.") . ( $curlerror != "" ? " (" . $curlerror . ")" : "");
      }
    }
  }

  /**
   * Check if plugin already exists
   */
  $parameters["upgrade"] = false;
  if ( ! array_key_exists ( "message", $data))
  {
    $pluginspath = plugin_get_plugins_path ();
    $existingdir = "";
    if ( file_exists ( $pluginspath . $parameters["name"]))
    {
      $existingdir = $pluginspath . $parameters["name"];
    } else {
      if ( file_exists ( $pluginspath . "disabled_" . $parameters["name"]))
      {
        $existingdir = $pluginspath . "disabled_" . $parameters["name"];
      }
    }
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Plugins` WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "'"))
    {
      database_query_error ( "plugins_import");
    }
    if ( $result->num_rows != 0)
    {
      $parameters["ORIGINAL"] = $result->fetch_assoc ();
      if ( version_compare ( $parameters["config"]["version"], $parameters["ORIGINAL"]["Version"], "<="))
      {
        $data["message"] = __ ( "Plugin already installed.");
      } else {
        $parameters["upgrade"] = true;
      }
    } else {
      if ( $existingdir != "")
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
   * Verify plugin signature
   */
  $verification = plugin_verify_signature ( $parameters["tmpdir"], $parameters["name"]);
  $policy_error = plugin_apply_signing_policy ( $verification);
  if ( $policy_error !== null)
  {
    unlink ( $parameters["zipfile"]);
    unlink_recursive ( $parameters["tmpdir"]);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return array ( "message" => $policy_error);
  }
  if ( $verification["valid"] && ! empty ( $verification["developer"]))
  {
    $parameters["config"]["signed_by"] = $verification["developer"];
    if ( $verification["official"])
    {
      $parameters["config"]["signed_by"] = "voipdomain-official";
    }
  }

  /**
   * Add or update plugin in database
   */
  if ( $parameters["upgrade"])
  {
    if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Plugins` SET `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["name"]) . "', `Version` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["version"]) . "', `Author` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["author"]) . "', `AuthorEmail` = " . ( ! empty ( $parameters["config"]["email"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["email"]) . "'" : "NULL") . ", `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["description"]) . "', `License` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["license"]) . "', `SupportURL` = " . ( ! empty ( $parameters["config"]["support_url"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["support_url"]) . "'" : "NULL") . ", `PublishedAt` = " . ( ! empty ( $parameters["config"]["published"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["published"]) . "'" : "NULL") . ", `Daemons` = " . ( ! empty ( $parameters["config"]["daemons"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $parameters["config"]["daemons"])) . "'" : "NULL") . ", `SignedBy` = " . ( ! empty ( $parameters["config"]["signed_by"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["signed_by"]) . "'" : "NULL") . ", `Conflicts` = " . ( ! empty ( $parameters["config"]["conflicts"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["config"]["conflicts"])) . "'" : "NULL") . ", `Requires` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["config"]["require"])) . "' WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "'"))
    {
      database_query_error ( "plugins_import");
    }
    $data["message"] = sprintf ( __ ( "Plugin %s (version %s) successfully upgraded."), $parameters["config"]["name"], $parameters["config"]["version"]);
  } else {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Plugins` (`Dirname`, `Name`, `Version`, `Author`, `AuthorEmail`, `Description`, `License`, `SupportURL`, `PublishedAt`, `Daemons`, `SignedBy`, `Conflicts`, `Status`, `Requires`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["version"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["author"]) . "', " . ( ! empty ( $parameters["config"]["email"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["email"]) . "'" : "NULL") . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["license"]) . "', " . ( ! empty ( $parameters["config"]["support_url"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["support_url"]) . "'" : "NULL") . ", " . ( ! empty ( $parameters["config"]["published"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["published"]) . "'" : "NULL") . ", " . ( ! empty ( $parameters["config"]["daemons"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $parameters["config"]["daemons"])) . "'" : "NULL") . ", " . ( ! empty ( $parameters["config"]["signed_by"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["config"]["signed_by"]) . "'" : "NULL") . ", " . ( ! empty ( $parameters["config"]["conflicts"]) ? "'" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["config"]["conflicts"])) . "'" : "NULL") . ", 'A', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["config"]["require"])) . "')"))
    {
      database_query_error ( "plugins_import");
    }
    $data["message"] = sprintf ( __ ( "Plugin %s (version %s) successfully installed."), $parameters["config"]["name"], $parameters["config"]["version"]);
  }

  /**
   * Move plugin to system plugins directory
   */
  $pluginspath = plugin_get_plugins_path ();
  if ( ! is_dir ( $pluginspath))
  {
    if ( ! @mkdir ( $pluginspath, 0755, true))
    {
      unlink ( $parameters["zipfile"]);
      unlink_recursive ( $parameters["tmpdir"]);
      header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
      return array ( "message" => sprintf ( __ ( "Cannot create plugins directory: %s"), $pluginspath));
    }
  }
  if ( ! is_writable ( $pluginspath))
  {
    unlink ( $parameters["zipfile"]);
    unlink_recursive ( $parameters["tmpdir"]);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return array ( "message" => sprintf ( __ ( "Plugins directory is not writable: %s"), $pluginspath));
  }
  $plugindir = $pluginspath . $parameters["name"];
  if ( $parameters["upgrade"] && file_exists ( $plugindir))
  {
    unlink_recursive ( $plugindir);
  }
  if ( file_exists ( $pluginspath . "disabled_" . $parameters["name"]))
  {
    unlink_recursive ( $pluginspath . "disabled_" . $parameters["name"]);
  }
  if ( ! @rename ( $parameters["tmpdir"] . "/" . $parameters["name"], $plugindir))
  {
    unlink ( $parameters["zipfile"]);
    unlink_recursive ( $parameters["tmpdir"]);
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return array ( "message" => sprintf ( __ ( "Cannot move plugin files to: %s"), $plugindir));
  }

  /**
   * Run plugin install/update scripts, lifecycle hooks and deploy daemon modules
   */
  $installresult = plugin_apply_installation ( $plugindir, $parameters["name"], $parameters);
  if ( sizeof ( $installresult))
  {
    $data = array_merge_recursive ( $data, $installresult);
  }

  /**
   * Remove temporary directory
   */
  unlink_recursive ( $parameters["tmpdir"]);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "plugins_import_post"))
  {
    $data = framework_call ( "plugins_import_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "plugins_import_finish"))
  {
    framework_call ( "plugins_import_finish", $parameters, false);
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
  "plugins_check_file",
  "plugins_check_file",
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
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
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
  "plugins_check_file",
  array (
    "permissions" => array ( "Internal"),
    "title" => __ ( "Check plugin file"),
    "description" => __ ( "Internal endpoint to check and validate a plugin file.")
  )
);

/**
 * Function to check a VoIP Domain plugin internal file. This function has been
 * created to internal system call, to validate a plugin file when checking it,
 * because PHP will trigger a fatal error if any syntax or other error happens
 * when include file, making it impossible to return a detailed check to system,
 * so we include it here and watch for a response error message if one occurs,
 * otherwise, return a complete list of included files, included system hooks,
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
function plugins_check_file ( $buffer, $parameters)
{
  global $_in, $_api, $_filters, $_plugins;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "plugins_check_file_start"))
  {
    $parameters = framework_call ( "plugins_check_file_start", $parameters);
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
  if ( framework_has_hook ( "plugins_check_file_validate"))
  {
    $data = framework_call ( "plugins_check_file_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "plugins_check_file_sanitize"))
  {
    $parameters = framework_call ( "plugins_check_file_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "plugins_check_file_pre"))
  {
    $parameters = framework_call ( "plugins_check_file_pre", $parameters, false, $parameters);
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
  if ( framework_has_hook ( "plugins_check_file_post"))
  {
    $data = framework_call ( "plugins_check_file_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "plugins_check_file_finish"))
  {
    framework_call ( "plugins_check_file_finish", $parameters, false);
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
                "type" => "string",
                "description" => __ ( "The semantic version of the plugin."),
                "example" => "1.1.0"
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
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Fields" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Fields contain invalid values.")
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
    "permissions" => array ( "Super-Administrator", "plugins_search"),
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
   * Add function parameters
   */
  $parameters["function"] = array (
    "DefaultFields" => "Dirname,Name,Version,Description,Status",
    "PermittedFields" => "Dirname,Name,Version,Author,Description,Status,Requires"
  );

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
  if ( ! array_key_exists ( "Fields", $parameters) || $parameters["Fields"] == "" || ( is_array ( $parameters["Fields"]) && sizeof ( $parameters["Fields"]) == 0))
  {
    $parameters["Fields"] = $parameters["function"]["DefaultFields"];
  }
  if ( ! api_filter_validate ( $parameters["Fields"], $parameters["function"]["PermittedFields"]))
  {
    $data["Fields"] = __ ( "Fields contain invalid values.");
  }

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
    database_query_error ( "plugins_search");
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], $parameters["function"]["DefaultFields"], $parameters["function"]["PermittedFields"]);
  while ( $result = $results->fetch_assoc ())
  {
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
        "description" => __ ( "Plugin toggled enabled/disabled successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
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
  array ( "Modify", "Edit"),
  "plugins_toggle",
  array (
    "permissions" => array ( "Super-Administrator", "plugins_toggle"),
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
  "/config/plugin/:Plugin/enable",
  array ( "Modify", "Edit"),
  "plugins_toggle",
  array (
    "permissions" => array ( "Super-Administrator", "plugins_toggle"),
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
  "/config/plugin/:Plugin/disable",
  array ( "Modify", "Edit"),
  "plugins_toggle",
  array (
    "permissions" => array ( "Super-Administrator", "plugins_toggle"),
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
    database_query_error ( "plugins_toggle");
  }
  if ( ! $plugin = $result->fetch_assoc ())
  {
    database_query_error ( "plugins_toggle");
  }

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
   * Enforce dependency constraints: cannot disable if other active plugins depend on this one
   */
  if ( $newstatus == "I" || $newstatus == "F")
  {
    $dep_check = plugin_check_dependents ( $parameters["Plugin"]);
    if ( sizeof ( $dep_check) > 0)
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
      return array ( "message" => sprintf ( __ ( "Cannot disable plugin: the following active plugins depend on it: %s"), implode ( ", ", $dep_check)));
    }
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
    database_query_error ( "plugins_toggle");
  }

  /**
   * Rename directory
   */
  $pluginspath = plugin_get_plugins_path ();
  if ( ! rename ( $pluginspath . ( $newstatus == "A" ? "disabled_" : "") . $parameters["Plugin"], $pluginspath . ( $newstatus == "A" ? "" : "disabled_") . $parameters["Plugin"]))
  {
    database_query_error ( "plugins_toggle");
  }

  /**
   * Sync daemon modules and lifecycle hooks for enable/disable
   */
  $plugindir = $pluginspath . ( $newstatus == "A" ? "" : "disabled_") . $parameters["Plugin"];
  $togglelifecycle = array (
    "name" => $parameters["Plugin"],
    "status" => $newstatus,
    "original" => $plugin
  );
  if ( $newstatus == "A")
  {
    foreach ( array ( "filter.php", "api.php") as $_pluginfile)
    {
      if ( is_readable ( $plugindir . "/" . $_pluginfile))
      {
        require_once ( $plugindir . "/" . $_pluginfile);
      }
    }
    unset ( $_pluginfile);
    plugin_call_lifecycle ( $parameters["Plugin"], "enable", $togglelifecycle);
    $deploy = plugin_deploy_daemons ( $plugindir, $parameters["Plugin"]);
    if ( sizeof ( $deploy["deployed"]))
    {
      $data["deployed"] = $deploy["deployed"];
      $data["remote"] = plugin_sync_daemon_modules_remote ( $plugindir, $parameters["Plugin"], $deploy["deployed"], "deploy", 0);
      $reloadunits = systemd_units_from_deployed_modules ( $deploy["deployed"]);
      if ( sizeof ( $reloadunits))
      {
        $data["reloaded"] = plugin_request_daemon_reload_all ( $reloadunits);
      }
    }
    $toggledaemons = ! empty ( $plugin["Daemons"]) ? explode ( ",", $plugin["Daemons"]) : array ();
    if ( sizeof ( $toggledaemons) && is_readable ( $plugindir . "/plugin.json"))
    {
      $togglemanifest = json_decode ( file_get_contents ( $plugindir . "/plugin.json"), true);
      if ( is_array ( $togglemanifest) && array_key_exists ( "config", $togglemanifest) && sizeof ( $togglemanifest["config"]))
      {
        plugin_sync_config_to_daemons ( $parameters["Plugin"], $togglemanifest["config"], $toggledaemons);
      }
    }
    notify_server ( 0, "server_rebuild", array ( "CleanUp" => false));
  } else {
    plugin_call_lifecycle ( $parameters["Plugin"], "disable", $togglelifecycle);
    $undeploy = plugin_undeploy_daemons ( $plugindir, $parameters["Plugin"]);
    if ( sizeof ( $undeploy["removed"]))
    {
      $data["removed"] = $undeploy["removed"];
      $data["remote"] = plugin_sync_daemon_modules_remote ( $plugindir, $parameters["Plugin"], $undeploy["removed"], "remove", 0);
      $reloadunits = systemd_units_from_deployed_modules ( $undeploy["removed"]);
      if ( sizeof ( $reloadunits))
      {
        $data["reloaded"] = plugin_request_daemon_reload_all ( $reloadunits);
      }
    }
    $toggledaemons = ! empty ( $plugin["Daemons"]) ? explode ( ",", $plugin["Daemons"]) : array ();
    if ( sizeof ( $toggledaemons))
    {
      plugin_remove_config_from_daemons ( $parameters["Plugin"], $toggledaemons);
    }
    notify_server ( 0, "server_rebuild", array ( "CleanUp" => false));
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
              "type" => "float",
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
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
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
    "permissions" => array ( "Super-Administrator", "plugins_view"),
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
    database_query_error ( "plugins_view");
  }
  if ( ! $plugin = $result->fetch_assoc ())
  {
    database_query_error ( "plugins_view");
  }

  /**
   * Format data
   */
  $data = array ();
  $data["plugin"] = $plugin["Dirname"];
  $data["name"] = $plugin["Name"];
  $data["version"] = $plugin["Version"];
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
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
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
    "permissions" => array ( "Super-Administrator", "plugins_remove"),
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
    database_query_error ( "plugins_remove");
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    database_query_error ( "plugins_remove");
  }

  /**
   * Enforce dependency constraints: cannot remove if other active plugins depend on this one
   */
  $dep_check = plugin_check_dependents ( $parameters["Plugin"]);
  if ( sizeof ( $dep_check) > 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return array ( "message" => sprintf ( __ ( "Cannot remove plugin: the following active plugins depend on it: %s"), implode ( ", ", $dep_check)));
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "plugins_remove_pre"))
  {
    $parameters = framework_call ( "plugins_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Run plugin uninstall scripts, lifecycle hooks and remove daemon modules
   */
  $pluginspath = plugin_get_plugins_path ();
  $plugindir = $pluginspath . ( $parameters["ORIGINAL"]["Status"] == "I" ? "disabled_" : "") . $parameters["ORIGINAL"]["Dirname"];
  if ( is_dir ( $plugindir))
  {
    plugin_apply_removal ( $plugindir, $parameters["ORIGINAL"]["Dirname"], $parameters);
  }

  /**
   * Remove plugin database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Plugins` WHERE `Dirname` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Plugin"]) . "'"))
  {
    database_query_error ( "plugins_remove");
  }

  /**
   * Remove plugin directory and information files
   */
  if ( is_dir ( $plugindir))
  {
    unlink_recursive ( $plugindir);
  }

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

/**
 * Modules registry hook and API
 */
framework_add_hook (
  "modules_search",
  "modules_search",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "Returns the registered modules list."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "Responses",
            "wrapped" => true
          ),
          "items" => array (
            "xml" => array (
              "name" => "Module"
            ),
            "oneOf" => array (
              array (
                "type" => "object",
                "description" => __ ( "A core module shipped with the system."),
                "properties" => array (
                  "Name" => array (
                    "type" => "string",
                    "description" => __ ( "The module directory name."),
                    "example" => "blocks"
                  ),
                  "Type" => array (
                    "type" => "string",
                    "description" => __ ( "The module type."),
                    "const" => "core"
                  ),
                  "Version" => array (
                    "type" => "string",
                    "description" => __ ( "The module version."),
                    "example" => "1.0.0"
                  ),
                  "Author" => array (
                    "type" => "string",
                    "description" => __ ( "The module author name."),
                    "example" => "VoIP Domain"
                  ),
                  "Loaded" => array (
                    "type" => "boolean",
                    "description" => __ ( "Whether the module is loaded into runtime."),
                    "example" => true
                  ),
                  "Path" => array (
                    "type" => "string",
                    "description" => __ ( "The filesystem path of the module directory."),
                    "example" => "/var/www/html/modules/blocks"
                  ),
                  "Files" => array (
                    "type" => "object",
                    "description" => __ ( "Map of standard module files and whether each exists."),
                    "example" => array ( "api.php" => true, "config.php" => true, "filter.php" => false, "webui.php" => true)
                  )
                ),
                "required" => array ( "Name", "Type", "Version", "Author", "Loaded", "Path", "Files")
              ),
              array (
                "type" => "object",
                "description" => __ ( "A plugin module installed from an external package."),
                "properties" => array (
                  "Name" => array (
                    "type" => "string",
                    "description" => __ ( "The module directory name."),
                    "example" => "sampleitems"
                  ),
                  "Type" => array (
                    "type" => "string",
                    "description" => __ ( "The module type."),
                    "const" => "plugin"
                  ),
                  "Version" => array (
                    "type" => "string",
                    "description" => __ ( "The module version."),
                    "example" => "1.0.0"
                  ),
                  "Author" => array (
                    "type" => "string",
                    "description" => __ ( "The module author name."),
                    "example" => "Ernani José Camargo Azevedo"
                  ),
                  "Loaded" => array (
                    "type" => "boolean",
                    "description" => __ ( "Whether the module is loaded into runtime."),
                    "example" => true
                  ),
                  "Path" => array (
                    "type" => "string",
                    "description" => __ ( "The filesystem path of the module directory."),
                    "example" => "/var/www/html/plugins/sampleitems"
                  ),
                  "Files" => array (
                    "type" => "object",
                    "description" => __ ( "Map of standard module files and whether each exists."),
                    "example" => array ( "api.php" => true, "config.php" => true, "filter.php" => false, "webui.php" => true)
                  ),
                  "DisplayName" => array (
                    "type" => "string",
                    "description" => __ ( "The plugin display name."),
                    "example" => "Sample Items"
                  ),
                  "Description" => array (
                    "type" => "string",
                    "description" => __ ( "The plugin description."),
                    "example" => "Sample plugin demonstrating the plugin system"
                  ),
                  "License" => array (
                    "type" => "string",
                    "description" => __ ( "The plugin license."),
                    "example" => "GPLv3"
                  ),
                  "Status" => array (
                    "type" => "string",
                    "description" => __ ( "The plugin status: Active, Inactive, Suspended or Failed."),
                    "enum" => array ( "A", "I", "S", "F"),
                    "example" => "A"
                  )
                ),
                "required" => array ( "Name", "Type", "Version", "Author", "Loaded", "Path", "Files", "DisplayName", "Description", "License", "Status")
              )
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "modules_search", __ ( "View registered modules"));
framework_add_api_call (
  "/config/modules",
  "Read",
  "modules_search",
  array (
    "permissions" => array ( "Administrator", "modules_search"),
    "title" => __ ( "List modules"),
    "description" => __ ( "List all registered core modules and plugins with metadata.")
  )
);

/**
 * Function to list all registered modules.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Modules registry
 */
function modules_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "modules_search_start"))
  {
    $parameters = framework_call ( "modules_search_start", $parameters);
  }

  /**
   * Build result array
   */
  $data = array ();
  foreach ( $_in["modules"] as $name => $info)
  {
    $entry = array (
      "Name" => $name,
      "Type" => $info["type"],
      "Version" => $info["version"],
      "Author" => $info["author"],
      "Loaded" => $info["loaded"],
      "Path" => $info["path"],
      "Files" => $info["files"]
    );
    if ( $info["type"] == "plugin")
    {
      $entry["DisplayName"] = $info["display_name"] ?? "";
      $entry["Description"] = $info["description"] ?? "";
      $entry["License"] = $info["license"] ?? "";
      $entry["Status"] = $info["status"] ?? "";
    }
    if ( ! empty ( $parameters["Filter"]))
    {
      if ( stripos ( $name, $parameters["Filter"]) === false && stripos ( $info["author"], $parameters["Filter"]) === false && ( $info["type"] != "plugin" || stripos ( $info["display_name"] ?? "", $parameters["Filter"]) === false))
      {
        continue;
      }
    }
    $data[] = $entry;
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "modules_search_post"))
  {
    framework_call ( "modules_search_post", $parameters, false, $data);
  }

  return $data;
}
?>
