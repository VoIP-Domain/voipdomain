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
 * VoIP Domain IVRs actions operators hooks.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage IVRs
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Resolve an audio ID to its Asterisk playback path.
 * Reads the local audio datafile and returns "audio-{ID}/{filename_without_ext}".
 * Returns false if the audio datafile is not found.
 */
function ivr_resolve_audio_path ( $audioID)
{
  $data = read_config ( "datafile", "audio-" . $audioID);
  if ( ! is_array ( $data) || ! array_key_exists ( "Filename", $data))
  {
    writeLog ( "ivr_resolve_audio_path(" . $audioID . ") - Audio datafile not found or missing Filename.", VoIP_LOG_WARNING);
    return false;
  }
  $filename = substr ( $data["Filename"], 0, strrpos ( $data["Filename"], "."));
  return "voipdomain/audio-" . $audioID . "/" . $filename;
}

/**
 * API call hooks
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
framework_add_hook ( "ivr_operator_queue", "ivr_operator_queue");
framework_add_hook ( "ivr_operator_voicemail", "ivr_operator_voicemail");
framework_add_hook ( "ivr_operator_say", "ivr_operator_say");
framework_add_hook ( "ivr_operator_gotoivr", "ivr_operator_gotoivr");
framework_add_hook ( "ivr_operator_calleridcheck", "ivr_operator_calleridcheck");
framework_add_hook ( "ivr_operator_http", "ivr_operator_http");
framework_add_hook ( "ivr_operator_conference", "ivr_operator_conference");
framework_add_hook ( "ivr_operator_callback", "ivr_operator_callback");

/**
 * Function to implement start IVR operator.
 * Required parameters are: (bool) AutoAnswer, (int) Delay
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_start ( $buffer, $parameters)
{
  $buffer .= " same => n,Ringing()\n";
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
  if ( array_key_exists ( "other", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["other"] . ")\n";
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
 * Required parameters are: (string) File (audio ID)
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_play ( $buffer, $parameters)
{
  $audioPath = ivr_resolve_audio_path ( $parameters["Properties"]["File"]);
  if ( $audioPath === false)
  {
    $buffer .= " same => n,Verbose(1,IVR ERROR: Audio ID " . $parameters["Properties"]["File"] . " not found)\n";
  } else {
    if ( ! empty ( $parameters["Properties"]["Interruptible"]))
    {
      $buffer .= " same => n,Background(" . $audioPath . ")\n";
    } else {
      $buffer .= " same => n,PlayBack(" . $audioPath . ")\n";
    }
  }
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
 * Required parameters are: (bool) Discard
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
 * Required parameters are: (string) Variable, (int) Digits, (int) Timeout, (bool) Echo
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
  if ( array_key_exists ( "ok", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["ok"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement menu IVR operator.
 * Required parameters are: (string) Variable, (string) Filename, (string) InvalidFilename, (bool) Echo, (int) Timeout, (int) Retries, (bool) Option0, (bool) Option1, (bool) Option2, (bool) Option3, (bool) Option4, (bool) Option5, (bool) Option6, (bool) Option7, (bool) Option8, (bool) Option9, (bool) OptionA, (bool) OptionS
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_menu ( $buffer, $parameters)
{
  $audioPath = ivr_resolve_audio_path ( $parameters["Properties"]["File"]);
  if ( $audioPath === false)
  {
    $audioPath = "invalid-audio";
  }
  $buffer .= " same => n,Set(audio=" . $audioPath . ")\n";
  $buffer .= " same => n,Set(tries=0)\n";
  $buffer .= " same => n(" . $parameters["ID"] . "_read),Read(vd_ivr_var_" . $parameters["Properties"]["Variable"] . ",\${audio},1,s,1," . (int) $parameters["Properties"]["Timeout"] . ")\n";
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
  $invalidAudioPath = ivr_resolve_audio_path ( $parameters["Properties"]["InvalidFile"]);
  if ( $invalidAudioPath === false)
  {
    $invalidAudioPath = "invalid-audio";
  }
  $buffer .= " same => n,Set(audio=" . $invalidAudioPath . ")\n";
  $buffer .= " same => n,Set(tries=\$[\${tries} + 1])\n";
  $buffer .= " same => n,GoToIf(\$[\${tries} <= " . (int) $parameters["Properties"]["Retries"] . "]?" . $parameters["ID"] . "_read)\n";
  $buffer .= " same => n,Set(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "=)\n";
  if ( array_key_exists ( "maxretries", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["maxretries"] . ")\n";
  } else if ( array_key_exists ( "timedout", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["timedout"] . ")\n";
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
  $logic = ( array_key_exists ( "Logic", $parameters["Properties"]) && $parameters["Properties"]["Logic"] == "and") ? "and" : "or";
  $x = 0;

  if ( $logic == "and")
  {
    $buffer .= " same => n,Set(vd_ivr_router_match=1)\n";
    foreach ( $parameters["Properties"]["Conditions"] as $condition)
    {
      $x++;
      $buffer .= " same => n,GoToIf(\$[";
      $buffer .= ivr_operator_router_condition ( $parameters["Properties"]["Variable"], $condition, $x);
      $buffer .= "]?" . $parameters["ID"] . "_and_" . $x . ")\n";
      $buffer .= " same => n,Set(vd_ivr_router_match=0)\n";
      $buffer .= " same => n(" . $parameters["ID"] . "_and_" . $x . "),NoOp()\n";
    }
    if ( array_key_exists ( "true", $parameters["Links"]))
    {
      $buffer .= " same => n,GoToIf(\$[\${vd_ivr_router_match} = 1]?" . $parameters["Links"]["true"] . ")\n";
    }
    if ( array_key_exists ( "false", $parameters["Links"]))
    {
      $buffer .= " same => n,GoTo(" . $parameters["Links"]["false"] . ")\n";
    }
  } else {
    foreach ( $parameters["Properties"]["Conditions"] as $condition)
    {
      $x++;
      $buffer .= " same => n,GoToIf(\$[";
      $buffer .= ivr_operator_router_condition ( $parameters["Properties"]["Variable"], $condition, $x);
      $buffer .= "]?" . $parameters["Links"]["true"] . ")\n";
    }
    if ( array_key_exists ( "false", $parameters["Links"]))
    {
      $buffer .= " same => n,GoTo(" . $parameters["Links"]["false"] . ")\n";
    }
  }

  return $buffer;
}

/**
 * Helper to generate a single router condition expression.
 *
 * @param string $variable The variable name
 * @param array $condition The condition array with Condition and Value keys
 * @param int $index The condition index for logging
 * @return string Asterisk expression
 */
function ivr_operator_router_condition ( $variable, $condition, $index)
{
  switch ( $condition["Condition"])
  {
    case "=":
    case "!=":
    case "<":
    case "<=":
    case ">":
    case ">=":
      return "\"\${vd_ivr_var_" . $variable . "}\" " . $condition["Condition"] . " \"" . $condition["Value"] . "\"";
    case "in":
      return "\"\${STRREPLACE(vd_ivr_var_" . $variable . "," . $condition["Value"] . ")}\" != \"\${vd_ivr_var_" . $variable . "}\"";
    case "notin":
      return "\"\${STRREPLACE(vd_ivr_var_" . $variable . "," . $condition["Value"] . ")}\" = \"\${vd_ivr_var_" . $variable . "}\"";
    case "regex":
      return "\${REGEX(\"" . $condition["Value"] . "\" \${vd_ivr_var_" . $variable . "})}";
    default:
      writeLog ( "IVR \"router\" operator with invalid condition \"" . $condition["Condition"] . "\" at condition " . $index . "!", VoIP_LOG_WARNING);
      return "1=0";
  }
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
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

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
  if ( ! empty ( $parameters["Properties"]["CallerIDName"]))
  {
    $buffer .= " same => n,Set(CALLERID(name)=" . operator_apply_variables ( $parameters["Properties"]["CallerIDName"]) . ")\n";
  }
  if ( ! empty ( $parameters["Properties"]["CallerIDNum"]))
  {
    $buffer .= " same => n,Set(CALLERID(num)=" . operator_apply_variables ( $parameters["Properties"]["CallerIDNum"]) . ")\n";
  }
  $dialopts = "g";
  if ( ! empty ( $parameters["Properties"]["MOHClass"]))
  {
    $dialopts .= "m(" . $parameters["Properties"]["MOHClass"] . ")";
  }
  $buffer .= " same => n,Dial(LOCAL/\${FILTER(\"0-9\"," . operator_apply_variables ( ( array_key_exists ( "Destination", $parameters["Properties"]) ? $parameters["Properties"]["Destination"] : ""), array ( "{var_VARIABLE}")) . ")}@VoIPDomain-extensions," . (int) ( array_key_exists ( "Timeout", $parameters["Properties"]) ? $parameters["Properties"]["Timeout"] : 30) . "," . $dialopts . ")\n";
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
 * Function to implement redirect IVR operator.
 * Required parameters are: (string) Destination
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_redirect ( $buffer, $parameters)
{
  $destination = operator_apply_variables ( $parameters["Properties"]["Destination"], array ( "{var_VARIABLE}"));
  $buffer .= " same => n,Dial(LOCAL/\${FILTER(0-9," . $destination . ")}@VoIPDomain-extensions,,g)\n";
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
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
  if ( is_array ( $parameters["Properties"]["Parameters"]))
  {
    foreach ( $parameters["Properties"]["Parameters"] as $parameter)
    {
      $buffer .= " \"" . str_replace ( "\$", "\\\$", str_replace ( "\"", "\\\"", str_replace ( "\\", "\\\\", str_replace ( chr ( 0x13), "", str_replace ( chr ( 0x10), "", $parameter)))));
    }
  }
  $buffer .= ")})\n";
  $buffer .= " same => n,Set(vd_ivr_script_exitcode=\${SHELL_EXIT_CODE})\n";
  if ( array_key_exists ( "error", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\${vd_ivr_script_exitcode} != 0]?" . $parameters["Links"]["error"] . ")\n";
  }
  if ( array_key_exists ( "ok", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["ok"] . ")\n";
  }

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
  $to = operator_apply_variables ( $parameters["Properties"]["To"]);
  $subject = operator_apply_variables ( $parameters["Properties"]["Subject"]);
  $body = operator_apply_variables ( $parameters["Properties"]["Body"]);
  $buffer .= " same => n,Set(vd_ivr_mailfile=mailbody-\${EPOCH}.txt)\n";
  $buffer .= " same => n,System(echo \"" . str_replace ( "\$", "\\\$", str_replace ( "\\", "\\\\", str_replace ( chr ( 0x13), "\\n", str_replace ( "\"", "\\\"", $body)))) . "\" | sed \"s/\\\\n/\\n/g\" > /tmp/\${vd_ivr_mailfile})\n";
  $buffer .= " same => n,System(mail -s \"" . str_replace ( "\$", "\\\$", str_replace ( "\\", "\\\\", str_replace ( "\"", "\\\"", $subject))) . "\" " . $to . " < /tmp/\${vd_ivr_mailfile})\n";
  $buffer .= " same => n,System(rm -f /tmp/\${vd_ivr_mailfile})\n";
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement queue IVR operator.
 * Required parameters are: (string) Queue, (string) MOHClass, (bool) AnnouncePosition, (int) Timeout, (int) MaxCallers
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_queue ( $buffer, $parameters)
{
  $qopts = "";
  if ( ! empty ( $parameters["Properties"]["MOHClass"]))
  {
    $buffer .= " same => n,Set(CHANNEL(musicclass)=" . $parameters["Properties"]["MOHClass"] . ")\n";
  }
  if ( $parameters["Properties"]["AnnouncePosition"])
  {
    $qopts .= "n";
  }
  $timeout = ( ! empty ( $parameters["Properties"]["Timeout"]) ? (int) $parameters["Properties"]["Timeout"] : "");
  $maxcallers = ( ! empty ( $parameters["Properties"]["MaxCallers"]) ? (int) $parameters["Properties"]["MaxCallers"] : "");
  $buffer .= " same => n,Queue(" . $parameters["Properties"]["Queue"] . "," . $qopts . ",,," . $timeout . ",,,,," . $maxcallers . ")\n";
  if ( array_key_exists ( "answered", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${QUEUESTATUS}\" = \"COMPLETED\"]?" . $parameters["Links"]["answered"] . ")\n";
  }
  if ( array_key_exists ( "timeout", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${QUEUESTATUS}\" = \"TIMEOUT\"]?" . $parameters["Links"]["timeout"] . ")\n";
  }
  if ( array_key_exists ( "full", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\"\${QUEUESTATUS}\" = \"FULL\"]?" . $parameters["Links"]["full"] . ")\n";
  }
  if ( array_key_exists ( "joinempty", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\$[\"\${QUEUESTATUS}\" = \"JOINEMPTY\"] | \$[\"\${QUEUESTATUS}\" = \"LEAVEEMPTY\"]]?" . $parameters["Links"]["joinempty"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement voicemail IVR operator.
 * Required parameters are: (string) Mailbox, (string) Greeting
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_voicemail ( $buffer, $parameters)
{
  $mailbox = operator_apply_variables ( $parameters["Properties"]["Mailbox"], array ( "{var_VARIABLE}"));
  $opts = "";
  switch ( $parameters["Properties"]["Greeting"])
  {
    case "busy":
      $opts = "b";
      break;
    case "unavailable":
      $opts = "u";
      break;
    case "skip":
      $opts = "s";
      break;
  }
  $buffer .= " same => n,VoiceMail(" . $mailbox . "@VoIPDomain," . $opts . ")\n";
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement say IVR operator.
 * Required parameters are: (string) Type, (string) Variable
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_say ( $buffer, $parameters)
{
  $source = "\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}";
  switch ( $parameters["Properties"]["Type"])
  {
    case "digits":
      $buffer .= " same => n,SayDigits(" . $source . ")\n";
      break;
    case "number":
      $buffer .= " same => n,SayNumber(" . $source . ")\n";
      break;
    case "alpha":
      $buffer .= " same => n,SayAlpha(" . $source . ")\n";
      break;
    case "datetime":
      $format = ( ! empty ( $parameters["Properties"]["Format"]) ? $parameters["Properties"]["Format"] : "ABdY \\'digits/at\\' IMp");
      $buffer .= " same => n,SayUnixTime(" . $source . ",," . $format . ")\n";
      break;
    default:
      writeLog ( "IVR \"say\" operator with invalid type \"" . $parameters["Properties"]["Type"] . "\"!", VoIP_LOG_WARNING);
      break;
  }
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement goto_ivr IVR operator.
 * Required parameters are: (string) IVRName, (bool) Return
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_gotoivr ( $buffer, $parameters)
{
  if ( $parameters["Properties"]["Return"])
  {
    $buffer .= " same => n,GoSub(vd_ivr_" . $parameters["Properties"]["IVRName"] . ",\${EXTEN},1)\n";
    if ( array_key_exists ( "output", $parameters["Links"]))
    {
      $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
    }
  } else {
    $buffer .= " same => n,GoTo(vd_ivr_" . $parameters["Properties"]["IVRName"] . ",\${EXTEN},1)\n";
  }

  return $buffer;
}

/**
 * Function to implement callerid_check IVR operator.
 * Required parameters are: (string) CheckType, (string) Pattern
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_calleridcheck ( $buffer, $parameters)
{
  switch ( $parameters["Properties"]["CheckType"])
  {
    case "blocklist":
      $buffer .= " same => n,GoToIf(\${DB_EXISTS(vd_blocked/\${CALLERID(num)})}?" . ( array_key_exists ( "match", $parameters["Links"]) ? $parameters["Links"]["match"] : $parameters["ID"] . "_nomatch") . ")\n";
      break;
    case "exceptions":
      $buffer .= " same => n,GoToIf(\${DB_EXISTS(vd_exceptions/\${CALLERID(num)})}?" . ( array_key_exists ( "match", $parameters["Links"]) ? $parameters["Links"]["match"] : $parameters["ID"] . "_nomatch") . ")\n";
      break;
    case "regex":
      $pattern = ! empty ( $parameters["Properties"]["Pattern"]) ? $parameters["Properties"]["Pattern"] : ".*";
      $buffer .= " same => n,GoToIf(\${REGEX(\"" . $pattern . "\" \${CALLERID(num)})}?" . ( array_key_exists ( "match", $parameters["Links"]) ? $parameters["Links"]["match"] : $parameters["ID"] . "_nomatch") . ")\n";
      break;
    case "variable":
      $buffer .= " same => n,GoToIf(\$[\"\${CALLERID(num)}\" = \"\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "}\"]?" . ( array_key_exists ( "match", $parameters["Links"]) ? $parameters["Links"]["match"] : $parameters["ID"] . "_nomatch") . ")\n";
      break;
    default:
      writeLog ( "IVR \"callerid_check\" operator with invalid check type \"" . $parameters["Properties"]["CheckType"] . "\"!", VoIP_LOG_WARNING);
      break;
  }
  $buffer .= " same => n(" . $parameters["ID"] . "_nomatch),NoOp()\n";
  if ( array_key_exists ( "nomatch", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["nomatch"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement http IVR operator.
 * Required parameters are: (string) URL, (string) Method, (string) Variable, (int) Timeout
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_http ( $buffer, $parameters)
{
  $url = operator_apply_variables ( $parameters["Properties"]["URL"]);
  $timeout = ( ! empty ( $parameters["Properties"]["Timeout"]) ? (int) $parameters["Properties"]["Timeout"] : 5);
  $buffer .= " same => n,Set(CURLOPT(conntimeout)=" . $timeout . ")\n";
  $buffer .= " same => n,Set(CURLOPT(dnstimeout)=" . $timeout . ")\n";
  if ( ! empty ( $parameters["Properties"]["Headers"]) && is_array ( $parameters["Properties"]["Headers"]))
  {
    foreach ( $parameters["Properties"]["Headers"] as $header)
    {
      if ( ! empty ( $header["Name"]) && ! empty ( $header["Value"]))
      {
        $buffer .= " same => n,Set(CURLOPT(httpheader)=" . operator_apply_variables ( $header["Name"]) . ": " . operator_apply_variables ( $header["Value"]) . ")\n";
      }
    }
  }
  if ( strtoupper ( $parameters["Properties"]["Method"]) == "POST")
  {
    $body = operator_apply_variables ( ! empty ( $parameters["Properties"]["Body"]) ? $parameters["Properties"]["Body"] : "");
    $buffer .= " same => n,Set(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "=\${CURL(" . $url . "," . $body . ")})\n";
  } else {
    $buffer .= " same => n,Set(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "=\${CURL(" . $url . ")})\n";
  }
  if ( array_key_exists ( "error", $parameters["Links"]))
  {
    $buffer .= " same => n,GoToIf(\$[\${LEN(\${vd_ivr_var_" . $parameters["Properties"]["Variable"] . "})} = 0]?" . $parameters["Links"]["error"] . ")\n";
  }
  if ( array_key_exists ( "ok", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["ok"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement conference IVR operator.
 * Required parameters are: (string) Room, (string) PIN, (string) Profile
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_conference ( $buffer, $parameters)
{
  $room = operator_apply_variables ( $parameters["Properties"]["Room"], array ( "{var_VARIABLE}"));
  $profile = ( ! empty ( $parameters["Properties"]["Profile"]) ? $parameters["Properties"]["Profile"] : "default_bridge");
  if ( ! empty ( $parameters["Properties"]["PIN"]))
  {
    $buffer .= " same => n,Set(CONFBRIDGE(bridge,pin)=" . $parameters["Properties"]["PIN"] . ")\n";
  }
  if ( ! empty ( $parameters["Properties"]["MOHClass"]))
  {
    $buffer .= " same => n,Set(CONFBRIDGE(bridge,sound_join)=" . $parameters["Properties"]["MOHClass"] . ")\n";
  }
  $buffer .= " same => n,ConfBridge(" . $room . ",," . $profile . ")\n";
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}

/**
 * Function to implement callback IVR operator.
 * Required parameters are: (string) Queue, (string) Variable
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Asterisk dialplan code
 */
function ivr_operator_callback ( $buffer, $parameters)
{
  $buffer .= " same => n,Set(vd_ivr_callback_number=\${FILTER(\"0-9\",\${CALLERID(num)})})\n";
  $buffer .= " same => n,Set(vd_ivr_callback_time=\${EPOCH})\n";
  if ( ! empty ( $parameters["Properties"]["Variable"]))
  {
    $buffer .= " same => n,Set(vd_ivr_var_" . $parameters["Properties"]["Variable"] . "=\${vd_ivr_callback_number})\n";
  }
  $buffer .= " same => n,Set(DB(vd_callbacks/\${vd_ivr_callback_number})=\${vd_ivr_callback_time}" . ( ! empty ( $parameters["Properties"]["Queue"]) ? "|" . $parameters["Properties"]["Queue"] : "") . ")\n";
  if ( ! empty ( $parameters["Properties"]["NotifyEmail"]))
  {
    $email = operator_apply_variables ( $parameters["Properties"]["NotifyEmail"]);
    $buffer .= " same => n,System(echo \"Callback requested by \${vd_ivr_callback_number} at \${STRFTIME(\${EPOCH},,%Y-%m-%d %H:%M:%S)}" . ( ! empty ( $parameters["Properties"]["Queue"]) ? " for queue " . $parameters["Properties"]["Queue"] : "") . "\" | mail -s \"VoIP Domain - Callback Request\" " . $email . ")\n";
  }
  if ( array_key_exists ( "output", $parameters["Links"]))
  {
    $buffer .= " same => n,GoTo(" . $parameters["Links"]["output"] . ")\n";
  }

  return $buffer;
}
?>
