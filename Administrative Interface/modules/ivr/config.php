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
 * VoIP Domain IVRs module configuration file.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage IVRs
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
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
                    "</form>\n",
  "modalfocus" => "workflow-dialog-play-description",
  "modalshow" => "$('#workflow-dialog-play-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-play-file').val ( ivrData.File).trigger ( 'change');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-play-description').val ();\n" .
                 "ivrData.File = $('#workflow-dialog-play-file').val ();\n",
  "javascript" => "$('#workflow-dialog-play-file').select2 (\n" .
                  "{\n" .
                  "  allowClear: true,\n" .
                  "  width: '100%',\n" .
                  "  data: VoIP.APIsearch ( { path: '/audios', fields: 'ID,Description', formatText: '%Description%'})\n" .
                  "});\n",
  "validation" => "var result = '';\n" .
                  "if ( $('#workflow-dialog-play-file').val () == '')\n" .
                  "{\n" .
                  "  result += '" . __ ( "File not selected.") . "\\n';\n" .
                  "}\n"
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
                    "</form>\n",
  "modalfocus" => "workflow-dialog-dial-description",
  "modalshow" => "$('#workflow-dialog-dial-description').val ( ivrData.Description ? ivrData.Description : '');\n" .
                 "$('#workflow-dialog-dial-destination').val ( ivrData.Destination ? ivrData.Destination : '');\n" .
                 "$('#workflow-dialog-dial-timeout').val ( ivrData.Timeout ? ivrData.Timeout : '');\n",
  "modalsave" => "ivrData.Description = $('#workflow-dialog-dial-description').val ();\n" .
                 "ivrData.Destination = $('#workflow-dialog-dial-destination').val ();\n" .
                 "ivrData.Timeout = $('#workflow-dialog-dial-timeout').val ();\n",
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
  "outputs" => 1,
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
