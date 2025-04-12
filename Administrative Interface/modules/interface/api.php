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
 * VoIP Domain main framework interface module API. This module has all basic
 * system API call implementations.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Interface
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Call component documentation
 */
framework_add_component_documentation (
  "call",
  array (
    "type" => "object",
    "xml" => array (
      "name" => "call"
    ),
    "properties" => array (
      "CallDates" => array (
        "type" => "object",
        "description" => __ ( "An object containing the call dates and timestamps."),
        "properties" => array (
          "Start" => array (
            "type" => "object",
            "description" => __ ( "An object containing the start date time and timestamp of the call."),
            "properties" => array (
              "Timestamp" => array (
                "type" => "int64",
                "description" => __ ( "The UNIX timestamp for the start of the call."),
                "example" => 1640640681
              ),
              "Datetime" => array (
                "type" => "date-time",
                "description" => __ ( "The ISO8601 date and time for the start of the call."),
                "example" => "2021-12-27T21:31:21Z"
              )
            )
          ),
          "Answered" => array (
            "type" => "object",
            "description" => __ ( "An object containing the date time and timestamp when the call was answered."),
            "properties" => array (
              "Timestamp" => array (
                "type" => "int64",
                "description" => __ ( "The UNIX timestamp for the time the call was answered (empty if not answered)."),
                "example" => 1640640681
              ),
              "Datetime" => array (
                "type" => "date-time",
                "description" => __ ( "The ISO8601 date and time for the time the call was answered (empty if not answered)."),
                "example" => "2021-12-27T21:31:21Z"
              )
            )
          ),
          "Finished" => array (
            "type" => "object",
            "description" => __ ( "An object containing the finish date time and timestamp of the call."),
            "properties" => array (
              "Timestamp" => array (
                "type" => "int64",
                "description" => __ ( "The UNIX timestamp for the start of the call."),
                "example" => 1640640681
              ),
              "Datetime" => array (
                "type" => "date-time",
                "description" => __ ( "The ISO8601 date and time for the start of the call."),
                "example" => "2021-12-27T21:31:21Z"
              )
            )
          )
        )
      ),
      "Server" => array (
        "type" => "object",
        "description" => __ ( "The server information of the call."),
        "properties" => array (
          "ID" => array (
            "type" => "integer",
            "description" => __ ( "The server unique identifier of the call."),
            "example" => 2
          ),
          "Description" => array (
            "type" => "string",
            "description" => __ ( "The server description of the call."),
            "example" => __ ( "Warehouse 1")
          )
        )
      ),
      "Flow" => array (
        "type" => "string",
        "enum" => array ( "in", "out"),
        "description" => __ ( "The flow of the call, if is an outbound or inbound call."),
        "example" => "out"
      ),
      "Duration" => array (
        "type" => "integer",
        "description" => __ ( "The total duration of call in seconds."),
        "example" => 105
      ),
      "RingingTime" => array (
        "type" => "integer",
        "description" => __ ( "The total ringing duration of call in seconds."),
        "example" => 13
      ),
      "BillingTime" => array (
        "type" => "integer",
        "description" => __ ( "The total billing duration of call in seconds."),
        "example" => 92
      ),
      "Result" => array (
        "type" => "string",
        "enum" => array ( "Answered", "Not answered", "Busy", "Failed", "Congestion"),
        "description" => __ ( "The result state of the call."),
        "example" => "Answered"
      ),
      "ResultI18N" => array (
        "type" => "string",
        "enum" => array ( __ ( "Answered"), __ ( "Not answered"), __ ( "Busy"), __ ( "Failed"), __ ( "Congestion")),
        "description" => __ ( "The translated result state of the call."),
        "example" => __ ( "Answered")
      ),
      "Value" => array (
        "type" => "float",
        "description" => __ ( "If call has any cost, the value will be available here."),
        "example" => 0.02312
      ),
      "ValueI18N" => array (
        "type" => "string",
        "description" => __ ( "If call has any cost, the translated value will be available here."),
        "example" => number_format ( 0.02312, 2, __ ( "."), __ ( ","))
      ),
      "Gateway" => array (
        "type" => "object",
        "description" => __ ( "The gateway information of the call."),
        "properties" => array (
          "ID" => array (
            "type" => "integer",
            "description" => __ ( "The gateway unique identifier that routed the call."),
            "example" => 1
          ),
          "Description" => array (
            "type" => "string",
            "description" => __ ( "The gateway description that routed the call."),
            "example" => __ ( "VoIP Provider")
          )
        )
      ),
      "CostCenter" => array (
        "type" => "object",
        "description" => __ ( "The cost center information of the call."),
        "properties" => array (
          "ID" => array (
            "type" => "integer",
            "description" => __ ( "The cost center unique identifier of the call."),
            "example" => 4
          ),
          "Description" => array (
            "type" => "string",
            "description" => __ ( "The cost center description of the call."),
            "example" => __ ( "IT Department")
          ),
          "Code" => array (
            "type" => "string",
            "description" => __ ( "The cost center code of the call."),
            "pattern" => "/^[0-9]{6}$/",
            "example" => "100001"
          )
        )
      ),
      "Source" => array (
        "type" => "object",
        "description" => __ ( "The call source information."),
        "properties" => array (
          "Type" => array (
            "type" => "string",
            "enum" => array ( "Unknown", "Local", "Interstate", "International"),
            "description" => __ ( "The type of call source endpoint."),
            "example" => "Local"
          ),
          "TypeI18N" => array (
            "type" => "string",
            "enum" => array ( __ ( "Unknown"), __ ( "Local"), __ ( "Interstate"), __ ( "International")),
            "description" => __ ( "The translated type of call source endpoint."),
            "example" => __ ( "Local")
          ),
          "Endpoint" => array (
            "type" => "string",
            "enum" => array ( "Unknown", "Extension", "Landline", "Mobile", "Marine", "Tollfree", "Special", "Satellite", "Services"),
            "description" => __ ( "The description of call source endpoint."),
            "example" => "Extension"
          ),
          "EndpointI18N" => array (
            "type" => "string",
            "enum" => array ( __ ( "Unknown"), __ ( "Extension"), __ ( "Landline"), __ ( "Mobile"), __ ( "Marine"), __ ( "Tollfree"), __ ( "Special"), __ ( "Satellite"), __ ( "Services")),
            "description" => __ ( "The translated description of call source endpoint."),
            "example" => __ ( "Extension")
          ),
          "Name" => array (
            "type" => "string",
            "description" => __ ( "The source name."),
            "example" => __ ( "John Doe")
          ),
          "Number" => array (
            "type" => "string",
            "description" => __ ( "The source number."),
            "example" => "1000"
          )
        )
      ),
      "Destination" => array (
        "type" => "object",
        "description" => __ ( "The call destination information."),
        "properties" => array (
          "Type" => array (
            "type" => "string",
            "enum" => array ( "Unknown", "Local", "Interstate", "International"),
            "description" => __ ( "The type of call destination endpoint."),
            "example" => "Local"
          ),
          "TypeI18N" => array (
            "type" => "string",
            "enum" => array ( __ ( "Unknown"), __ ( "Local"), __ ( "Interstate"), __ ( "International")),
            "description" => __ ( "The translated type of call destination endpoint."),
            "example" => __ ( "Local")
          ),
          "Endpoint" => array (
            "type" => "string",
            "enum" => array ( "Unknown", "Extension", "Landline", "Mobile", "Marine", "Tollfree", "Special", "Satellite", "Services"),
            "description" => __ ( "The description of call destination endpoint."),
            "example" => "Extension"
          ),
          "EndpointI18N" => array (
            "type" => "string",
            "enum" => array ( __ ( "Unknown"), __ ( "Extension"), __ ( "Landline"), __ ( "Mobile"), __ ( "Marine"), __ ( "Tollfree"), __ ( "Special"), __ ( "Satellite"), __ ( "Services")),
            "description" => __ ( "The translated description of call destination endpoint."),
            "example" => __ ( "Extension")
          ),
          "Name" => array (
            "type" => "string",
            "description" => __ ( "The destination name."),
            "example" => __ ( "John Doe")
          ),
          "Number" => array (
            "type" => "string",
            "description" => __ ( "The destination number."),
            "example" => "1000"
          )
        )
      ),
      "WhoHungUp" => array (
        "type" => "string",
        "enum" => array ( "Caller", "Called"),
        "description" => __ ( "Who hung up the call, if was the caller or the called party."),
        "example" => "Called"
      ),
      "Quality" => array (
        "type" => "object",
        "description" => __ ( "The call quality analysis."),
        "properties" => array (
          "MOS" => array (
            "type" => "float",
            "description" => __ ( "The MOS (Mean Opinion Score) of the call based on call statistics."),
            "example" => 4.40
          ),
          "Color" => array (
            "type" => "string",
            "enum" => array ( "green", "yellow", "red"),
            "description" => __ ( "The quality semaphore of the call quality."),
            "example" => "green"
          ),
          "QOS" => array (
            "type" => "object",
            "description" => __ ( "If available, the QOS (Quality Of Service) statistics of the call."),
            "properties" => array (
              "SSRC" => array (
                "type" => "object",
                "description" => __ ( "Synchronization Source address."),
                "properties" => array (
                  "Our" => array (
                    "type" => "integer",
                    "description" => __ ( "Our synchronization source (SSRC)."),
                    "example" => 293318885
                  ),
                  "Their" => array (
                    "type" => "integer",
                    "description" => __ ( "Their synchronization source (SSRC)."),
                    "example" => 663645067
                  )
                )
              ),
              "SentPackets" => array (
                "type" => "object",
                "description" => __ ( "Statistics of sent packets."),
                "properties" => array (
                  "Packets" => array (
                    "type" => "integer",
                    "description" => __ ( "The number of sent packets."),
                    "example" => 6217
                  ),
                  "Lost" => array (
                    "type" => "integer",
                    "description" => __ ( "The number of sent packets lost."),
                    "example" => 0
                  ),
                  "LostPercentage" => array (
                    "type" => "double",
                    "description" => __ ( "The percentage of sent packets lost."),
                    "example" => 0.00
                  ),
                  "Jitter" => array (
                    "type" => "float",
                    "description" => __ ( "The sent packets jitter."),
                    "example" => 0.000858
                  )
                )
              ),
              "ReceivedPackets" => array (
                "type" => "object",
                "description" => __ ( "Statistics of received packets."),
                "properties" => array (
                  "Packets" => array (
                    "type" => "integer",
                    "description" => __ ( "The number of received packets."),
                    "example" => 6193
                  ),
                  "Lost" => array (
                    "type" => "integer",
                    "description" => __ ( "The number of received packets lost."),
                    "example" => 0
                  ),
                  "LostPercentage" => array (
                    "type" => "double",
                    "description" => __ ( "The percentage of received packets lost."),
                    "example" => 0.00
                  ),
                  "Jitter" => array (
                    "type" => "float",
                    "description" => __ ( "The received packets jitter."),
                    "example" => 0.000000
                  )
                )
              ),
              "RTT" => array (
                "type" => "float",
                "description" => __ ( "The round trip time (RTT)."),
                "example" => 0.305239
              )
            )
          )
        )
      ),
      "CODEC" => array (
        "type" => "object",
        "description" => __ ( "The CODEC used on the call."),
        "properties" => array (
          "Native" => array (
            "type" => "string",
            "description" => __ ( "The native codec used on the call."),
            "example" => "uLaw"
          ),
          "Read" => array (
            "type" => "string",
            "description" => __ ( "The read codec used on the call."),
            "example" => "opus"
          ),
          "Write" => array (
            "type" => "string",
            "description" => __ ( "The write codec used on the call."),
            "example" => "g729"
          )
        )
      ),
      "UniqueID" => array (
        "type" => "string",
        "description" => __ ( "The call unique identifier."),
        "example" => "1407858765.489"
      ),
      "LinkedID" => array (
        "type" => "string",
        "description" => __ ( "The call linked unique identifier, if any."),
        "example" => "1407854767.21"
      ),
      "SIPID" => array (
        "type" => "string",
        "description" => __ ( "The SIP unique identification number of the call."),
        "example" => "dWRT-KvUgMPJCI988W0WnF1B8KMQyjvj"
      ),
      "Recorded" => array (
        "type" => "boolean",
        "description" => __ ( "If the call was recorded."),
        "example" => true
      ),
      "Captured" => array (
        "type" => "boolean",
        "description" => __ ( "If the call SIP flow was captured."),
        "example" => true
      ),
      "Flags" => array (
        "type" => "array",
        "description" => __ ( "The flags of the call."),
        "example" => array ( "transhipped", "hunt"),
        "items" => array (
          "type" => "string",
          "description" => __ ( "The flag itself.")
        )
      )
    )
  )
);

/**
 * Add framework daemon return controll API hooks.
 */
framework_add_hook (
  "tasks_return",
  "tasks_return",
  IN_HOOK_INSERT_FIRST,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Result" => array (
          "type" => "integer",
          "description" => __ ( "Result code of operation."),
          "required" => true,
          "example" => 200
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An object with result."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Result" => array (
              "type" => "boolean",
              "description" => __ ( "The result of operation."),
              "example" => true
            )
          )
        )
      ),
      201 => array (),
      422 => array ()
    )
  )
);
framework_add_api_call (
  "/return/:ID",
  "Create",
  "tasks_return",
  array (
    "permissions" => array ( "server"),
    "title" => __ ( "System remote task pingback endpoint."),
    "description" => __ ( "Return result of a remote server operation task to the system."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "int64",
        "description" => __ ( "System remote server task unique identifier."),
        "example" => 2371
      )
    )
  )
);

/**
 * Function to process remote Asterisk controller daemon return data.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of execution
 */
function tasks_return ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tasks_return_start"))
  {
    $parameters = framework_call ( "tasks_return_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tasks_return_validate"))
  {
    $data = framework_call ( "tasks_return_validate", $parameters);
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
  $parameters["Result"] = (int) $parameters["Result"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "tasks_return_sanitize"))
  {
    $parameters = framework_call ( "tasks_return_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tasks_return_pre"))
  {
    $parameters = framework_call ( "tasks_return_pre", $parameters, false, $parameters);
  }

  /**
   * If result is 2XX (OK), remove event (if ID = 0, don't remove, it's a pingback)
   */
  if ( $parameters["Result"] >= 200 && $parameters["Result"] <= 299 && $parameters["ID"] != 0)
  {
    /**
     * Check if it's a grouped command
     */
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Group` FROM `GroupCommand` WHERE `Command` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `GroupedCommands` SET `Left` = `Left` - 1 WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $result->fetch_assoc ()["Group"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
    }
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Commands` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " AND `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $_in["server"]["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Check for unprocessed events in queue and resend
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Commands`.*, `Servers`.`Password` FROM `Commands`, `Servers` WHERE `Servers`.`ID` = `Commands`.`Server` AND `Commands`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( $_in["server"]["ID"]) . ( $parameters["ID"] != 0 ? " AND `Commands`.`ID` < " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) : "")))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    while ( $data = $result->fetch_assoc ())
    {
      $ch = curl_init ();
      curl_setopt_array ( $ch, array (
                                 CURLOPT_URL => $_in["general"]["pushpub"] . "?id=" . urlencode ( $data["Server"]),
                                 CURLOPT_RETURNTRANSFER => true,
                                 CURLOPT_POST => true,
                                 CURLOPT_POSTFIELDS => serialize ( array ( "event" => @openssl_encrypt ( serialize ( array ( "event" => $data["Event"], "id" => $data["ID"], "data" => unserialize ( $data["Data"]))), "AES-256-CBC", $data["Password"], OPENSSL_RAW_DATA)))
                        ));
      @curl_exec ( $ch);
      curl_close ( $ch);
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tasks_return_post"))
  {
    $data = framework_call ( "tasks_return_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tasks_return_finish"))
  {
    framework_call ( "tasks_return_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "Result" => true));
}

/**
 * Add framework session control login endpoind.
 */
framework_add_hook (
  "user_login",
  "user_login",
  IN_HOOK_INSERT_FIRST,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Username" => array (
          "type" => "string",
          "description" => __ ( "The username of user to login."),
          "required" => true,
          "example" => __ ( "johndoe")
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "The password of user to login."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Code" => array (
          "type" => "string",
          "description" => __ ( "The second factor authentication code of user to login."),
          "required" => false,
          "pattern" => "/^[0-9]{6}$/",
          "example" => "231951"
        ),
        "Remember" => array (
          "type" => "boolean",
          "description" => __ ( "If using second factor authentication, remember the terminal and don't ask it again."),
          "required" => false,
          "example" => true
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system user session created sucessfully."),
        "headers" => array (
          "Set-Cookie" => array (
            "schema" => array (
              "type" => "string",
              "description" => __ ( "System access cookie token."),
              "example" => "vd=80a2a3c21a167ca3cd1781c49875f0ac16d669afd11181c92589e0d73c482b00; path=/"
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Result" => array (
              "type" => "boolean",
              "description" => __ ( "The status of session creation request."),
              "example" => false
            ),
            "Message" => array (
              "type" => "string",
              "description" => __ ( "The login error message, if failed."),
              "example" => __ ( "Invalid username and/or password.")
            )
          )
        )
      ),
      423 => array (
        "description" => __ ( "User requires second factor authentication to login."),
      ),
      401 => array ()
    )
  )
);
framework_add_api_call (
  "/session",
  "Create",
  "user_login",
  array (
    "unauthenticated" => true,
    "title" => __ ( "System user authentication."),
    "description" => __ ( "Create a new system user session.")
  )
);

/**
 * Function to authenticate a user and create a new session. The session are
 * controlled with PHP session functions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of execution
 */
function user_login ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "user_login_start"))
  {
    $parameters = framework_call ( "user_login_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( empty ( $parameters["Username"]))
  {
    $data["Username"] = __ ( "The username is required.");
  }
  if ( empty ( $parameters["Password"]))
  {
    $data["Password"] = __ ( "The password is required.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "user_login_validate"))
  {
    $data = framework_call ( "user_login_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    $data["Result"] = false;
    if ( ! array_key_exists ( "Message", $data))
    {
      $data["Message"] = __ ( "Error authenticating user.");
    }
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["Username"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Username"])));

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "user_login_sanitize"))
  {
    $parameters = framework_call ( "user_login_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "user_login_pre"))
  {
    $parameters = framework_call ( "user_login_pre", $parameters, false, $parameters);
  }

  /**
   * Validate user into database
   */
  if ( ! $result = $_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    /**
     * Call authentication failure plugin modules if exists
     */
    filters_call ( "authentication_failure");

    /**
     * And return error message
     */
    $data["Result"] = false;
    $data["Message"] = __ ( "Invalid username and/or password.");
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Check if password match
   */
  $userdata = $result->fetch_assoc ();
  if ( $userdata["Password"] != hash_pbkdf2 ( "sha256", $parameters["Password"], $userdata["Salt"], $userdata["Iterations"], 64))
  {
    /**
     * Call authentication failure plugin modules if exists
     */
    filters_call ( "authentication_failure");

    /**
     * And return error message
     */
    $data["Result"] = false;
    $data["Message"] = __ ( "Invalid username and/or password.");
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Check if user has second factor authentication
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `UserSFA` WHERE `UID` = '" . $_in["mysql"]["id"]->real_escape_string ( $userdata["ID"]) . "' AND `Status` = 'Active'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 1)
  {
    $sfadata = $result->fetch_assoc ();
    if ( array_key_exists ( $_in["general"]["cookie"] . "_sfa", $_COOKIE))
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `SFACache` WHERE `UID` = '" . $_in["mysql"]["id"]->real_escape_string ( $userdata["ID"]) . "' AND `Key` = '" . $_in["mysql"]["id"]->real_escape_string ( $_COOKIE[$_in["general"]["cookie"] . "_sfa"]) . "'"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 1)
      {
        $ignoresfa = true;
      } else {
        setcookie ( $_in["general"]["cookie"] . "_sfa", null, -1, "/");
      }
    }
    if ( ! $ignoresfa)
    {
      if ( $parameters["Code"])
      {
        if ( ! rfc6238_validate ( $sfadata["Key"], $parameters["Code"], $_in["security"]["totprange"]))
        {
          $data["Message"] = __ ( "Invalid second factor authentication code.");
          $data["Result"] = false;
          header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
          return $data;
        }
      } else {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 423 Locked");
        return array ();
      }
    }
  }

  /**
   * Add SFA cache if requested
   */
  if ( $parameters["Remember"])
  {
    $key = random_password ( 32);
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `SFACache` (`UID`, `Key`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $userdata["ID"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $key) . "')"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    setcookie ( $_in["general"]["cookie"] . "_sfa", $key, time () + 31536000, "/" . ( PHP_VERSION_ID < 70300 ? "; SameSite=Strict" : ""));
  }

  /**
   * Create global configuration session information
   */
  $_in["session"] = $userdata;
  $_in["session"]["SID"] = hash ( "sha256", uniqid ( "", true));
  $_in["session"]["LastSeen"] = time ();
  $_in["session"]["Permissions"] = json_decode ( $_in["session"]["Permissions"], true);

  /**
   * Create session at database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Sessions` (`SID`, `User`, `LastSeen`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $_in["session"]["SID"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $_in["session"]["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( time ()) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "user_login_post"))
  {
    $data = framework_call ( "user_login_post", $parameters, false, $data);
  }

  /**
   * Insert audit entry
   */
  audit ( "system", "login", array ( "ID" => $_in["session"]["ID"], "User" => $_in["session"]["Username"], "IP" => $_SERVER["REMOTE_ADDR"]));

  /**
   * Start user session.
   */
  setcookie ( $_in["general"]["cookie"], $_in["session"]["SID"], 0, "/" . ( PHP_VERSION_ID < 70300 ? "; SameSite=Strict" : ""));

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "user_login_finish"))
  {
    framework_call ( "user_login_finish", $parameters);
  }

  /**
   * Return OK
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/users/" . $_in["session"]["ID"]);
  return $buffer;
}

/**
 * Add framework session control logout endpoind.
 */

framework_add_hook (
  "user_logout",
  "user_logout",
  IN_HOOK_INSERT_FIRST,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "User session was successfully destroyed."),
        "headers" => array (
          "Set-Cookie" => array (
            "schema" => array (
              "type" => "string",
              "description" => __ ( "Empty system access cookie token."),
              "example" => "vd=; path=/"
            )
          )
        )
      ),
      401 => array (),
      422 => array ()
    )
  )
);
framework_add_api_call (
  "/session",
  "Delete",
  "user_logout",
  array (
    "permissions" => array ( "user"),
    "title" => __ ( "Destroy system user session."),
    "description" => __ ( "Destroy the system user session.")
  )
);

/**
 * Function to remove user session. Basically, destroy PHP session control.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of execution
 */
function user_logout ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "user_logout_start"))
  {
    $parameters = framework_call ( "user_logout_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Check if code was already added
   */
  if ( ! array_key_exists ( "Code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Code"] = __ ( "The provided code was already in use.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "user_logout_validate"))
  {
    $data = framework_call ( "user_logout_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "user_logout_sanitize"))
  {
    $parameters = framework_call ( "user_logout_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "user_logout_pre"))
  {
    $parameters = framework_call ( "user_logout_pre", $parameters, false, $parameters);
  }

  /**
   * Insert audit entry
   */
  audit ( "system", "logout", array ( "ID" => $_in["session"]["ID"], "User" => $_in["session"]["ID"], "Reason" => "Logged off"));

  /**
   * Remove system cookie and destroy global configuration session information
   */
  setcookie ( $_in["general"]["cookie"], null, -1, "/" . ( PHP_VERSION_ID < 70300 ? "; SameSite=Strict" : ""));
  $_in["session"] = array ();

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "user_logout_post"))
  {
    framework_call ( "user_logout_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "user_logout_finish"))
  {
    framework_call ( "user_logout_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * Add framework dashboard information hook.
 */
framework_add_hook (
  "dashboard_information",
  "dashboard_information",
  IN_HOOK_INSERT_FIRST,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date of report start. If empty, 1 month early will be set."),
          "required" => false,
          "example" => "2020-05-01"
        ),
        "End" => array (
          "type" => "date-time",
          "description" => __ ( "The date of report end. If empty, will be set today."),
          "required" => false,
          "example" => "2020-05-01"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system dashboard data."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ASR" => array (
              "type" => "object",
              "description" => __ ( "Answer-Seizure Ratio (ASR)."),
              "properties" => array (
                "Percent" => array (
                  "type" => "double",
                  "description" => __ ( "ASR percentage."),
                  "example" => 98.6
                ),
                "Value" => array (
                  "type" => "integer",
                  "description" => __ ( "ASR read value."),
                  "example" => 986
                ),
                "Total" => array (
                  "type" => "integer",
                  "description" => __ ( "ASR total value."),
                  "example" => 1000
                )
              )
            ),
            "NER" => array (
              "type" => "object",
              "description" => __ ( "Network Efficiency Ratio (NER)."),
              "properties" => array (
                "Percent" => array (
                  "type" => "double",
                  "description" => __ ( "NER percentage."),
                  "example" => 100
                ),
                "Value" => array (
                  "type" => "integer",
                  "description" => __ ( "NER read value."),
                  "example" => 1000
                ),
                "Total" => array (
                  "type" => "integer",
                  "description" => __ ( "NER total value."),
                  "example" => 1000
                )
              )
            ),
            "SBR" => array (
              "type" => "object",
              "description" => __ ( "Subscriber Busy Ratio (SBR)."),
              "properties" => array (
                "Percent" => array (
                  "type" => "double",
                  "description" => __ ( "SBR percentage."),
                  "example" => 15.2
                ),
                "Value" => array (
                  "type" => "integer",
                  "description" => __ ( "SBR read value."),
                  "example" => 152
                ),
                "Total" => array (
                  "type" => "integer",
                  "description" => __ ( "SBR total value."),
                  "example" => 1000
                )
              )
            ),
            "SCR" => array (
              "type" => "object",
              "description" => __ ( "Short Calls Ratio (SCR)."),
              "properties" => array (
                "Percent" => array (
                  "type" => "double",
                  "description" => __ ( "SCR percentage."),
                  "example" => 87.3
                ),
                "Value" => array (
                  "type" => "integer",
                  "description" => __ ( "SCR read value."),
                  "example" => 873
                ),
                "Total" => array (
                  "type" => "integer",
                  "description" => __ ( "SCR total value."),
                  "example" => 1000
                )
              )
            ),
            "LCR" => array (
              "type" => "object",
              "description" => __ ( "Long Calls Ratio (LCR)."),
              "properties" => array (
                "Percent" => array (
                  "type" => "double",
                  "description" => __ ( "LCR percentage."),
                  "example" => 12.7
                ),
                "Value" => array (
                  "type" => "integer",
                  "description" => __ ( "LCR read value."),
                  "example" => 127
                ),
                "Total" => array (
                  "type" => "integer",
                  "description" => __ ( "LCR total value."),
                  "example" => 1000
                )
              )
            ),
            "Allocation" => array (
              "type" => "object",
              "description" => __ ( "Allocation Ratio."),
              "properties" => array (
                "Percent" => array (
                  "type" => "double",
                  "description" => __ ( "Allocation percentage."),
                  "example" => 50.5
                ),
                "Value" => array (
                  "type" => "integer",
                  "description" => __ ( "Allocation read value."),
                  "example" => 505
                ),
                "Total" => array (
                  "type" => "integer",
                  "description" => __ ( "Allocation total value."),
                  "example" => 1000
                )
              )
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An invalid parameter or missing required parameter was found."),
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
framework_add_api_call (
  "/dashboard",
  "Read",
  "dashboard_information",
  array (
    "permissions" => array ( "user"),
    "title" => __ ( "System dashboard"),
    "description" => __ ( "Generate the system dashboard statistics.")
  )
);

/**
 * Function to generate dashboard information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $page Buffer from plugin system if processed by other function
 *                     before
 * @param array $parameters Framework page structure
 * @return array Framework page structure with generated content
 */
function dashboard_information ( $page, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "dashboard_information_start"))
  {
    $parameters = framework_call ( "dashboard_information_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $start = format_form_date ( $parameters["Start"]);
  if ( empty ( $start))
  {
    $data["Start"] = __ ( "Invalid start date.");
  }
  $end = format_form_date ( $parameters["End"]);
  if ( empty ( $end))
  {
    $data["End"] = __ ( "Invalid end date.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "dashboard_information_validate"))
  {
    $data = framework_call ( "dashboard_information_validate", $parameters);
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
   * Sanityze parameters
   */
  $parameters["Start"] = ( ! empty ( $parameters["Start"]) ? format_form_date ( $parameters["Start"]) : date ( "%Y-%M-%D", strtotime ( "29 days ago")));
  $parameters["End"] = ( ! empty ( $parameters["End"]) ? format_form_date ( $parameters["End"]) : date ( "%Y-%M-%D", time ()));

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "dashboard_information_sanitize"))
  {
    $parameters = framework_call ( "dashboard_information_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "dashboard_information_pre"))
  {
    $parameters = framework_call ( "dashboard_information_pre", $parameters, false, $parameters);
  }

  /**
   * Get system usage information
   */
  $data = array ();

  /**
   * Get ASR (Answer-Seizure Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'ANSWERED' AND `calldate` >= '" . date ( "Y-m-d", $parameters["Start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["End"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $asr = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `calldate` >= '" . date ( "Y-m-d", $parameters["Start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["End"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $total = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["ASR"] = array ( "Percent" => ( $total != 0 ? round (( $asr * 100) / $total) : 0), "Value" => $asr, "Total" => $total);

  /**
   * Get NER (Network Efficiency Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE ( `disposition` = 'ANSWERED' OR `disposition` = 'NO ANSWER' OR `disposition` = 'BUSY') AND `calldate` >= '" . date ( "Y-m-d", $parameters["Start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["End"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $ner = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["NER"] = array ( "Percent" => ( $total != 0 ? round (( $ner * 100) / $total) : 0), "Value" => $ner, "Total" => $total);

  /**
   * Get SBR (Subscriber Busy Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'BUSY' AND `calldate` >= '" . date ( "Y-m-d", $parameters["Start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["End"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $sbr = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE ( `disposition` = 'ANSWERED' OR `disposition` = 'BUSY') AND `calldate` >= '" . date ( "Y-m-d", $parameters["Start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["End"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $total = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["SBR"] = array ( "Percent" => ( $total != 0 ? round (( $sbr * 100) / $total) : 0), "Value" => $sbr, "Total" => $total);

  /**
   * Get SCR (Short Calls Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'ANSWERED' AND `billsec` <= 60 AND `calldate` >= '" . date ( "Y-m-d", $parameters["Start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["End"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $scr = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'ANSWERED' AND `calldate` >= '" . date ( "Y-m-d", $parameters["Start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["End"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $total = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["SCR"] = array ( "Percent" => ( $total != 0 ? round (( $scr * 100) / $total) : 0), "Value" => $scr, "Total" => $total);

  /**
   * Get LCR (Long Calls Ratio)
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `cdr` WHERE `disposition` = 'ANSWERED' AND `billsec` >= 300 AND `calldate` >= '" . date ( "Y-m-d", $parameters["Start"]) . " 00:00:00' AND `calldate` <= '" . date ( "Y-m-d", $parameters["End"]) . " 23:59:59'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $lcr = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  $data["LCR"] = array ( "Percent" => ( $total != 0 ? round (( $lcr * 100) / $total) : 0), "Value" => $lcr, "Total" => $total);

  /**
   * Get allocations percentage
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `Extensions`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $total = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $ranges = 0;
  while ( $range = $result->fetch_assoc ())
  {
    $ranges += $range["Finish"] - $range["Start"];
  }
  $result->free ();
  $data["Allocation"] = array ( "Percent" => round (( $total * 100) / $ranges), "Value" => $total, "Total" => $ranges);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "dashboard_information_post"))
  {
    $data = framework_call ( "dashboard_information_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "dashboard_information_finish"))
  {
    framework_call ( "dashboard_information_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get call information
 */
framework_add_hook (
  "calls_view",
  "calls_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the call."),
        "schema" => array (
          "\$ref" => "#/components/schemas/call"
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
              "example" => __ ( "Invalid call ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "calls_view", __ ( "View call information"));
framework_add_api_call (
  "/calls/:ID",
  "Read",
  "calls_view",
  array (
    "permissions" => array ( "user", "calls_view"),
    "title" => __ ( "View call information"),
    "description" => __ ( "Get detailed information about a call."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The call unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate call information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function calls_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "calls_view_start"))
  {
    $parameters = framework_call ( "calls_view_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "calls_view_validate"))
  {
    $data = framework_call ( "calls_view_validate", $parameters);
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
  $parameters["ID"] = preg_replace ( "/[^0-9-\.]/", "", $parameters["ID"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "calls_view_sanitize"))
  {
    $parameters = framework_call ( "calls_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "calls_view_pre"))
  {
    $parameters = framework_call ( "calls_view_pre", $parameters, false, $parameters);
  }

  /**
   * Check if call exist into database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `uniqueid` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $call = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = filters_call ( "process_call", $call);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "calls_view_post"))
  {
    $data = framework_call ( "calls_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "calls_view_finish"))
  {
    framework_call ( "calls_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to process call SIP dump
 */
framework_add_hook (
  "calls_sipdump_view",
  "calls_sipdump_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the call SIP dump."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Result" => array (
              "type" => "boolean",
              "description" => __ ( "If the SIP dump of the call is available."),
              "example" => true
            ),
            "SIPDump" => array (
              "type" => "object",
              "description" => __ ( "The SIP dump of the call."),
              "properties" => array (
                "Diagram" => array (
                  "type" => "string",
                  "description" => __ ( "The call flow diagram with brief description.")
                ),
                "Text" => array (
                  "type" => "string",
                  "description" => __ ( "The call flow packets into text format.")
                ),
                "PCAP" => array (
                  "type" => "string",
                  "format" => "byte",
                  "description" => __ ( "The call SIP dump PCAP file encoded with base64.")
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
              "example" => __ ( "Invalid call ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "calls_sipdump_view", __ ( "View call SIP dump information"));
framework_add_api_call (
  "/calls/:ID/sipdump",
  "Read",
  "calls_sipdump_view",
  array (
    "permissions" => array ( "user", "calls_sipdump_view"),
    "title" => __ ( "View call SIP dump information"),
    "description" => __ ( "Get detailed SIP dump information about a call."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The call unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate call SIP dump information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function calls_sipdump_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "calls_sipdump_view_start"))
  {
    $parameters = framework_call ( "calls_sipdump_view_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "calls_sipdump_view_validate"))
  {
    $data = framework_call ( "calls_sipdump_view_validate", $parameters);
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
  $parameters["ID"] = preg_replace ( "/[^0-9-\.]/", "", $parameters["ID"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "calls_sipdump_view_sanitize"))
  {
    $parameters = framework_call ( "calls_sipdump_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "calls_sipdump_view_pre"))
  {
    $parameters = framework_call ( "calls_sipdump_view_pre", $parameters, false, $parameters);
  }

  /**
   * Check if call exist into database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `uniqueid` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $call = $result->fetch_assoc ();

  /**
   * Check if there's call capture file (if has, process it)
   */
  $data = array ();
  if ( is_readable ( $_in["general"]["storagedir"] . "/captures/" . basename ( $call["SIPID"]) . ".pcap"))
  {
    $data["Result"] = true;
    $sipdump = filters_call ( "process_sipdump", array ( "Filename" => $_in["general"]["storagedir"] . "/captures/" . basename ( $call["SIPID"]) . ".pcap"));
    $data["SIPDump"] = array ( "Diagram" => $sipdump["diagram"], "Text" => $sipdump["text"], "PCAP" => $sipdump["pcap"]);
  } else {
    $data["Result"] = false;
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "calls_sipdump_view_post"))
  {
    $data = framework_call ( "calls_sipdump_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "calls_sipdump_view_finish"))
  {
    framework_call ( "calls_sipdump_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to request call recording
 */
framework_add_hook (
  "calls_recording_view",
  "calls_recording_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the call recording."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Result" => array (
              "type" => "boolean",
              "description" => __ ( "If the recording of the call is available."),
              "example" => true
            ),
            "Audio" => array (
              "type" => "string",
              "format" => "byte",
              "description" => __ ( "The call recording MP3 file encoded with base64.")
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
              "example" => __ ( "Invalid call ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "calls_recording_view", __ ( "View call recording information"));
framework_add_api_call (
  "/calls/:ID/recording",
  "Read",
  "calls_recording_view",
  array (
    "permissions" => array ( "user", "calls_recording_view"),
    "title" => __ ( "View call recording information"),
    "description" => __ ( "Get recording information about a call."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The call unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate call recording information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function calls_recording_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "calls_recording_view_start"))
  {
    $parameters = framework_call ( "calls_recording_view_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "calls_recording_view_validate"))
  {
    $data = framework_call ( "calls_recording_view_validate", $parameters);
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
  $parameters["ID"] = preg_replace ( "/[^0-9-\.]/", "", $parameters["ID"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "calls_recording_view_sanitize"))
  {
    $parameters = framework_call ( "calls_recording_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "calls_recording_view_pre"))
  {
    $parameters = framework_call ( "calls_recording_view_pre", $parameters, false, $parameters);
  }

  /**
   * Check if call exist into database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `uniqueid` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $call = $result->fetch_assoc ();

  /**
   * Check if there's call recording file (if has, process it)
   */
  $data = array ();
  if ( is_readable ( $_in["general"]["storagedir"] . "/recordings/" . basename ( $call["SIPID"]) . ".mp3"))
  {
    $data["Result"] = true;
    $data["Audio"] = base64_encode ( file_get_contents ( $_in["general"]["storagedir"] . "/recordings/" . basename ( $call["SIPID"]) . ".mp3"));
  } else {
    $data["Result"] = false;
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "calls_recording_view_post"))
  {
    $data = framework_call ( "calls_recording_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "calls_recording_view_finish"))
  {
    framework_call ( "calls_recording_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fast search
 */
framework_add_hook (
  "fastsearch",
  "fastsearch",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Number,Type,Description",
          "example" => "Number,Description"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system objects."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the object."),
                "example" => 1
              ),
              "Number" => array (
                "type" => "integer",
                "description" => __ ( "The extension number associated to object."),
                "example" => "1000"
              ),
              "Type" => array (
                "type" => "string",
                "description" => __ ( "The type of the object."),
                "enum" => array ()
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the object."),
                "example" => __ ( "John Doe")
              )
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "fastsearch", __ ( "Fast search"));
framework_add_api_call (
  "/fastsearch/:Filter",
  "Read",
  "fastsearch",
  array (
    "permissions" => array ( "user", "fastsearch"),
    "title" => __ ( "Interface objects search."),
    "description" => __ ( "Search for all system objects (that's available at fastsearch)."),
    "parameters" => array (
      array (
        "name" => "Filter",
        "type" => "string",
        "description" => __ ( "Filter search with this string. If not provided, return all objects."),
        "example" => __ ( "John Doe")
      )
    )
  )
);

/**
 * Function to fast search.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function fastsearch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "fastsearch_start"))
  {
    $parameters = framework_call ( "fastsearch_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "fastsearch_validate"))
  {
    $data = framework_call ( "fastsearch_validate", $parameters);
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
  $parameters["Filter"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Filter"])));

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "fastsearch_sanitize"))
  {
    $parameters = framework_call ( "fastsearch_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "fastsearch_pre"))
  {
    $parameters = framework_call ( "fastsearch_pre", $parameters, false, $parameters);
  }

  /**
   * Create result structure
   */
  $search = framework_call ( "fastsearch_objects", $parameters, false, array ());
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Number,Type,Description", "ID,Number,Type,Description");
  $data = array ();
  foreach ( $search as $field)
  {
    $data[] = api_filter_entry ( $fields, $field);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "fastsearch_post"))
  {
    $data = framework_call ( "fastsearch_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "fastsearch_finish"))
  {
    framework_call ( "fastsearch_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
