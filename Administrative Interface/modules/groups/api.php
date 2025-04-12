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
 * VoIP Domain groups module API. This module add the API calls related to
 * groups.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Groups
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search groups
 */
framework_add_hook (
  "groups_search",
  "groups_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all groups."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Profile,CostCenter,Extensions",
          "example" => "Description"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system groups."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "group"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the group."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the group."),
                "example" => __ ( "IT Team")
              ),
              "Profile" => array (
                "type" => "object",
                "description" => __ ( "The profile used on this group."),
                "properties" => array (
                  "ID" => array (
                    "type" => "integer",
                    "description" => __ ( "The profile internal system unique identifier of the group."),
                    "example" => 1
                  ),
                  "Description" => array (
                    "type" => "string",
                    "description" => __ ( "The description of the profile of the group."),
                    "example" => "Default profile"
                  )
                )
              ),
              "CostCenter" => array (
                "type" => "object",
                "description" => __ ( "The cost center used on this group."),
                "properties" => array (
                  "ID" => array (
                    "type" => "integer",
                    "description" => __ ( "The cost center internal system unique identifier of the group."),
                    "example" => 1
                  ),
                  "Description" => array (
                    "type" => "string",
                    "description" => __ ( "The description of the cost center of the group."),
                    "example" => "Default profile"
                  )
                )
              ),
              "Extensions" => array (
                "type" => "integer",
                "description" => __ ( "The number of extensions using this group."),
                "example" => 21
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
framework_add_permission ( "groups_search", __ ( "Search groups"));
framework_add_api_call (
  "/groups",
  "Read",
  "groups_search",
  array (
    "permissions" => array ( "user", "groups_search"),
    "title" => __ ( "Search groups"),
    "description" => __ ( "Search for system groups.")
  )
);

/**
 * Function to search groups.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "groups_search_start"))
  {
    $parameters = framework_call ( "groups_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Groups", "CostCenters"));

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "groups_search_validate"))
  {
    $data = framework_call ( "groups_search_validate", $parameters);
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
  if ( framework_has_hook ( "groups_search_sanitize"))
  {
    $parameters = framework_call ( "groups_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "groups_search_pre"))
  {
    $parameters = framework_call ( "groups_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search groups
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Groups`.`ID`, `Groups`.`Description`, `Groups`.`CostCenter` AS `CostCenterID`, `CostCenters`.`Description` AS `CostCenterDescription`, `Groups`.`Profile` AS `ProfileID`, `Profiles`.`Description` AS `ProfileDescription` FROM `Groups` LEFT JOIN `CostCenters` ON `Groups`.`CostCenter` = `CostCenters`.`ID` LEFT JOIN `Profiles` ON `Groups`.`Profile` = `Profiles`.`ID`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `CostCenter` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " GROUP BY `Groups`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Profile,CostCenter,Extensions", "ID,Description,Profile,CostCenter,Extensions");
  while ( $result = $results->fetch_assoc ())
  {
    $result["Profile"] = array ( "ID" => $result["ProfileID"], "Description" => $result["ProfileDescription"]);
    $result["CostCenter"] = array ( "ID" => $result["CostCenterID"], "Description" => $result["CostCenterDescription"]);
    $result["Extensions"] = filters_call ( "count_extensions", array ( "Group" => $result["ID"]));;
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "groups_search_post"))
  {
    $data = framework_call ( "groups_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "groups_search_finish"))
  {
    framework_call ( "groups_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get group information
 */
framework_add_hook (
  "groups_view",
  "groups_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system group."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "integer",
              "description" => __ ( "The internal unique identification number of the group."),
              "example" => 1
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the group."),
              "example" => __ ( "IT Team")
            ),
            "Profile" => array (
              "type" => "object",
              "description" => __ ( "The profile used on this group."),
              "properties" => array (
                "ID" => array (
                  "type" => "integer",
                  "description" => __ ( "The profile internal system unique identifier of the group."),
                  "example" => 1
                ),
                "Description" => array (
                  "type" => "string",
                  "description" => __ ( "The description of the profile of the group."),
                  "example" => "Default profile"
                )
              )
            ),
            "CostCenter" => array (
              "type" => "object",
              "nullable" => true,
              "properties" => array (
                "ID" => array (
                  "type" => "integer",
                  "description" => __ ( "The cost center system internal identified for this group."),
                  "example" => 1
                ),
                "Description" => array (
                  "type" => "string",
                  "description" => __ ( "The cost center description for this group."),
                  "example" => __ ( "IT Team")
                ),
                "Code" => array (
                  "type" => "integer",
                  "description" => __ ( "The cost center code for this group."),
                  "example" => 1000
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
              "example" => __ ( "Invalid group ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "groups_view", __ ( "View groups information"));
framework_add_api_call (
  "/groups/:ID",
  "Read",
  "groups_view",
  array (
    "permissions" => array ( "user", "groups_view"),
    "title" => __ ( "View groups"),
    "description" => __ ( "Get a system group information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The system group internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate group information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "groups_view_start"))
  {
    $parameters = framework_call ( "groups_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Groups", "CostCenters"));

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid group ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "groups_view_validate"))
  {
    $data = framework_call ( "groups_view_validate", $parameters);
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
  if ( framework_has_hook ( "groups_view_sanitize"))
  {
    $parameters = framework_call ( "groups_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "groups_view_pre"))
  {
    $parameters = framework_call ( "groups_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search groups
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Groups`.*, `Profiles`.`Description` AS `ProfileDescription`, `CostCenters`.`Description` AS `CostCenterDescription`, `CostCenters`.`Code` AS `CostCenterCode` FROM `Groups` LEFT JOIN `CostCenters` ON `Groups`.`CostCenter` = `CostCenters`.`ID` LEFT JOIN `Profiles` ON `Groups`.`Profile` = `Profiles`.`ID` WHERE `Groups`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " GROUP BY `Groups`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $group = $result->fetch_assoc ();

  /**
   * Format data
   */
  $group["Profile"] = array ( "ID" => $group["Profile"], "Description" => $group["ProfileDescription"]);
  $group["CostCenter"] = array ( "ID" => $group["CostCenter"], "Description" => $group["CostCenterDescription"], "Code" => $group["CostCenterCode"]);
  $data = api_filter_entry ( array ( "ID", "Description", "Profile", "CostCenter"), $group);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "groups_view_post"))
  {
    $data = framework_call ( "groups_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "groups_view_finish"))
  {
    framework_call ( "groups_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new group
 */
framework_add_hook (
  "groups_add",
  "groups_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the group."),
          "required" => true,
          "example" => __ ( "IT Team")
        ),
        "Profile" => array (
          "type" => "integer",
          "description" => __ ( "The system profile unique identifier of the group."),
          "required" => true,
          "example" => 1
        ),
        "CostCenter" => array (
          "type" => "integer",
          "description" => __ ( "The system cost center unique identifier of the group."),
          "required" => true,
          "example" => 1
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system group was added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The group description is required.")
            ),
            "Profile" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid profile.")
            ),
            "CostCenter" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid cost center.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "groups_add", __ ( "Add groups"));
framework_add_api_call (
  "/groups",
  "Create",
  "groups_add",
  array (
    "permissions" => array ( "user", "groups_add"),
    "title" => __ ( "Add groups"),
    "description" => __ ( "Add a new system group.")
  )
);

/**
 * Function to add a new group.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "groups_add_start"))
  {
    $parameters = framework_call ( "groups_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The group description is required.");
  }
  if ( empty ( $parameters["Profile"]))
  {
    $data["Profile"] = __ ( "The profile is required.");
  }
  if ( empty ( $parameters["CostCenter"]))
  {
    $data["CostCenter"] = __ ( "The cost center is required.");
  }

  /**
   * Check if profile exist
   */
  if ( ! array_key_exists ( "Profile", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Profiles` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Profile"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["Profile"] = __ ( "Invalid profile.");
    }
  }

  /**
   * Check if cost center exist
   */
  if ( ! array_key_exists ( "CostCenter", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["CostCenter"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["CostCenter"] = __ ( "Invalid cost center.");
    }
  }

  /**
   * Call add validate hook if exist
   */
  if ( framework_has_hook ( "groups_add_validate"))
  {
    $data = framework_call ( "groups_add_validate", $parameters, false, $data);
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
  $parameters["Profile"] = (int) $parameters["Profile"];
  $parameters["CostCenter"] = (int) $parameters["CostCenter"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "groups_add_sanitize"))
  {
    $parameters = framework_call ( "groups_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "groups_add_pre"))
  {
    $parameters = framework_call ( "groups_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new group record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Groups` (`Description`, `Profile`, `CostCenter`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Profile"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["CostCenter"]) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "groups_add_post"))
  {
    framework_call ( "groups_add_post", $parameters);
  }

  /**
   * Add new group at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["ID"], "Profile" => $parameters["Profile"], "CostCenter" => $parameters["CostCenter"]);
  if ( framework_has_hook ( "groups_add_notify"))
  {
    $notify = framework_call ( "groups_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "group_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "groups_add_finish"))
  {
    framework_call ( "groups_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/groups/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing group
 */
framework_add_hook (
  "groups_edit",
  "groups_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the group."),
          "required" => true,
          "example" => __ ( "IT Team")
        ),
        "Profile" => array (
          "type" => "integer",
          "description" => __ ( "The system profile unique identifier of the group."),
          "required" => true,
          "example" => 1
        ),
        "CostCenter" => array (
          "type" => "integer",
          "description" => __ ( "The system cost center unique identifier of the group."),
          "required" => true,
          "example" => 1
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system group was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The group description is required.")
            ),
            "Profile" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid profile.")
            ),
            "CostCenter" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid cost center.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "groups_edit", __ ( "Edit groups"));
framework_add_api_call (
  "/groups/:ID",
  "Modify",
  "groups_edit",
  array (
    "permissions" => array ( "user", "groups_edit"),
    "title" => __ ( "Edit groups"),
    "description" => __ ( "Change a system group information.")
  )
);
framework_add_api_call (
  "/groups/:ID",
  "Edit",
  "groups_edit",
  array (
    "permissions" => array ( "user", "groups_edit"),
    "title" => __ ( "Edit groups"),
    "description" => __ ( "Change a system group information.")
  )
);

/**
 * Function to edit an existing group.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "groups_edit_start"))
  {
    $parameters = framework_call ( "groups_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The group description is required.");
  }
  if ( empty ( $parameters["Profile"]))
  {
    $data["Profile"] = __ ( "The profile is required.");
  }
  if ( empty ( $parameters["CostCenter"]))
  {
    $data["CostCenter"] = __ ( "The cost center is required.");
  }

  /**
   * Check if profile exist
   */
  if ( ! array_key_exists ( "Profile", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Profiles` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Profile"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["Profile"] = __ ( "Invalid profile.");
    }
  }

  /**
   * Check if cost center exist
   */
  if ( ! array_key_exists ( "CostCenter", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["CostCenter"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["CostCenter"] = __ ( "Invalid cost center.");
    }
  }

  /**
   * Check if group exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
  if ( framework_has_hook ( "groups_edit_validate"))
  {
    $data = framework_call ( "groups_edit_validate", $parameters, false, $data);
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
  $parameters["Profile"] = (int) $parameters["Profile"];
  $parameters["CostCenter"] = (int) $parameters["CostCenter"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "groups_edit_sanitize"))
  {
    $parameters = framework_call ( "groups_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "groups_edit_pre"))
  {
    $parameters = framework_call ( "groups_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change group record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Groups` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Profile` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Profile"]) . "', `CostCenter` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["CostCenter"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "groups_edit_post"))
  {
    framework_call ( "groups_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["ID"], "Profile" => $parameters["Profile"], "CostCenter" => $parameters["CostCenter"]);
  if ( framework_has_hook ( "groups_edit_notify"))
  {
    $notify = framework_call ( "groups_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "group_change", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "groups_edit_finish"))
  {
    framework_call ( "groups_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a group
 */
framework_add_hook (
  "groups_remove",
  "groups_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system group was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid group ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "groups_remove", __ ( "Remove groups"));
framework_add_api_call (
  "/groups/:ID",
  "Delete",
  "groups_remove",
  array (
    "permissions" => array ( "user", "groups_remove"),
    "title" => __ ( "Remove groups"),
    "description" => __ ( "Remove a system group.")
  )
);

/**
 * Function to remove an existing group.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "groups_remove_start"))
  {
    $parameters = framework_call ( "groups_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid group ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "groups_remove_validate"))
  {
    $data = framework_call ( "groups_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "groups_remove_sanitize"))
  {
    $parameters = framework_call ( "groups_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if group exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
   * Check if group has any extension
   */
  $allocations = filters_call ( "count_extensions", array ( "group" => $parameters["ID"]));
  $total = 0;
  foreach ( $allocations as $allocation)
  {
    $total += $allocation;
  }
  if ( $total != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "groups_remove_pre"))
  {
    $parameters = framework_call ( "groups_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove group database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "groups_remove_post"))
  {
    framework_call ( "groups_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["ID"]);
  if ( framework_has_hook ( "groups_remove_notify"))
  {
    $notify = framework_call ( "groups_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "group_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "groups_remove_finish"))
  {
    framework_call ( "groups_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to generate group call's report
 */
framework_add_hook (
  "groups_report",
  "groups_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-04-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-31T23:59:59Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the call records made by the required group."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "\$ref" => "#/components/schemas/call"
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid start date.")
            ),
            "End" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid end date.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "groups_report", __ ( "Groups use report"));
framework_add_api_call (
  "/groups/:ID/report",
  "Read",
  "groups_report",
  array (
    "permissions" => array ( "user", "groups_report"),
    "title" => __ ( "Group report"),
    "description" => __ ( "Generate a group call's usage report.", true, false),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The system group internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "groups_report_start"))
  {
    $parameters = framework_call ( "groups_report_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( empty ( $parameters["Start"]))
  {
    $data["Start"] = __ ( "Missing start date.");
  }
  $datecheck = format_form_datetime ( $parameters["Start"]);
  if ( ! array_key_exists ( "Start", $data) && empty ( $datecheck))
  {
    $data["Start"] = __ ( "Invalid start date.");
  }
  if ( empty ( $parameters["End"]))
  {
    $data["End"] = __ ( "Missing end date.");
  }
  $datecheck = format_form_datetime ( $parameters["End"]);
  if ( ! array_key_exists ( "End", $data) && empty ( $datecheck))
  {
    $data["End"] = __ ( "Invalid end date.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "groups_report_validate"))
  {
    $data = framework_call ( "groups_report_validate", $parameters);
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
  $parameters["Start"] = format_form_datetime ( $parameters["Start"]);
  $parameters["End"] = format_form_datetime ( $parameters["End"]);
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "groups_report_sanitize"))
  {
    $parameters = framework_call ( "groups_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "groups_report_pre"))
  {
    $parameters = framework_call ( "groups_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get all extensions from group information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`ExtensionPhone`.`Extension` SEPARATOR ',') AS `Extensions` FROM `Groups` LEFT JOIN `ExtensionPhone` ON `Groups`.`ID` = `ExtensionPhone`.`Group` WHERE `Groups`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " GROUP BY `Groups`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extensions = $result->fetch_assoc ()["Extensions"];

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE ( `srcid` IN ('" . $_in["mysql"]["id"]->real_escape_string ( $extensions) . "') OR `dstid` IN ('" . $_in["mysql"]["id"]->real_escape_string ( $extensions) . "')) AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $data = array ();
  while ( $call = $records->fetch_assoc ())
  {
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "groups_report_post"))
  {
    $data = framework_call ( "groups_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "groups_report_finish"))
  {
    framework_call ( "groups_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "groups_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "groups_server_reconfig");

/**
 * Function to notify server to include all groups.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all groups and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $group = $result->fetch_assoc ())
  {
    $notify = array ( "ID" => $group["ID"], "Profile" => $group["Profile"], "CostCenter" => $group["CostCenter"]);
    if ( framework_has_hook ( "groups_add_notify"))
    {
      $notify = framework_call ( "groups_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "group_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
