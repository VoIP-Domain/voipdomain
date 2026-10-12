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
 * VoIP Domain IVRs module configuration file.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage IVRs
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Be sure we've the IVR structure at global $_in variable
 */
if ( ! array_key_exists ( "ivr", $_in))
{
  $_in["ivr"] = array ();
}
if ( ! array_key_exists ( "operators", $_in["ivr"]))
{
  $_in["ivr"]["operators"] = array ();
}

/**
 * Add basic system IVR module operators
 */

/**
 * An IVR module operator will be an array with the keys:
 *
 * name => The name of the operator (lowercase, single word)
 * style => The style of the workflow title background. Could be: default, info, success, primary, warning or danger (bootstrap buttons styles classes)
 * outputs => Could be a number (will be exploded as key outX and title "Out X") or an array with key as output key and value as title
 * modal => A boolean if element has a modal form
 * icon => Font Awesome icon of the operator
 * title => The title of the operator
 * modaltitle => The title of the operator modal
 * modalcontent => The HTML code to be inserted into operator modal
 * modalfocus => The HTML object ID to be focused when opened the modal
 * modalshow => The javascript code to be executed upon requested the operator configuration modal open.
 *              Available variables to the executed code:
 *                * ivrBlock => The jQuery Flowchart operator data object
 *                * ivrData => The operator data object
 * modalsave => The javascript code to be executed upon requested the operator modal save and close button.
 *              Available variables to the executed code:
 *                * ivrBlock => The jQuery Flowchart operator data object. I.e., you can change input and output operators of the operator box here. (warning: this object will be updated on the jQuery Flowchart element)
 *                * ivrData => The operator data object to be saved into edited object
 * javascript => The javascript code to be executed when inserted the IVR operator
 * validation => The javascript code to validate the operator data. It must return the variable *result* with an empty string if no error found, or a string containing the errors found (one error per line).
 *               Available variables to the executed code:
 *                * ivrBlock => The jQuery Flowchart operator data object. I.e., you can change input and output operators of the operator box here. (warning: this object will be updated on the jQuery Flowchart element)
 *                * ivrData => The operator data object
 * requiredcss => An array using the system addcss () format of required CSS libraries required to the operator works
 * requiredjs => An array using the system addjs () format of required JS libraries required to the operator works
 */

/**
 * IVR time operator
 *
 * Implements the possibility to check current time on an array of start and finish times.
 */
$_in["ivr"]["operators"]["time"] = array (
  "name" => "time",
  "style" => "default",
  "outputs" => array (
                 "other" => __ ( "Other")
               ),
  "modal" => true,
  "icon" => "clock",
  "title" => __ ( "Time"),
  "modaltitle" => __ ( "Time options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_time_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-time-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"time-description\" id=\"workflow-dialog-time-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-time-start-_X_\" class=\"control-label col-xs-2\">" . __ ( "Conditions") . "</label>\n" .
                    "  <div class=\"col-xs-10 inputGroupContainer\">\n" .
                    "    <div class=\"input-group col-xs-12 form-time-conditions hidden\">\n" .
                    "      <input type=\"text\" name=\"time-start-_X_\" id=\"workflow-dialog-time-start-_X_\" class=\"form-control time-start\" placeholder=\"" . __ ( "Start time") . "\" />\n" .
                    "      <input type=\"text\" name=\"time-finish-_X_\" id=\"workflow-dialog-time-finish-_X_\" class=\"form-control time-finish\" placeholder=\"" . __ ( "Finish time") . "\" />\n" .
                    "      <span class=\"input-group-addon input-icon-button btn btn-error btn-deltimecondition\" disabled=\"disabled\"><i class=\"fa fa-minus\"></i></span>\n" .
                    "    </div>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\" style=\"margin-top: -15px;\">\n" .
                    "  <div class=\"col-xs-12\">\n" .
                    "    <span class=\"input-icon-button btn btn-success btn-addtimecondition pull-right\"><i class=\"fa fa-plus\"></i></span>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-time-description",
  "modalshow" => "$('#workflow-dialog-time-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#ivr_time_form .form-time-conditions:not(.hidden)').remove ();\n" .
                 "$('.btn-addtimecondition').data ( 'id', 0);\n" .
                 "if ( ivrData.Conditions && ivrData.Conditions.length > 0)\n" .
                 "{\n" .
                 "  for ( let x = 0; x < ivrData.Conditions.length; x++)\n" .
                 "  {\n" .
                 "    $('.btn-addtimecondition').trigger ( 'click');\n" .
                 "    $('#workflow-dialog-time-start-' + $('.btn-addtimecondition').data ( 'id')).val ( ivrData.Conditions[x].Start);\n" .
                 "    $('#workflow-dialog-time-finish-' + $('.btn-addtimecondition').data ( 'id')).val ( ivrData.Conditions[x].Finish);\n" .
                 "  }\n" .
                 "} else {\n" .
                 "  $('.btn-addtimecondition').trigger ( 'click');\n" .
                 "}\n" .
                 "if ( flowchart.mode == 'view')\n" .
                 "{\n" .
                 "  $(this).find ( 'span.btn-addtimecondition').parent ().css ( 'display', 'none');\n" .
                 "  $(this).find ( 'span.btn-deltimecondition:not(.hidden)').remove ();\n" .
                 "}\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-time-description').val ();\n" .
                 "ivrData.Conditions = new Array ();\n" .
                 "delete ( ivrBlock.properties.outputs);\n" .
                 "ivrBlock.properties.outputs = new Object ();\n" .
                 "ivrBlock.properties.outputs['other'] = new Object ();\n" .
                 "ivrBlock.properties.outputs['other'].label = '" .  __ ( "Other") . "';\n" .
                 "var counter = 0;\n" .
                 "$('#ivr_time_form .form-time-conditions:not(.hidden)').each ( function ()\n" .
                 "{\n" .
                 "  counter++;\n" .
                 "  var Condition = new Object ();\n" .
                 "  Condition.Start = $(this).find ( '.time-start').val ();\n" .
                 "  Condition.Finish = $(this).find ( '.time-finish').val ();\n" .
                 "  ivrData.Conditions.push ( Condition);\n" .
                 "  ivrBlock.properties.outputs['cond_' + counter] = new Object ();\n" .
                 "  ivrBlock.properties.outputs['cond_' + counter].label = '" . __ ( "Cond.") . " ' + counter;\n" .
                 "});\n",
  "javascript" => "$('.btn-addtimecondition').data ( 'id', 0).on ( 'click', function ( event)\n" .
                  "{\n" .
                  "  event && event.preventDefault ();\n" .
                  "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
                  "  $('<div class=\"input-group col-xs-12 form-time-conditions\" style=\"margin-bottom: 15px\">' + $('#ivr_time_form .form-time-conditions.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertAfter ( $('#ivr_time_form .form-time-conditions:last')).removeClass ( 'hidden');\n" .
                  "  $('#workflow-dialog-time-start-' + $(this).data ( 'id') + ',#workflow-dialog-time-finish-' + $(this).data ( 'id')).mask ( '00:00').datetimepicker ( { format: 'HH:mm'});\n" .
                  "  if ( $('#ivr_time_form .form-time-conditions:not(.hidden)').length != 0)\n" .
                  "  {\n" .
                  "    $('#ivr_time_form .btn-deltimecondition:not(.hidden)').removeAttr ( 'disabled');\n" .
                  "  } else {\n" .
                  "    $('#ivr_time_form .btn-deltimecondition:not(.hidden)').attr ( 'disabled', 'disabled');\n" .
                  "  }\n" .
                  "  $('#workflow-dialog-time-start-' + $(this).data ( 'id')).focus ();\n" .
                  "});\n" .
                  "$('#ivr_time_form').on ( 'click', '.btn-deltimecondition', function ()\n" .
                  "{\n" .
                  "  if ( $(this).attr ( 'disabled') != 'disabled' && $('#ivr_time_form .form-time-conditions:not(.hidden)').length > 1)\n" .
                  "  {\n" .
                  "    $(this).closest ( 'div.form-time-conditions').remove ();\n" .
                  "  }\n" .
                  "  if ( $('#ivr_time_form .form-time-conditions:not(.hidden)').length == 1)\n" .
                  "  {\n" .
                  "    $('#ivr_time_form .btn-deltimecondition:not(.hidden)').attr ( 'disabled', 'disabled');\n" .
                  "  }\n" .
                  "});\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#ivr_time_form .form-time-conditions:not(.hidden)').length == 0)\n" .
                  "{\n" .
                  "  result += '" . __ ( "You need at least one condition.") . "\\n';\n" .
                  "} else {\n" .
                  "  var counter = 0;\n" .
                  "  $('#ivr_time_form .form-time-conditions:not(.hidden)').each ( function ()\n" .
                  "  {\n" .
                  "    counter++;\n" .
                  "    if ( $(this).find ( '.time-start').val () == '' || ! moment ( $(this).find ( '.time-start').val (), 'hh:mm').isValid ())\n" .
                  "    {\n" .
                  "      result += sprintf ( '" . __ ( "Invalid start time of condition %d.") . "\\n', counter);\n" .
                  "    }\n" .
                  "    if ( $(this).find ( '.time-finish').val () == '' || ! moment ( $(this).find ( '.time-finish').val (), 'hh:mm').isValid ())\n" .
                  "    {\n" .
                  "      result += sprintf ( '" . __ ( "Invalid finish time of condition %d.") . "\\n', counter);\n" .
                  "    }\n" .
                  "    if ( moment ( $(this).find ( '.time-start').val (), 'hh:mm').isValid () && moment ( $(this).find ( '.time-finish').val (), 'hh:mm').isValid () && moment ( $(this).find ( '.time-start').val (), 'hh:mm') > moment ( $(this).find ( '.time-finish').val (), 'hh:mm'))\n" .
                  "    {\n" .
                  "      result += sprintf ( '" . __ ( "Start time later than finish time at condition %d.") . "\\n', counter);\n" .
                  "    }\n" .
                  "  });\n" .
                  "}\n",
  "requiredcss" => array (
                     array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap"))
                   ),
  "requiredjs" => array (
                    array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")),
                    array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ())
                  )
);

/**
 * IVR date operator
 *
 * Implements the possibility to check current date on an array of start and finish dates.
 */
$_in["ivr"]["operators"]["date"] = array (
  "name" => "date",
  "style" => "default",
  "outputs" => array (
                 "other" => __ ( "Other")
               ),
  "modal" => true,
  "icon" => "calendar-alt",
  "title" => __ ( "Date"),
  "modaltitle" => __ ( "Date options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_date_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-date-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"date-description\" id=\"workflow-dialog-date-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-date-start-_X_\" class=\"control-label col-xs-2\">" . __ ( "Conditions") . "</label>\n" .
                    "  <div class=\"col-xs-10 inputGroupContainer\">\n" .
                    "    <div class=\"input-group col-xs-12 form-date-conditions hidden\">\n" .
                    "      <input type=\"text\" name=\"date-start-_X_\" id=\"workflow-dialog-date-start-_X_\" class=\"form-control date-start\" placeholder=\"" . __ ( "Start date") . "\" />\n" .
                    "      <input type=\"text\" name=\"date-finish-_X_\" id=\"workflow-dialog-date-finish-_X_\" class=\"form-control date-finish\" placeholder=\"" . __ ( "Finish date") . "\" />\n" .
                    "      <span class=\"input-group-addon input-icon-button btn btn-error btn-deldatecondition\" disabled=\"disabled\"><i class=\"fa fa-minus\"></i></span>\n" .
                    "    </div>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\" style=\"margin-top: -15px;\">\n" .
                    "  <div class=\"col-xs-12\">\n" .
                    "    <span class=\"input-icon-button btn btn-success btn-adddatecondition pull-right\"><i class=\"fa fa-plus\"></i></span>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-date-description",
  "modalshow" => "$('#workflow-dialog-date-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#ivr_date_form .form-date-conditions:not(.hidden)').remove ();\n" .
                 "$('.btn-adddatecondition').data ( 'id', 0);\n" .
                 "if ( ivrData.Conditions && ivrData.Conditions.length > 0)\n" .
                 "{\n" .
                 "  for ( let x = 0; x < ivrData.Conditions.length; x++)\n" .
                 "  {\n" .
                 "    $('.btn-adddatecondition').trigger ( 'click');\n" .
                 "    $('#workflow-dialog-date-start-' + $('.btn-adddatecondition').data ( 'id')).val ( ivrData.Conditions[x].Start);\n" .
                 "    $('#workflow-dialog-date-finish-' + $('.btn-adddatecondition').data ( 'id')).val ( ivrData.Conditions[x].Finish);\n" .
                 "  }\n" .
                 "} else {\n" .
                 "  $('.btn-adddatecondition').trigger ( 'click');\n" .
                 "}\n" .
                 "if ( flowchart.mode == 'view')\n" .
                 "{\n" .
                 "  $(this).find ( 'span.btn-adddatecondition').parent ().css ( 'display', 'none');\n" .
                 "  $(this).find ( 'span.btn-deldatecondition:not(.hidden)').remove ();\n" .
                 "}\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-date-description').val ();\n" .
                 "ivrData.Conditions = new Array ();\n" .
                 "delete ( ivrBlock.properties.outputs);\n" .
                 "ivrBlock.properties.outputs = new Object ();\n" .
                 "ivrBlock.properties.outputs['other'] = new Object ();\n" .
                 "ivrBlock.properties.outputs['other'].label = '" .  __ ( "Other") . "';\n" .
                 "var counter = 0;\n" .
                 "$('#ivr_date_form .form-date-conditions:not(.hidden)').each ( function ()\n" .
                 "{\n" .
                 "  counter++;\n" .
                 "  var Condition = new Object ();\n" .
                 "  Condition.Start = $(this).find ( '.date-start').val ();\n" .
                 "  Condition.Finish = $(this).find ( '.date-finish').val ();\n" .
                 "  ivrData.Conditions.push ( Condition);\n" .
                 "  ivrBlock.properties.outputs['cond_' + counter] = new Object ();\n" .
                 "  ivrBlock.properties.outputs['cond_' + counter].label = '" . __ ( "Cond.") . " ' + counter;\n" .
                 "});\n",
  "javascript" => "$('.btn-adddatecondition').data ( 'id', 0).on ( 'click', function ( event)\n" .
                  "{\n" .
                  "  event && event.preventDefault ();\n" .
                  "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
                  "  $('<div class=\"input-group col-xs-12 form-date-conditions\" style=\"margin-bottom: 15px\">' + $('#ivr_date_form .form-date-conditions.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertAfter ( $('#ivr_date_form .form-date-conditions:last')).removeClass ( 'hidden');\n" .
                  "  $('#workflow-dialog-date-start-' + $(this).data ( 'id') + ',#workflow-dialog-date-finish-' + $(this).data ( 'id')).mask ( '00/00/0000').datetimepicker ( { format: '" . __ ( "MM/DD/YYYY") . "'});\n" .
                  "  if ( $('#ivr_date_form .form-date-conditions:not(.hidden)').length != 0)\n" .
                  "  {\n" .
                  "    $('#ivr_date_form .btn-deldatecondition:not(.hidden)').removeAttr ( 'disabled');\n" .
                  "  } else {\n" .
                  "    $('#ivr_date_form .btn-deldatecondition:not(.hidden)').attr ( 'disabled', 'disabled');\n" .
                  "  }\n" .
                  "  $('#workflow-dialog-date-start-' + $(this).data ( 'id')).focus ();\n" .
                  "});\n" .
                  "$('#ivr_date_form').on ( 'click', '.btn-deldatecondition', function ()\n" .
                  "{\n" .
                  "  if ( $(this).attr ( 'disabled') != 'disabled' && $('#ivr_date_form .form-date-conditions:not(.hidden)').length > 1)\n" .
                  "  {\n" .
                  "    $(this).closest ( 'div.form-date-conditions').remove ();\n" .
                  "  }\n" .
                  "  if ( $('#ivr_date_form .form-date-conditions:not(.hidden)').length == 1)\n" .
                  "  {\n" .
                  "    $('#ivr_date_form .btn-deldatecondition:not(.hidden)').attr ( 'disabled', 'disabled');\n" .
                  "  }\n" .
                  "});\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#ivr_date_form .form-date-conditions:not(.hidden)').length == 0)\n" .
                  "{\n" .
                  "  result += '" . __ ( "You need at least one condition.") . "\\n';\n" .
                  "} else {\n" .
                  "  var counter = 0;\n" .
                  "  $('#ivr_date_form .form-date-conditions:not(.hidden)').each ( function ()\n" .
                  "  {\n" .
                  "    counter++;\n" .
                  "    if ( $(this).find ( '.date-start').val () == '' || ! moment ( $(this).find ( '.date-start').val (), '" . __ ( "MM/DD/YYYY") . "').isValid ())\n" .
                  "    {\n" .
                  "      result += sprintf ( '" . __ ( "Invalid start date of condition %d.") . "\\n', x + 1);\n" .
                  "    }\n" .
                  "    if ( $(this).find ( '.date-finish').val () == '' || ! moment ( $(this).find ( '.date-finish').val (), '" . __ ( "MM/DD/YYYY") . "').isValid ())\n" .
                  "    {\n" .
                  "      result += sprintf ( '" . __ ( "Invalid finish date of condition %d.") . "\\n', x + 1);\n" .
                  "    }\n" .
                  "    if ( moment ( $(this).find ( '.date-start').val (), '" . __ ( "MM/DD/YYYY") . "').isValid () && moment ( $(this).find ( '.date-finish').val (), '" . __ ( "MM/DD/YYYY") . "').isValid () && moment ( $(this).find ( '.date-start').val (), '" . __ ( "MM/DD/YYYY") . "') > moment ( $(this).find ( '.date-finish').val (), '" . __ ( "MM/DD/YYYY") . "'))\n" .
                  "    {\n" .
                  "      result += sprintf ( '" . __ ( "Start date later than finish date at condition %d.") . "\\n', x + 1);\n" .
                  "    }\n" .
                  "  });\n" .
                  "}\n",
  "requiredcss" => array (
                     array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap"))
                   ),
  "requiredjs" => array (
                    array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")),
                    array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ())
                  )
);

/**
 * IVR weekday operator
 *
 * Implements the possibility to route based on the week day.
 */
$_in["ivr"]["operators"]["weekday"] = array (
  "name" => "weekday",
  "style" => "default",
  "outputs" => array (
                 "sunday" => __ ( "Sunday"),
                 "monday" => __ ( "Monday"),
                 "tuesday" => __ ( "Tuesday"),
                 "wednesday" => __ ( "Wednesday"),
                 "thursday" => __ ( "Thursday"),
                 "friday" => __ ( "Friday"),
                 "saturday" => __ ( "Saturday")
               ),
  "modal" => false,
  "icon" => "calendar-week",
  "title" => __ ( "Week day")
);

/**
 * IVR read operator
 *
 * Implements the possibility to read digits from current call and set it to a variable.
 */
$_in["ivr"]["operators"]["read"] = array (
  "name" => "read",
  "style" => "info",
  "outputs" => array (
                 "ok" => __ ( "OK"),
                 "timedout" => __ ( "Timed out")
               ),
  "modal" => true,
  "icon" => "assistive-listening-systems",
  "title" => __ ( "Read"),
  "modaltitle" => __ ( "Read options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_read_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-read-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"read-description\" id=\"workflow-dialog-read-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-read-variable\" class=\"control-label col-xs-2\">" . __ ( "Variable") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"read-variable\" id=\"workflow-dialog-read-variable\" maxlength=\"128\" class=\"form-control\" placeholder=\"" . __ ( "Variable name") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-read-digits\" class=\"control-label col-xs-2\">" . __ ( "Digits") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"read-digits\" id=\"workflow-dialog-read-digits\" class=\"form-control\" placeholder=\"" . __ ( "Number of digits") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-read-timeout\" class=\"control-label col-xs-2\">" . __ ( "Timeout") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"read-timeout\" id=\"workflow-dialog-read-timeout\" class=\"form-control\" placeholder=\"" . __ ( "Read timeout") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-read-echo\" class=\"control-label col-xs-2\">" . __ ( "Echo") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"checkbox\" name=\"read-echo\" id=\"workflow-dialog-read-echo\" value=\"true\" class=\"form-control\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-read-description",
  "modalshow" => "$('#workflow-dialog-read-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-read-variable').val ( ivrData.Variable);\n" .
                 "$('#workflow-dialog-read-digits').val ( ivrData.Digits);\n" .
                 "$('#workflow-dialog-read-timeout').val ( ivrData.Timeout);\n" .
                 "$('#workflow-dialog-read-echo').bootstrapToggle ( ivrData.Echo ? 'on' : 'off');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-read-description').val ();\n" .
                 "ivrData.Variable = $('#workflow-dialog-read-variable').val ();\n" .
                 "ivrData.Digits = $('#workflow-dialog-read-digits').val ();\n" .
                 "ivrData.Timeout = $('#workflow-dialog-read-timeout').val ();\n" .
                 "ivrData.Echo = $('#workflow-dialog-read-echo').prop ( 'checked');\n",
  "javascript" => "$('#workflow-dialog-read-variable').mask ( 'Z',\n" .
                  "{\n" .
                  "  translation:\n" .
                  "  {\n" .
                  "    Z:\n" .
                  "    {\n" .
                  "     pattern: /[a-z0-9_]/,\n" .
                  "      recursive: true\n" .
                  "    }\n" .
                  "  }\n" .
                  "});\n" .
                  "$('#workflow-dialog-read-digits,#workflow-dialog-read-timeout').mask ( '0#');\n" .
                  "$('#workflow-dialog-read-echo').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-read-variable').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable name missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-read-digits').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Number of digits missing.") . "\\n';\n" .
                  "}\n",
  "requiredcss" => array (
                     array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap"))
                   ),
  "requiredjs" => array (
                    array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()),
                    array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ())
                  )
);

/**
 * IVR menu operator
 *
 * Implements the possibility to create a simple logic menu.
 */
$_in["ivr"]["operators"]["menu"] = array (
  "name" => "menu",
  "style" => "info",
  "outputs" => array (
                 "maxretries" => __ ( "Max retries"),
                 "timedout" => __ ( "Timed out")
               ),
  "modal" => true,
  "icon" => "align-justify",
  "title" => __ ( "Menu"),
  "modaltitle" => __ ( "Menu options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_menu_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-menu-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"menu-description\" id=\"workflow-dialog-menu-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-menu-variable\" class=\"control-label col-xs-2\">" . __ ( "Variable") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"menu-variable\" id=\"workflow-dialog-menu-variable\" maxlength=\"128\" class=\"form-control\" placeholder=\"" . __ ( "Variable name") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-menu-file\" class=\"control-label col-xs-2\">" . __ ( "Play file") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <select name=\"menu-file\" id=\"workflow-dialog-menu-file\" class=\"form-control\" data-placeholder=\"" . __ ( "Audio file to play") . "\"><option value=\"\"></option></select>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-menu-invalidfile\" class=\"control-label col-xs-2\">" . __ ( "Invalid file") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <select name=\"menu-invalidfile\" id=\"workflow-dialog-menu-invalidfile\" class=\"form-control\" data-placeholder=\"" . __ ( "Audio file to play when timedout or invalid option selected") . "\"><option value=\"\"></option></select>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-menu-echo\" class=\"control-label col-xs-2\">" . __ ( "Echo") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"checkbox\" name=\"menu-echo\" id=\"workflow-dialog-menu-echo\" value=\"true\" class=\"form-control\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-menu-timeout\" class=\"control-label col-xs-2\">" . __ ( "Timeout") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"menu-timeout\" id=\"workflow-dialog-menu-timeout\" class=\"form-control\" placeholder=\"" . __ ( "Read timeout") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-menu-retries\" class=\"control-label col-xs-2\">" . __ ( "Retries") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"menu-retries\" id=\"workflow-dialog-menu-retries\" class=\"form-control\" placeholder=\"" . __ ( "Retries limit") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-menu-options-1\" class=\"control-label col-xs-2\">" . __ ( "Enabled options") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <div class=\"form-group\">\n" .
                    "      1 <input type=\"checkbox\" name=\"menu-options-1\" id=\"workflow-dialog-menu-options-1\" value=\"true\" class=\"form-control\" />\n" .
                    "      2 <input type=\"checkbox\" name=\"menu-options-2\" id=\"workflow-dialog-menu-options-2\" value=\"true\" class=\"form-control\" />\n" .
                    "      3 <input type=\"checkbox\" name=\"menu-options-3\" id=\"workflow-dialog-menu-options-3\" value=\"true\" class=\"form-control\" />\n" .
                    "    </div>\n" .
                    "    <div class=\"form-group\">\n" .
                    "      4 <input type=\"checkbox\" name=\"menu-options-4\" id=\"workflow-dialog-menu-options-4\" value=\"true\" class=\"form-control\" />\n" .
                    "      5 <input type=\"checkbox\" name=\"menu-options-5\" id=\"workflow-dialog-menu-options-5\" value=\"true\" class=\"form-control\" />\n" .
                    "      6 <input type=\"checkbox\" name=\"menu-options-6\" id=\"workflow-dialog-menu-options-6\" value=\"true\" class=\"form-control\" />\n" .
                    "    </div>\n" .
                    "    <div class=\"form-group\">\n" .
                    "      7 <input type=\"checkbox\" name=\"menu-options-7\" id=\"workflow-dialog-menu-options-7\" value=\"true\" class=\"form-control\" />\n" .
                    "      8 <input type=\"checkbox\" name=\"menu-options-8\" id=\"workflow-dialog-menu-options-8\" value=\"true\" class=\"form-control\" />\n" .
                    "      9 <input type=\"checkbox\" name=\"menu-options-9\" id=\"workflow-dialog-menu-options-9\" value=\"true\" class=\"form-control\" />\n" .
                    "    </div>\n" .
                    "    <div class=\"form-group\">\n" .
                    "      * <input type=\"checkbox\" name=\"menu-options-a\" id=\"workflow-dialog-menu-options-a\" value=\"true\" class=\"form-control\" />\n" .
                    "      0 <input type=\"checkbox\" name=\"menu-options-0\" id=\"workflow-dialog-menu-options-0\" value=\"true\" class=\"form-control\" />\n" .
                    "      # <input type=\"checkbox\" name=\"menu-options-s\" id=\"workflow-dialog-menu-options-s\" value=\"true\" class=\"form-control\" />\n" .
                    "    </div>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-menu-description",
  "modalshow" => "$('#workflow-dialog-menu-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-menu-variable').val ( ivrData.Variable ? ivrData.Variable : '');\n" .
                 "$('#workflow-dialog-menu-file').val ( ivrData.File).trigger ( 'change');\n" .
                 "$('#workflow-dialog-menu-invalidfile').val ( ivrData.InvalidFile).trigger ( 'change');\n" .
                 "$('#workflow-dialog-menu-echo').bootstrapToggle ( ivrData.Echo ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-timeout').val ( ivrData.Timeout ? ivrData.Timeout : '');\n" .
                 "$('#workflow-dialog-menu-retries').val ( ivrData.Retries ? ivrData.Retries : '');\n" .
                 "$('#workflow-dialog-menu-options-1').bootstrapToggle ( ivrData.Option1 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-2').bootstrapToggle ( ivrData.Option2 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-3').bootstrapToggle ( ivrData.Option3 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-4').bootstrapToggle ( ivrData.Option4 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-5').bootstrapToggle ( ivrData.Option5 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-6').bootstrapToggle ( ivrData.Option6 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-7').bootstrapToggle ( ivrData.Option7 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-8').bootstrapToggle ( ivrData.Option8 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-9').bootstrapToggle ( ivrData.Option9 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-0').bootstrapToggle ( ivrData.Option0 ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-a').bootstrapToggle ( ivrData.OptionA ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-menu-options-s').bootstrapToggle ( ivrData.OptionS ? 'on' : 'off');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-menu-description').val ();\n" .
                 "ivrData.Variable = $('#workflow-dialog-menu-variable').val ();\n" .
                 "ivrData.File = $('#workflow-dialog-menu-file').val ();\n" .
                 "ivrData.InvalidFile = $('#workflow-dialog-menu-invalidfile').val ();\n" .
                 "ivrData.Echo = $('#workflow-dialog-menu-echo').prop ( 'checked');\n" .
                 "ivrData.Timeout = $('#workflow-dialog-menu-timeout').val ();\n" .
                 "ivrData.Retries = $('#workflow-dialog-menu-retries').val ();\n" .
                 "ivrData.Option0 = $('#workflow-dialog-menu-options-0').prop ( 'checked');\n" .
                 "ivrData.Option1 = $('#workflow-dialog-menu-options-1').prop ( 'checked');\n" .
                 "ivrData.Option2 = $('#workflow-dialog-menu-options-2').prop ( 'checked');\n" .
                 "ivrData.Option3 = $('#workflow-dialog-menu-options-3').prop ( 'checked');\n" .
                 "ivrData.Option4 = $('#workflow-dialog-menu-options-4').prop ( 'checked');\n" .
                 "ivrData.Option5 = $('#workflow-dialog-menu-options-5').prop ( 'checked');\n" .
                 "ivrData.Option6 = $('#workflow-dialog-menu-options-6').prop ( 'checked');\n" .
                 "ivrData.Option7 = $('#workflow-dialog-menu-options-7').prop ( 'checked');\n" .
                 "ivrData.Option8 = $('#workflow-dialog-menu-options-8').prop ( 'checked');\n" .
                 "ivrData.Option9 = $('#workflow-dialog-menu-options-9').prop ( 'checked');\n" .
                 "ivrData.OptionA = $('#workflow-dialog-menu-options-a').prop ( 'checked');\n" .
                 "ivrData.OptionS = $('#workflow-dialog-menu-options-s').prop ( 'checked');\n" .
                 "delete ( ivrBlock.properties.outputs);\n" .
                 "ivrBlock.properties.outputs = new Object ();\n" .
                 "var letter, digit;\n" .
                 "for ( var x = 0; x <= 12; x++)\n" .
                 "{\n" .
                 "  if ( x < 10)\n" .
                 "  {\n" .
                 "    letter = digit = x;\n" .
                 "  }\n" .
                 "  if ( x == 11)\n" .
                 "  {\n" .
                 "    letter = 's';\n" .
                 "    digit = '#';\n" .
                 "  }\n" .
                 "  if ( x == 10)\n" .
                 "  {\n" .
                 "    letter = 'a';\n" .
                 "    digit = '*';\n" .
                 "  }\n" .
                 "  if ( $('#workflow-dialog-menu-options-' + letter).prop ( 'checked'))\n" .
                 "  {\n" .
                 "    ivrBlock.properties.outputs['option' + letter] = new Object ();\n" .
                 "    ivrBlock.properties.outputs['option' + letter].label = '" . __ ( "Option") . "' + ' ' + digit;\n" .
                 "  }\n" .
                 "}\n" .
                 "ivrBlock.properties.outputs['maxretries'] = new Object ();\n" .
                 "ivrBlock.properties.outputs['maxretries'].label = '" . __ ( "Max retries") . "';\n" .
                 "ivrBlock.properties.outputs['timedout'] = new Object ();\n" .
                 "ivrBlock.properties.outputs['timedout'].label = '" . __ ( "Timed out") . "';\n",
  "javascript" => "$('#workflow-dialog-menu-variable').mask ( 'Z',\n" .
                  "{\n" .
                  "  translation:\n" .
                  "  {\n" .
                  "    Z:\n" .
                  "    {\n" .
                  "     pattern: /[a-z0-9_]/,\n" .
                  "      recursive: true\n" .
                  "    }\n" .
                  "  }\n" .
                  "});\n" .
                  "$('#workflow-dialog-menu-file,#workflow-dialog-menu-invalidfile').select2 (\n" .
                  "{\n" .
                  "  allowClear: true,\n" .
                  "  width: '100%',\n" .
                  "  data: VoIP.APIsearch ( { path: '/audios', fields: 'ID,Description', formatText: '%Description%'})\n" .
                  "});\n" .
                  "$('#workflow-dialog-menu-echo,#workflow-dialog-menu-options-1,#workflow-dialog-menu-options-2,#workflow-dialog-menu-options-3,#workflow-dialog-menu-options-4,#workflow-dialog-menu-options-5,#workflow-dialog-menu-options-6,#workflow-dialog-menu-options-7,#workflow-dialog-menu-options-8,#workflow-dialog-menu-options-9,#workflow-dialog-menu-options-0,#workflow-dialog-menu-options-a,#workflow-dialog-menu-options-s').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});" .
                  "$('#workflow-dialog-menu-timeout,#workflow-dialog-menu-retries').mask ( '0#');\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-menu-variable').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable name missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-menu-timeout').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Timeout value missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-menu-retries').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Retries value missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( ! $('#workflow-dialog-menu-options-0').prop ( 'checked') && ! $('#workflow-dialog-menu-options-1').prop ( 'checked') && ! $('#workflow-dialog-menu-options-2').prop ( 'checked') && ! $('#workflow-dialog-menu-options-3').prop ( 'checked') && ! $('#workflow-dialog-menu-options-4').prop ( 'checked') && ! $('#workflow-dialog-menu-options-5').prop ( 'checked') && ! $('#workflow-dialog-menu-options-6').prop ( 'checked') && ! $('#workflow-dialog-menu-options-7').prop ( 'checked') && ! $('#workflow-dialog-menu-options-8').prop ( 'checked') && ! $('#workflow-dialog-menu-options-9').prop ( 'checked') && ! $('#workflow-dialog-menu-options-a').prop ( 'checked') && ! $('#workflow-dialog-menu-options-s').prop ( 'checked'))\n" .
                  "{\n" .
                  "  result += '" . __ ( "At least one output option should be enabled.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR play operator
 *
 * Implements the possibility to play an audio file.
 */
$_in["ivr"]["operators"]["play"] = array (
  "name" => "play",
  "style" => "success",
  "outputs" => 1,
  "modal" => true,
  "icon" => "play-circle",
  "title" => __ ( "Play"),
  "modaltitle" => __ ( "Play options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_play_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-play-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"play-description\" id=\"workflow-dialog-play-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-play-file\" class=\"control-label col-xs-2\">" . __ ( "Play file") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <select name=\"play-file\" id=\"workflow-dialog-play-file\" class=\"form-control\" data-placeholder=\"" . __ ( "Audio file to play") . "\"><option value=\"\"></option></select>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-play-interruptible\" class=\"control-label col-xs-2\">" . __ ( "Interruptible") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"checkbox\" name=\"play-interruptible\" id=\"workflow-dialog-play-interruptible\" value=\"true\" class=\"form-control\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-play-description",
  "modalshow" => "$('#workflow-dialog-play-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-play-file').val ( ivrData.File).trigger ( 'change');\n" .
                 "$('#workflow-dialog-play-interruptible').bootstrapToggle ( ivrData.Interruptible ? 'on' : 'off');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-play-description').val ();\n" .
                 "ivrData.File = $('#workflow-dialog-play-file').val ();\n" .
                 "ivrData.Interruptible = $('#workflow-dialog-play-interruptible').prop ( 'checked');\n",
  "javascript" => "$('#workflow-dialog-play-file').select2 (\n" .
                  "{\n" .
                  "  allowClear: true,\n" .
                  "  width: '100%',\n" .
                  "  data: VoIP.APIsearch ( { path: '/audios', fields: 'ID,Description', formatText: '%Description%'})\n" .
                  "});\n" .
                  "$('#workflow-dialog-play-interruptible').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-play-file').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "File not selected.") . "\\n';\n" .
                  "}\n",
  "requiredcss" => array (
                     array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap"))
                   ),
  "requiredjs" => array (
                    array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ())
                  )
);

/**
 * IVR record operator
 *
 * Implements the possibility to record the call to an audio file.
 */
$_in["ivr"]["operators"]["record"] = array (
  "name" => "record",
  "style" => "success",
  "outputs" => 1,
  "modal" => true,
  "icon" => "microphone-alt",
  "title" => __ ( "Record"),
  "modaltitle" => __ ( "Record options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_record_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-record-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"record-description\" id=\"workflow-dialog-record-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-record-filename\" class=\"control-label col-xs-2\">" . __ ( "Record filename") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"record-filename\" id=\"workflow-dialog-record-filename\" class=\"form-control\" placeholder=\"" . __ ( "Record filename") . "\" />\n" .
                    "    " . __ ( "You can insert some substitutions as follow") . ":<br />\n" .
                    "    <ul>\n" .
                    "      <li><b>{date}</b>: " . __ ( "Current date in YYYYMMDD format") . ";</li>\n" .
                    "      <li><b>{time}</b>: " . __ ( "Current time in HHMMSS format") . ";</li>\n" .
                    "      <li><b>{callerid}</b>: " . __ ( "Number of caller telephone (digits only)") . ";</li>\n" .
                    "      <li><b>{var_VARIABLE}</b>: " . __ ( "The content of IVR variable <i>VARIABLE</i>") . ".</li>\n" .
                    "    </ul>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-record-description",
  "modalshow" => "$('#workflow-dialog-record-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-record-filename').val ( ivrData.Filename);\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-record-description').val ();\n" .
                 "ivrData.Filename = $('#workflow-dialog-record-filename').val ();\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-record-filename').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "File name missing.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR stop recording operator
 *
 * Implements the possibility to stop recording.
 */
$_in["ivr"]["operators"]["stop"] = array (
  "name" => "stop",
  "style" => "success",
  "outputs" => 1,
  "modal" => true,
  "icon" => "microphone-alt-slash",
  "title" => __ ( "Stop"),
  "modaltitle" => __ ( "Stop recording options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_stop_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-stop-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"stop-description\" id=\"workflow-dialog-stop-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-stop-discard\" class=\"control-label col-xs-2\">" . __ ( "Discard") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"checkbox\" name=\"stop-discard\" id=\"workflow-dialog-stop-discard\" value=\"false\" class=\"form-control\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-stop-description",
  "modalshow" => "$('#workflow-dialog-stop-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-stop-discard').bootstrapToggle ( ivrData.Discard ? 'on' : 'off');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-stop-description').val ();\n" .
                 "ivrData.Discard = $('#workflow-dialog-stop-discard').prop ( 'checked');\n",
  "javascript" => "$('#workflow-dialog-stop-discard').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});",
  "requiredcss" => array (
                     array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap"))
                   ),
  "requiredjs" => array (
                    array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ())
                  )
);

/**
 * IVR call answer operator
 *
 * Implements the possibility to answer the call.
 */
$_in["ivr"]["operators"]["answer"] = array (
  "name" => "answer",
  "style" => "primary",
  "outputs" => 1,
  "modal" => false,
  "icon" => "phone",
  "title" => "Answer"
);

/**
 * IVR wait operator
 *
 * Implements the possibility to wait execution.
 */
$_in["ivr"]["operators"]["wait"] = array (
  "name" => "wait",
  "style" => "primary",
  "outputs" => 1,
  "modal" => true,
  "icon" => "stopwatch",
  "title" => __ ( "Wait"),
  "modaltitle" => __ ( "Wait options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_wait_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-wait-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"wait-description\" id=\"workflow-dialog-wait-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-wait-time\" class=\"control-label col-xs-2\">" . __ ( "Time") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"wait-time\" id=\"workflow-dialog-wait-time\" class=\"form-control\" placeholder=\"" . __ ( "Wait time (in seconds)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-wait-description",
  "modalshow" => "$('#workflow-dialog-wait-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-wait-time').val ( ivrData.Time);\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-wait-description').val ();\n" .
                 "ivrData.Time = $('#workflow-dialog-wait-time').val ();\n",
  "javascript" => "$('#workflow-dialog-wait-time').mask ( '0#');\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-wait-time').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Waiting time missing.") . "\\n';\n" .
                  "}\n",
  "requiredjs" => array (
                    array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ())
                  )
);

/**
 * IVR router operator
 *
 * Implements the possibility to change workflow route based on variable conditions.
 */
$_in["ivr"]["operators"]["router"] = array (
  "name" => "router",
  "style" => "primary",
  "outputs" => array (
                 "true" => __ ( "True"),
                 "false" => __ ( "False")
               ),
  "modal" => true,
  "icon" => "project-diagram",
  "title" => __ ( "Router"),
  "modaltitle" => __ ( "Router options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_router_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-router-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"router-description\" id=\"workflow-dialog-router-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-router-variable\" class=\"control-label col-xs-2\">" . __ ( "Variable") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"router-variable\" id=\"workflow-dialog-router-variable\" class=\"form-control\" placeholder=\"" . __ ( "Filter variable") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-router-logic\" class=\"control-label col-xs-2\">" . __ ( "Logic") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <select name=\"router-logic\" id=\"workflow-dialog-router-logic\" class=\"form-control\">\n" .
                    "      <option value=\"or\">" . __ ( "Any condition matches (OR)") . "</option>\n" .
                    "      <option value=\"and\">" . __ ( "All conditions match (AND)") . "</option>\n" .
                    "    </select>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-router-condition-_X_\" class=\"control-label col-xs-2\">" . __ ( "Conditions") . "</label>\n" .
                    "  <div class=\"col-xs-10 inputGroupContainer\">\n" .
                    "    <div class=\"input-group col-xs-12 form-router-conditions hidden\">\n" .
                    "      <select name=\"router-condition-_X_\" id=\"workflow-dialog-router-condition-_X_\" class=\"form-control router-condition\" data-placeholder=\"" . __ ( "Variable condition logic") . "\">\n" .
                    "        <option value=\"\"></option>\n" .
                    "        <option value=\"=\">" . __ ( "Equal") . "</option>\n" .
                    "        <option value=\"!=\">" . __ ( "Different") . "</option>\n" .
                    "        <option value=\"in\">" . __ ( "Contains") . "</option>\n" .
                    "        <option value=\"notin\">" . __ ( "Not contains") . "</option>\n" .
                    "        <option value=\"<\">" . __ ( "Lower than") . "</option>\n" .
                    "        <option value=\"<=\">" . __ ( "Lower or equal than") . "</option>\n" .
                    "        <option value=\">\">" . __ ( "Greater than") . "</option>\n" .
                    "        <option value=\">=\">" . __ ( "Greater or equal than") . "</option>\n" .
                    "        <option value=\"regex\">" . __ ( "Regex match") . "</option>\n" .
                    "      </select>\n" .
                    "      <input type=\"text\" name=\"router-value-_X_\" id=\"workflow-dialog-router-value-_X_\" class=\"form-control router-value\" placeholder=\"" . __ ( "Condition value") . "\" />\n" .
                    "      <span class=\"input-group-addon input-icon-button btn btn-error btn-delroutercondition\"><i class=\"fa fa-minus\"></i></span>\n" .
                    "    </div>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\" style=\"margin-top: -15px;\">\n" .
                    "  <div class=\"col-xs-12\">\n" .
                    "    <span class=\"input-icon-button btn btn-success btn-addroutercondition pull-right\"><i class=\"fa fa-plus\"></i></span>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-router-description",
  "modalshow" => "$('#workflow-dialog-router-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-router-variable').val ( ivrData.Variable ? ivrData.Variable : '');\n" .
                 "$('#workflow-dialog-router-logic').val ( ivrData.Logic ? ivrData.Logic : 'or');\n" .
                 "$('#ivr_router_form .form-router-conditions:not(.hidden)').remove ();\n" .
                 "$('.btn-addroutercondition').data ( 'id', 0);\n" .
                 "if ( ivrData.Conditions && ivrData.Conditions.length > 0)\n" .
                 "{\n" .
                 "  for ( let x = 0; x < ivrData.Conditions.length; x++)\n" .
                 "  {\n" .
                 "    $('.btn-addroutercondition').trigger ( 'click');\n" .
                 "    $('#workflow-dialog-router-condition-' + $('.btn-addroutercondition').data ( 'id')).val ( ivrData.Conditions[x].Condition).trigger ( 'change');\n" .
                 "    $('#workflow-dialog-router-value-' + $('.btn-addroutercondition').data ( 'id')).val ( ivrData.Conditions[x].Value);\n" .
                 "  }\n" .
                 "} else {\n" .
                 "  $('.btn-addroutercondition').trigger ( 'click');\n" .
                 "}\n" .
                 "if ( flowchart.mode == 'view')\n" .
                 "{\n" .
                 "  $(this).find ( 'span.btn-addroutercondition').parent ().css ( 'display', 'none');\n" .
                 "  $(this).find ( 'span.btn-delroutercondition:not(.hidden)').remove ();\n" .
                 "}\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-router-description').val ();\n" .
                 "ivrData.Variable = $('#workflow-dialog-router-variable').val ();\n" .
                 "ivrData.Logic = $('#workflow-dialog-router-logic').val ();\n" .
                 "ivrData.Conditions = new Array ();\n" .
                 "$('#ivr_router_form .form-router-conditions:not(.hidden)').each ( function ()\n" .
                 "{\n" .
                 "  var Condition = new Object ();\n" .
                 "  Condition.Condition = $(this).find ( '.router-condition').val ();\n" .
                 "  Condition.Value = $(this).find ( '.router-value').val ();\n" .
                 "  ivrData.Conditions.push ( Condition);\n" .
                 "});\n",
  "javascript" => "$('.btn-addroutercondition').data ( 'id', 0).on ( 'click', function ( event)\n" .
                  "{\n" .
                  "  event && event.preventDefault ();\n" .
                  "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
                  "  $('<div class=\"input-group col-xs-12 form-router-conditions\" style=\"margin-bottom: 15px\">' + $('#ivr_router_form .form-router-conditions.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertAfter ( $('#ivr_router_form .form-router-conditions:last')).removeClass ( 'hidden');\n" .
                  "  $('#workflow-dialog-router-condition-' + $(this).data ( 'id')).select2 (\n" .
                  "  {\n" .
                  "    allowClear: true,\n" .
                  "    width: '100%'\n" .
                  "  });\n" .
                  "  $('#workflow-dialog-router-condition-' + $(this).data ( 'id')).focus ();\n" .
                  "});\n" .
                  "$('#ivr_router_form').on ( 'click', '.btn-delroutercondition', function ()\n" .
                  "{\n" .
                  "  if ( $('#ivr_router_form .form-router-conditions:not(.hidden)').length > 1)\n" .
                  "  {\n" .
                  "    $(this).closest ( 'div.form-router-conditions').remove ();\n" .
                  "  }\n" .
                  "});\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-router-variable').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Missing variable name.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#ivr_router_form .form-router-conditions:not(.hidden)').length == 0)\n" .
                  "{\n" .
                  "  result += '" . __ ( "You need at least one condition.") . "\\n';\n" .
                  "} else {\n" .
                  "  var counter = 0;\n" .
                  "  $('#ivr_router_form .form-router-conditions:not(.hidden)').each ( function ()\n" .
                  "  {\n" .
                  "    counter++;\n" .
                  "    if ( ! $(this).find ( '.router-condition').val ())\n" .
                  "    {\n" .
                  "      result += sprintf ( '" . __ ( "You must select the condition logic of condition %d.") . "\\n', counter);\n" .
                  "    }\n" .
                  "    if ( $(this).find ( '.router-value').val () == '')\n" .
                  "    {\n" .
                  "      result += sprintf ( '" . __ ( "You must provide a value comparison of condition %d.") . "\\n', counter);\n" .
                  "    }\n" .
                  "  });\n" .
                  "}\n"
);

/**
 * IVR variable set operator
 *
 * Implements the possibility to set a variable with a static or dynamic value.
 */
$_in["ivr"]["operators"]["setvar"] = array (
  "name" => "setvar",
  "style" => "primary",
  "outputs" => 1,
  "modal" => true,
  "icon" => "pencil-alt",
  "title" => __ ( "Set variable"),
  "modaltitle" => __ ( "Set variable options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_setvar_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-setvar-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"setvar-description\" id=\"workflow-dialog-setvar-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-setvar-variable\" class=\"control-label col-xs-2\">" . __ ( "Variable") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"setvar-variable\" id=\"workflow-dialog-setvar-variable\" class=\"form-control\" placeholder=\"" . __ ( "Variable name") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-setvar-value\" class=\"control-label col-xs-2\">" . __ ( "Value") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"setvar-value\" id=\"workflow-dialog-setvar-value\" class=\"form-control\" placeholder=\"" . __ ( "Variable value") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-setvar-description",
  "modalshow" => "$('#workflow-dialog-setvar-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-setvar-variable').val ( ivrData.Variable ? ivrData.Variable : '');\n" .
                 "$('#workflow-dialog-setvar-value').val ( ivrData.Value ? ivrData.Value : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-setvar-description').val ();\n" .
                 "ivrData.Variable = $('#workflow-dialog-setvar-variable').val ();\n" .
                 "ivrData.Value = $('#workflow-dialog-setvar-value').val ();\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-setvar-variable').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable name missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-setvar-value').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable value missing.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR dial operator
 *
 * Implements the possibility to dial to a destination.
 */
$_in["ivr"]["operators"]["dial"] = array (
  "name" => "dial",
  "style" => "primary",
  "outputs" => array (
                 "ok" => __ ( "OK"),
                 "timedout" => __ ( "Timed out"),
                 "busy" => __ ( "Busy"),
                 "refused" => __ ( "Refused")
               ),
  "modal" => true,
  "icon" => "keyboard",
  "title" => __ ( "Dial"),
  "modaltitle" => __ ( "Dial options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_dial_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-dial-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"dial-description\" id=\"workflow-dialog-dial-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-dial-destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"dial-destination\" id=\"workflow-dialog-dial-destination\" class=\"form-control\" placeholder=\"" . __ ( "Dial destination (number or {var_VARIABLE})") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-dial-timeout\" class=\"control-label col-xs-2\">" . __ ( "Timeout") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"dial-timeout\" id=\"workflow-dialog-dial-timeout\" class=\"form-control\" placeholder=\"" . __ ( "Dial timeout") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-dial-calleridname\" class=\"control-label col-xs-2\">" . __ ( "CallerID name") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"dial-calleridname\" id=\"workflow-dialog-dial-calleridname\" class=\"form-control\" placeholder=\"" . __ ( "Override caller ID name (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-dial-calleridnum\" class=\"control-label col-xs-2\">" . __ ( "CallerID number") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"dial-calleridnum\" id=\"workflow-dialog-dial-calleridnum\" class=\"form-control\" placeholder=\"" . __ ( "Override caller ID number (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-dial-mohclass\" class=\"control-label col-xs-2\">" . __ ( "MOH class") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"dial-mohclass\" id=\"workflow-dialog-dial-mohclass\" class=\"form-control\" placeholder=\"" . __ ( "Music on hold class (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-dial-description",
  "modalshow" => "$('#workflow-dialog-dial-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-dial-destination').val ( ivrData.Destination ? ivrData.Destination : '');\n" .
                 "$('#workflow-dialog-dial-timeout').val ( ivrData.Timeout ? ivrData.Timeout : '');\n" .
                 "$('#workflow-dialog-dial-calleridname').val ( ivrData.CallerIDName ? ivrData.CallerIDName : '');\n" .
                 "$('#workflow-dialog-dial-calleridnum').val ( ivrData.CallerIDNum ? ivrData.CallerIDNum : '');\n" .
                 "$('#workflow-dialog-dial-mohclass').val ( ivrData.MOHClass ? ivrData.MOHClass : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-dial-description').val ();\n" .
                 "ivrData.Destination = $('#workflow-dialog-dial-destination').val ();\n" .
                 "ivrData.Timeout = $('#workflow-dialog-dial-timeout').val ();\n" .
                 "ivrData.CallerIDName = $('#workflow-dialog-dial-calleridname').val ();\n" .
                 "ivrData.CallerIDNum = $('#workflow-dialog-dial-calleridnum').val ();\n" .
                 "ivrData.MOHClass = $('#workflow-dialog-dial-mohclass').val ();\n",
  "javascript" => "$('#workflow-dialog-dial-timeout').mask ( '0#');\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-dial-destination').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable destination missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-dial-timeout').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable timeout missing.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR script operator
 *
 * Implements the possibility to execute an external script.
 */
$_in["ivr"]["operators"]["script"] = array (
  "name" => "script",
  "style" => "warning",
  "outputs" => array (
                 "ok" => __ ( "OK"),
                 "error" => __ ( "Error")
               ),
  "modal" => true,
  "icon" => "file-alt",
  "title" => __ ( "Script"),
  "modaltitle" => __ ( "Script options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_script_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-script-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"script-description\" id=\"workflow-dialog-script-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-script-name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"script-name\" id=\"workflow-dialog-script-name\" class=\"form-control\" placeholder=\"" . __ ( "Script name") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-script-variable\" class=\"control-label col-xs-2\">" . __ ( "Variable") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"script-variable\" id=\"workflow-dialog-script-variable\" class=\"form-control\" placeholder=\"" . __ ( "Script output variable") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-script-parameters-_X_\" class=\"control-label col-xs-2\">" . __ ( "Parameters") . "</label>\n" .
                    "  <div class=\"col-xs-10 inputGroupContainer\">\n" .
                    "    <div class=\"input-group col-xs-12 form-script-parameters hidden\">\n" .
                    "      <input type=\"text\" name=\"parameters-parameter-_X_\" id=\"workflow-dialog-script-parameter-_X_\" class=\"form-control\" placeholder=\"" . __ ( "Script execution parameter") . "\" />\n" .
                    "      <span class=\"input-group-addon input-icon-button btn btn-error btn-delscriptparameter\" disabled=\"disabled\"><i class=\"fa fa-minus\"></i></span>\n" .
                    "    </div>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\" style=\"margin-top: -15px;\">\n" .
                    "  <div class=\"col-xs-12\">\n" .
                    "    <span class=\"input-icon-button btn btn-success btn-addscriptparameter pull-right\"><i class=\"fa fa-plus\"></i></span>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-script-description",
  "modalshow" => "$('#workflow-dialog-script-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-script-name').val ( ivrData.Name ? ivrData.Name : '');\n" .
                 "$('#workflow-dialog-script-variable').val ( ivrData.Variable ? ivrData.Variable : '');\n" .
                 "$('#ivr_script_form .form-script-parameters:not(.hidden)').remove ();\n" .
                 "$('.btn-addscriptparameter').data ( 'id', 0);\n" .
                 "if ( ivrData.Parameters && ivrData.Parameters.length > 0)\n" .
                 "{\n" .
                 "  for ( let x = 0; x < ivrData.Parameters.length; x++)\n" .
                 "  {\n" .
                 "    $('.btn-addscriptparameter').trigger ( 'click');\n" .
                 "    $('#workflow-dialog-script-parameter-' + $('.btn-addscriptparameter').data ( 'id')).val ( ivrData.Parameters[x]);\n" .
                 "  }\n" .
                 "} else {\n" .
                 "  $('.btn-addscriptparameter').trigger ( 'click');\n" .
                 "}\n" .
                 "if ( flowchart.mode == 'view')\n" .
                 "{\n" .
                 "  $(this).find ( 'span.btn-addscriptparameter').parent ().css ( 'display', 'none');\n" .
                 "  $(this).find ( 'span.btn-delscriptparameter:not(.hidden)').remove ();\n" .
                 "}\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-script-description').val ();\n" .
                 "ivrData.Name = $('#workflow-dialog-script-name').val ();\n" .
                 "ivrData.Variable = $('#workflow-dialog-script-variable').val ();\n" .
                 "ivrData.Parameters = new Array ();\n" .
                 "$('#ivr_script_form .form-script-parameters:not(.hidden)').each ( function ()\n" .
                 "{\n" .
                 "  ivrData.Parameters.push ( $(this).find ( 'input').val ());\n" .
                 "});\n",
  "javascript" => "$('.btn-addscriptparameter').data ( 'id', 0).on ( 'click', function ( event)\n" .
                  "{\n" .
                  "  event && event.preventDefault ();\n" .
                  "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
                  "  $('<div class=\"input-group col-xs-12 form-script-parameters\" style=\"margin-bottom: 15px\">' + $('#ivr_script_form .form-script-parameters.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertAfter ( $('#ivr_script_form .form-script-parameters:last')).removeClass ( 'hidden');\n" .
                  "  if ( $('#ivr_script_form .form-script-parameters:not(.hidden)').length != 0)\n" .
                  "  {\n" .
                  "    $('#ivr_script_form .btn-delscriptparameter:not(.hidden)').removeAttr ( 'disabled');\n" .
                  "  } else {\n" .
                  "    $('#ivr_script_form .btn-delscriptparameter:not(.hidden)').attr ( 'disabled', 'disabled');\n" .
                  "  }\n" .
                  "  $('#workflow-dialog-script-parameter-' + $(this).data ( 'id')).focus ();\n" .
                  "});\n" .
                  "$('#ivr_script_form').on ( 'click', '.btn-delscriptparameter:not(:disabled)', function ()\n" .
                  "{\n" .
                  "  if ( $(this).attr ( 'disabled') != 'disabled' && $('#ivr_script_form .form-script-parameters:not(.hidden)').length > 1)\n" .
                  "  {\n" .
                  "    $(this).closest ( 'div.form-script-parameters').remove ();\n" .
                  "  }\n" .
                  "  if ( $('#ivr_script_form .form-script-parameters:not(.hidden)').length == 1)\n" .
                  "  {\n" .
                  "    $('#ivr_script_form .btn-delscriptparameter:not(.hidden)').attr ( 'disabled', 'disabled');\n" .
                  "  }\n" .
                  "});\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-script-name').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable name missing.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR email operator
 *
 * Implements the possibility to send email.
 */
$_in["ivr"]["operators"]["email"] = array (
  "name" => "email",
  "style" => "warning",
  "outputs" => 1,
  "modal" => true,
  "icon" => "envelope",
  "title" => __ ( "Email"),
  "modaltitle" => __ ( "Email options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_email_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-email-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"email-description\" id=\"workflow-dialog-email-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-email-to\" class=\"control-label col-xs-2\">" . __ ( "To") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"email-to\" id=\"workflow-dialog-email-to\" class=\"form-control\" placeholder=\"" . __ ( "Destination email") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-email-subject\" class=\"control-label col-xs-2\">" . __ ( "Subject") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"email-subject\" id=\"workflow-dialog-email-subject\" class=\"form-control\" placeholder=\"" . __ ( "Subject") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-email-body\" class=\"control-label col-xs-2\">" . __ ( "Body") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <textarea name=\"email-body\" id=\"workflow-dialog-email-body\" class=\"form-control\" placeholder=\"" . __ ( "Message body") . "\" style=\"max-width: 100%;min-width: 100%;max-height: 12em;min-height: 3em\"></textarea>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-email-description",
  "modalshow" => "$('#workflow-dialog-email-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-email-to').val ( ivrData.To ? ivrData.To : '');\n" .
                 "$('#workflow-dialog-email-subject').val ( ivrData.Subject ? ivrData.Subject : '');\n" .
                 "$('#workflow-dialog-email-body').val ( ivrData.Body ? ivrData.Body : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-email-description').val ();\n" .
                 "ivrData.To = $('#workflow-dialog-email-to').val ();\n" .
                 "ivrData.Subject = $('#workflow-dialog-email-subject').val ();\n" .
                 "ivrData.Body = $('#workflow-dialog-email-body').val ();\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-email-to').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable to missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-email-subject').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable subject missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-email-body').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable body missing.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR queue operator
 *
 * Implements the possibility to place the caller into a queue.
 */
$_in["ivr"]["operators"]["queue"] = array (
  "name" => "queue",
  "style" => "danger",
  "outputs" => array (
                 "answered" => __ ( "Answered"),
                 "timeout" => __ ( "Timeout"),
                 "full" => __ ( "Full"),
                 "joinempty" => __ ( "Empty")
               ),
  "modal" => true,
  "icon" => "headset",
  "title" => __ ( "Queue"),
  "modaltitle" => __ ( "Queue options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_queue_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-queue-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"queue-description\" id=\"workflow-dialog-queue-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-queue-name\" class=\"control-label col-xs-2\">" . __ ( "Queue") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"queue-name\" id=\"workflow-dialog-queue-name\" class=\"form-control\" placeholder=\"" . __ ( "Queue name") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-queue-mohclass\" class=\"control-label col-xs-2\">" . __ ( "MOH class") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"queue-mohclass\" id=\"workflow-dialog-queue-mohclass\" class=\"form-control\" placeholder=\"" . __ ( "Music on hold class (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-queue-announceposition\" class=\"control-label col-xs-2\">" . __ ( "Announce position") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"checkbox\" name=\"queue-announceposition\" id=\"workflow-dialog-queue-announceposition\" value=\"true\" class=\"form-control\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-queue-timeout\" class=\"control-label col-xs-2\">" . __ ( "Timeout") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"queue-timeout\" id=\"workflow-dialog-queue-timeout\" class=\"form-control\" placeholder=\"" . __ ( "Queue timeout (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-queue-maxcallers\" class=\"control-label col-xs-2\">" . __ ( "Max callers") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"queue-maxcallers\" id=\"workflow-dialog-queue-maxcallers\" class=\"form-control\" placeholder=\"" . __ ( "Maximum callers in queue (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-queue-description",
  "modalshow" => "$('#workflow-dialog-queue-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-queue-name').val ( ivrData.Queue ? ivrData.Queue : '');\n" .
                 "$('#workflow-dialog-queue-mohclass').val ( ivrData.MOHClass ? ivrData.MOHClass : '');\n" .
                 "$('#workflow-dialog-queue-announceposition').bootstrapToggle ( ivrData.AnnouncePosition ? 'on' : 'off');\n" .
                 "$('#workflow-dialog-queue-timeout').val ( ivrData.Timeout ? ivrData.Timeout : '');\n" .
                 "$('#workflow-dialog-queue-maxcallers').val ( ivrData.MaxCallers ? ivrData.MaxCallers : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-queue-description').val ();\n" .
                 "ivrData.Queue = $('#workflow-dialog-queue-name').val ();\n" .
                 "ivrData.MOHClass = $('#workflow-dialog-queue-mohclass').val ();\n" .
                 "ivrData.AnnouncePosition = $('#workflow-dialog-queue-announceposition').prop ( 'checked');\n" .
                 "ivrData.Timeout = $('#workflow-dialog-queue-timeout').val ();\n" .
                 "ivrData.MaxCallers = $('#workflow-dialog-queue-maxcallers').val ();\n",
  "javascript" => "$('#workflow-dialog-queue-announceposition').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
                  "$('#workflow-dialog-queue-timeout,#workflow-dialog-queue-maxcallers').mask ( '0#');\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-queue-name').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Queue name missing.") . "\\n';\n" .
                  "}\n",
  "requiredcss" => array (
                     array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap"))
                   ),
  "requiredjs" => array (
                    array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()),
                    array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ())
                  )
);

/**
 * IVR voicemail operator
 *
 * Implements the possibility to send the caller to a voicemail box.
 */
$_in["ivr"]["operators"]["voicemail"] = array (
  "name" => "voicemail",
  "style" => "success",
  "outputs" => 1,
  "modal" => true,
  "icon" => "voicemail",
  "title" => __ ( "Voicemail"),
  "modaltitle" => __ ( "Voicemail options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_voicemail_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-voicemail-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"voicemail-description\" id=\"workflow-dialog-voicemail-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-voicemail-mailbox\" class=\"control-label col-xs-2\">" . __ ( "Mailbox") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"voicemail-mailbox\" id=\"workflow-dialog-voicemail-mailbox\" class=\"form-control\" placeholder=\"" . __ ( "Extension number or {var_VARIABLE}") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-voicemail-greeting\" class=\"control-label col-xs-2\">" . __ ( "Greeting") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <select name=\"voicemail-greeting\" id=\"workflow-dialog-voicemail-greeting\" class=\"form-control\">\n" .
                    "      <option value=\"unavailable\">" . __ ( "Unavailable") . "</option>\n" .
                    "      <option value=\"busy\">" . __ ( "Busy") . "</option>\n" .
                    "      <option value=\"skip\">" . __ ( "Skip") . "</option>\n" .
                    "    </select>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-voicemail-description",
  "modalshow" => "$('#workflow-dialog-voicemail-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-voicemail-mailbox').val ( ivrData.Mailbox ? ivrData.Mailbox : '');\n" .
                 "$('#workflow-dialog-voicemail-greeting').val ( ivrData.Greeting ? ivrData.Greeting : 'unavailable');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-voicemail-description').val ();\n" .
                 "ivrData.Mailbox = $('#workflow-dialog-voicemail-mailbox').val ();\n" .
                 "ivrData.Greeting = $('#workflow-dialog-voicemail-greeting').val ();\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-voicemail-mailbox').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Mailbox missing.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR say operator
 *
 * Implements the possibility to say digits, numbers, alpha or datetime values.
 */
$_in["ivr"]["operators"]["say"] = array (
  "name" => "say",
  "style" => "success",
  "outputs" => 1,
  "modal" => true,
  "icon" => "comment-dots",
  "title" => __ ( "Say"),
  "modaltitle" => __ ( "Say options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_say_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-say-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"say-description\" id=\"workflow-dialog-say-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-say-type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <select name=\"say-type\" id=\"workflow-dialog-say-type\" class=\"form-control\">\n" .
                    "      <option value=\"\">" . __ ( "Select type") . "</option>\n" .
                    "      <option value=\"digits\">" . __ ( "Digits") . "</option>\n" .
                    "      <option value=\"number\">" . __ ( "Number") . "</option>\n" .
                    "      <option value=\"alpha\">" . __ ( "Alpha") . "</option>\n" .
                    "      <option value=\"datetime\">" . __ ( "Date/Time") . "</option>\n" .
                    "    </select>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-say-variable\" class=\"control-label col-xs-2\">" . __ ( "Variable") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"say-variable\" id=\"workflow-dialog-say-variable\" class=\"form-control\" placeholder=\"" . __ ( "Source variable") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-say-format\" class=\"control-label col-xs-2\">" . __ ( "Format") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"say-format\" id=\"workflow-dialog-say-format\" class=\"form-control\" placeholder=\"" . __ ( "Format (only for datetime)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-say-description",
  "modalshow" => "$('#workflow-dialog-say-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-say-type').val ( ivrData.Type ? ivrData.Type : '');\n" .
                 "$('#workflow-dialog-say-variable').val ( ivrData.Variable ? ivrData.Variable : '');\n" .
                 "$('#workflow-dialog-say-format').val ( ivrData.Format ? ivrData.Format : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-say-description').val ();\n" .
                 "ivrData.Type = $('#workflow-dialog-say-type').val ();\n" .
                 "ivrData.Variable = $('#workflow-dialog-say-variable').val ();\n" .
                 "ivrData.Format = $('#workflow-dialog-say-format').val ();\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-say-type').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Type missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-say-variable').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable missing.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR go to IVR operator
 *
 * Implements the possibility to transfer flow to another IVR.
 */
$_in["ivr"]["operators"]["gotoivr"] = array (
  "name" => "gotoivr",
  "style" => "info",
  "outputs" => 1,
  "modal" => true,
  "icon" => "share",
  "title" => __ ( "Go to IVR"),
  "modaltitle" => __ ( "Go to IVR options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_gotoivr_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-gotoivr-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"gotoivr-description\" id=\"workflow-dialog-gotoivr-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-gotoivr-name\" class=\"control-label col-xs-2\">" . __ ( "IVR name") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"gotoivr-name\" id=\"workflow-dialog-gotoivr-name\" class=\"form-control\" placeholder=\"" . __ ( "IVR name") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-gotoivr-return\" class=\"control-label col-xs-2\">" . __ ( "Return") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"checkbox\" name=\"gotoivr-return\" id=\"workflow-dialog-gotoivr-return\" value=\"true\" class=\"form-control\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-gotoivr-description",
  "modalshow" => "$('#workflow-dialog-gotoivr-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-gotoivr-name').val ( ivrData.Name ? ivrData.Name : '');\n" .
                 "$('#workflow-dialog-gotoivr-return').bootstrapToggle ( ivrData.Return ? 'on' : 'off');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-gotoivr-description').val ();\n" .
                 "ivrData.Name = $('#workflow-dialog-gotoivr-name').val ();\n" .
                 "ivrData.Return = $('#workflow-dialog-gotoivr-return').prop ( 'checked');\n",
  "javascript" => "$('#workflow-dialog-gotoivr-return').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-gotoivr-name').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "IVR name missing.") . "\\n';\n" .
                  "}\n",
  "requiredcss" => array (
                     array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap"))
                   ),
  "requiredjs" => array (
                    array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ())
                  )
);

/**
 * IVR caller ID check operator
 *
 * Implements the possibility to check caller ID against blocklists or patterns.
 */
$_in["ivr"]["operators"]["calleridcheck"] = array (
  "name" => "calleridcheck",
  "style" => "default",
  "outputs" => array (
                 "match" => __ ( "Match"),
                 "nomatch" => __ ( "No match")
               ),
  "modal" => true,
  "icon" => "phone-square-alt",
  "title" => __ ( "CallerID check"),
  "modaltitle" => __ ( "CallerID check options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_calleridcheck_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-calleridcheck-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"calleridcheck-description\" id=\"workflow-dialog-calleridcheck-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-calleridcheck-type\" class=\"control-label col-xs-2\">" . __ ( "Check type") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <select name=\"calleridcheck-type\" id=\"workflow-dialog-calleridcheck-type\" class=\"form-control\">\n" .
                    "      <option value=\"\">" . __ ( "Select check type") . "</option>\n" .
                    "      <option value=\"blocklist\">" . __ ( "Blocklist") . "</option>\n" .
                    "      <option value=\"exceptions\">" . __ ( "Exceptions") . "</option>\n" .
                    "      <option value=\"regex\">" . __ ( "Regex") . "</option>\n" .
                    "      <option value=\"variable\">" . __ ( "Variable") . "</option>\n" .
                    "    </select>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-calleridcheck-pattern\" class=\"control-label col-xs-2\">" . __ ( "Pattern") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"calleridcheck-pattern\" id=\"workflow-dialog-calleridcheck-pattern\" class=\"form-control\" placeholder=\"" . __ ( "Regex pattern (for regex type)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-calleridcheck-variable\" class=\"control-label col-xs-2\">" . __ ( "Variable") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"calleridcheck-variable\" id=\"workflow-dialog-calleridcheck-variable\" class=\"form-control\" placeholder=\"" . __ ( "Variable name (for variable type)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-calleridcheck-description",
  "modalshow" => "$('#workflow-dialog-calleridcheck-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-calleridcheck-type').val ( ivrData.CheckType ? ivrData.CheckType : '');\n" .
                 "$('#workflow-dialog-calleridcheck-pattern').val ( ivrData.Pattern ? ivrData.Pattern : '');\n" .
                 "$('#workflow-dialog-calleridcheck-variable').val ( ivrData.Variable ? ivrData.Variable : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-calleridcheck-description').val ();\n" .
                 "ivrData.CheckType = $('#workflow-dialog-calleridcheck-type').val ();\n" .
                 "ivrData.Pattern = $('#workflow-dialog-calleridcheck-pattern').val ();\n" .
                 "ivrData.Variable = $('#workflow-dialog-calleridcheck-variable').val ();\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-calleridcheck-type').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Check type missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-calleridcheck-type').val () == 'regex' && $('#workflow-dialog-calleridcheck-pattern').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Pattern missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-calleridcheck-type').val () == 'variable' && $('#workflow-dialog-calleridcheck-variable').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable missing.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR HTTP request operator
 *
 * Implements the possibility to make HTTP requests and store the response.
 */
$_in["ivr"]["operators"]["http"] = array (
  "name" => "http",
  "style" => "warning",
  "outputs" => array (
                 "ok" => __ ( "OK"),
                 "error" => __ ( "Error")
               ),
  "modal" => true,
  "icon" => "globe",
  "title" => __ ( "HTTP"),
  "modaltitle" => __ ( "HTTP request options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_http_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-http-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"http-description\" id=\"workflow-dialog-http-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-http-url\" class=\"control-label col-xs-2\">" . __ ( "URL") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"http-url\" id=\"workflow-dialog-http-url\" class=\"form-control\" placeholder=\"" . __ ( "Request URL") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-http-method\" class=\"control-label col-xs-2\">" . __ ( "Method") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <select name=\"http-method\" id=\"workflow-dialog-http-method\" class=\"form-control\">\n" .
                    "      <option value=\"GET\">GET</option>\n" .
                    "      <option value=\"POST\">POST</option>\n" .
                    "    </select>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-http-variable\" class=\"control-label col-xs-2\">" . __ ( "Variable") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"http-variable\" id=\"workflow-dialog-http-variable\" class=\"form-control\" placeholder=\"" . __ ( "Variable to store response") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-http-timeout\" class=\"control-label col-xs-2\">" . __ ( "Timeout") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"http-timeout\" id=\"workflow-dialog-http-timeout\" class=\"form-control\" placeholder=\"" . __ ( "Request timeout (default 5)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-http-body\" class=\"control-label col-xs-2\">" . __ ( "Body") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <textarea name=\"http-body\" id=\"workflow-dialog-http-body\" class=\"form-control\" placeholder=\"" . __ ( "Request body (for POST)") . "\" style=\"max-width: 100%;min-width: 100%;max-height: 12em;min-height: 3em\"></textarea>\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-http-description",
  "modalshow" => "$('#workflow-dialog-http-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-http-url').val ( ivrData.URL ? ivrData.URL : '');\n" .
                 "$('#workflow-dialog-http-method').val ( ivrData.Method ? ivrData.Method : 'GET');\n" .
                 "$('#workflow-dialog-http-variable').val ( ivrData.Variable ? ivrData.Variable : '');\n" .
                 "$('#workflow-dialog-http-timeout').val ( ivrData.Timeout ? ivrData.Timeout : '5');\n" .
                 "$('#workflow-dialog-http-body').val ( ivrData.Body ? ivrData.Body : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-http-description').val ();\n" .
                 "ivrData.URL = $('#workflow-dialog-http-url').val ();\n" .
                 "ivrData.Method = $('#workflow-dialog-http-method').val ();\n" .
                 "ivrData.Variable = $('#workflow-dialog-http-variable').val ();\n" .
                 "ivrData.Timeout = $('#workflow-dialog-http-timeout').val ();\n" .
                 "ivrData.Body = $('#workflow-dialog-http-body').val ();\n",
  "javascript" => "$('#workflow-dialog-http-timeout').mask ( '0#');\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-http-url').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "URL missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-http-method').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Method missing.") . "\\n';\n" .
                  "}\n" .
                  "if ( $('#workflow-dialog-http-variable').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Variable missing.") . "\\n';\n" .
                  "}\n",
  "requiredjs" => array (
                    array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ())
                  )
);

/**
 * IVR conference operator
 *
 * Implements the possibility to place the caller into a conference room.
 */
$_in["ivr"]["operators"]["conference"] = array (
  "name" => "conference",
  "style" => "danger",
  "outputs" => 1,
  "modal" => true,
  "icon" => "users",
  "title" => __ ( "Conference"),
  "modaltitle" => __ ( "Conference options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_conference_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-conference-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"conference-description\" id=\"workflow-dialog-conference-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group required\">\n" .
                    "  <label for=\"workflow-dialog-conference-room\" class=\"control-label col-xs-2\">" . __ ( "Room") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"conference-room\" id=\"workflow-dialog-conference-room\" class=\"form-control\" placeholder=\"" . __ ( "Conference room name") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-conference-pin\" class=\"control-label col-xs-2\">" . __ ( "PIN") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"conference-pin\" id=\"workflow-dialog-conference-pin\" class=\"form-control\" placeholder=\"" . __ ( "Access PIN (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-conference-profile\" class=\"control-label col-xs-2\">" . __ ( "Profile") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"conference-profile\" id=\"workflow-dialog-conference-profile\" class=\"form-control\" placeholder=\"" . __ ( "Conference profile (default: default_bridge)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-conference-mohclass\" class=\"control-label col-xs-2\">" . __ ( "MOH class") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"conference-mohclass\" id=\"workflow-dialog-conference-mohclass\" class=\"form-control\" placeholder=\"" . __ ( "Music on hold class (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-conference-description",
  "modalshow" => "$('#workflow-dialog-conference-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-conference-room').val ( ivrData.Room ? ivrData.Room : '');\n" .
                 "$('#workflow-dialog-conference-pin').val ( ivrData.PIN ? ivrData.PIN : '');\n" .
                 "$('#workflow-dialog-conference-profile').val ( ivrData.Profile ? ivrData.Profile : '');\n" .
                 "$('#workflow-dialog-conference-mohclass').val ( ivrData.MOHClass ? ivrData.MOHClass : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-conference-description').val ();\n" .
                 "ivrData.Room = $('#workflow-dialog-conference-room').val ();\n" .
                 "ivrData.PIN = $('#workflow-dialog-conference-pin').val ();\n" .
                 "ivrData.Profile = $('#workflow-dialog-conference-profile').val ();\n" .
                 "ivrData.MOHClass = $('#workflow-dialog-conference-mohclass').val ();\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-conference-room').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "Room missing.") . "\\n';\n" .
                  "}\n"
);

/**
 * IVR callback operator
 *
 * Implements the possibility to schedule a callback.
 */
$_in["ivr"]["operators"]["callback"] = array (
  "name" => "callback",
  "style" => "warning",
  "outputs" => 1,
  "modal" => true,
  "icon" => "phone-volume",
  "title" => __ ( "Callback"),
  "modaltitle" => __ ( "Callback options"),
  "modalcontent" => "<form class=\"form-horizontal\" id=\"ivr_callback_form\">\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-callback-description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"callback-description\" id=\"workflow-dialog-callback-description\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-callback-queue\" class=\"control-label col-xs-2\">" . __ ( "Queue") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"callback-queue\" id=\"workflow-dialog-callback-queue\" class=\"form-control\" placeholder=\"" . __ ( "Associated queue (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-callback-variable\" class=\"control-label col-xs-2\">" . __ ( "Variable") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"callback-variable\" id=\"workflow-dialog-callback-variable\" class=\"form-control\" placeholder=\"" . __ ( "Variable to store caller number (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "<div class=\"form-group\">\n" .
                    "  <label for=\"workflow-dialog-callback-email\" class=\"control-label col-xs-2\">" . __ ( "Notify email") . "</label>\n" .
                    "  <div class=\"col-xs-10\">\n" .
                    "    <input type=\"text\" name=\"callback-email\" id=\"workflow-dialog-callback-email\" class=\"form-control\" placeholder=\"" . __ ( "Email to notify (optional)") . "\" />\n" .
                    "  </div>\n" .
                    "</div>\n" .
                    "</form>\n",
  "modalfocus" => "workflow-dialog-callback-description",
  "modalshow" => "$('#workflow-dialog-callback-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-callback-queue').val ( ivrData.Queue ? ivrData.Queue : '');\n" .
                 "$('#workflow-dialog-callback-variable').val ( ivrData.Variable ? ivrData.Variable : '');\n" .
                 "$('#workflow-dialog-callback-email').val ( ivrData.Email ? ivrData.Email : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-callback-description').val ();\n" .
                 "ivrData.Queue = $('#workflow-dialog-callback-queue').val ();\n" .
                 "ivrData.Variable = $('#workflow-dialog-callback-variable').val ();\n" .
                 "ivrData.Email = $('#workflow-dialog-callback-email').val ();\n"
);

/**
 * IVR hangup operator
 *
 * Implements the possibility to end the call and the flow.
 */
$_in["ivr"]["operators"]["hangup"] = array (
  "name" => "hangup",
  "style" => "danger",
  "outputs" => 0,
  "modal" => false,
  "icon" => "phone-slash",
  "title" => "Hangup"
);
?>
