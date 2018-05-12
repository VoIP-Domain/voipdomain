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
 * VoIP Domain IVRs actions operators hooks.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage IVRs
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "ivr_operator_start", "ivr_operator_start");
framework_add_hook ( "ivr_operator_answer", "ivr_operator_answer");
framework_add_hook ( "ivr_operator_wait", "ivr_operator_wait");
framework_add_hook ( "ivr_operator_hangup", "ivr_operator_hangup");
framework_add_hook ( "ivr_operator_time", "ivr_operator_time");
framework_add_hook ( "ivr_operator_date", "ivr_operator_date");
framework_add_hook ( "ivr_operator_weekday", "ivr_operator_weekday");
framework_add_hook ( "ivr_operator_play", "ivr_operator_play");
framework_add_hook ( "ivr_operator_record", "ivr_operator_record");
framework_add_hook ( "ivr_operator_stop", "ivr_operator_stop");
framework_add_hook ( "ivr_operator_read", "ivr_operator_read");
framework_add_hook ( "ivr_operator_menu", "ivr_operator_menu");
framework_add_hook ( "ivr_operator_router", "ivr_operator_router");
framework_add_hook ( "ivr_operator_setvar", "ivr_operator_setvar");
framework_add_hook ( "ivr_operator_dial", "ivr_operator_dial");
framework_add_hook ( "ivr_operator_redirect", "ivr_operator_redirect");
framework_add_hook ( "ivr_operator_script", "ivr_operator_script");
framework_add_hook ( "ivr_operator_email", "ivr_operator_email");

/**
 * Function to implement start IVR operator.
 * Required parameters are: (boolean) AutoAnswer, (int) Delay
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_start ( $buffer, $parameters)
{
  if ( $parameters["Properties"]["AutoAnswer"])
  {
    $buffer .= " same => n,Wait(" . (int) $parameters["Properties"]["Delay"] . ")\n";
    $buffer .= " same => n,Answer()\n";
  }
  $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";

  return $buffer;
}

/**
 * Function to implement answer IVR operator.
 * Required parameters are: none
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_answer ( $buffer, $parameters)
{
  $buffer .= " same => n,Answer()\n";
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement wait IVR operator.
 * Required parameters are: (int) Time
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_wait ( $buffer, $parameters)
{
  $buffer .= " same => n,Wait(" . (int) $parameters["Properties"]["Time"] . ")\n";
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement hangup IVR operator.
 * Required parameters are: none
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_hangup ( $buffer, $parameters)
{
  $buffer .= " same => n,HangUp()\n";

  return $buffer;
}

/**
 * Function to implement time IVR operator.
 * Required parameters are: (array) Conditions[(string) Start, (string) Finish]
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_time ( $buffer, $parameters)
{
  $x = 0;
  foreach ( $parameters["Properties"]["Conditions"] as $condition)
  {
    $x++;
    if ( preg_match ( "/^([01][0-9]|2[0-4]):[0-5][0-9]$/", $condition["Start"]) && preg_match ( "/^([01][0-9]|2[0-4]):[0-5][0-9]$/", $condition["Finish"]) && array_key_exists ( "cond_" . $x, $parameters["Links"]))
    {
      $buffer .= " same => n,GoToIfTime(" . $condition["Start"] . "-" . $condition["Finish"] . ",*,*,*?" . $parameters["Links"]["cond_" . $x] . ")\n";
    } else {
      writeLog ( "IVR \"time\" operator \"Start\" or \"Finish\" time for condition " . $x . " are invalid!", VoIP_LOG_WARNING);
    }
  }
  if ( array_key_exists ( "other", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["other"] .")\n";
  }

  return $buffer;
}

/**
 * Function to implement date IVR operator.
 * Required parameters are: (array) Conditions[(string) Start, (string) Finish]
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_date ( $buffer, $parameters)
{
  $x = 0;
  foreach ( $parameters["Properties"]["Conditions"] as $condition)
  {
    $x++;
    if ( preg_match ( "/^[1-2][0-9]{3}-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])$/", $condition["Start"]) && preg_match ( "/^[1-2][0-9]{3}-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])$/", $condition["Finish"]))
    {
      $buffer .= " same => n,GoToIf(\$[\$[\${EPOCH} >= \${STRPTIME(" . $condition["Start"] . " 00:00:00,,%Y-%m-%d %H:%M:%S)}] & \$[\${EPOCH} <= \${STRPTIME(" . $condition["Finish"] . " 23:59:59,,%Y-%m-%d %H:%M:%S)}]]]?" . $parameters["Links"]["cond_" . $x] . ")\n";
    } else {
      writeLog ( "IVR \"date\" operator \"Start\" or \"Finish\" date for condition " . $x . " are invalid!", VoIP_LOG_WARNING);
    }
  }

  return $buffer;
}

/**
 * Function to implement weekday IVR operator.
 * Required parameters are: none
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_weekday ( $buffer, $parameters)
{
  foreach ( $parameters["Links"] as $label => $link)
  {
    switch ( $label)
    {
      case "sunday":
      case "monday":
      case "tuesday":
      case "wednesday":
      case "thursday":
      case "friday":
      case "saturday":
        $buffer .= " same => n,GoToIfTime(*," . substr ( $label, 0, 3) . ",*,*?" . $link . ")\n";
        break;
      default:
        writeLog ( "IVR \"weekday\" operator with invalid link label \"" . $label . "\"!", VoIP_LOG_WARNING);
        break;
    }
  }

  return $buffer;
}

/**
 * Function to implement play IVR operator.
 * Required parameters are: (string) Filename
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_play ( $buffer, $parameters)
{
  $buffer .= " same => n,PlayBack(" . basename ( $parameters["Properties"]["Filename"]) . ")\n";
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement record IVR operator.
 * Required parameters are: (string) Filename
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function 
 * @return string Asterisk dialplan code
 */
function ivr_operator_record ( $buffer, $parameters)
{
  $parameters["Properties"]["Filename"] = operator_apply_variables ( $parameters["Properties"]["Filename"]);
  $buffer .= " same => n,MixMonitor(\${STRREPLACE(\${STRREPLACE(" . basename ( $parameters["Properties"]["Filename"]) . ",\"/\",\"\")},\",\",\"\")},a)\n";
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement stop IVR operator.
 * Required parameters are: (boolean) Discard
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_stop ( $buffer, $parameters)
{
  $buffer .= " same => n,StopMixMonitor()\n";
  if ( $parameters["Properties"]["Discard"])
  {
    $buffer .= " same => n,System(rm -f \"\${MIXMONITOR_FILENAME}\")\n";
  }
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement read IVR operator.
 * Required parameters are: (string) Variable, (int) Digits, (int) Timeout, (boolean) Echo
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_read ( $buffer, $parameters)
{
  $buffer .= " same => n,GoSub(VoIPDomain-Tools,get_digits,1(vd_ivr_var_" . $parameters["Properties"]["Variable"] . ",," . $parameters["Properties"]["Digits"] . "," . $parameters["Properties"]["Timeout"] . ",yes," . ( $parameters["Properties"]["Echo"] ? "yes" : "no") . ",vd_ivr_status))\n";
  if ( array_key_exists ( "timedout", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_status}\" = \"TIMEOUT\"]?" . $parameters["Links"]["timedout"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement menu IVR operator.
 * Required parameters are: (string) Variable, (string) Filename, (string) InvalidFilename, (boolean) Echo, (int) Timeout, (int) Retries, (boolean) Option0, (boolean) Option1, (boolean) Option2, (boolean) Option3, (boolean) Option4, (boolean) Option5, (boolean) Option6, (boolean) Option7, (boolean) Option8, (boolean) Option9, (boolean) OptionA, (boolean) OptionS
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_menu ( $buffer, $parameters)
{
  $buffer .= " same => n,Set(audio=" . $parameters["Properties"]["Filename"] . ")\n";
  $buffer .= " same => n,Set(tries=0)\n";
  $buffer .= " same => n(" . $parameters["ID"] . "_read,Read(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "),\${audio},1,s,1," . (int) $parameters["Properties"]["Timeout"] . ")\n";
  if ( $parameters["Properties"]["Option0"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"0\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["Option1"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"1\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["Option2"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"2\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["Option3"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"3\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["Option4"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"4\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["Option5"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"5\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["Option6"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"6\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["Option7"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"7\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["Option8"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"8\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["Option9"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"9\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["OptionA"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"*\"]?" . $parameters["ID"] . "_valid)\n";
  }
  if ( $parameters["Properties"]["OptionS"])
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"#\"]?" . $parameters["ID"] . "_valid)\n";
  }
  $buffer .= " same => n,Set(audio=" . $parameters["Properties"]["InvalidFilename"] . ")\n";
  $buffer .= " same => n,Set(tries=\$[\${tries} + 1])\n";
  $buffer .= " same => n,GoToIf(\$[\${tries} <= " . (int) $parameters["Properties"]["Retries"] . "]?" . $parameters["ID"] . "_read)\n";
  $buffer .= " same => n,Set(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "=)\n";
  if ( array_key_exists ( "timedout", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"\"]?" . $parameters["Links"]["timedout"] . ")\n";
  }
  $buffer .= " same => n,HangUp()\n";
  $buffer .= " same => n(" . $parameters["ID"] . "_valid),NoOp()\n";
  if ( $parameters["Properties"]["Echo"])
  {
    $buffer .= " same => n,PlayBack(digits/\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "})\n";
  }
  if ( array_key_exists ( "option0", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"0\"]?" . $parameters["Links"]["option0"] . ")\n";
  }
  if ( array_key_exists ( "option1", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"1\"]?" . $parameters["Links"]["option1"] . ")\n";
  }
  if ( array_key_exists ( "option2", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"2\"]?" . $parameters["Links"]["option2"] . ")\n";
  }
  if ( array_key_exists ( "option3", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"3\"]?" . $parameters["Links"]["option3"] . ")\n";
  }
  if ( array_key_exists ( "option4", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"4\"]?" . $parameters["Links"]["option4"] . ")\n";
  }
  if ( array_key_exists ( "option5", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"5\"]?" . $parameters["Links"]["option5"] . ")\n";
  }
  if ( array_key_exists ( "option6", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"6\"]?" . $parameters["Links"]["option6"] . ")\n";
  }
  if ( array_key_exists ( "option7", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"7\"]?" . $parameters["Links"]["option7"] . ")\n";
  }
  if ( array_key_exists ( "option8", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"8\"]?" . $parameters["Links"]["option8"] . ")\n";
  }
  if ( array_key_exists ( "option9", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"9\"]?" . $parameters["Links"]["option9"] . ")\n";
  }
  if ( array_key_exists ( "optiona", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"*\"]?" . $parameters["Links"]["optiona"] . ")\n";
  }
  if ( array_key_exists ( "options", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" = \"#\"]?" . $parameters["Links"]["options"] . ")\n";
  }

  return $buffer;
}     

/**
 * Function to implement router IVR operator.
 * Required parameters are: (string) Variable, (array) Conditions[(string) Condition, (string) Value]
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_router ( $buffer, $parameters)
{
  $x = 0;
  foreach ( $parameters["Properties"]["Conditions"] as $condition)
  {
    $x++;
    $buffer .= " same => n,GoToIf(\$[";
    switch ( $condition["Condition"])
    {
      case "=":
      case "!=":
      case "<":
      case "<=":
      case ">":
      case ">=":
        $buffer .= "\"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\" " . $condition["Condition"] . " \"" . $condition["Value"] . "\"";
        break;
      case "in":
        $buffer .= "\"\${STRREPLACE(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "," . $condition["Value"] . ")}\" != \"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\"";
        break;
      case "notin":
        $buffer .= "\"\${STRREPLACE(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "," . $condition["Value"] . ")}\" = \"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\"";
        break;
      default:
        writeLog ( "IVR \"router\" operator with invalid condition \"" . $condition["Condition"] . "\" at condition " . $x . "!", VoIP_LOG_WARNING);
        $buffer .= "1=0";
        break;
    }
    $buffer .= "]?" . $parameters["Links"]["true"] . ")\n";
  }
  $buffer .= " same => n,GoTo(" . $parameters["Links"]["false"] . ")\n";

  return $buffer;
}     

/**
 * Function to implement setvar IVR operator.
 * Required parameters are: (string) Variable, (string) Value
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_setvar ( $buffer, $parameters)
{
  $buffer .= " same => n,Set(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "=" . $parameters["Properties"]["Value"] . ")\n";

  return $buffer;
}     

/**
 * Function to implement dial IVR operator.
 * Required parameters are: (string) Destination, (int) Timeout
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_dial ( $buffer, $parameters)
{
  $buffer .= " same => n,Dial(LOCAL/\${FILTER(\"0-9\"," . operator_apply_variables ( $parameters["Properties"]["Destination"], array ( "{var_VARIABLE}")) . ")}@VoIPDomain-extensions," . (int) $parameters["Properties"]["Timeout"] . ",g)\n";
  if ( array_key_exists ( "ok", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${DIALSTATUS}\" = \"ANSWER\"]?" . $parameters["Links"]["ok"] . ")\n";
  }
  if ( array_key_exists ( "timedout", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${DIALSTATUS}\" = \"NOANSWER\"]?" . $parameters["Links"]["timedout"] . ")\n";
  }
  if ( array_key_exists ( "busy", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${DIALSTATUS}\" = \"BUSY\"]?" . $parameters["Links"]["busy"] . ")\n";
  }
  if ( array_key_exists ( "refused", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\$[\"\${DIALSTATUS}\" = \"DONTCALL\"] | \$[\"\${DIALSTATUS}\" = \"TORTURE\"] | \$[\"\${DIALSTATUS}\" = \"CONGESTION\"] | \$[\"\${DIALSTATUS}\" = \"CHANUNAVAIL\"] | \$[\"\${DIALSTATUS}\" = \"CANCEL\"]]?" . $parameters["Links"]["refused"] . ")\n";
  }

  return $buffer;
}     

/**
 * Function to implement script IVR operator.
 * Required parameters are: (string) Name, (string) Variable, (array) Parameters[(string) *]
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_script ( $buffer, $parameters)
{
  $buffer .= " same => n,Set(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "=\${SHELL(/usr/share/asterisk/agi-bin/" . basename ( $parameters["Properties"]["Name"]);
  foreach ( $parameters["Parameters"] as $parameter)
  {
    $buffer .= " \"" . str_replace ( "\$", "\\\$", str_replace ( "\"", "\\\"", str_replace ( "\\", "\\\\", str_replace ( chr ( 0x13), "", str_replace ( chr ( 0x10), "", $parameter)))));
  }
  $buffer .= ")})\n";
  $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";

  return $buffer;
}     

/**
 * Function to implement email IVR operator.
 * Required parameters are: (string) To, (string) Subject, (string) Body
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_email ( $buffer, $parameters)
{
  $buffer .= " same => n,Set(vd_ivr_mailfile=mailbody-${EPOCH}.txt)\n";
  $buffer .= " same => n,System(echo \"" . str_replace ( "\$", "\\\$", str_replace ( "\\", "\\\\", str_replace ( chr ( 0x13), "\\n", str_replace ( "\"", "\\\"", $parameters["Properties"]["Body"])))) . "\" | sed \"s/\\\\n/\\n/g\" > /tmp/\${vd_ivr_mailfile})\n";
  $buffer .= " same => n,System(mail -s \"" . str_replace ( "\$", "\\\$", str_replace ( "\\", "\\\\", str_replace ( "\"", "\\\"", $parameters["Properties"]["Subject"]))) . "\" " . $parameters["Properties"]["To"] . " < /tmp/\${vd_ivr_mailfile})\n";
  $buffer .= " same => n,System(rm -f /tmp/\${vd_ivr_mailfile})\n";
  $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";

  return $buffer;
}     
?>
