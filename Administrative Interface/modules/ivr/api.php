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
 * VoIP Domain IVRs module API. This module add the API calls related to IVRs.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage IVRs
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Gateway component documentation
 */
framework_add_component_documentation (
  "ivr",
  array (
    "type" => "object",
    "xml" => array (
      "name" => "ivr"
    ), 
    "properties" => array (
      "Operators" => array (
        "type" => "array",
        "required" => true,
        "xml" => array (
          "name" => "Operators",
          "wrapped" => true
        ),
        "items" => array (
          "type" => "object",
          "xml" => array (
            "name" => "Operator"
          ),
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "required" => true,
              "pattern" => "/^(start|op_\d)$/",
              "description" => __ ( "The IVR operator ID."),
              "example" => "op_1"
            ),
            "Operator" => array (
              "type" => "string",
              "required" => true,
              "enum" => array (),
              "description" => __ ( "The IVR module operator type."),
              "example" => "start"
            ),
            "Left" => array (
              "type" => "float",
              "required" => false,
              "description" => __ ( "The IVR operator left distance on the workflow."),
              "example" => 346.85714285714283
            ),
            "Top" => array (
              "type" => "float",
              "required" => false,
              "description" => __ ( "The IVR operator top distance on the workflow."),
              "example" => 53.161917550223215
            ),
            "Properties" => array (
              "oneOf" => array (),
              "discriminator" => array (
                "propertyName" => "Output"
              ),
              "required" => false,
              "description" => __ ( "Any property related to the IVR module operator type.")
            ),
            "Outputs" => array (
              "type" => "array",
              "required" => false,
              "xml" => array (
                "name" => "Outputs",
                "wrapped" => true
              ),
              "items" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Output"
                ),
                "properties" => array (
                  "Name" => array (
                    "type" => "string",
                    "required" => true,
                    "description" => __ ( "The output name of IVR operator."),
                    "example" => "output"
                  ),
                  "Label" => array (
                    "type" => "string",
                    "required" => true,
                    "description" => __ ( "The output label of IVR operator."),
                    "example" => __ ( "Output")
                  ),
                  "LabelArgs" => array (
                    "type" => "object",
                    "required" => false,
                    "description" => __ ( "The output label parameters (if used) of IVR operator."),
                    "additionalProperties" => array (
                      "type" => "string"
                    )
                  )
                )
              )
            )
          )
        )
      ),
      "Links" => array (
        "type" => "array",
        "required" => true,
        "xml" => array (
          "name" => "Links",
          "wrapped" => true
        ),
        "items" => array (
          "type" => "object",
          "xml" => array (
            "name" => "Link"
          ),
          "properties" => array (
            "FromOperator" => array (
              "type" => "string",
              "required" => true,
              "pattern" => "/^(start|op_\d)$/",
              "description" => __ ( "The IVR operator ID link origin."),
              "example" => "op_1"
            ),
            "FromConnector" => array (
              "type" => "string",
              "required" => true,
              "description" => __ ( "The IVR operator connector link origin."),
              "example" => "output"
            ),
            "ToOperator" => array (
              "type" => "string",
              "required" => true,
              "description" => __ ( "The IVR operator ID link target."),
              "example" => "op_2"
            )
          )
        )
      )
    )
  )
);

/**
 * Register IVR operators hooks
 */
framework_add_hook ( "ivr_operator_filter_start", "ivr_operator_filter_start");
framework_add_hook ( "ivr_operator_filter_answer", "ivr_operator_filter_answer");
framework_add_hook ( "ivr_operator_filter_wait", "ivr_operator_filter_wait");
framework_add_hook ( "ivr_operator_filter_hangup", "ivr_operator_filter_hangup");
framework_add_hook ( "ivr_operator_filter_time", "ivr_operator_filter_time");
framework_add_hook ( "ivr_operator_filter_date", "ivr_operator_filter_date");
framework_add_hook ( "ivr_operator_filter_weekday", "ivr_operator_filter_weekday");
framework_add_hook ( "ivr_operator_filter_play", "ivr_operator_filter_play");
framework_add_hook ( "ivr_operator_filter_record", "ivr_operator_filter_record");
framework_add_hook ( "ivr_operator_filter_stop", "ivr_operator_filter_stop");
framework_add_hook ( "ivr_operator_filter_read", "ivr_operator_filter_read");
framework_add_hook ( "ivr_operator_filter_menu", "ivr_operator_filter_menu");
framework_add_hook ( "ivr_operator_filter_router", "ivr_operator_filter_router");
framework_add_hook ( "ivr_operator_filter_setvar", "ivr_operator_filter_setvar");
framework_add_hook ( "ivr_operator_filter_dial", "ivr_operator_filter_dial");
framework_add_hook ( "ivr_operator_filter_script", "ivr_operator_filter_script");
framework_add_hook ( "ivr_operator_filter_email", "ivr_operator_filter_email");

/**
 * IVR start operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "start")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"start\"."),
                  "properties" => array (
                    "AutoAnswer" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the call should be answered at the IVR start."),
                      "example" => true
                    ),
                    "Delay" => array (
                      "type" => "integer",
                      "required" => false,
                      "description" => __ ( "The number of seconds to wait before answer (if AutoAnswer is true)."),
                      "example" => 3
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter start IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_start ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "AutoAnswer" => (boolean) $parameters["Properties"]["AutoAnswer"], "Delay" => (int) $parameters["Properties"]["Delay"]), "Outputs" => array ( "output"));
  if ( ! array_key_exists ( "AutoAnswer", $parameters["Properties"]))
  {
    $data["Errors"]["AutoAnswer"] = __ ( "AutoAnswer field is required.");
  }
  if ( ! array_key_exists ( "AutoAnswer", $data["Errors"]) && $parameters["Properties"]["AutoAnswer"] != true && $parameters["Properties"]["AutoAnswer"] != false)
  {
    $data["Errors"]["AutoAnswer"] = __ ( "AutoAnswer must be true or false.");
  }
  if ( $parameters["Properties"]["AutoAnswer"] && ! array_key_exists ( "Delay", $parameters["Properties"]))
  {
    $data["Errors"]["Delay"] = __ ( "Delay is required when Auto Start is enabled.");
  }
  if ( $parameters["Properties"]["AutoAnswer"] && ! array_key_exists ( "Delay", $data["Errors"]) && $parameters["Properties"]["Delay"] != (int) $parameters["Properties"]["Delay"])
  {
    $data["Errors"]["Delay"] = __ ( "Delay must be a number.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output.");
  } else {
    if ( $parameters["Outputs"][0]["Name"] != "output")
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"output\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR answer operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "answer")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"answer\"."),
                  "properties" => array ()
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter answer IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_answer ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array (), "Outputs" => array ( "output"));
  if ( sizeof ( $parameters["Outputs"]) != 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output.");
  } else {
    if ( $parameters["Outputs"][0]["Name"] != "output")
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"output\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR wait operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "wait")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"wait\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Wait 4 seconds.")
                    ),
                    "Time" => array (
                      "type" => "integer",
                      "required" => true,
                      "description" => __ ( "The number of seconds to wait."),
                      "example" => 4
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter wait IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_wait ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Time" => (int) $parameters["Properties"]["Time"]), "Outputs" => array ( "output"));
  if ( ! array_key_exists ( "Time", $parameters["Properties"]))
  {
    $data["Errors"]["Time"] = __ ( "Time field is required.");
  }
  if ( ! array_key_exists ( "Time", $data["Errors"]) && $parameters["Properties"]["Time"] != (int) $parameters["Properties"]["Time"])
  {
    $data["Errors"]["Time"] = __ ( "Time must be a number.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output.");
  } else {
    if ( $parameters["Outputs"][0]["Name"] != "output")
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"output\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR hangup operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "hangup")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"hangup\"."),
                  "properties" => array ()
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter hangup IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_hangup ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array (), "Outputs" => array ());
  if ( sizeof ( $parameters["Outputs"]) != 0)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator does not must have an output.");
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR time operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "time")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"time\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Check for working hours.")
                    ),
                    "Conditions" => array (
                      "type" => "array",
                      "required" => true,
                      "description" => __ ( "The array containing object of start and finish time conditions."),
                      "xml" => array (
                        "name" => "Conditions",
                        "wrapped" => true
                      ),
                      "items" => array (
                        "type" => "object",
                        "xml" => array (
                          "name" => "Condition"
                        ),
                        "properties" => array (
                          "Start" => array (
                            "type" => "string",
                            "required" => true,
                            "description" => __ ( "The start time of the condition."),
                            "pattern" => "/^([01][0-9]|2[0-4]):[0-5][0-9]$/",
                            "example" => "08:00"
                          ),
                          "Finish" => array (
                            "type" => "string",
                            "required" => true,
                            "description" => __ ( "The finish time of the condition."),
                            "pattern" => "/^([01][0-9]|2[0-4]):[0-5][0-9]$/",
                            "example" => "17:59"
                          )
                        )
                      ),
                      "example" => array (
                        array (
                          "Start" => "00:00",
                          "Finish" => "07:59"
                        ),
                        array (
                          "Start" => "08:00",
                          "Finish" => "17:59"
                        ),
                        array (
                          "Start" => "18:00",
                          "Finish" => "23:59"
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter time IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_time ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Conditions" => array ()), "Outputs" => array ( "other"));
  if ( ! array_key_exists ( "Conditions", $parameters["Properties"]))
  {
    $data["Errors"]["Conditions"] = __ ( "At least one condition is required.");
  }
  if ( ! array_key_exists ( "Conditions", $data["Errors"]) && ! is_array ( $parameters["Properties"]["Conditions"]))
  {
    $data["Errors"]["Conditions"] = __ ( "Conditions must be an array.");
  }
  if ( ! array_key_exists ( "Conditions", $data["Errors"]))
  {
    foreach ( $parameters["Properties"]["Conditions"] as $index => $condition)
    {
      if ( ! array_key_exists ( "Start", $condition))
      {
        $data["Errors"]["Start_" . $index] = sprintf ( __ ( "Condition %d Start is required."), $index);
      } else {
        if ( ! preg_match ( "/^([01][0-9]|2[0-4]):[0-5][0-9]$/", $condition["Start"]))
        {
          $data["Errors"]["Start_" . $index] = sprintf ( __ ( "Condition %d Start value is invalid."), $index);
          $condition["Start"] = "";
        }
      }
      if ( ! array_key_exists ( "Finish", $condition))
      {
        $data["Errors"]["Finish_" . $index] = sprintf ( __ ( "Condition %d Finish is required."), $index);
      } else {
        if ( ! preg_match ( "/^([01][0-9]|2[0-4]):[0-5][0-9]$/", $condition["Finish"]))
        {
          $data["Errors"]["Finish_" . $index] = sprintf ( __ ( "Condition %d Finish value is invalid."), $index);
          $condition["Finish"] = "";
        }
      }
      if ( ! empty ( $condition["Start"]) && ! empty ( $condition["Finish"]))
      {
        $data["Properties"]["Conditions"][] = array ( "Start" => $condition["Start"], "Finish" => $condition["Finish"]);
      }
    }
  }
  if ( sizeof ( $parameters["Outputs"]) != sizeof ( $parameters["Properties"]["Conditions"]) + 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output for each condition plus the other output.");
  } else {
    $other = false;
    foreach ( $parameters["Outputs"] as $output)
    {
      if ( $output["Name"] == "other")
      {
        $other = true;
      } else {
        $data["Outputs"][] = $output["Name"];
      }
    }
    if ( ! $other)
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"other\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR date operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "date")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"date\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Check for holiday dates.")
                    ),
                    "Conditions" => array (
                      "type" => "array",
                      "required" => true,
                      "description" => __ ( "The array containing object of start and finish date conditions."),
                      "xml" => array (
                        "name" => "Conditions",
                        "wrapped" => true
                      ),
                      "items" => array (
                        "type" => "object",
                        "xml" => array (
                          "name" => "Condition"
                        ),
                        "properties" => array (
                          "Start" => array (
                            "type" => "string",
                            "required" => true,
                            "description" => __ ( "The start date of the condition. Format YYYY-MM-DD."),
                            "pattern" => "/^[1-2][0-9]{3}-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])$/",
                            "example" => "2023-05-11"
                          ),
                          "Finish" => array (
                            "type" => "string",
                            "required" => true,
                            "description" => __ ( "The finish date of the condition. Format YYYY-MM-DD."),
                            "pattern" => "/^[1-2][0-9]{3}-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])$/",
                            "example" => "2023-05-11"
                          )
                        )
                      ),
                      "example" => array (
                        array (
                          "Start" => "2023-05-11",
                          "Finish" => "2023-05-11"
                        ),
                        array (
                          "Start" => "2023-06-12",
                          "Finish" => "2023-06-22"
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter date IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_date ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Conditions" => array ()), "Outputs" => array ( "other"));
  if ( ! array_key_exists ( "Conditions", $parameters["Properties"]))
  {
    $data["Errors"]["Conditions"] = __ ( "At least one condition is required.");
  }
  if ( ! array_key_exists ( "Conditions", $data["Errors"]) && ! is_array ( $parameters["Properties"]["Conditions"]))
  {
    $data["Errors"]["Conditions"] = __ ( "Conditions must be an array.");
  }
  if ( ! array_key_exists ( "Conditions", $data["Errors"]))
  {
    foreach ( $parameters["Properties"]["Conditions"] as $index => $condition)
    {
      if ( ! array_key_exists ( "Start", $condition))
      {
        $data["Errors"]["Start_" . $index] = sprintf ( __ ( "Condition %d Start is required."), $index);
      } else {
        if ( ! preg_match ( "/^[1-2][0-9]{3}-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])$/", $condition["Start"]))
        {
          $data["Errors"]["Start_" . $index] = sprintf ( __ ( "Condition %d Start value is invalid."), $index);
          $condition["Start"] = "";
        }
      }
      if ( ! array_key_exists ( "Finish", $condition))
      {
        $data["Errors"]["Finish_" . $index] = sprintf ( __ ( "Condition %d Finish is required."), $index);
      } else {
        if ( ! preg_match ( "/^[1-2][0-9]{3}-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])$/", $condition["Finish"]))
        {
          $data["Errors"]["Finish_" . $index] = sprintf ( __ ( "Condition %d Finish value is invalid."), $index);
          $condition["Finish"] = "";
        }
      }
      if ( ! empty ( $condition["Start"]) && ! empty ( $condition["Finish"]))
      {
        $data["Properties"]["Conditions"][] = array ( "Start" => $condition["Start"], "Finish" => $condition["Finish"]);
      }
    }
  }
  if ( sizeof ( $parameters["Outputs"]) != sizeof ( $parameters["Properties"]["Conditions"]) + 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output for each condition plus the other output.");
  } else {
    $other = false;
    foreach ( $parameters["Outputs"] as $output)
    {
      if ( $output["Name"] == "other")
      {
        $other = true;
      } else {
        $data["Outputs"][] = $output["Name"];
      }
    }
    if ( ! $other)
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"other\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR weekday operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "weekday")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"weekday\"."),
                  "properties" => array ()
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter weekday IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_weekday ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array (), "Outputs" => array ( "sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"));
  if ( sizeof ( $parameters["Outputs"]) != 7)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have 7 outputs.");
  } else {
    $weekdays = array ( "sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
    foreach ( $parameters["Outputs"] as $output)
    {
      $weekdays = array_diff ( $weekdays, array ( $output["Name"]));
    }
    if ( sizeof ( $weekdays) != 0)
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output for each week day.");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR play operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "play")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"play\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Play welcome message.")
                    ),
                    "File" => array (
                      "type" => "integer",
                      "required" => true,
                      "description" => __ ( "The audio file internal system unique identifier."),
                      "example" => 1
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter play IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_play ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "File" => (int) $parameters["Properties"]["File"]), "Outputs" => array ( "output"));
  if ( ! array_key_exists ( "File", $parameters["Properties"]))
  {
    $data["Errors"]["File"] = __ ( "Filename is required.");
  }
  $file = filters_call ( "get_audios", array ( "ID" => (int) $parameters["Properties"]["File"]));
  if ( sizeof ( $file) == 1)
  {
    $data["Properties"]["Filename"] = "audio-" . $file[0]["ID"] . "/" . $file[0]["Filename"];
  }
  if ( ! array_key_exists ( "File", $data["Errors"]) && ( (int) $parameters["Properties"]["File"] != $parameters["Properties"]["File"] || sizeof ( $file) != 1))
  {
    $data["Errors"]["File"] = __ ( "Invalid Filename.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output.");
  } else {
    if ( $parameters["Outputs"][0]["Name"] != "output")
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"output\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR record operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "record")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"record\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Record customer audio message.")
                    ),
                    "Filename" => array (
                      "type" => "string",
                      "required" => true,
                      "description" => __ ( "The audio filename to be written."),
                      "example" => __ ( "customeraudio")
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter record IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_record ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Filename" => $parameters["Properties"]["Filename"]), "Outputs" => array ( "output"));
  if ( ! array_key_exists ( "Filename", $parameters["Properties"]))
  {
    $data["Errors"]["Filename"] = __ ( "Filename is required.");
  }
  if ( ! array_key_exists ( "Filename", $data["Errors"]) && ( strpos ( $parameters["Properties"]["Filename"], "/") !== false || strpos ( $parameters["Properties"]["Filename"], ",") !== false)) 
  {
    $data["Errors"]["Filename"] = __ ( "Filename cannot contains / or ,.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output.");
  } else {
    if ( $parameters["Outputs"][0]["Name"] != "output")
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"output\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR stop operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "stop")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"stop\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Stop recording audio.")
                    ),
                    "Discard" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the recorded audio must be discarded."),
                      "example" => false
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter stop IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_stop ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Discard" => (boolean) $parameters["Properties"]["Discard"]), "Outputs" => array ( "output"));
  if ( ! array_key_exists ( "Discard", $parameters["Properties"]))
  {
    $data["Errors"]["Discard"] = __ ( "Option Discard is required.");
  }
  if ( ! array_key_exists ( "Discard", $data["Errors"]) && $parameters["Properties"]["Discard"] != true && $parameters["Properties"]["Discard"] != false)
  {
    $data["Errors"]["Discard"] = __ ( "Option Discard must be true or false.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output.");
  } else {
    if ( $parameters["Outputs"][0]["Name"] != "output")
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"output\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR read operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "read")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"read\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Read destination extension number.")
                    ),
                    "Variable" => array (
                      "type" => "string",
                      "required" => true,
                      "pattern" => "/^[a-z0-9_]+$/",
                      "description" => __ ( "The variable name to store the digits."),
                      "example" => __ ( "extension")
                    ),
                    "Digits" => array (
                      "type" => "integer",
                      "required" => true,
                      "description" => __ ( "The number of digits to read."),
                      "example" => 4
                    ),
                    "Timeout" => array (
                      "type" => "integer",
                      "required" => true,
                      "description" => __ ( "The time in seconds to wait for digits."),
                      "example" => 10
                    ),
                    "Echo" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the system should say back the dialed digits."),
                      "example" => true
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter read IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_read ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Variable" => $parameters["Properties"]["Variable"], "Digits" => (int) $parameters["Properties"]["Digits"], "Timeout" => $parameters["Properties"]["Timeout"], "Echo" => (boolean) $parameters["Properties"]["Echo"]), "Outputs" => array ( "ok", "timedout"));
  if ( ! array_key_exists ( "Variable", $parameters["Properties"]))
  {
    $data["Errors"]["Variable"] = __ ( "Variable is required.");
  }
  if ( ! array_key_exists ( "Variable", $data["Errors"]) && ! preg_match ( "/^[a-z0-9_]+$/", $parameters["Properties"]["Variable"]))
  {
    $data["Errors"]["Variable"] = __ ( "Variable must have only lowercase letters, numbers and underscore.");
  }
  if ( ! array_key_exists ( "Variable", $data["Errors"]) && strlen ( $parameters["Properties"]["Variable"]) > 128)
  {
    $data["Errors"]["Variable"] = __ ( "Variable cannot have more than 128 digits.");
  }
  if ( ! array_key_exists ( "Digits", $parameters["Properties"]))
  {
    $data["Errors"]["Digits"] = __ ( "Digits is required.");
  }
  if ( ! array_key_exists ( "Digits", $data["Errors"]) && (int) $parameters["Properties"]["Digits"] != $parameters["Properties"]["Digits"])
  {
    $data["Errors"]["Digits"] = __ ( "Digits must be a number.");
  }
  if ( ! array_key_exists ( "Digits", $data["Errors"]) && (int) $parameters["Properties"]["Digits"] < 1)
  {
    $data["Errors"]["Digits"] = __ ( "Digits must be at least 1.");
  }
  if ( ! array_key_exists ( "Timeout", $parameters["Properties"]))
  {
    $data["Errors"]["Timeout"] = __ ( "Timeout is required.");
  }
  if ( ! array_key_exists ( "Timeout", $data["Errors"]) && (int) $parameters["Properties"]["Timeout"] != $parameters["Properties"]["Timeout"])
  {
    $data["Errors"]["Timeout"] = __ ( "Timeout must be a number.");
  }
  if ( ! array_key_exists ( "Timeout", $data["Errors"]) && (int) $parameters["Properties"]["Timeout"] < 1)
  {
    $data["Errors"]["Timeout"] = __ ( "Timeout must be at least 1.");
  }
  if ( ! array_key_exists ( "Echo", $parameters["Properties"]))
  {
    $data["Errors"]["Echo"] = __ ( "Echo field is required.");
  }
  if ( ! array_key_exists ( "Echo", $data["Errors"]) && $parameters["Properties"]["Echo"] != true && $parameters["Properties"]["Echo"] != false)
  {
    $data["Errors"]["Echo"] = __ ( "Echo must be true or false.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 2)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have two outputs.");
  } else {
    $outputs = array ( "ok", "timedout");
    foreach ( $parameters["Outputs"] as $output)
    {
      $outputs = array_diff ( $outputs, array ( $output["Name"]));
    }
    if ( sizeof ( $outputs) != 0)
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have two outputs, \"ok\" and \"timedout\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR menu operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "menu")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"menu\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Main IVR menu.")
                    ),
                    "Variable" => array (
                      "type" => "string",
                      "required" => true,
                      "pattern" => "/^[a-z0-9_]+$/",
                      "description" => __ ( "The variable name to store the selected option."),
                      "example" => __ ( "mainmenu")
                    ),
                    "File" => array (
                      "type" => "integer",
                      "required" => true,
                      "description" => __ ( "The audio file internal system unique identifier to play at menu start."),
                      "example" => 1
                    ),
                    "InvalidFile" => array (
                      "type" => "integer",
                      "required" => false,
                      "description" => __ ( "The audio file internal system unique identifier to play when selected invalid option."),
                      "example" => 2
                    ),
                    "Echo" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the system should say back the dialed option."),
                      "example" => true
                    ),
                    "Timeout" => array (
                      "type" => "integer",
                      "required" => true,
                      "description" => __ ( "The time in seconds to wait for digits."),
                      "example" => 10
                    ),
                    "Retries" => array (
                      "type" => "integer",
                      "required" => true,
                      "description" => __ ( "The maximum retries to get a valid response."),
                      "example" => 3
                    ),
                    "Option0" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 0 is considered a valid option."),
                      "example" => true
                    ),
                    "Option1" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 1 is considered a valid option."),
                      "example" => true
                    ),
                    "Option2" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 2 is considered a valid option."),
                      "example" => true
                    ),
                    "Option3" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 3 is considered a valid option."),
                      "example" => false
                    ),
                    "Option4" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 4 is considered a valid option."),
                      "example" => false
                    ),
                    "Option5" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 5 is considered a valid option."),
                      "example" => false
                    ),
                    "Option6" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 6 is considered a valid option."),
                      "example" => false
                    ),
                    "Option7" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 7 is considered a valid option."),
                      "example" => false
                    ),
                    "Option8" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 8 is considered a valid option."),
                      "example" => false
                    ),
                    "Option9" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit 9 is considered a valid option."),
                      "example" => true
                    ),
                    "OptionA" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit * is considered a valid option."),
                      "example" => false
                    ),
                    "OptionS" => array (
                      "type" => "boolean",
                      "required" => true,
                      "description" => __ ( "If the digit # is considered a valid option."),
                      "example" => false
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter menu IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_menu ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Variable" => $parameters["Properties"]["Variable"], "File" => (int) $parameters["Properties"]["File"], "InvalidFile" => (int) $parameters["Properties"]["InvalidFile"], "Echo" => (boolean) $parameters["Properties"]["Echo"], "Timeout" => (int) $parameters["Properties"]["Timeout"], "Retries" => (int) $parameters["Properties"]["Retries"], "Option0" => (boolean) $parameters["Properties"]["Option0"], "Option1" => (boolean) $parameters["Properties"]["Option1"], "Option2" => (boolean) $parameters["Properties"]["Option2"], "Option3" => (boolean) $parameters["Properties"]["Option3"], "Option4" => (boolean) $parameters["Properties"]["Option4"], "Option5" => (boolean) $parameters["Properties"]["Option5"], "Option6" => (boolean) $parameters["Properties"]["Option6"], "Option7" => (boolean) $parameters["Properties"]["Option7"], "Option8" => (boolean) $parameters["Properties"]["Option8"], "Option9" => (boolean) $parameters["Properties"]["Option9"], "OptionA" => (boolean) $parameters["Properties"]["OptionA"], "OptionS" => (boolean) $parameters["Properties"]["OptionS"]), "Outputs" => array ( "timedout"));
  if ( ! array_key_exists ( "Variable", $parameters["Properties"]))
  {
    $data["Errors"]["Variable"] = __ ( "Variable is required.");
  }
  if ( ! array_key_exists ( "Variable", $data["Errors"]) && ! preg_match ( "/^[a-z0-9_]+$/", $parameters["Properties"]["Variable"]))
  {
    $data["Errors"]["Variable"] = __ ( "Variable must have only lowercase letters, numbers and underscore.");
  }
  if ( ! array_key_exists ( "Variable", $data["Errors"]) && strlen ( $parameters["Properties"]["Variable"]) > 128)
  {
    $data["Errors"]["Variable"] = __ ( "Variable cannot have more than 128 digits.");
  }
  if ( ! array_key_exists ( "File", $parameters["Properties"]))
  {
    $data["Errors"]["File"] = __ ( "Filename is required.");
  }
  $file = filters_call ( "get_audios", array ( "ID" => (int) $parameters["Properties"]["File"]));
  if ( sizeof ( $file) == 1)
  {
    $data["Properties"]["Filename"] = "audio-" . $file[0]["ID"] . "/" . $file[0]["Filename"];
  }
  if ( ! array_key_exists ( "File", $data["Errors"]) && ( (int) $parameters["Properties"]["File"] != $parameters["Properties"]["File"] || sizeof ( $file) != 1))
  {
    $data["Errors"]["File"] = __ ( "Invalid Filename.");
  }
  if ( array_key_exists ( "InvalidFile", $parameters["Properties"]) && $parameters["Properties"]["InvalidFile"])
  {
    $invalidfile = filters_call ( "get_audios", array ( "ID" => (int) $parameters["Properties"]["InvalidFile"]));
    if ( sizeof ( $invalidfile) == 1)
    {
      $data["Properties"]["InvalidFilename"] = "audio-" . $invalidfile[0]["ID"] . "/" . $invalidfile[0]["Filename"];
    }
    if ( (int) $parameters["Properties"]["InvalidFile"] != $parameters["Properties"]["InvalidFile"] || sizeof ( $invalidfile) != 1)
    {
      $data["Errors"]["InvalidFile"] = __ ( "Invalid InvalidFile.");
    }
  }
  if ( ! array_key_exists ( "Echo", $parameters["Properties"]))
  {
    $data["Errors"]["Echo"] = __ ( "Echo field is required.");
  }
  if ( ! array_key_exists ( "Echo", $data["Errors"]) && $parameters["Properties"]["Echo"] != true && $parameters["Properties"]["Echo"] != false)
  {
    $data["Errors"]["Echo"] = __ ( "Echo must be true or false.");
  }
  if ( ! array_key_exists ( "Timeout", $parameters["Properties"]))
  {
    $data["Errors"]["Timeout"] = __ ( "Timeout is required.");
  }
  if ( ! array_key_exists ( "Timeout", $data["Errors"]) && (int) $parameters["Properties"]["Timeout"] != $parameters["Properties"]["Timeout"])
  {
    $data["Errors"]["Timeout"] = __ ( "Timeout must be a number.");
  }
  if ( ! array_key_exists ( "Timeout", $data["Errors"]) && (int) $parameters["Properties"]["Timeout"] < 1)
  {
    $data["Errors"]["Timeout"] = __ ( "Timeout must be at least 1.");
  }
  if ( ! array_key_exists ( "Retries", $parameters["Properties"]))
  {
    $data["Errors"]["Retries"] = __ ( "Retries is required.");
  }
  if ( ! array_key_exists ( "Retries", $data["Errors"]) && (int) $parameters["Properties"]["Retries"] != $parameters["Properties"]["Retries"])
  {
    $data["Errors"]["Retries"] = __ ( "Retries must be a number.");
  }
  if ( ! array_key_exists ( "Retries", $data["Errors"]) && (int) $parameters["Properties"]["Retries"] < 1)
  {
    $data["Errors"]["Retries"] = __ ( "Retries must be at least 1.");
  }
  if ( ! array_key_exists ( "Option0", $parameters["Properties"]))
  {
    $data["Errors"]["Option0"] = __ ( "Option0 field is required.");
  }
  if ( ! array_key_exists ( "Option0", $data["Errors"]) && $parameters["Properties"]["Option0"] != true && $parameters["Properties"]["Option0"] != false)
  {
    $data["Errors"]["Option0"] = __ ( "Option0 must be true or false.");
  }
  if ( ! array_key_exists ( "Option1", $parameters["Properties"]))
  {
    $data["Errors"]["Option1"] = __ ( "Option1 field is required.");
  }
  if ( ! array_key_exists ( "Option1", $data["Errors"]) && $parameters["Properties"]["Option1"] != true && $parameters["Properties"]["Option1"] != false)
  {
    $data["Errors"]["Option1"] = __ ( "Option1 must be true or false.");
  }
  if ( ! array_key_exists ( "Option2", $parameters["Properties"]))
  {
    $data["Errors"]["Option2"] = __ ( "Option2 field is required.");
  }
  if ( ! array_key_exists ( "Option2", $data["Errors"]) && $parameters["Properties"]["Option2"] != true && $parameters["Properties"]["Option2"] != false)
  {
    $data["Errors"]["Option2"] = __ ( "Option2 must be true or false.");
  }
  if ( ! array_key_exists ( "Option3", $parameters["Properties"]))
  {
    $data["Errors"]["Option3"] = __ ( "Option3 field is required.");
  }
  if ( ! array_key_exists ( "Option3", $data["Errors"]) && $parameters["Properties"]["Option3"] != true && $parameters["Properties"]["Option3"] != false)
  {
    $data["Errors"]["Option3"] = __ ( "Option3 must be true or false.");
  }
  if ( ! array_key_exists ( "Option4", $parameters["Properties"]))
  {
    $data["Errors"]["Option4"] = __ ( "Option4 field is required.");
  }
  if ( ! array_key_exists ( "Option4", $data["Errors"]) && $parameters["Properties"]["Option4"] != true && $parameters["Properties"]["Option4"] != false)
  {
    $data["Errors"]["Option4"] = __ ( "Option4 must be true or false.");
  }
  if ( ! array_key_exists ( "Option5", $parameters["Properties"]))
  {
    $data["Errors"]["Option5"] = __ ( "Option5 field is required.");
  }
  if ( ! array_key_exists ( "Option5", $data["Errors"]) && $parameters["Properties"]["Option5"] != true && $parameters["Properties"]["Option5"] != false)
  {
    $data["Errors"]["Option5"] = __ ( "Option5 must be true or false.");
  }
  if ( ! array_key_exists ( "Option6", $parameters["Properties"]))
  {
    $data["Errors"]["Option6"] = __ ( "Option6 field is required.");
  }
  if ( ! array_key_exists ( "Option6", $data["Errors"]) && $parameters["Properties"]["Option6"] != true && $parameters["Properties"]["Option6"] != false)
  {
    $data["Errors"]["Option6"] = __ ( "Option6 must be true or false.");
  }
  if ( ! array_key_exists ( "Option7", $parameters["Properties"]))
  {
    $data["Errors"]["Option7"] = __ ( "Option7 field is required.");
  }
  if ( ! array_key_exists ( "Option7", $data["Errors"]) && $parameters["Properties"]["Option7"] != true && $parameters["Properties"]["Option7"] != false)
  {
    $data["Errors"]["Option7"] = __ ( "Option7 must be true or false.");
  }
  if ( ! array_key_exists ( "Option8", $parameters["Properties"]))
  {
    $data["Errors"]["Option8"] = __ ( "Option8 field is required.");
  }
  if ( ! array_key_exists ( "Option8", $data["Errors"]) && $parameters["Properties"]["Option8"] != true && $parameters["Properties"]["Option8"] != false)
  {
    $data["Errors"]["Option8"] = __ ( "Option8 must be true or false.");
  }
  if ( ! array_key_exists ( "Option9", $parameters["Properties"]))
  {
    $data["Errors"]["Option9"] = __ ( "Option9 field is required.");
  }
  if ( ! array_key_exists ( "Option9", $data["Errors"]) && $parameters["Properties"]["Option9"] != true && $parameters["Properties"]["Option9"] != false)
  {
    $data["Errors"]["Option9"] = __ ( "Option9 must be true or false.");
  }
  if ( ! array_key_exists ( "OptionA", $parameters["Properties"]))
  {
    $data["Errors"]["OptionA"] = __ ( "OptionA field is required.");
  }
  if ( ! array_key_exists ( "OptionA", $data["Errors"]) && $parameters["Properties"]["OptionA"] != true && $parameters["Properties"]["OptionA"] != false)
  {
    $data["Errors"]["OptionA"] = __ ( "OptionA must be true or false.");
  }
  if ( ! array_key_exists ( "OptionS", $parameters["Properties"]))
  {
    $data["Errors"]["OptionS"] = __ ( "OptionS field is required.");
  }
  if ( ! array_key_exists ( "OptionS", $data["Errors"]) && $parameters["Properties"]["OptionS"] != true && $parameters["Properties"]["OptionS"] != false)
  {
    $data["Errors"]["OptionS"] = __ ( "OptionS must be true or false.");
  }
  if ( ! $parameters["Properties"]["Option0"] && ! $parameters["Properties"]["Option1"] && ! $parameters["Properties"]["Option2"] && ! $parameters["Properties"]["Option3"] && ! $parameters["Properties"]["Option4"] && ! $parameters["Properties"]["Option5"] && ! $parameters["Properties"]["Option6"] && ! $parameters["Properties"]["Option7"] && ! $parameters["Properties"]["Option8"] && ! $parameters["Properties"]["Option9"] && ! $parameters["Properties"]["OptionA"] && ! $parameters["Properties"]["OptionS"])
  {
    $data["Errors"]["Option0"] = __ ( "At least one option must be enabled.");
  }
  if ( sizeof ( $parameters["Outputs"]) < 2)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have at least two outputs.");
  } else {
    $outputs = array ( "timedout");
    if ( $parameters["Properties"]["Option0"])
    {
      $outputs[] = "option0";
    }
    if ( $parameters["Properties"]["Option1"])
    {
      $outputs[] = "option1";
    }
    if ( $parameters["Properties"]["Option2"])
    {
      $outputs[] = "option2";
    }
    if ( $parameters["Properties"]["Option3"])
    {
      $outputs[] = "option3";
    }
    if ( $parameters["Properties"]["Option4"])
    {
      $outputs[] = "option4";
    }
    if ( $parameters["Properties"]["Option5"])
    {
      $outputs[] = "option5";
    }
    if ( $parameters["Properties"]["Option6"])
    {
      $outputs[] = "option6";
    }
    if ( $parameters["Properties"]["Option7"])
    {
      $outputs[] = "option7";
    }
    if ( $parameters["Properties"]["Option8"])
    {
      $outputs[] = "option8";
    }
    if ( $parameters["Properties"]["Option9"])
    {
      $outputs[] = "option9";
    }
    if ( $parameters["Properties"]["OptionA"])
    {
      $outputs[] = "optiona";
    }
    if ( $parameters["Properties"]["OptionS"])
    {
      $outputs[] = "options";
    }
    $data["Outputs"] = $outputs;
    foreach ( $parameters["Outputs"] as $output)
    {
      $outputs = array_diff ( $outputs, array ( $output["Name"]));
    }
    if ( sizeof ( $outputs) != 0)
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have outputs to \"timedout\" and each enabled option.");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR router operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "router")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"router\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Route call based on caller action.")
                    ),
                    "Variable" => array (
                      "type" => "string",
                      "required" => true,
                      "pattern" => "/^[a-z0-9_]+$/",
                      "description" => __ ( "The variable name to store the digits."),
                      "example" => __ ( "extension")
                    ),
                    "Conditions" => array (
                      "type" => "array",
                      "required" => true,
                      "description" => __ ( "The array containing object of conditions."),
                      "xml" => array (
                        "name" => "Conditions",
                        "wrapped" => true
                      ),
                      "items" => array (
                        "type" => "object",
                        "xml" => array (
                          "name" => "Condition"
                        ),
                        "properties" => array (
                          "Condition" => array (
                            "type" => "string",
                            "required" => true,
                            "enum" => array ( "=", "!= ", "in", "notin", "<", "<=", ">", ">="),
                            "description" => __ ( "The condition logic."),
                            "example" => ">="
                          ),
                          "Value" => array (
                            "type" => "string",
                            "required" => true,
                            "description" => __ ( "The condition value."),
                            "example" => "1000"
                          )
                        )
                      ),
                      "example" => array (
                        array (
                          "Condition" => ">=",
                          "Value" => "1000"
                        ),
                        array (
                          "Condition" => "<",
                          "Value" => "2000"
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter router IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_router ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Variable" => $parameters["Properties"]["Variable"], "Conditions" => array ()), "Outputs" => array ( "true", "false"));
  if ( ! array_key_exists ( "Variable", $parameters["Properties"]))
  {
    $data["Errors"]["Variable"] = __ ( "Variable is required.");
  }
  if ( ! array_key_exists ( "Variable", $data["Errors"]) && ! preg_match ( "/^[a-z0-9_]+$/", $parameters["Properties"]["Variable"]))
  {
    $data["Errors"]["Variable"] = __ ( "Variable must have only lowercase letters, numbers and underscore.");
  }
  if ( ! array_key_exists ( "Variable", $data["Errors"]) && strlen ( $parameters["Properties"]["Variable"]) > 128)
  {
    $data["Errors"]["Variable"] = __ ( "Variable cannot have more than 128 digits.");
  }
  if ( ! array_key_exists ( "Conditions", $parameters["Properties"]))
  {
    $data["Errors"]["Conditions"] = __ ( "At least one condition is required.");
  }
  if ( ! array_key_exists ( "Conditions", $data["Errors"]) && ! is_array ( $parameters["Properties"]["Conditions"]))
  {
    $data["Errors"]["Conditions"] = __ ( "Conditions must be an array.");
  }
  $conditions = array ( "=", "!=", "in", "notin", "<", "<=", ">", ">=");
  if ( ! array_key_exists ( "Conditions", $data["Errors"]))
  {
    foreach ( $parameters["Properties"]["Conditions"] as $index => $condition)
    {
      if ( ! array_key_exists ( "Condition", $condition))
      {
        $data["Errors"]["Condition_" . $index] = sprintf ( __ ( "Condition %d condition is required."), $index);
      } else {
        if ( ! array_key_exists ( $condition["Condition"], $conditions))
        {
          $data["Errors"]["Condition_" . $index] = sprintf ( __ ( "Condition %d condition is invalid."), $index);
          $condition["Condition"] = "";
        }
      }
      if ( ! array_key_exists ( "Value", $condition))
      {
        $data["Errors"]["Value_" . $index] = sprintf ( __ ( "Condition %d value is required."), $index);
      }
      if ( $condition["Condition"] != "")
      {
        $data["Properties"]["Conditions"][] = array ( "Condition" => $condition["Condition"], "Value" => $condition["Value"]);
      }
    }
  }
  if ( sizeof ( $parameters["Outputs"]) != 2)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have two outputs \"true\" and \"false\".");
  } else {
    $outputs = array ( "true", "false");
    foreach ( $parameters["Outputs"] as $output)
    {
      $outputs = array_diff ( $outputs, array ( $output["Name"]));
    }
    if ( sizeof ( $outputs) != 0)
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have two outputs \"true\" and \"false\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR setvar operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "setvar")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"setvar\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Set call type variable.")
                    ),
                    "Variable" => array (
                      "type" => "string",
                      "required" => true,
                      "pattern" => "/^[a-z0-9_]+$/",
                      "description" => __ ( "The variable name."),
                      "example" => "calltype"
                    ),
                    "Value" => array (
                      "type" => "string",
                      "required" => true,
                      "description" => __ ( "The value to be assigned."),
                      "example" => "internal"
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter setvar IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_setvar ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Variable" => $parameters["Properties"]["Variable"], "Value" => $parameters["Properties"]["Value"]), "Outputs" => array ( "output"));
  if ( ! array_key_exists ( "Variable", $parameters["Properties"]))
  {
    $data["Errors"]["Variable"] = __ ( "Variable is required.");
  }
  if ( ! array_key_exists ( "Variable", $data["Errors"]) && ! preg_match ( "/^[a-z0-9_]+$/", $parameters["Properties"]["Variable"]))
  {
    $data["Errors"]["Variable"] = __ ( "Variable must have only lowercase letters, numbers and underscore.");
  }
  if ( ! array_key_exists ( "Variable", $data["Errors"]) && strlen ( $parameters["Properties"]["Variable"]) > 128)
  {
    $data["Errors"]["Variable"] = __ ( "Variable cannot have more than 128 digits.");
  }
  if ( ! array_key_exists ( "Value", $parameters["Properties"]))
  {
    $data["Errors"]["Value"] = __ ( "Value is required.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output.");
  } else {
    if ( $parameters["Outputs"][0]["Name"] != "output")
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"output\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR dial operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "dial")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"dial\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Dial to the extension.")
                    ),
                    "Destination" => array (
                      "type" => "string",
                      "required" => true,
                      "description" => __ ( "The number of destination extension (allow use of {var_VARIABLE})."),
                      "example" => 1000
                    ),
                    "Timeout" => array (
                      "type" => "integer",
                      "required" => true,
                      "description" => __ ( "The number of seconds to timeout dial."),
                      "example" => 30
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter dial IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_dial ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Destination" => $parameters["Properties"]["Destination"], "Timeout" => (int) $parameters["Properties"]["Timeout"]), "Outputs" => array ( "ok", "timedout", "busy", "refused"));
  if ( ! array_key_exists ( "Destination", $parameters["Properties"]))
  {
    $data["Errors"]["Destination"] = __ ( "Destination is required.");
  }
  if ( ! array_key_exists ( "Timeout", $parameters["Properties"]))
  {
    $data["Errors"]["Timeout"] = __ ( "Timeout is required.");
  }
  if ( ! array_key_exists ( "Timeout", $data["Errors"]) && (int) $parameters["Properties"]["Timeout"] != $parameters["Properties"]["Timeout"])
  {
    $data["Errors"]["Timeout"] = __ ( "Timeout must be a number.");
  }
  if ( ! array_key_exists ( "Timeout", $data["Errors"]) && (int) $parameters["Properties"]["Timeout"] < 1)
  {
    $data["Errors"]["Timeout"] = __ ( "Timeout must be at least 1.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 4)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have 4 outputs.");
  } else {
    $weekdays = array ( "ok", "timedout", "busy", "refused");
    foreach ( $parameters["Outputs"] as $output)
    {
      $weekdays = array_diff ( $weekdays, array ( $output["Name"]));
    }
    if ( sizeof ( $weekdays) != 0)
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have four outputs \"ok\", \"timedout\", \"busy\" and \"refused\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR script operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "script")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"script\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Call internal script.")
                    ),
                    "Name" => array (
                      "type" => "string",
                      "required" => true,
                      "description" => __ ( "The name of the script to run."),
                      "example" => "script.sh"
                    ),
                    "Variable" => array (
                      "type" => "string", 
                      "required" => true,
                      "pattern" => "/^[a-z0-9_]+$/",
                      "description" => __ ( "The variable name to store the script output."),
                      "example" => __ ( "script")
                    ),
                    "Parameters" => array (
                      "type" => "array",
                      "required" => true,
                      "description" => __ ( "The array containing object of parameters."),
                      "xml" => array (
                        "name" => "Parameters",
                        "wrapped" => true
                      ),
                      "items" => array (
                        "type" => "object",
                        "xml" => array (
                          "name" => "Parameter"
                        ),
                        "properties" => array (
                          "Parameter" => array (
                            "type" => "string",
                            "required" => true,
                            "description" => __ ( "The parameter to be passed ot the script."),
                            "example" => "1000"
                          )
                        )
                      ),
                      "example" => array (
                        array (
                          "Parameter" => "extension"
                        ),
                        array (
                          "Parameter" => "1000"
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter script IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_script ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "Name" => basename ( $parameters["Properties"]["Name"]), "Variable" => $parameters["Properties"]["Variable"], "Parameters" => $parameters["Properties"]["Parameters"]), "Outputs" => array ( "output"));
  if ( ! array_key_exists ( "Name", $parameters["Properties"]) || ! $parameters["Properties"]["Name"])
  {
    $data["Errors"]["Name"] = __ ( "Name is required.");
  }
  if ( ! array_key_exists ( "Name", $data["Errors"]) && $parameters["Properties"]["Name"] != basename ( $parameters["Properties"]["Name"]))
  {
    $data["Errors"]["Name"] = __ ( "Name cannot contain a directory.");
  }
  if ( $parameters["Properties"]["Variable"] && ! preg_match ( "/^[a-z0-9_]+$/", $parameters["Properties"]["Variable"]))
  {
    $data["Errors"]["Variable"] = __ ( "Variable must have only lowercase letters, numbers and underscore.");
  }
  if ( ! array_key_exists ( "Variable", $data["Errors"]) && strlen ( $parameters["Properties"]["Variable"]) > 128)
  {
    $data["Errors"]["Variable"] = __ ( "Variable cannot have more than 128 digits.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output.");
  } else {
    if ( $parameters["Outputs"][0]["Name"] != "output")
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"output\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * IVR email operator
 */
framework_add_component_documentation (
  "ivr",
  array (
    "properties" => array (
      "Operators" => array (
        "items" => array (
          "properties" => array ( 
            "Operator" => array (
              "enum" => array ( "email")
            ),
            "Properties" => array (
              "oneOf" => array (
                array (
                  "type" => "object",
                  "description" => __ ( "Variables when operator type is \"email\"."),
                  "properties" => array (
                    "Description" => array (
                      "type" => "string",
                      "required" => false,
                      "description" => __ ( "The description of the operator."),
                      "example" => __ ( "Send email to adminstrator.")
                    ),
                    "To" => array (
                      "type" => "string",
                      "required" => true,
                      "description" => __ ( "The destination email address."),
                      "example" => "admin@example.com"
                    ),
                    "Subject" => array (
                      "type" => "string",
                      "required" => true,
                      "description" => __ ( "The email subject."),
                      "example" => __ ( "New call at main IVR.")
                    ),
                    "Body" => array (
                      "type" => "string",
                      "required" => true,
                      "description" => __ ( "The email body."),
                      "example" => __ ( "Hi admin! We received a new call at our main IVR.")
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to filter email IVR operator.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array containing any error
 */
function ivr_operator_filter_email ( $buffer, $parameters)
{
  /**
   * Check for required parameters
   */
  $data = array ( "Errors" => array (), "Properties" => array ( "Description" => $parameters["Properties"]["Description"], "To" => $parameters["Properties"]["To"], "Subject" => $parameters["Properties"]["Subject"], "Body" => $parameters["Properties"]["Body"]), "Outputs" => array ( "output"));
  if ( ! array_key_exists ( "To", $parameters["Properties"]) || ! $parameters["Properties"]["To"])
  {
    $data["Errors"]["To"] = __ ( "To is required.");
  }
  if ( ! array_key_exists ( "To", $data["Errors"]) && ! validate_email ( $parameters["Properties"]["To"]))
  {
    $data["Errors"]["To"] = __ ( "Invalid email address.");
  }
  if ( ! array_key_exists ( "Subject", $parameters["Properties"]) || ! $parameters["Properties"]["Subject"])
  {
    $data["Errors"]["Subject"] = __ ( "Subject is required.");
  }
  if ( ! array_key_exists ( "Body", $parameters["Properties"]) || ! $parameters["Properties"]["Body"])
  {
    $data["Errors"]["Body"] = __ ( "Body is required.");
  }
  if ( sizeof ( $parameters["Outputs"]) != 1)
  {
    $data["Errors"]["Outputs"] = __ ( "Operator must have an output.");
  } else {
    if ( $parameters["Outputs"][0]["Name"] != "output")
    {
      $data["Errors"]["Outputs"] = __ ( "Operator must have an output with name \"output\".");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to search IVRs
 */
framework_add_hook (
  "ivrs_search",
  "ivrs_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all IVRs."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Name,Description,Uses",
          "example" => "Name,Description"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system IVRs."),
        "schema" => array (
          "type" => "array",
          "items" => array (
            "type" => "object",
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the IVRs."),
                "example" => 1
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the IVRs."),
                "example" => __ ( "Sales")
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the IVR."),
                "example" => __ ( "Sales IVR workflow.")
              ),
              "Uses" => array (
                "type" => "integer",
                "description" => __ ( "How many times this IVR are in use into the system."),
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
              "example" => __ ( "Invalid filter content.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "ivrs_search", __ ( "Search IVRs"));
framework_add_api_call (
  "/ivrs",
  "Read",
  "ivrs_search",
  array (
    "permissions" => array ( "administrator", "ivrs_search"),
    "title" => __ ( "Search IVRs"),
    "description" => __ ( "Search for system IVRs.")
  )
);

/**
 * Function to search IVRs.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ivrs_search_start"))
  {
    $parameters = framework_call ( "ivrs_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "IVRs");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ivrs_search_validate"))
  {
    $data = framework_call ( "ivrs_search_validate", $parameters);
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
  if ( framework_has_hook ( "ivrs_search_sanitize"))
  {
    $parameters = framework_call ( "ivrs_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ivrs_search_pre"))
  {
    $parameters = framework_call ( "ivrs_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search IVRs
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Name`, `Description` FROM `IVRs`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Name`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Name,Description,Uses", "ID,Name,Description,Uses");
  while ( $result = $results->fetch_assoc ())
  {
    $result["Uses"] = filters_call ( "ivr_inuse", array ( "ID" => $result["ID"]));
    if ( ! is_array ( $result["Uses"]))
    {
      $result["Uses"] = 0;
    } else {
      $result["Uses"] = sizeof ( $result["Uses"]);
    }
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ivrs_search_finish"))
  {
    framework_call ( "ivrs_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get IVR information
 */
framework_add_hook (
  "ivrs_view",
  "ivrs_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system IVR."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "integer",
              "description" => __ ( "The IVR internal system unique identifier."),
              "example" => 1
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name of the IVR."),
              "example" => __ ( "call-center")
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the IVR."),
              "example" => __ ( "Main Call Center IVR workflow")
            ),
            "Workflow" => array (
              "\$ref" => "#/components/schemas/ivr"
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
              "example" => __ ( "Invalid IVR ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "ivrs_view", __ ( "View IVRs information"));
framework_add_api_call (
  "/ivrs/:ID",
  "Read",
  "ivrs_view",
  array (
    "permissions" => array ( "administrator", "ivrs_view"),
    "title" => __ ( "View IVRs"),
    "description" => __ ( "Get a system IVR information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The IVR internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate IVR information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ivrs_view_start"))
  {
    $parameters = framework_call ( "ivrs_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "IVRs");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid IVR ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ivrs_view_validate"))
  {
    $data = framework_call ( "ivrs_view_validate", $parameters);
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
  if ( framework_has_hook ( "ivrs_view_sanitize"))
  {
    $parameters = framework_call ( "ivrs_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ivrs_view_pre"))
  {
    $parameters = framework_call ( "ivrs_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search IVRs
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `IVRs`.`ID`, `IVRs`.`Name`, `IVRs`.`Description`, `IVRWorkflows`.`Workflow` FROM `IVRs` LEFT JOIN `IVRWorkflows` ON `IVRs`.`ID` = `IVRWorkflows`.`IVR` WHERE `IVRs`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " ORDER BY `IVRWorkflows`.`Revision` DESC LIMIT 0,1"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $ivr = $result->fetch_assoc ();

  /**
   * Format data
   */
  $ivr["Workflow"] = json_decode ( $ivr["Workflow"], true);
  $data = api_filter_entry ( array ( "ID", "Name", "Description", "Workflow"), $ivr);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ivrs_view_post"))
  {
    $data = framework_call ( "ivrs_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ivrs_view_finish"))
  {
    framework_call ( "ivrs_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new IVR
 */
framework_add_hook (
  "ivrs_add",
  "ivrs_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the system IVR."),
          "pattern" => "/^[a-z0-9\-\.]$/",
          "required" => true,
          "example" => __ ( "call-center")
        ),
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the system IVR."),
          "required" => true,
          "example" => __ ( "Main Call Center IVR workflow")
        ),
        "Workflow" => array (
          "\$ref" => "#/components/schemas/ivr"
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system IVR added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The IVR name is required.")
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The IVR description is required.")
            ),
            "Workflow" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The IVR workflow is required.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "ivrs_add", __ ( "Add IVRs"));
framework_add_api_call (
  "/ivrs",
  "Create",
  "ivrs_add",
  array (
    "permissions" => array ( "administrator", "ivrs_add"),
    "title" => __ ( "Add IVRs"),
    "description" => __ ( "Add a new system IVR.")
  )
);

/**
 * Function to add a new IVR.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "blocks_add_start"))
  {
    $parameters = framework_call ( "blocks_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The IVR name is required.");
  }
  if ( ! array_key_exists ( "Name", $data) && $parameters["Name"] != preg_replace ( "/[^a-z0-9\-\.]/", "", $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name could only contain lower case characters, numbers, hifen and dot.");
  }
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The IVR description is required.");
  }
  if ( empty ( $parameters["Workflow"]))
  {
    $data["Workflow"] = __ ( "The IVR workflow cannot be empty.");
  }

  /**
   * Filter each operator
   */
  foreach ( $parameters["Workflow"]["Operators"] as $id => $operator)
  {
    /**
     * Process operator
     */
    $workflow = array ();
    if ( framework_has_hook ( "ivr_operator_filter_" . $operator["Operator"]))
    {
      $operatorfilter = framework_call ( "ivr_operator_filter_" . $operator["Operator"], $operator, false, array ( "Errors" => array (), "Parameters" => array (), "Outputs" => array ()));
      if ( sizeof ( $operatorfilter["Errors"]) != 0)
      {
        $data["op_" . $id] = $operatorfilter["Errors"];
      }

      /**
       * Sanitize by overwriting properties to the ones returned by the filter hook. This will filter any unwanted variable.
       */
      $workflow["Properties"] = $operatorfilter["Properties"];
      $workflow["Outputs"] = array ();
      foreach ( $operator["Outputs"] as $output)
      {
        if ( in_array ( $output["Name"], $operatorfilter["Outputs"]))
        {
          $workflow["Outputs"][] = array ( "Name" => $output["Name"], "Label" => $output["Label"]);
        }
      }
    } else {
      /**
       * Just reinject properties, due to no filter hook found. This could allow some unwanted data to the operator properties and must be avoided.
       */
      $workflow["Properties"] = $operator["Properties"];
      $workflow["Outputs"] = $operator["Outputs"];
    }
    $workflow["ID"] = $operator["ID"];
    $workflow["Operator"] = $operator["Operator"];
    $workflow["Top"] = (float) $operator["Top"];
    $workflow["Left"] = (float) $operator["Left"];
    $parameters["Workflow"]["Operators"][$id] = $workflow;
  }

  /**
   * Check if IVR was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `IVRs` WHERE `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Name"] = __ ( "The IVR name is already in use.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "blocks_add_validate"))
  {
    $data = framework_call ( "blocks_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "blocks_add_sanitize"))
  {
    $parameters = framework_call ( "blocks_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "blocks_add_pre"))
  {
    $parameters = framework_call ( "blocks_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new IVR record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `IVRs` (`Name`, `Description`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Add new IVR workflow record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `IVRWorkflows` (`IVR`, `Revision`, `Workflow`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", 1, '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Workflow"])) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ivrs_add_post"))
  {
    framework_call ( "ivrs_add_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Name" => $parameters["Name"], "Description" => $parameters["Description"], "Workflow" => $parameters["Workflow"]);
  if ( framework_has_hook ( "ivrs_add_notify"))
  {
    $notify = framework_call ( "ivrs_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "ivr_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "blocks_add_finish"))
  {
    framework_call ( "blocks_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/ivrs/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing IVR
 */
framework_add_hook (
  "ivrs_edit",
  "ivrs_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the system IVR."),
          "pattern" => "/^[a-z0-9\-\.]$/",
          "required" => true,
          "example" => __ ( "call-center")
        ),
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the system IVR."),
          "required" => true,
          "example" => __ ( "Main Call Center IVR workflow")
        ),
        "Workflow" => array (
          "\$ref" => "#/components/schemas/ivr"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system IVR was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The IVR name is required.")
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The IVR description is required.")
            ),
            "Workflow" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The IVR workflow is required.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "ivrs_edit", __ ( "Edit IVRs"));
framework_add_api_call (
  "/ivrs/:ID",
  "Modify",
  "ivrs_edit",
  array (
    "permissions" => array ( "administrator", "ivrs_edit"),
    "title" => __ ( "Edit IVRs"),
    "description" => __ ( "Change a system IVR information.")
  )
);
framework_add_api_call (
  "/ivrs/:ID",
  "Edit",
  "ivrs_edit",
  array (
    "permissions" => array ( "administrator", "ivrs_edit"),
    "title" => __ ( "Edit IVRs"),
    "description" => __ ( "Change a system IVR information.")
  )
);

/**
 * Function to edit an existing IVR.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ivrs_edit_start"))
  {
    $parameters = framework_call ( "ivrs_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The IVR name is required.");
  }
  if ( ! array_key_exists ( "Name", $data) && $parameters["Name"] != preg_replace ( "/[^a-z0-9\-\.]/", "", $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name could only contain lower case characters, numbers, hifen and dot.");
  }
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The IVR description is required.");
  }
  if ( empty ( $parameters["Workflow"]))
  {
    $data["Workflow"] = __ ( "The IVR workflow cannot be empty.");
  }

  /**
   * Filter each operator
   */
  foreach ( $parameters["Workflow"]["Operators"] as $id => $operator)
  {
    $workflow = array ();
    if ( framework_has_hook ( "ivr_operator_filter_" . $operator["Operator"]))
    {
      $operatorfilter = framework_call ( "ivr_operator_filter_" . $operator["Operator"], $operator, false, array ( "Errors" => array (), "Parameters" => array (), "Outputs" => array ()));
      if ( sizeof ( $operatorfilter["Errors"]) != 0)
      {
        $data["op_" . $id] = $operatorfilter["Errors"];
      }

      /**
       * Sanitize by overwriting properties to the ones returned by the filter hook. This will filter any unwanted variable.
       */
      $workflow["Properties"] = $operatorfilter["Properties"];
      $workflow["Outputs"] = array ();
      foreach ( $operator["Outputs"] as $output)
      {
        if ( in_array ( $output["Name"], $operatorfilter["Outputs"]))
        {
          $workflow["Outputs"][] = array ( "Name" => $output["Name"], "Label" => $output["Label"]);
        }
      }
    } else {
      /**
       * Just reinject properties, due to no filter hook found. This could allow some unwanted data to the operator properties and must be avoided.
       */
      $workflow["Properties"] = $operator["Properties"];
      $workflow["Outputs"] = $operator["Outputs"];
    }
    $workflow["ID"] = $operator["ID"];
    $workflow["Operator"] = $operator["Operator"];
    $workflow["Top"] = (float) $operator["Top"];
    $workflow["Left"] = (float) $operator["Left"];
    $parameters["Workflow"]["Operators"][$id] = $workflow;
  }

  /**
   * Check if IVR was already in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `IVRs` WHERE `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Name"] = __ ( "The IVR name is already in use.");
  }

  /**
   * Check if IVR exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `IVRs`.*, `IVRWorkflows`.`Revision`, `IVRWorkflows`.`Workflow` FROM `IVRs` LEFT JOIN `IVRWorkflows` ON `IVRs`.`ID` = `IVRWorkflows`.`IVR` WHERE `IVRs`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]) . " ORDER BY `IVRWorkflows`.`Revision` DESC LIMIT 0, 1"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $ivr = $result->fetch_assoc ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ivrs_edit_validate"))
  {
    $data = framework_call ( "ivrs_edit_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "ivrs_edit_sanitize"))
  {
    $parameters = framework_call ( "ivrs_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ivrs_edit_pre"))
  {
    $parameters = framework_call ( "ivrs_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change IVR record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `IVRs` Set `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `IVRWorkflows` (`IVR`, `Name`, `Description`, `Revision`, `Workflow`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', " . ( $ivr["Revision"] + 1) . ", '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Workflow"])) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ivrs_edit_post"))
  {
    framework_call ( "ivrs_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Name" => $ivr["Name"], "NewName" => $parameters["Name"], "Description" => $parameters["Description"], "Revision" => $ivr["Revision"] + 1, "Workflow" => $parameters["Workflow"]);
  if ( framework_has_hook ( "ivrs_edit_notify"))
  {
    $notify = framework_call ( "ivrs_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "ivr_change", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ivrs_edit_finish"))
  {
    framework_call ( "ivrs_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove an IVR
 */
framework_add_hook (
  "ivrs_remove",
  "ivrs_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system IVR was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid IVR ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "ivrs_remove", __ ( "Remove IVRs"));
framework_add_api_call (
  "/ivrs/:ID",
  "Delete",
  "ivrs_remove",
  array (
    "permissions" => array ( "administrator", "ivrs_remove"),
    "title" => __ ( "Remove IVRs"),
    "description" => __ ( "Remove a IVR from system.")
  )
);

/**
 * Function to remove an existing IVR.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ivrs_remove_start"))
  {
    $parameters = framework_call ( "ivrs_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid IVR ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ivrs_remove_validate"))
  {
    $data = framework_call ( "ivrs_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "ivrs_remove_sanitize"))
  {
    $parameters = framework_call ( "ivrs_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if IVR exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `IVRs`.*, `IVRWorkflows`.`Revision`, `IVRWorkflows`.`Workflow` FROM `IVRs` LEFT JOIN `IVRWorkflows` ON `IVRs`.`ID` = `IVRWorkflows`.`IVR` WHERE `IVRs`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " ORDER BY `IVRWorkflows`.`Revision` DESC LIMIT 0, 1"))
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
   * Check if IVR is in use
   */
  $use = filters_call ( "ivr_inuse", array ( "ID" => $parameters["ID"]));
  if ( is_array ( $use["Uses"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ivrs_remove_pre"))
  {
    $parameters = framework_call ( "ivrs_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove IVR database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `IVRWorkflows` WHERE `IVR` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `IVRs` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ivrs_remove_post"))
  {
    framework_call ( "ivrs_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Name" => $parameters["ORIGINAL"]["Name"]);
  if ( framework_has_hook ( "ivrs_remove_notify"))
  {
    $notify = framework_call ( "ivrs_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "ivr_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ivrs_remove_finish"))
  {
    framework_call ( "ivrs_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "ivrs_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "ivrs_server_reconfig");

/**
 * Function to notify server to include all IVRs.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all IVRs and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `IVRs`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $ivr = $result->fetch_assoc ())
  {
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT * FROM `IVRWorkflows` WHERE `IVR` = " . $_in["mysql"]["id"]->real_escape_string ( $ivr["ID"]) . " ORDER BY `Revision`"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $ivrworkflow = $result2->fetch_assoc ())
    {
      if ( $ivrworkflow["Revision"] == 1)
      {
        $notify = array ( "Name" => $ivr["Name"], "Description" => $ivr["Description"], "Workflow" => json_decode ( $ivrworkflow["Workflow"]));
        if ( framework_has_hook ( "ivrs_add_notify"))
        {
          $notify = framework_call ( "ivrs_add_notify", $parameters, false, $notify);
        }
        notify_server ( (int) $parameters["ID"], "ivr_add", $notify);
      } else {
        $notify = array ( "Name" => $lastname, "NewName" => $ivr["Name"], "Description" => $ivr["Description"], "Revision" => $ivrworkflow["Revision"], "Workflow" => json_decode ( $ivrworkflow["Workflow"]));
        if ( framework_has_hook ( "ivrs_edit_notify"))
        {
          $notify = framework_call ( "ivrs_edit_notify", $parameters, false, $notify);
        }
        notify_server ( (int) $parameters["ID"], "ivr_change", $notify);
      }
      $lastname = $ivrworkflow["Name"];
    }
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
