<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * VoIP Domain reports api module. This module add the api calls related to
 * reports.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Reports
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch heat report
 */
framework_add_hook ( "reports_heat", "reports_heat");
framework_add_permission ( "reports_heat", __ ( "Request heat map report"));
framework_add_api_call ( "/reports/heat", "Read", "reports_heat", array ( "permissions" => array ( "user", "reports_heat")));

/**
 * Function to generate heat report data.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_heat ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["type"] = (int) $parameters["type"];
  $parameters["start"] = substr ( $parameters["start"], 6, 4) . "-" . substr ( $parameters["start"], 3, 2) . "-" . substr ( $parameters["start"], 0, 2);
  $parameters["finish"] = substr ( $parameters["finish"], 6, 4) . "-" . substr ( $parameters["finish"], 3, 2) . "-" . substr ( $parameters["finish"], 0, 2);

  /**
   * Create filter based on call type
   */
  switch ( $parameters["type"])
  {
    case "2":
      $filter = " AND `calltype` = 1";
      break;
    case "3":
      $filter = " AND (`calltype` = 2 OR `calltype` = 3 OR `calltype` = 4 OR `calltype` = 5 OR `calltype` = 6 OR `calltype` = 7 OR `calltype` = 8)";
      break;
    case "4":
      $filter = " AND (`calltype` = 7 OR `calltype` = 8)";
      break;
    case "5":
      $filter = " AND (`calltype` = 2 OR `calltype` = 3 OR `calltype` = 4 OR `calltype` = 5 OR `calltype` = 6)";
      break;
    case "6":
      $filter = " AND `calltype` = 3";
      break;
    case "7":
      $filter = " AND `calltype` = 4";
      break;
    case "8":
      $filter = " AND `calltype` = 5";
      break;
    default:
      $filter = "";
      break;
  }

  /**
   * Request call count from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT SUBSTR(`calldate`, 1, 13) AS `Data`, COUNT(*) AS `Total` FROM `cdr` WHERE `calldate` != '0000-00-00 00:00:00' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . " 00:00:00' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["finish"]) . " 23:59:59'" . $filter . " GROUP BY `Data` ORDER BY `Data`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Prepare data to D3
   */
  $matrix = array ();
  $daykey = array ();
  for ( $day = 1; $day <= 7; $day++)
  {
    $daykey[date ( "Y-m-d", mktime ( 0, 0, 0, substr ( $parameters["start"], 3, 2), substr ( $parameters["start"], 0, 2) + $day - 1, substr ( $parameters["start"], 6, 4)))] = $day;
    $matrix[$day] = array ();
    for ( $hour = 0; $hour <= 23; $hour++)
    {
      $matrix[$day][$hour] = 0;
    }
  }
  while ( $record = $result->fetch_assoc ())
  {
    $matrix[$daykey[substr ( $record["Data"], 0, 10)]][(int) substr ( $record["Data"], 11, 2)] = $record["Total"];
  }
  $data = array ();
  for ( $day = 1; $day <= 7; $day++)
  {
    for ( $hour = 0; $hour <= 23; $hour++)
    {
      $data[] = array ( "day" => $day, "hour" => $hour, "value" => (int) $matrix[$day][$hour]);
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch extensions listing report
 */
framework_add_hook ( "reports_list", "reports_list");
framework_add_permission ( "reports_list", __ ( "Request extensions listing"));
framework_add_api_call ( "/reports/list", "Read", "reports_list", array ( "permissions" => array ( "user", "reports_list")));

/**
 * Function to generate extensions list report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_list ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["name"])));
  $parameters["group"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["group"])));
  $parameters["server"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["server"])));

  /**
   * Prepare where clause
   */
  $where = "";
  if ( ! empty ( $parameters["name"]))
  {
    $where .= " AND ( `Extensions`.`NameFon` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( fonetiza ( $parameters["name"])) . "%' OR `Extensions`.`Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "%')";
  }
  if ( ! empty ( $parameters["group"]))
  {
    $where .= " AND `Groups`.`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["group"]) . "%'";
  }
  if ( ! empty ( $parameters["server"]))
  {
    $where .= " AND `Servers`.`Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["server"]) . "%'";
  }

  /**
   * Search extensions
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Extension`, `Extensions`.`Name`, `Extensions`.`Permissions`, `Groups`.`ID` AS `GroupID`, `Groups`.`Description` AS `Group`, `Servers`.`ID` AS `ServerID`, `Servers`.`Name` AS `Server` FROM `Extensions` LEFT JOIN `Groups` ON `Extensions`.`Group` = `Groups`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "") . " ORDER BY `Extensions`.`Extension`"))
  {
    while ( $extension = $result->fetch_assoc ())
    {
      $extension["Permissions"] = json_decode ( $extension["Permissions"], true);
      $permissions = array ();
      $permissions["mobile"] = $extension["Permissions"]["mobile"] == true;
      $permissions["international"] = $extension["Permissions"]["international"] == true;
      $permissions["longdistance"] = $extension["Permissions"]["longdistance"] == true;
      $permissions["nopass"] = $extension["Permissions"]["nopass"] == true;
      $permissions["voicemail"] = $extension["Permissions"]["voicemail"] == true;
      $data[] = array ( $extension["ID"], $extension["Extension"], $extension["Name"], $extension["GroupID"], $extension["Group"], $extension["ServerID"], $extension["Server"], $permissions);
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch ranges listing report
 */
framework_add_hook ( "reports_ranges", "reports_ranges");
framework_add_permission ( "reports_ranges", __ ( "Request ranges listing"));
framework_add_api_call ( "/reports/ranges", "Read", "reports_ranges", array ( "permissions" => array ( "user", "reports_ranges")));

/**
 * Function to generate ranges list report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_ranges ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["range"] = (int) $parameters["range"];

  /**
   * Search range
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `Ranges`.*, `Servers`.`Name` AS `ServerName` FROM `Ranges` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID` WHERE `Ranges`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["range"])))
  {
    while ( $range = $result->fetch_assoc ())
    {
      for ( $extension = $range["Start"]; $extension <= $range["Finish"]; $extension++)
      {
        $allocation = filters_call ( "get_allocations", array ( "number" => $extension));
        if ( sizeof ( $allocation) != 0)
        {
          $data[] = array ( $extension, ( array_key_exists ( "ViewPath", $allocation[0]) ? "<a href=\"" . $allocation[0]["ViewPath"] . "\">" : "") . ( array_key_exists ( "Name", $allocation[0]["Record"]) ? $allocation[0]["Record"]["Name"] : $allocation[0]["Record"]["Description"]) . ( array_key_exists ( "ViewPath", $allocation[0]) ? "</a>" : ""), __ ( $allocation[0]["Type"]), ( array_key_exists ( "GroupName", $allocation[0]["Record"]) ? "<a href=\"/groups/" . $allocation[0]["Record"]["Group"] . "/view\">" . $allocation[0]["Record"]["GroupName"] . "</a>" : "--"), $range["ServerName"]);
        } else {
          $data[] = array ( $extension, "--", "<i>" . __ ( "Free") . "</i>", "--", $range["ServerName"]);
        }
      }
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch cost centers financial report
 */
framework_add_hook ( "reports_financial_costcenters", "reports_financial_costcenters");
framework_add_permission ( "reports_financial_costcenters", __ ( "Request financial cost centers report"));
framework_add_api_call ( "/reports/financial/costcenter/:id", "Read", "reports_financial_costcenters", array ( "permissions" => array ( "user", "reports_financial_costcenters")));

/**
 * Function to generate cost centers financial report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_financial_costcenters ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( $parameters["start"]);
  $parameters["end"] = format_form_datetime ( $parameters["end"]);
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search extensions with the requested cost center
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.* FROM `Extensions` LEFT JOIN `Groups` ON `Extensions`.`Group` = `Groups`.`ID` WHERE ( `Extensions`.`CostCenter` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . " OR ( `Extensions`.`CostCenter` IS NULL AND `Groups`.`CostCenter` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . ")) ORDER BY `Extensions`.`Extension`"))
  {
    while ( $extension = $result->fetch_assoc ())
    {
      if ( $sum = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total`, SUM(value) AS `Cost`, SUM(billsec) as `Time` FROM `cdr` WHERE `src` = '" . $_in["mysql"]["id"]->real_escape_string ( $extension["Extension"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "'"))
      {
        $values = $sum->fetch_assoc ();
      } else {
        $values = array ( "Total" => 0, "Cost" => 0, "Time" => 0);
      }
      $data[] = array ( $extension["ID"], $extension["Extension"], $extension["Name"], $values["Total"], $values["Time"], format_secs_to_string ( $values["Time"]), number_format ( $values["Cost"], 2, __ ( ","), __ ( ".")));
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch groups financial report
 */
framework_add_hook ( "reports_financial_groups", "reports_financial_groups");
framework_add_permission ( "reports_financial_groups", __ ( "Request financial groups report"));
framework_add_api_call ( "/reports/financial/group/:id", "Read", "reports_financial_groups", array ( "permissions" => array ( "user", "reports_financial_groups")));

/**
 * Function to generate groups financial report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_financial_groups ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( $parameters["start"]);
  $parameters["end"] = format_form_datetime ( $parameters["end"]);
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search extensions from the requested group
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Group` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . " ORDER BY `Extension`"))
  {
    while ( $extension = $result->fetch_assoc ())
    {
      if ( $sum = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total`, SUM(value) AS `Cost`, SUM(billsec) as `Time` FROM `cdr` WHERE `src` = '" . $_in["mysql"]["id"]->real_escape_string ( $extension["Extension"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "'"))
      {
        $values = $sum->fetch_assoc ();
      } else {
        $values = array ( "Total" => 0, "Cost" => 0, "Time" => 0);
      }
      $data[] = array ( $extension["ID"], $extension["Extension"], $extension["Name"], $values["Total"], $values["Time"], format_secs_to_string ( $values["Time"]), number_format ( $values["Cost"], 2, __ ( ","), __ ( ".")));
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch gateways financial report
 */
framework_add_hook ( "reports_financial_gateways", "reports_financial_gateways");
framework_add_permission ( "reports_financial_gateways", __ ( "Request financial gateways report"));
framework_add_api_call ( "/reports/financial/gateway", "Read", "reports_financial_gateways", array ( "permissions" => array ( "user", "reports_financial_gateways")));

/**
 * Function to generate gateways financial report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_financial_gateways ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( $parameters["start"]);
  $parameters["end"] = format_form_datetime ( $parameters["end"]);

  /**
   * Search gateways
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` ORDER BY `Description`"))
  {
    while ( $gateway = $result->fetch_assoc ())
    {
      if ( $sum = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total`, SUM(value) AS `Cost`, SUM(billsec) as `Time` FROM `cdr` WHERE `gateway` = '" . $_in["mysql"]["id"]->real_escape_string ( $gateway["ID"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "'"))
      {
        $values = $sum->fetch_assoc ();
      } else {
        $values = array ( "Total" => 0, "Cost" => 0, "Time" => 0);
      }
      $data[] = array ( $gateway["ID"], $gateway["Description"], $values["Total"], $values["Time"], format_secs_to_string ( $values["Time"]), number_format ( $values["Cost"], 2, __ ( ","), __ ( ".")));
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch system health informations
 */
framework_add_hook ( "reports_system_health", "reports_system_health");
framework_add_permission ( "reports_system_health", __ ( "Request server health"));
framework_add_api_call ( "/reports/status", "Read", "reports_system_health", array ( "permissions" => array ( "user", "reports_system_health")));

/**
 * Function to generate system health informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_system_health ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get CPU usage informations
   */
  $exec_loads = sys_getloadavg ();
  $exec_cores = trim ( shell_exec ( "grep -P '^processor' /proc/cpuinfo | wc -l"));
  $cpu = round ( $exec_loads[1] / ( $exec_cores + 1) * 100, 0);

  /**
   * Get memory usage informations
   */
  $exec_free = trim ( shell_exec ( "free | grep -P '^Mem:'"));
  $get_mem = preg_split ( "/[\s]+/", $exec_free);
  $mem = round ( $get_mem[2] / $get_mem[1] * 100, 0);
  $suffix = array ( "B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
  $class = min ( (int) log ( $get_mem[2], 1024) , count ( $suffix) - 1);
  $total_mem = sprintf ( "%1.2f", $get_mem[2] / pow ( 1024, $class)) . " " . $suffix[$class];
  $class = min ( (int) log ( $get_mem[1], 1024) , count ( $suffix) - 1);
  $used_mem = sprintf ( "%1.2f", $get_mem[1] / pow ( 1024, $class)) . " " . $suffix[$class];

  /**
   * Get storage usage informations
   */
  $total_disk = disk_total_space ( ".");
  $used_disk = $total_disk - disk_free_space ( ".");
  $disk = round ( $used_disk * 100 / $total_disk, 0);
  $class = min ( (int) log ( $total_disk, 1024) , count ( $suffix) - 1);
  $total_disk = sprintf ( "%1.2f", $total_disk / pow ( 1024, $class)) . " " . $suffix[$class];
  $class = min ( (int) log ( $used_disk, 1024) , count ( $suffix) - 1);
  $used_disk = sprintf ( "%1.2f", $used_disk / pow ( 1024, $class)) . " " . $suffix[$class];

  /**
   * Format data
   */
  $data = array ();
  $data["memory"] = array ( "percent" => $mem, "used" => $used_mem, "total" => $total_mem);
  $data["cpu"] = array ( "percent" => $cpu, "processors" => $exec_cores);
  $data["storage"] = array ( "percent" => $disk, "used" => $used_disk, "total" => $total_disk);

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch extensions activity listing report
 */
framework_add_hook ( "reports_activity", "reports_activity");
framework_add_permission ( "reports_activity", __ ( "Request extensions activity report"));
framework_add_api_call ( "/reports/activity", "Read", "reports_activity", array ( "permissions" => array ( "user", "reports_activity")));

/**
 * Function to generate extensions activity list report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_activity ( $buffer, $parameters)
{
  global $_in;

  /**
   * Search extensions
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Extension`, `Extensions`.`Name`, `Activity`.`Date` FROM `Extensions` LEFT JOIN `Activity` ON `Extensions`.`ID` = `Activity`.`UID`"))
  {
    while ( $extension = $result->fetch_assoc ())
    {
      $data[] = array ( $extension["ID"], $extension["Extension"], $extension["Name"], format_db_timestamp ( $extension["Date"]), format_db_datetime ( $extension["Date"]));
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate user received call's report
 */
framework_add_hook ( "user_received_report", "user_received_report");
framework_add_permission ( "user_received_report", __ ( "User received calls report"));
framework_add_api_call ( "/reports/received/user/:id", "Read", "user_received_report", array ( "permissions" => array ( "user", "user_received_report")));

/**
 * Function to generate user received calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function user_received_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Get user extension informations
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `dst` = '" . $_in["mysql"]["id"]->real_escape_string ( $extension["Extension"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "' ORDER BY `calldate` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $records->fetch_assoc ())
  {
    $data["extension"] = $extension["Extension"];
    $output[] = filters_call ( "process_call", $data);
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}

/**
 * API call to generate group received call's report
 */
framework_add_hook ( "group_received_report", "group_received_report");
framework_add_permission ( "group_received_report", __ ( "Group received calls report"));
framework_add_api_call ( "/reports/received/group/:id", "Read", "group_received_report", array ( "permissions" => array ( "user", "group_received_report")));

/**
 * Function to generate group received calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function group_received_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Get all extensions at defined group informations
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Group` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extensions = "";
  while ( $extension = $result->fetch_assoc ())
  {
    $extensions .= ", " . $extension["Extension"];
  }
  $extensions = substr ( $extensions, 2);

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `dst` IN (" . $extensions . ") AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "' ORDER BY `calldate` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $records->fetch_assoc ())
  {
    $data["extension"] = $data["dst"];
    $output[] = filters_call ( "process_call", $data);
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}

/**
 * API call to generate gateway received call's report
 */
framework_add_hook ( "gateway_received_report", "gateway_received_report");
framework_add_permission ( "gateway_received_report", __ ( "Gateway received calls report"));
framework_add_api_call ( "/reports/received/gateway/:id", "Read", "gateway_received_report", array ( "permissions" => array ( "user", "gateway_received_report")));

/**
 * Function to generate gateway received calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateway_received_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Get all ranges to filter
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
  }
  $ranges = "";
  while ( $range = $result->fetch_assoc ())
  {
    $ranges .= " OR (`dst` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) $range["Start"]) . " AND `dst` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) $range["Finish"]) . ")";
  }
  $ranges = "(" . substr ( $ranges, 4) . ")";

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `channel` LIKE 'SIP/gateway" . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . "-%' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "' AND " . $ranges))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $records->fetch_assoc ())
  {
    $parameters["extension"] = $data["dst"];
    $output[] = filters_call ( "process_call", $data);
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}

/**
 * API call to generate system received call's report
 */
framework_add_hook ( "system_received_report", "system_received_report");
framework_add_permission ( "system_received_report", __ ( "System received calls report"));
framework_add_api_call ( "/reports/received/all", "Read", "system_received_report", array ( "permissions" => array ( "user", "system_received_report")));

/**
 * Function to generate system received calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function system_received_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));

  /**
   * Get all ranges to filter
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
  }
  $ranges = "";
  while ( $range = $result->fetch_assoc ())
  {
    $ranges .= " OR (`dst` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) $range["Start"]) . " AND `dst` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) $range["Finish"]) . ")";
  }
  $ranges = "(" . substr ( $ranges, 4) . ")";

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "' AND " . $ranges))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $records->fetch_assoc ())
  {
    $output[] = filters_call ( "process_call", $data);
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}

/**
 * API call to generate user made call's report
 */
framework_add_hook ( "user_made_report", "user_made_report");
framework_add_permission ( "user_made_report", __ ( "User made calls report"));
framework_add_api_call ( "/reports/made/user/:id", "Read", "user_made_report", array ( "permissions" => array ( "user", "user_made_report")));

/**
 * Function to generate users made calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function user_made_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Get extension informations
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `src` = '" . $_in["mysql"]["id"]->real_escape_string ( $extension["Extension"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "' ORDER BY `calldate` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $records->fetch_assoc ())
  {
    $data["extension"] = $extension["Extension"];
    $output[] = filters_call ( "process_call", $data);
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}

/**
 * API call to generate group made call's report
 */
framework_add_hook ( "group_made_report", "group_made_report");
framework_add_permission ( "group_made_report", __ ( "Group made calls report"));
framework_add_api_call ( "/reports/made/group/:id", "Read", "group_made_report", array ( "permissions" => array ( "user", "group_made_report")));

/**
 * Function to generate group made calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function group_made_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Get all extensions at defined group informations
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Group` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each extension
   */
  $output = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `src` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $extension["Extension"]) . " AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $data = $records->fetch_assoc ())
    {
      $data["extension"] = $extension["Extension"];
      $output[] = filters_call ( "process_call", $data);
    }
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}

/**
 * API call to generate gateway made call's report
 */
framework_add_hook ( "gateway_made_report", "gateway_made_report");
framework_add_permission ( "gateway_made_report", __ ( "Gateway made calls report"));
framework_add_api_call ( "/reports/made/gateway/:id", "Read", "gateway_made_report", array ( "permissions" => array ( "user", "gateway_made_report")));

/**
 * Function to generate gateway made calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateway_made_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `gateway` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "' ORDER BY `calldate` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $records->fetch_assoc ())
  {
    $output[] = filters_call ( "process_call", $data);
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}

/**
 * API call to generate system made call's report
 */
framework_add_hook ( "system_made_report", "system_made_report");
framework_add_permission ( "system_made_report", __ ( "System made calls report"));
framework_add_api_call ( "/reports/made/all", "Read", "system_made_report", array ( "permissions" => array ( "user", "system_made_report")));

/**
 * Function to generate system made calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function system_made_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));

  /**
   * Get all ranges to filter
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
  }
  $ranges = "";
  while ( $range = $result->fetch_assoc ())
  {
    $ranges .= " OR (`src` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) $range["Start"]) . " AND `src` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) $range["Finish"]) . ")";
  }
  $ranges = "(" . substr ( $ranges, 4) . ")";

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "' AND " . $ranges))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $records->fetch_assoc ())
  {
    $output[] = filters_call ( "process_call", $data);
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}
?>
