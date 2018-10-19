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
 * VoIP Domain dialer actions module. This module add the Asterisk client actions
 * calls related to dialer.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Dialer
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "createcampaign", "dialer_campaign_create");
framework_add_hook ( "changecampaign", "dialer_campaign_change");
framework_add_hook ( "removecampaign", "dialer_campaign_remove");
framework_add_hook ( "associatecampaign", "dialer_campaign_associate");
framework_add_hook ( "unassociatecampaign", "dialer_campaign_unassociate");
framework_add_hook ( "loadcampaignnumbers", "dialer_campaign_numbers_load");

framework_add_hook ( "createdialernumber", "dialer_campaign_number_create");
framework_add_hook ( "changedialernumber", "dialer_campaign_number_change");
framework_add_hook ( "removedialernumber", "dialer_campaign_number_remove");
framework_add_hook ( "pausedialernumber", "dialer_campaign_number_pause");
framework_add_hook ( "resumedialernumber", "dialer_campaign_number_resume");

/**
 * Function to create a new dialer campaign.
 * Required parameters are: ID, State
 * Possible results:
 *   - 200: OK, dialer campaign created
 *   - 400: Dialer campaign already exist
 *   - 401: Invalid parameters
 *   - 500: Error creating record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "State", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if dialer campaign exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 0)
  {
    return array ( "code" => 400, "message" => "Dialer campaign already exist.");
  }

  // Create database record
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Campaigns` (`ID`, `State`, `Queue`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["State"] ? "A" : "I") . "', NULL)"))
  {
    return array ( "code" => 500, "message" => "Error creating database record.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Dialer campaign created.");
}

/**
 * Function to change an existing dialer campaign.
 * Required parameters are: ID, State
 * Possible results:
 *   - 200: OK, dialer campaign changed
 *   - 400: Dialer campaign doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error updating record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "State", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if dialer campaign exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 400, "message" => "Dialer campaign doesn't exist.");
  }

  // Update database record
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Campaigns` SET `State` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["State"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 500, "message" => "Error updating database record.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Dialer campaign changed.");
}

/**
 * Function to remove an existing dialer campaign.
 * Required parameters are: ID
 * Possible results:
 *   - 200: OK, dialer campaign removed
 *   - 400: Dialer campaign doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if dialer campaign exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 400, "message" => "Dialer campaign doesn't exist.");
  }

  // Remove database record
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 500, "message" => "Error removing database record.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Dialer campaign removed.");
}

/**
 * Function to associate a dialer campaign to a queue.
 * Required parameters are: Queue, Campaign
 * Possible results:
 *   - 200: OK, dialer campaign associated
 *   - 400: Queue doesn't exist
 *   - 401: Dialer campaign doesn't exist
 *   - 402: Invalid parameters
 *   - 500: Error updating record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_associate ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Queue", $parameters) || ! array_key_exists ( "Campaign", $parameters))
  {
    return array ( "code" => 402, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if queue exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Queue"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue doesn't exist.");
  }

  // Verify if dialer campaign exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Campaign"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 401, "message" => "Dialer campaign doesn't exist.");
  }

  // Update database record
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Campaigns` SET `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"]) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Campaign"])))
  {
    return array ( "code" => 500, "message" => "Error updating database record.");
  }

  // Associate diale campaign to queue
  asterisk_exec ( "dialplan set global queue_" . (int) $parameters["Queue"] . " " . (int) $parameters["Campaign"]);

  // Finish event
  return array ( "code" => 200, "message" => "Dialer campaign associated.");
}

/**
 * Function to unassociate a dialer campaign from a queue.
 * Required parameters are: Queue, Campaign
 * Possible results:
 *   - 200: OK, dialer campaign unassociated
 *   - 400: Queue doesn't exist
 *   - 401: Dialer campaign doesn't exist
 *   - 402: Invalid parameters
 *   - 500: Error updating record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_unassociate ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Queue", $parameters) || ! array_key_exists ( "Campaign", $parameters))
  {
    return array ( "code" => 402, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if queue exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Queue"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue doesn't exist.");
  }

  // Verify if dialer campaign exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Campaign"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 401, "message" => "Dialer campaign doesn't exist.");
  }

  // Update database record
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Campaigns` SET `Queue` = NULL WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Campaign"])))
  {
    return array ( "code" => 500, "message" => "Error updating database record.");
  }

  // Unassociate dialer campaign from queue
  asterisk_exec ( "dialplan set global queue_" . (int) $parameters["Queue"] . " \"\"");

  // Finish event
  return array ( "code" => 200, "message" => "Dialer campaign unassociated.");
}

/**
 * Function to load numbers into dialer campaign.
 * Required parameters are: Campaign, Numbers[][ID, Grouper, Number, Tries, State]
 * Possible results:
 *   - 200: OK, numbers added to campaign
 *   - 400: Dialer campaign doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error inserting record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_numbers_load ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Campaign", $parameters) || ! array_key_exists ( "Numbers", $parameters) || ! is_array ( $parameters["Numbers"]))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check Numbers parameters structure
  foreach ( $parameters["Numbers"] as $number)
  {
    if ( ! array_key_exists ( "ID", $number) || ! array_key_exists ( "Grouper", $number) || ! array_key_exists ( "Number", $number) || ! array_key_exists ( "Tries", $number) || ! array_key_exists ( "State", $number))
    {
      return array ( "code" => 401, "message" => "Invalid parameters.");
    }
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if dialer campaign exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Campaign"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 400, "message" => "Dialer campaign doesn't exist.");
  }

  // Insert each number
  foreach ( $parameters["Numbers"] as $number)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `CampaignEntries` (`ID`, `Campaign`, `Grouper`, `Number`, `Tries`, `State`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( (int) $number["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Campaign"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $number["Grouper"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $number["Number"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( (int) $number["Tries"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $number["State"]) . "')"))
    {
      return array ( "code" => 500, "message" => "Error inserting database record.");
    }
  }

  // Finish event
  return array ( "code" => 200, "message" => "Numbers added to database.");
}

/**
 * Function to create one number into dialer campaign.
 * Required parameters are: Campaign, ID, Grouper, Number, Tries, State
 * Possible results:
 *   - 200: OK, number added to campaign
 *   - 400: Dialer campaign doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error inserting record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_number_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Campaign", $parameters) || ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Grouper", $parameters) || ! array_key_exists ( "Number", $parameters) || ! array_key_exists ( "Tries", $parameters) || ! array_key_exists ( "State", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if dialer campaign exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Campaigns` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Campaign"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 400, "message" => "Dialer campaign doesn't exist.");
  }

  // Insert number
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `CampaignEntries` (`ID`, `Campaign`, `Grouper`, `Number`, `Tries`, `State`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Campaign"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Grouper"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Tries"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["State"]) . "')"))
  {
    return array ( "code" => 500, "message" => "Error inserting database record.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Number added to database.");
}

/**
 * Function to change number at dialer campaign.
 * Required parameters are: ID, Grouper, Number, Tries, State
 * Possible results:
 *   - 200: OK, number changed
 *   - 400: Dialer number doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error updating record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_number_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Grouper", $parameters) || ! array_key_exists ( "Number", $parameters) || ! array_key_exists ( "Tries", $parameters) || ! array_key_exists ( "State", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if dialer number exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntriess` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 400, "message" => "Dialer number doesn't exist.");
  }

  // Insert number
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `CampaignEntries` SET `Grouper` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Grouper"]) . "', `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "', `Tries` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Tries"]) . ", `State` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["State"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 500, "message" => "Error updating database record.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Number changed at database.");
}

/**
 * Function to remove number from dialer campaign.
 * Required parameters are: ID
 * Possible results:
 *   - 200: OK, number removed
 *   - 400: Dialer number doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_number_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if dialer number exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntriess` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 400, "message" => "Dialer number doesn't exist.");
  }

  // Remove number
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `CampaignEntries` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 500, "message" => "Error removing database record.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Number removed from database.");
}

/**
 * Function to pause a number from dialer campaign.
 * Required parameters are: ID
 * Possible results:
 *   - 200: OK, number paused
 *   - 400: Dialer number doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error updating record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_number_pause ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if dialer number exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntriess` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 400, "message" => "Dialer number doesn't exist.");
  }

  // Pause number
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `CampaignEntries` SET `State` = 'P' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 500, "message" => "Error updating database record.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Number paused from database.");
}

/**
 * Function to unpause a number from dialer campaign.
 * Required parameters are: ID
 * Possible results:
 *   - 200: OK, number unpaused
 *   - 400: Dialer number doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error updating record
 *   - 501: Internal system error
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dialer_campaign_number_unpause ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Check if database connection is alive
  mysql_check ();

  // Verify if dialer number exist
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CampaignEntriess` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 501, "message" => "Error requesting database record.");
  }
  if ( $result->num_rows != 1)
  {
    return array ( "code" => 400, "message" => "Dialer number doesn't exist.");
  }

  // Pause number
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `CampaignEntries` SET `State` = 'W' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    return array ( "code" => 500, "message" => "Error updating database record.");
  }

  // Finish event
  return array ( "code" => 200, "message" => "Number unpaused from database.");
}
?>
